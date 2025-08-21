<?php
session_start();
header('Content-Type:<?php application/json');

//<?php Log<?php de<?php debug
error_log('Payment<?php Debug<?php -<?php Início<?php da<?php requisição');

if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo<?php json_encode(['error'<?php =><?php 'Método<?php não<?php permitido']);
<?php exit;
}

sleep(2);

$amount<?php =<?php isset($_POST['amount'])<?php ?<?php floatval(str_replace(',',<?php '.',<?php $_POST['amount']))<?php :<?php 0;
$cpf<?php =<?php isset($_POST['cpf'])<?php ?<?php preg_replace('/\D/',<?php '',<?php $_POST['cpf'])<?php :<?php '';

error_log('Payment<?php Debug<?php -<?php Amount:<?php '<?php .<?php $amount<?php .<?php ',<?php CPF:<?php '<?php .<?php $cpf);

if<?php ($amount<?php <=<?php 0<?php ||<?php strlen($cpf)<?php !==<?php 11)<?php {
<?php error_log('Payment<?php Debug<?php -<?php Dados<?php inválidos');
<?php http_response_code(400);
<?php echo<?php json_encode(['error'<?php =><?php 'Dados<?php inválidos']);
<?php exit;
}

require_once<?php __DIR__<?php .<?php '/../conexao.php';
require_once<?php __DIR__<?php .<?php '/../classes/RushPay.php';

try<?php {
<?php //<?php Verificar<?php autenticação<?php do<?php usuário
<?php if<?php (!isset($_SESSION['usuario_id']))<?php {
<?php error_log('Payment<?php Debug<?php -<?php Usuário<?php não<?php autenticado');
<?php throw<?php new<?php Exception('Usuário<?php não<?php autenticado.');
<?php }

<?php $usuario_id<?php =<?php $_SESSION['usuario_id'];
<?php error_log('Payment<?php Debug<?php -<?php Usuario<?php ID:<?php '<?php .<?php $usuario_id);

<?php //<?php Buscar<?php dados<?php do<?php usuário
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php nome,<?php email,<?php telefone<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :id<?php LIMIT<?php 1");
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);
<?php $stmt->execute();
<?php $usuario<?php =<?php $stmt->fetch();

<?php if<?php (!$usuario)<?php {
<?php error_log('Payment<?php Debug<?php -<?php Usuário<?php não<?php encontrado');
<?php throw<?php new<?php Exception('Usuário<?php não<?php encontrado.');
<?php }

<?php error_log('Payment<?php Debug<?php -<?php Usuario<?php encontrado:<?php '<?php .<?php $usuario['nome']);

<?php //<?php Configurar<?php URLs<?php base
<?php $protocol<?php =<?php (!empty($_SERVER['HTTPS'])<?php &&<?php $_SERVER['HTTPS']<?php !==<?php 'off')<?php ?<?php 'https://'<?php :<?php 'http://';
<?php $host<?php =<?php $_SERVER['HTTP_HOST'];
<?php $base<?php =<?php $protocol<?php .<?php $host;

<?php $external_id<?php =<?php uniqid();
<?php $idempotencyKey<?php =<?php uniqid()<?php .<?php '-'<?php .<?php time();

<?php //<?php Capturar<?php utm_source<?php da<?php sessão
<?php $utmSource<?php =<?php $_SESSION['utm_source']<?php ??<?php null;
<?php error_log("Payment<?php Debug<?php -<?php UTM<?php Source<?php capturado:<?php "<?php .<?php ($utmSource<?php ??<?php "NULL"));

<?php error_log('Payment<?php Debug<?php -<?php Iniciando<?php RushPay');

<?php //<?php Processar<?php com<?php RushPay
<?php $rushPay<?php =<?php new<?php RushPay($pdo);
<?php 
$callbackUrl<?php =<?php 'https://api.xtracky.com/api/integrations/rushpay';
<?php 
<?php //<?php Preparar<?php telefone<?php no<?php formato<?php correto
<?php $phone<?php =<?php $usuario['telefone']<?php ??<?php '11999999999';
<?php $phone<?php =<?php preg_replace('/\D/',<?php '',<?php $phone);
<?php if<?php (strlen($phone)<?php ===<?php 11)<?php {
<?php $phone<?php =<?php substr($phone,<?php 2);<?php //<?php Remove<?php código<?php do<?php país<?php se<?php presente
<?php }<?php elseif<?php (strlen($phone)<?php ===<?php 10)<?php {
<?php //<?php Telefone<?php já<?php no<?php formato<?php correto
<?php }<?php else<?php {
<?php $phone<?php =<?php '11999999999';<?php //<?php Telefone<?php padrão<?php se<?php inválido
<?php }

<?php error_log('Payment<?php Debug<?php -<?php Telefone<?php formatado:<?php '<?php .<?php $phone);

<?php $depositData<?php =<?php $rushPay->createDeposit(
<?php $amount,
<?php $cpf,
<?php $usuario['nome'],
<?php $usuario['email'],
<?php $phone,
<?php $callbackUrl,
<?php $idempotencyKey,
<?php $utmSource
<?php );

<?php error_log('Payment<?php Debug<?php -<?php RushPay<?php retornou:<?php '<?php .<?php json_encode($depositData));

<?php //<?php Salvar<?php no<?php banco
<?php $stmt<?php =<?php $pdo->prepare("
<?php INSERT<?php INTO<?php depositos<?php (transactionId,<?php user_id,<?php nome,<?php cpf,<?php valor,<?php status,<?php qrcode,<?php gateway,<?php idempotency_key)
<?php VALUES<?php (:transactionId,<?php :user_id,<?php :nome,<?php :cpf,<?php :valor,<?php 'PENDING',<?php :qrcode,<?php 'rushpay',<?php :idempotency_key)
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

<?php error_log('Payment<?php Debug<?php -<?php Salvo<?php no<?php banco<?php com<?php sucesso');

<?php //<?php XTracky<?php será<?php notificado<?php via<?php webhook<?php RushPay<?php -><?php xTracky<?php (não<?php duplicar<?php aqui)


<?php $_SESSION['transactionId']<?php =<?php $depositData['transactionId'];

<?php echo<?php json_encode([
<?php 'qrcode'<?php =><?php $depositData['pixCode'],<?php //<?php Usar<?php pixCode<?php como<?php qrcode<?php para<?php compatibilidade
<?php 'pixCode'<?php =><?php $depositData['pixCode'],
<?php 'gateway'<?php =><?php 'rushpay',
<?php 'transactionId'<?php =><?php $depositData['transactionId'],
<?php 'expiresAt'<?php =><?php $depositData['expiresAt'],
<?php 'debug'<?php =><?php 'Sucesso!'
<?php ]);

}<?php catch<?php (Exception<?php $e)<?php {
<?php error_log('Payment<?php Debug<?php -<?php Erro:<?php '<?php .<?php $e->getMessage());
<?php http_response_code(500);
<?php echo<?php json_encode(['error'<?php =><?php $e->getMessage(),<?php 'debug'<?php =><?php 'Erro<?php capturado']);
<?php exit;
}
?>

