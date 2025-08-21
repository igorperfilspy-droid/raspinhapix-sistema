<?php
session_start();
header('Content-Type:<?php application/json');

//<?php Log de debug
error_log('Payment Debug -<?php Início da requisição');

if ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo json_encode(['error'<?php =><?php 'Método não permitido']);
<?php exit;
}

sleep(2);

$amount =<?php isset($_POST['amount'])<?php ?<?php floatval(str_replace(',',<?php '.',<?php $_POST['amount']))<?php :<?php 0;
$cpf =<?php isset($_POST['cpf'])<?php ?<?php preg_replace('/\D/',<?php '',<?php $_POST['cpf'])<?php :<?php '';

error_log('Payment Debug -<?php Amount:<?php '<?php .<?php $amount .<?php ',<?php CPF:<?php '<?php .<?php $cpf);

if ($amount <=<?php 0 ||<?php strlen($cpf)<?php !==<?php 11)<?php {
<?php error_log('Payment Debug -<?php Dados inválidos');
<?php http_response_code(400);
<?php echo json_encode(['error'<?php =><?php 'Dados inválidos']);
<?php exit;
}

require_once __DIR__ .<?php '/../conexao.php';
require_once __DIR__ .<?php '/../classes/RushPay.php';

try {
<?php //<?php Verificar autenticação do usuário if (!isset($_SESSION['usuario_id']))<?php {
<?php error_log('Payment Debug -<?php Usuário não autenticado');
<?php throw new Exception('Usuário não autenticado.');
<?php }

<?php $usuario_id =<?php $_SESSION['usuario_id'];
<?php error_log('Payment Debug -<?php Usuario ID:<?php '<?php .<?php $usuario_id);

<?php //<?php Buscar dados do usuário $stmt =<?php $pdo->prepare("SELECT nome,<?php email,<?php telefone FROM usuarios WHERE id =<?php :id LIMIT 1");
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);
<?php $stmt->execute();
<?php $usuario =<?php $stmt->fetch();

<?php if (!$usuario)<?php {
<?php error_log('Payment Debug -<?php Usuário não encontrado');
<?php throw new Exception('Usuário não encontrado.');
<?php }

<?php error_log('Payment Debug -<?php Usuario encontrado:<?php '<?php .<?php $usuario['nome']);

<?php //<?php Configurar URLs base $protocol =<?php (!empty($_SERVER['HTTPS'])<?php &&<?php $_SERVER['HTTPS']<?php !==<?php 'off')<?php ?<?php 'https://'<?php :<?php 'http://';
<?php $host =<?php $_SERVER['HTTP_HOST'];
<?php $base =<?php $protocol .<?php $host;

<?php $external_id =<?php uniqid();
<?php $idempotencyKey =<?php uniqid()<?php .<?php '-'<?php .<?php time();

<?php //<?php Capturar utm_source da sessão $utmSource =<?php $_SESSION['utm_source']<?php ??<?php null;
<?php error_log("Payment Debug -<?php UTM Source capturado:<?php "<?php .<?php ($utmSource ??<?php "NULL"));

<?php error_log('Payment Debug -<?php Iniciando RushPay');

<?php //<?php Processar com RushPay $rushPay =<?php new RushPay($pdo);
<?php 
$callbackUrl =<?php 'https://api.xtracky.com/api/integrations/rushpay';
<?php //<?php Preparar telefone no formato correto $phone =<?php $usuario['telefone']<?php ??<?php '11999999999';
<?php $phone =<?php preg_replace('/\D/',<?php '',<?php $phone);
<?php if (strlen($phone)<?php ===<?php 11)<?php {
<?php $phone =<?php substr($phone,<?php 2);<?php //<?php Remove código do país se presente }<?php elseif (strlen($phone)<?php ===<?php 10)<?php {
<?php //<?php Telefone já<?php no formato correto }<?php else {
<?php $phone =<?php '11999999999';<?php //<?php Telefone padrão se inválido }

<?php error_log('Payment Debug -<?php Telefone formatado:<?php '<?php .<?php $phone);

<?php $depositData =<?php $rushPay->createDeposit(
<?php $amount,
<?php $cpf,
<?php $usuario['nome'],
<?php $usuario['email'],
<?php $phone,
<?php $callbackUrl,
<?php $idempotencyKey,
<?php $utmSource );

<?php error_log('Payment Debug -<?php RushPay retornou:<?php '<?php .<?php json_encode($depositData));

<?php //<?php Salvar no banco $stmt =<?php $pdo->prepare("
<?php INSERT INTO depositos (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'rushpay',<?php :idempotency_key)
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

<?php error_log('Payment Debug -<?php Salvo no banco com sucesso');

<?php //<?php XTracky será<?php notificado via webhook RushPay -><?php xTracky (não duplicar aqui)


<?php $_SESSION['transactionId']<?php =<?php $depositData['transactionId'];

<?php echo json_encode([
<?php 'qrcode'<?php =><?php $depositData['pixCode'],<?php //<?php Usar pixCode como qrcode para compatibilidade 'pixCode'<?php =><?php $depositData['pixCode'],
<?php 'gateway'<?php =><?php 'rushpay',
<?php 'transactionId'<?php =><?php $depositData['transactionId'],
<?php 'expiresAt'<?php =><?php $depositData['expiresAt'],
<?php 'debug'<?php =><?php 'Sucesso!'
<?php ]);

}<?php catch (Exception $e)<?php {
<?php error_log('Payment Debug -<?php Erro:<?php '<?php .<?php $e->getMessage());
<?php http_response_code(500);
<?php echo json_encode(['error'<?php =><?php $e->getMessage(),<?php 'debug'<?php =><?php 'Erro capturado']);
<?php exit;
}
?>

