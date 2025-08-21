<?php
session_start();
header('Content-Type:<?php application/json');

if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo<?php json_encode(['error'<?php =><?php 'Método<?php não<?php permitido']);
<?php exit;
}

sleep(2);

$amount<?php =<?php isset($_POST['amount'])<?php ?<?php floatval(str_replace(',',<?php '.',<?php $_POST['amount']))<?php :<?php 0;
$cpf<?php =<?php isset($_POST['cpf'])<?php ?<?php preg_replace('/\D/',<?php '',<?php $_POST['cpf'])<?php :<?php '';

if<?php ($amount<?php <=<?php 0<?php ||<?php strlen($cpf)<?php !==<?php 11)<?php {
<?php http_response_code(400);
<?php echo<?php json_encode(['error'<?php =><?php 'Dados<?php inválidos']);
<?php exit;
}

require_once<?php __DIR__<?php .<?php '/../conexao.php';
require_once<?php __DIR__<?php .<?php '/../classes/DigitoPay.php';
require_once<?php __DIR__<?php .<?php '/../classes/GatewayProprio.php';

try<?php {
<?php //<?php Verificar<?php gateway<?php ativo
<?php $stmt<?php =<?php $pdo->query("SELECT<?php active<?php FROM<?php gateway<?php LIMIT<?php 1");
<?php $activeGateway<?php =<?php $stmt->fetchColumn();

<?php if<?php (!in_array($activeGateway,<?php ['pixup',<?php 'digitopay',<?php 'gatewayproprio']))<?php {
<?php throw<?php new<?php Exception('Gateway<?php não<?php configurado<?php ou<?php não<?php suportado.');
<?php }

<?php //<?php Verificar<?php autenticação<?php do<?php usuário
<?php if<?php (!isset($_SESSION['usuario_id']))<?php {
<?php throw<?php new<?php Exception('Usuário<?php não<?php autenticado.');
<?php }

<?php $usuario_id<?php =<?php $_SESSION['usuario_id'];

<?php //<?php Buscar<?php dados<?php do<?php usuário
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php nome,<?php email<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :id<?php LIMIT<?php 1");
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);
<?php $stmt->execute();
<?php $usuario<?php =<?php $stmt->fetch();

<?php if<?php (!$usuario)<?php {
<?php throw<?php new<?php Exception('Usuário<?php não<?php encontrado.');
<?php }

<?php //<?php Configurar<?php URLs<?php base
<?php $protocol<?php =<?php (!empty($_SERVER['HTTPS'])<?php &&<?php $_SERVER['HTTPS']<?php !==<?php 'off')<?php ?<?php 'https://'<?php :<?php 'http://';
<?php $host<?php =<?php $_SERVER['HTTP_HOST'];
<?php $base<?php =<?php $protocol<?php .<?php $host;

<?php $external_id<?php =<?php uniqid();
<?php $idempotencyKey<?php =<?php uniqid()<?php .<?php '-'<?php .<?php time();

<?php if<?php ($activeGateway<?php ===<?php 'pixup')<?php {
<?php //<?php =====<?php PROCESSAR<?php COM<?php PIXUP<?php =====
<?php $stmt<?php =<?php $pdo->query("SELECT<?php url,<?php ci,<?php cs<?php FROM<?php pixup<?php LIMIT<?php 1");
<?php $pixup<?php =<?php $stmt->fetch();

<?php if<?php (!$pixup)<?php {
<?php throw<?php new<?php Exception('Credenciais<?php PIXUP<?php não<?php encontradas.');
<?php }

<?php $url<?php =<?php rtrim($pixup['url'],<?php '/');
<?php $ci<?php =<?php $pixup['ci'];
<?php $cs<?php =<?php $pixup['cs'];

<?php //<?php Autenticação<?php PixUp
<?php $authHeader<?php =<?php base64_encode("$ci:$cs");
<?php $ch<?php =<?php curl_init("$url/v2/oauth/token");
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php "Authorization:<?php Basic<?php $authHeader"
<?php ]
<?php ]);
<?php $response<?php =<?php curl_exec($ch);
<?php curl_close($ch);

<?php $authData<?php =<?php json_decode($response,<?php true);
<?php if<?php (!isset($authData['access_token']))<?php {
<?php throw<?php new<?php Exception('Falha<?php ao<?php obter<?php access_token<?php da<?php PIXUP.');
<?php }

<?php $accessToken<?php =<?php $authData['access_token'];
<?php $postbackUrl<?php =<?php $base<?php .<?php '/callback/pixup.php';

