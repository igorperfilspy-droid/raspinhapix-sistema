<?php<?php 
header('Content-Type:<?php application/json');<?php 
header('Access-Control-Allow-Origin:<?php *');<?php 
header('Access-Control-Allow-Methods:<?php POST');<?php 
header('Access-Control-Allow-Headers:<?php Content-Type');<?php 
session_start();<?php 
<?php 
require_once<?php __DIR__<?php .<?php '/../conexao.php';<?php 
<?php 
if<?php (!isset($_SESSION['usuario_id']))<?php {<?php 
<?php echo<?php json_encode(['success'<?php =><?php false,<?php 'message'<?php =><?php 'Usuário<?php não<?php autenticado']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$usuario_id<?php =<?php $_SESSION['usuario_id'];<?php 
$data<?php =<?php json_decode(file_get_contents('php://input'),<?php true);<?php 
<?php 
if<?php (empty($data['amount'])<?php ||<?php empty($data['cpf']))<?php {<?php 
<?php echo<?php json_encode(['success'<?php =><?php false,<?php 'message'<?php =><?php 'Dados<?php incompletos']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$amount<?php =<?php (float)<?php $data['amount'];<?php 
$cpf<?php =<?php preg_replace('/[^0-9]/',<?php '',<?php $data['cpf']);<?php 
<?php 
if<?php (strlen($cpf)<?php !==<?php 11)<?php {<?php 
<?php echo<?php json_encode(['success'<?php =><?php false,<?php 'message'<?php =><?php 'CPF<?php inválido']);<?php 
<?php exit;<?php 
}<?php 
<?php 
try<?php {<?php 
<?php $pdo->beginTransaction();<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :id<?php FOR<?php UPDATE");<?php 
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt->execute();<?php 
<?php $usuario<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
<?php if<?php (!$usuario)<?php {<?php 
<?php throw<?php new<?php Exception('Usuário<?php não<?php encontrado');<?php 
<?php }<?php 
<?php 
<?php if<?php ($usuario['saldo']<?php <?php $amount)<?php {<?php 
<?php throw<?php new<?php Exception('Saldo<?php insuficiente<?php para<?php realizar<?php o<?php saque');<?php 
<?php }<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php as<?php pending<?php FROM<?php saques<?php WHERE<?php user_id<?php =<?php :user_id<?php AND<?php status<?php =<?php 'PENDING'");<?php 
<?php $stmt->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt->execute();<?php 
<?php $hasPending<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC)['pending'];<?php 
<?php 
<?php if<?php ($hasPending<?php ><?php 0)<?php {<?php 
<?php throw<?php new<?php Exception('Você<?php já<?php possui<?php um<?php saque<?php pendente.<?php Aguarde<?php a<?php conclusão<?php para<?php solicitar<?php outro.');<?php 
<?php }<?php 
<?php 
<?php $nome<?php =<?php "Nome<?php não<?php encontrado";<?php 
<?php 
<?php $curl<?php =<?php curl_init();<?php 
<?php curl_setopt_array($curl,<?php [<?php 
<?php CURLOPT_URL<?php =><?php "https://api-cpf-gratis.p.rapidapi.com/?cpf="<?php .<?php $cpf,<?php 
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,<?php 
<?php CURLOPT_HTTPHEADER<?php =><?php [<?php 
<?php "x-rapidapi-host:<?php api-cpf-gratis.p.rapidapi.com",<?php 
<?php "x-rapidapi-key:<?php e5c1fd4e13msh008c726672c9a43p1218d5jsn9a8b01aa6822"<?php 
<?php ],<?php 
<?php ]);<?php 
<?php 
<?php $response<?php =<?php curl_exec($curl);<?php 
<?php $err<?php =<?php curl_error($curl);<?php 
<?php curl_close($curl);<?php 
<?php 
<?php if<?php (!$err)<?php {<?php 
<?php $apiData<?php =<?php json_decode($response,<?php true);<?php 
<?php if<?php ($apiData['code']<?php ==<?php 200<?php &&<?php !empty($apiData['data']['nome']))<?php {<?php 
<?php $nome<?php =<?php $apiData['data']['nome'];<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php $newBalance<?php =<?php $usuario['saldo']<?php -<?php $amount;<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php :saldo<?php WHERE<?php id<?php =<?php :id");<?php 
<?php $stmt->bindParam(':saldo',<?php $newBalance);<?php 
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt->execute();<?php 
<?php 
<?php $transactionId<?php =<?php uniqid('WTH_');<?php 
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php saques<?php (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status)<?php 
<?php VALUES<?php (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING')");<?php 
<?php $stmt->bindParam(':transactionId',<?php $transactionId);<?php 
<?php $stmt->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt->bindParam(':nome',<?php $nome);<?php 
<?php $stmt->bindParam(':cpf',<?php $cpf);<?php 
<?php $stmt->bindParam(':valor',<?php $amount);<?php 
<?php $stmt->execute();<?php 
<?php 
<?php $pdo->commit();<?php 
<?php 
<?php echo<?php json_encode([<?php 
<?php 'success'<?php =><?php true,<?php 
<?php 'message'<?php =><?php 'Saque<?php solicitado<?php com<?php sucesso!'<?php 
<?php ]);<?php 
<?php 
}<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php $pdo->rollBack();<?php 
<?php echo<?php json_encode(['success'<?php =><?php false,<?php 'message'<?php =><?php $e->getMessage()]);<?php 
}