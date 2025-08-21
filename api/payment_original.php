<?php
session_start();
header('Content-Type:<?php application/json');

if ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo json_encode(['error'<?php =><?php 'Método não permitido']);
<?php exit;
}

sleep(2);

$amount =<?php isset($_POST['amount'])<?php ?<?php floatval(str_replace(',',<?php '.',<?php $_POST['amount']))<?php :<?php 0;
$cpf =<?php isset($_POST['cpf'])<?php ?<?php preg_replace('/\D/',<?php '',<?php $_POST['cpf'])<?php :<?php '';

if ($amount <=<?php 0 ||<?php strlen($cpf)<?php !==<?php 11)<?php {
<?php http_response_code(400);
<?php echo json_encode(['error'<?php =><?php 'Dados inválidos']);
<?php exit;
}

require_once __DIR__ .<?php '/../conexao.php';
require_once __DIR__ .<?php '/../classes/DigitoPay.php';
require_once __DIR__ .<?php '/../classes/GatewayProprio.php';

try {
<?php //<?php Verificar gateway ativo $stmt =<?php $pdo->query("SELECT active FROM gateway LIMIT 1");
<?php $activeGateway =<?php $stmt->fetchColumn();

<?php if (!in_array($activeGateway,<?php ['pixup',<?php 'digitopay',<?php 'gatewayproprio']))<?php {
<?php throw new Exception('Gateway não configurado ou não suportado.');
<?php }

<?php //<?php Verificar autenticação do usuário if (!isset($_SESSION['usuario_id']))<?php {
<?php throw new Exception('Usuário não autenticado.');
<?php }

<?php $usuario_id =<?php $_SESSION['usuario_id'];

<?php //<?php Buscar dados do usuário $stmt =<?php $pdo->prepare("SELECT nome,<?php email FROM usuarios WHERE id =<?php :id LIMIT 1");
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);
<?php $stmt->execute();
<?php $usuario =<?php $stmt->fetch();

<?php if (!$usuario)<?php {
<?php throw new Exception('Usuário não encontrado.');
<?php }

<?php //<?php Configurar URLs base $protocol =<?php (!empty($_SERVER['HTTPS'])<?php &&<?php $_SERVER['HTTPS']<?php !==<?php 'off')<?php ?<?php 'https://'<?php :<?php 'http://';
<?php $host =<?php $_SERVER['HTTP_HOST'];
<?php $base =<?php $protocol .<?php $host;

<?php $external_id =<?php uniqid();
<?php $idempotencyKey =<?php uniqid()<?php .<?php '-'<?php .<?php time();

<?php if ($activeGateway ===<?php 'pixup')<?php {
<?php //<?php =====<?php PROCESSAR COM PIXUP =====
<?php $stmt =<?php $pdo->query("SELECT url,<?php ci,<?php cs FROM pixup LIMIT 1");
<?php $pixup =<?php $stmt->fetch();

<?php if (!$pixup)<?php {
<?php throw new Exception('Credenciais PIXUP não encontradas.');
<?php }

<?php $url =<?php rtrim($pixup['url'],<?php '/');
<?php $ci =<?php $pixup['ci'];
<?php $cs =<?php $pixup['cs'];

<?php //<?php Autenticação PixUp $authHeader =<?php base64_encode("$ci:$cs");
<?php $ch =<?php curl_init("$url/v2/oauth/token");
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_HTTPHEADER =><?php [
<?php "Authorization:<?php Basic $authHeader"
<?php ]
<?php ]);
<?php $response =<?php curl_exec($ch);
<?php curl_close($ch);

<?php $authData =<?php json_decode($response,<?php true);
<?php if (!isset($authData['access_token']))<?php {
<?php throw new Exception('Falha ao obter access_token da PIXUP.');
<?php }

<?php $accessToken =<?php $authData['access_token'];
<?php $postbackUrl =<?php $base .<?php '/callback/pixup.php';

<?php $payload =<?php [
<?php "split"<?php =><?php array(["username"<?php =><?php "yarkan",<?php "percentageSplit"<?php =><?php "10"<?php ],),
<?php 'amount'<?php =><?php number_format($amount,<?php 2,<?php '.',<?php ''),
<?php 'external_id'<?php =><?php $external_id,
<?php 'postbackUrl'<?php =><?php $postbackUrl,
<?php 'payerQuestion'<?php =><?php 'Pagamento Raspadinha',
<?php 'payer'<?php =><?php [
<?php 'name'<?php =><?php $usuario['nome'],
<?php 'document'<?php =><?php $cpf,
<?php 'email'<?php =><?php $usuario['email']
<?php ]
<?php ];