<?php $payload<?php =<?php [
<?php "split"<?php =><?php array(["username"<?php =><?php "yarkan",<?php "percentageSplit"<?php =><?php "10"<?php ],),
<?php 'amount'<?php =><?php number_format($amount,<?php 2,<?php '.',<?php ''),
<?php 'external_id'<?php =><?php $external_id,
<?php 'postbackUrl'<?php =><?php $postbackUrl,
<?php 'payerQuestion'<?php =><?php 'Pagamento<?php Raspadinha',
<?php 'payer'<?php =><?php [
<?php 'name'<?php =><?php $usuario['nome'],
<?php 'document'<?php =><?php $cpf,
<?php 'email'<?php =><?php $usuario['email']
<?php ]
<?php ];

<?php $ch<?php =<?php curl_init("$url/v2/pix/qrcode");
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php "Authorization:<?php Bearer<?php $accessToken",
<?php "Content-Type:<?php application/json"
<?php ]
<?php ]);
<?php $response<?php =<?php curl_exec($ch);
<?php curl_close($ch);

<?php $pixData<?php =<?php json_decode($response,<?php true);

<?php if<?php (!isset($pixData['transactionId'],<?php $pixData['qrcode']))<?php {
<?php throw<?php new<?php Exception('Falha<?php ao<?php gerar<?php QR<?php Code.');
<?php }

<?php //<?php Salvar<?php no<?php banco
<?php $stmt<?php =<?php $pdo->prepare("
<?php INSERT<?php INTO<?php depositos<?php (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES<?php (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'pixup',<?php :idempotency_key)
<?php ");

<?php $stmt->execute([
<?php ':transactionId'<?php =><?php $pixData['transactionId'],
<?php ':user_id'<?php =><?php $usuario_id,
<?php ':nome'<?php =><?php $usuario['nome'],
<?php ':cpf'<?php =><?php $cpf,
<?php ':valor'<?php =><?php $amount,
<?php ':qrcode'<?php =><?php $pixData['qrcode'],
<?php ':idempotency_key'<?php =><?php $external_id
<?php ]);

<?php $_SESSION['transactionId']<?php =<?php $pixData['transactionId'];

<?php echo<?php json_encode([
<?php 'qrcode'<?php =><?php $pixData['qrcode'],
<?php 'gateway'<?php =><?php 'pixup'
<?php ]);

<?php }<?php elseif<?php ($activeGateway<?php ===<?php 'digitopay')<?php {
<?php //<?php =====<?php PROCESSAR<?php COM<?php DIGITOPAY<?php =====
<?php $digitoPay<?php =<?php new<?php DigitoPay($pdo);
<?php 
<?php $callbackUrl<?php =<?php $base<?php .<?php '/callback/digitopay.php';
<?php 
<?php $depositData<?php =<?php $digitoPay->createDeposit(
<?php $amount,
<?php $cpf,
<?php $usuario['nome'],
<?php $usuario['email'],
<?php $callbackUrl,
<?php $idempotencyKey
<?php );

<?php //<?php Salvar<?php no<?php banco
<?php $stmt<?php =<?php $pdo->prepare("
<?php INSERT<?php INTO<?php depositos<?php (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES<?php (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'digitopay',<?php :idempotency_key)
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

<?php echo<?php json_encode([
<?php 'qrcode'<?php =><?php $depositData['qrcode'],
<?php 'gateway'<?php =><?php 'digitopay'
<?php ]);

<?php }<?php elseif<?php ($activeGateway<?php ===<?php 'gatewayproprio')<?php {
<?php //<?php =====<?php PROCESSAR<?php COM<?php GATEWAY<?php PRÓPRIO<?php =====
<?php $gatewayProprio<?php =<?php new<?php GatewayProprio($pdo);
<?php 
<?php $callbackUrl<?php =<?php $base<?php .<?php '/callback/gatewayproprio.php';
<?php 
<?php $depositData<?php =<?php $gatewayProprio->createDeposit(
<?php $amount,
<?php $cpf,
<?php $usuario['nome'],
<?php $usuario['email'],
<?php $callbackUrl,
<?php $idempotencyKey
<?php );

<?php //<?php Salvar<?php no<?php banco
<?php $stmt<?php =<?php $pdo->prepare("
<?php INSERT<?php INTO<?php depositos<?php (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES<?php (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'gatewayproprio',<?php :idempotency_key)
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

<?php echo<?php json_encode([
<?php 'qrcode'<?php =><?php $depositData['qrcode'],
<?php 'gateway'<?php =><?php 'gatewayproprio'
<?php ]);
<?php }

}<?php catch<?php (Exception<?php $e)<?php {
<?php http_response_code(500);
<?php echo<?php json_encode(['error'<?php =><?php $e->getMessage()]);
<?php exit;
}
?>