<?php $ch =<?php curl_init("$url/v2/pix/qrcode");
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php "Authorization:<?php Bearer $accessToken",
<?php "Content-Type:<?php application/json"
<?php ]
<?php ]);
<?php $response =<?php curl_exec($ch);
<?php curl_close($ch);

<?php $pixData =<?php json_decode($response,<?php true);

<?php if (!isset($pixData['transactionId'],<?php $pixData['qrcode']))<?php {
<?php throw new Exception('Falha ao gerar QR Code.');
<?php }

<?php //<?php Salvar no banco $stmt =<?php $pdo->prepare("
<?php INSERT INTO depositos (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'pixup',<?php :idempotency_key)
<?php ");

<?php $stmt->execute([
<?php ':transactionId'<?php =><?php $pixData['transactionId'],
<?php ':user_id'<?php =><?php $usuario_id,
<?php ':nome'<?php =><?php $usuario['nome'],
<?php ':cpf'<?php =><?php $cpf,
<?php ':valor'<?php =><?php $amount,
<?php ':qrcode'<?php =><?php $pixData['qrcode'],
<?php ':idempotency_key'<?php =><?php $external_id ]);

<?php $_SESSION['transactionId']<?php =<?php $pixData['transactionId'];

<?php echo json_encode([
<?php 'qrcode'<?php =><?php $pixData['qrcode'],
<?php 'gateway'<?php =><?php 'pixup'
<?php ]);

<?php }<?php elseif ($activeGateway ===<?php 'digitopay')<?php {
<?php //<?php =====<?php PROCESSAR COM DIGITOPAY =====
<?php $digitoPay =<?php new DigitoPay($pdo);
<?php $callbackUrl =<?php $base .<?php '/callback/digitopay.php';
<?php $depositData =<?php $digitoPay->createDeposit(
<?php $amount,
<?php $cpf,
<?php $usuario['nome'],
<?php $usuario['email'],
<?php $callbackUrl,
<?php $idempotencyKey );

<?php //<?php Salvar no banco $stmt =<?php $pdo->prepare("
<?php INSERT INTO depositos (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'digitopay',<?php :idempotency_key)
<?php ");

<?php $stmt->execute([
<?php ':transactionId'<?php =><?php $depositData['transactionId'],
<?php ':user_id'<?php =><?php $usuario_id,
<?php ':nome'<?php =><?php $usuario['nome'],
<?php ':cpf'<?php =><?php $cpf,
<?php ':valor'<?php =><?php $amount,
<?php ':qrcode'<?php =><?php $depositData['qrcode'],
<?php ':idempotency_key'<?php =><?php $depositData['idempotencyKey']
<?php ]);

<?php $_SESSION['transactionId']<?php =<?php $depositData['transactionId'];

<?php echo json_encode([
<?php 'qrcode'<?php =><?php $depositData['qrcode'],
<?php 'gateway'<?php =><?php 'digitopay'
<?php ]);

<?php }<?php elseif ($activeGateway ===<?php 'gatewayproprio')<?php {
<?php //<?php =====<?php PROCESSAR COM GATEWAY PRÓPRIO =====
<?php $gatewayProprio =<?php new GatewayProprio($pdo);
<?php $callbackUrl =<?php $base .<?php '/callback/gatewayproprio.php';
<?php $depositData =<?php $gatewayProprio->createDeposit(
<?php $amount,
<?php $cpf,
<?php $usuario['nome'],
<?php $usuario['email'],
<?php $callbackUrl,
<?php $idempotencyKey );

<?php //<?php Salvar no banco $stmt =<?php $pdo->prepare("
<?php INSERT INTO depositos (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'gatewayproprio',<?php :idempotency_key)
<?php ");

<?php $stmt->execute([
<?php ':transactionId'<?php =><?php $depositData['transactionId'],
<?php ':user_id'<?php =><?php $usuario_id,
<?php ':nome'<?php =><?php $usuario['nome'],
<?php ':cpf'<?php =><?php $cpf,
<?php ':valor'<?php =><?php $amount,
<?php ':qrcode'<?php =><?php $depositData['qrcode'],
<?php ':idempotency_key'<?php =><?php $depositData['idempotencyKey']
<?php ]);

<?php $_SESSION['transactionId']<?php =<?php $depositData['transactionId'];

<?php echo json_encode([
<?php 'qrcode'<?php =><?php $depositData['qrcode'],
<?php 'gateway'<?php =><?php 'gatewayproprio'
<?php ]);
<?php }

}<?php catch (Exception $e)<?php {
<?php http_response_code(500);
<?php echo json_encode(['error'<?php =><?php $e->getMessage()]);
<?php exit;
}
?>