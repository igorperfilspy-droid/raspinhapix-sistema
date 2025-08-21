<?php
include '../includes/session.php';
include '../conexao.php';
include '../includes/notiflix.php';

$usuarioId =<?php $_SESSION['usuario_id'];
$admin =<?php ($stmt =<?php $pdo->prepare("SELECT admin FROM usuarios WHERE id =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;

if ($admin !=<?php 1)<?php {
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php não é<?php um administrador!'];
<?php header("Location:<?php /");
<?php exit;
}

if (isset($_POST['aprovar_saque']))<?php {
<?php $saque_id =<?php $_POST['saque_id'];
<?php try {
<?php $pdo->beginTransaction();
<?php //<?php Verificar qual gateway está<?php ativo $stmt =<?php $pdo->prepare("SELECT active FROM gateway LIMIT 1");
<?php $stmt->execute();
<?php $activeGateway =<?php $stmt->fetchColumn();
<?php if (!in_array($activeGateway,<?php ['pixup',<?php 'digitopay']))<?php {
<?php throw new Exception('Gateway não configurado ou não suportado.');
<?php }
<?php //<?php Buscar dados do saque $stmt =<?php $pdo->prepare("SELECT s.*,<?php u.nome,<?php u.email FROM saques s JOIN usuarios u ON s.user_id =<?php u.id WHERE s.id =<?php ?<?php LIMIT 1 FOR UPDATE");
<?php $stmt->execute([$saque_id]);
<?php $saque =<?php $stmt->fetch();
<?php if (!$saque)<?php {
<?php throw new Exception("Saque não encontrado");
<?php }
<?php if ($activeGateway ===<?php 'pixup')<?php {
<?php //<?php =====<?php PROCESSAR COM PIXUP =====
<?php $stmt =<?php $pdo->prepare("SELECT ci,<?php cs,<?php url FROM pixup LIMIT 1");
<?php $stmt->execute();
<?php $pixup =<?php $stmt->fetch();
<?php if (!$pixup)<?php {
<?php throw new Exception("Credenciais PIXUP não configuradas");
<?php }
<?php $auth =<?php base64_encode($pixup['ci']<?php .<?php ':'<?php .<?php $pixup['cs']);
<?php //<?php STEP 1:<?php Obter Token (FORÇANDO IPv4)
<?php $ch =<?php curl_init($pixup['url']<?php .<?php '/v2/oauth/token');
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_POST,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 30);
<?php curl_setopt($ch,<?php CURLOPT_CONNECTTIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php 2);
<?php curl_setopt($ch,<?php CURLOPT_IPRESOLVE,<?php CURL_IPRESOLVE_V4);<?php //<?php Forçar IPv4 curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php [
<?php 'Authorization:<?php Basic '<?php .<?php $auth,
<?php 'Content-Type:<?php application/json',
<?php 'Accept:<?php application/json',
<?php 'User-Agent:<?php PHP-CURL/7.0'
<?php ]);
<?php $tokenResponse =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError =<?php curl_error($ch);
<?php curl_close($ch);
<?php if ($curlError)<?php {
<?php throw new Exception("Erro cURL no token:<?php "<?php .<?php $curlError);
<?php }
<?php $tokenData =<?php json_decode($tokenResponse,<?php true);
<?php if ($httpCode !==<?php 200 ||<?php !isset($tokenData['access_token']))<?php {
<?php throw new Exception("Falha ao obter token de acesso da PIXUP.<?php HTTP:<?php $httpCode |<?php Response:<?php $tokenResponse");
<?php }
<?php $accessToken =<?php $tokenData['access_token'];
<?php //<?php STEP 2:<?php Preparar Payload $external_id =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php $cpf_limpo =<?php preg_replace('/\D/',<?php '',<?php $saque['cpf']);
<?php $payload =<?php [
<?php 'amount'<?php =><?php (float)$saque['valor'],
<?php 'description'<?php =><?php 'Saque Raspadinha -<?php ID:<?php '<?php .<?php $saque['id'],
<?php 'external_id'<?php =><?php $external_id,
<?php 'creditParty'<?php =><?php [
<?php 'name'<?php =><?php trim($saque['nome']),
<?php 'keyType'<?php =><?php 'CPF',
<?php 'key'<?php =><?php $cpf_limpo,
<?php 'taxId'<?php =><?php $cpf_limpo ]
<?php ];
<?php //<?php STEP 3:<?php Fazer Pagamento (FORÇANDO IPv4)
<?php $ch =<?php curl_init($pixup['url']<?php .<?php '/v2/pix/payment');
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_POST,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 60);
<?php curl_setopt($ch,<?php CURLOPT_CONNECTTIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php 2);
<?php curl_setopt($ch,<?php CURLOPT_POSTFIELDS,<?php json_encode($payload));
<?php curl_setopt($ch,<?php CURLOPT_IPRESOLVE,<?php CURL_IPRESOLVE_V4);<?php //<?php Forçar IPv4 curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php [
<?php 'Authorization:<?php Bearer '<?php .<?php $accessToken,
<?php 'Content-Type:<?php application/json',
<?php 'Accept:<?php application/json',
<?php 'User-Agent:<?php PHP-CURL/7.0'
<?php ]);
<?php $paymentResponse =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError =<?php curl_error($ch);
<?php curl_close($ch);
<?php if ($curlError)<?php {
<?php throw new Exception("Erro cURL no pagamento:<?php "<?php .<?php $curlError);
<?php }
<?php $paymentData =<?php json_decode($paymentResponse,<?php true);
<?php if ($httpCode !==<?php 200 &&<?php $httpCode !==<?php 201)<?php {
<?php $errorMsg =<?php "Falha ao processar pagamento na PIXUP.<?php HTTP:<?php $httpCode |<?php Response:<?php $paymentResponse";
<?php if ($paymentData &&<?php isset($paymentData['message']))<?php {
<?php $errorMsg .=<?php "<?php |<?php Erro da API:<?php "<?php .<?php $paymentData['message'];
<?php }
<?php if ($paymentData &&<?php isset($paymentData['errors']))<?php {
<?php $errorMsg .=<?php "<?php |<?php Erros:<?php "<?php .<?php json_encode($paymentData['errors']);
<?php }
<?php throw new Exception($errorMsg);
<?php }
<?php //<?php STEP 4:<?php Atualizar status para PAID usando colunas existentes $stmt =<?php $pdo->prepare("UPDATE saques SET <?php status =<?php 'PAID',<?php gateway =<?php 'pixup',
<?php transaction_id =<?php ?,
<?php webhook_data =<?php ?
<?php WHERE id =<?php ?");
<?php $updateResult =<?php $stmt->execute([
<?php $external_id,
<?php json_encode($paymentData),
<?php $saque_id ]);
<?php if (!$updateResult)<?php {
<?php throw new Exception("Erro ao atualizar status do saque no banco de dados");
<?php }
<?php if ($stmt->rowCount()<?php ===<?php 0)<?php {
<?php throw new Exception("Nenhuma linha foi atualizada -<?php saque ID $saque_id pode não existir");
<?php }
<?php }<?php elseif ($activeGateway ===<?php 'digitopay')<?php {
<?php //<?php =====<?php PROCESSAR COM DIGITOPAY =====
<?php require_once __DIR__ .<?php '/../classes/DigitoPay.php';
<?php $digitoPay =<?php new DigitoPay($pdo);
<?php //<?php Configurar URLs base para callback $protocol =<?php (!empty($_SERVER['HTTPS'])<?php &&<?php $_SERVER['HTTPS']<?php !==<?php 'off')<?php ?<?php 'https://'<?php :<?php 'http://';
<?php $host =<?php $_SERVER['HTTP_HOST'];
<?php $callbackUrl =<?php $protocol .<?php $host .<?php '/callback/digitopay_withdraw.php';
<?php $idempotencyKey =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php //<?php Criar saque via DigitoPay $withdrawData =<?php $digitoPay->createWithdraw(
<?php $saque['valor'],
<?php $saque['cpf'],
<?php $saque['nome'],
<?php $saque['cpf'],<?php //<?php pixKey (usando CPF)
<?php 'CPF',<?php //<?php pixKeyType $callbackUrl,
<?php $idempotencyKey );
<?php //<?php Atualizar o saque com os dados da transação $stmt =<?php $pdo->prepare("UPDATE saques SET <?php status =<?php 'PROCESSING',<?php gateway =<?php 'digitopay',
<?php transaction_id_digitopay =<?php :transaction_id,
<?php digitopay_idempotency_key =<?php :idempotency_key,
<?php webhook_data =<?php :response,
<?php updated_at =<?php NOW()<?php WHERE id =<?php :id");
<?php $stmt->execute([
<?php ':id'<?php =><?php $saque_id,
<?php ':transaction_id'<?php =><?php $withdrawData['transactionId']<?php ??<?php null,
<?php ':idempotency_key'<?php =><?php $idempotencyKey,
<?php ':response'<?php =><?php json_encode($withdrawData)
<?php ]);
<?php $pdo->commit();
<?php $_SESSION['success']<?php =<?php 'Saque enviado para processamento via DigitoPay!<?php Status:<?php '<?php .<?php ($withdrawData['status']<?php ??<?php 'PROCESSING');
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
<?php }
<?php $pdo->commit();
<?php $_SESSION['success']<?php =<?php 'Saque aprovado e pagamento realizado com sucesso!';
<?php }<?php catch (Exception $e)<?php {
<?php $pdo->rollBack();
<?php $_SESSION['failure']<?php =<?php 'Erro ao aprovar o saque:<?php '<?php .<?php $e->getMessage();
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

if (isset($_POST['reprovar_saque']))<?php {
<?php $saque_id =<?php $_POST['saque_id'];
<?php try {
<?php $pdo->beginTransaction();
<?php //<?php Buscar dados do saque antes de deletar $stmt =<?php $pdo->prepare("SELECT user_id,<?php valor FROM saques WHERE id =<?php ?");
<?php $stmt->execute([$saque_id]);
<?php $saque =<?php $stmt->fetch();
<?php if ($saque)<?php {
<?php //<?php Buscar saldo atual do usuário $stmt =<?php $pdo->prepare("SELECT saldo FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$saque['user_id']]);
<?php $saldoAtual =<?php $stmt->fetchColumn();
<?php //<?php Devolver o valor para o saldo do usuário $novoSaldo =<?php $saldoAtual +<?php $saque['valor'];
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php ?<?php WHERE id =<?php ?");
<?php $stmt->execute([$novoSaldo,<?php $saque['user_id']]);
<?php //<?php Registrar transação de estorno com saldos corretos $stmt =<?php $pdo->prepare("
<?php INSERT INTO transacoes (user_id,<?php tipo,<?php valor,<?php saldo_anterior,<?php saldo_posterior,<?php status,<?php descricao,<?php created_at)<?php VALUES (?,<?php 'REFUND',<?php ?,<?php ?,<?php ?,<?php 'COMPLETED',<?php 'Estorno de saque reprovado',<?php NOW())
<?php ");
<?php $stmt->execute([$saque['user_id'],<?php $saque['valor'],<?php $saldoAtual,<?php $novoSaldo]);
<?php }
<?php //<?php Deletar o saque $stmt =<?php $pdo->prepare("DELETE FROM saques WHERE id =<?php ?");
<?php $stmt->execute([$saque_id]);
<?php $pdo->commit();
<?php $_SESSION['success']<?php =<?php 'Saque reprovado e valor devolvido ao usuário!';
<?php }<?php catch (Exception $e)<?php {
<?php $pdo->rollBack();
<?php $_SESSION['failure']<?php =<?php 'Erro ao reprovar o saque:<?php '<?php .<?php $e->getMessage();
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

$nome =<?php ($stmt =<?php $pdo->prepare("SELECT nome FROM usuarios WHERE id =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;
$nome =<?php $nome ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;

$stmt =<?php $pdo->query("SELECT saques.id,<?php saques.user_id,<?php saques.valor,<?php saques.cpf,<?php saques.status,<?php saques.updated_at,<?php saques.gateway,<?php usuarios.nome <?php FROM saques <?php JOIN usuarios ON saques.user_id =<?php usuarios.id ORDER BY saques.updated_at DESC");
$saques =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);

//<?php Calculate statistics
$total_saques =<?php count($saques);
$saques_aprovados =<?php array_filter($saques,<?php function($s)<?php {<?php return in_array($s['status'],<?php ['PAID',<?php 'REALIZADO']);<?php 
});
$saques_pendentes =<?php array_filter($saques,<?php function($s)<?php {<?php return in_array($s['status'],<?php ['PENDING',<?php 'PROCESSING',<?php 'EM PROCESSAMENTO',<?php 'ANALISE']);<?php 
});
$valor_total_aprovado =<?php array_sum(array_column($saques_aprovados,<?php 'valor'));
$valor_total_pendente =<?php array_sum(array_column($saques_pendentes,<?php 'valor'));

//<?php Verificar gateway ativo para exibir na interface
$stmt =<?php $pdo->prepare("SELECT active FROM gateway LIMIT 1");
$stmt->execute();
$activeGateway =<?php $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?php <meta charset="UTF-8">
<?php <meta name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php echo $nomeSite ??<?php 'Admin';<?php ?><?php -<?php Gerenciar Saques</title>
<?php <?php <?php //<?php Se as variáveis não estiverem definidas,<?php buscar do banco if (!isset($faviconSite))<?php {
<?php try {
<?php $stmt =<?php $pdo->prepare("SELECT favicon FROM config WHERE id =<?php 1 LIMIT 1");
<?php $stmt->execute();
<?php $config_favicon =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $faviconSite =<?php $config_favicon['favicon']<?php ??<?php null;
<?php //<?php Se $nomeSite não estiver definido,<?php buscar também if (!isset($nomeSite))<?php {
<?php $stmt =<?php $pdo->prepare("SELECT nome_site FROM config WHERE id =<?php 1 LIMIT 1");
<?php $stmt->execute();
<?php $config_nome =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $nomeSite =<?php $config_nome['nome_site']<?php ??<?php 'Raspadinha';
<?php }
<?php }<?php catch (PDOException $e)<?php {
<?php $faviconSite =<?php null;
<?php $nomeSite =<?php $nomeSite ??<?php 'Raspadinha';
<?php }
<?php }
<?php ?>
<?php <?php if ($faviconSite &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $faviconSite)):<?php ?>
<?php <link rel="icon"<?php type="image/x-icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <link rel="shortcut icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <link rel="apple-touch-icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <?php else:<?php ?>
<?php <link rel="icon"<?php href="data:image/svg+xml,<?php=<?php urlencode('<svg xmlns="http://www.w3.org/2000/svg"<?php viewBox="0 0 100 100"><rect width="100"<?php height="100"<?php fill="#22c55e"/><text x="50"<?php y="50"<?php text-anchor="middle"<?php dominant-baseline="middle"<?php fill="white"<?php font-family="Arial"<?php font-size="40"<?php font-weight="bold">'<?php .<?php strtoupper(substr($nomeSite,<?php 0,<?php 1))<?php .<?php '</text></svg>')<?php ?>"/>
<?php <?php endif;<?php ?>
<?php <!--<?php TailwindCSS -->
<?php <script src="https://cdn.tailwindcss.com"></script>
<?php <!--<?php Font Awesome -->
<?php <link rel="stylesheet"<?php href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php <!--<?php Notiflix -->
<?php <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<?php <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet">
<?php <!--<?php Google Fonts -->
<?php <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"<?php rel="stylesheet">
<?php <style>
<?php *<?php {
<?php margin:<?php 0;
<?php padding:<?php 0;
<?php box-sizing:<?php border-box;
<?php }
<?php body {
<?php font-family:<?php 'Inter',<?php -apple-system,<?php BlinkMacSystemFont,<?php sans-serif;
<?php background:<?php #000000;
<?php color:<?php #ffffff;
<?php min-height:<?php 100vh;
<?php overflow-x:<?php hidden;
<?php }
<?php /*<?php Sidebar Styles */
<?php .sidebar {
<?php position:<?php fixed;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 320px;
<?php height:<?php 100vh;
<?php background:<?php linear-gradient(145deg,<?php #0a0a0a 0%,<?php #141414 25%,<?php #1a1a1a 50%,<?php #0f0f0f 100%);
<?php backdrop-filter:<?php blur(20px);
<?php border-right:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php transition:<?php all 0.4s cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php z-index:<?php 1000;
<?php box-shadow:<?php 0 0 50px rgba(34,<?php 197,<?php 94,<?php 0.1),
<?php inset 1px 0 0 rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php }
<?php .sidebar::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background:<?php radial-gradient(circle at 20%<?php 20%,<?php rgba(34,<?php 197,<?php 94,<?php 0.15)<?php 0%,<?php transparent 50%),
<?php radial-gradient(circle at 80%<?php 80%,<?php rgba(16,<?php 185,<?php 129,<?php 0.1)<?php 0%,<?php transparent 50%),
<?php radial-gradient(circle at 40%<?php 60%,<?php rgba(59,<?php 130,<?php 246,<?php 0.05)<?php 0%,<?php transparent 50%);
<?php opacity:<?php 0.8;
<?php pointer-events:<?php none;
<?php }
<?php .sidebar.hidden {
<?php transform:<?php translateX(-100%);
<?php }
<?php .sidebar-header {
<?php position:<?php relative;
<?php padding:<?php 2.5rem 2rem;
<?php border-bottom:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent 100%);
<?php }
<?php .logo {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php text-decoration:<?php none;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }
<?php .logo-icon {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e 0%,<?php #16a34a 100%);
<?php border-radius:<?php 16px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.5rem;
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0 8px 20px rgba(34,<?php 197,<?php 94,<?php 0.3),
<?php 0 4px 8px rgba(0,<?php 0,<?php 0,<?php 0.2);
<?php position:<?php relative;
<?php }
<?php .logo-icon::after {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php -2px;
<?php left:<?php -2px;
<?php right:<?php -2px;
<?php bottom:<?php -2px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a,<?php #22c55e);
<?php border-radius:<?php 18px;
<?php z-index:<?php -1;
<?php opacity:<?php 0;
<?php transition:<?php opacity 0.3s ease;
<?php }
<?php .logo:hover .logo-icon::after {
<?php opacity:<?php 1;
<?php }
<?php .logo-text {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php }
<?php .logo-title {
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php line-height:<?php 1.2;
<?php }
<?php .logo-subtitle {
<?php font-size:<?php 0.75rem;
<?php color:<?php #22c55e;
<?php font-weight:<?php 500;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 1px;
<?php }
<?php .nav-menu {
<?php padding:<?php 2rem 0;
<?php position:<?php relative;
<?php }
<?php .nav-section {
<?php margin-bottom:<?php 2rem;
<?php }
<?php .nav-section-title {
<?php padding:<?php 0 2rem 0.75rem 2rem;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php color:<?php #6b7280;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 1px;
<?php position:<?php relative;
<?php }
<?php .nav-item {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php padding:<?php 1rem 2rem;
<?php color:<?php #a1a1aa;
<?php text-decoration:<?php none;
<?php transition:<?php all 0.3s cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php position:<?php relative;
<?php margin:<?php 0.25rem 1rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 500;
<?php }
<?php .nav-item::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php left:<?php 0;
<?php top:<?php 0;
<?php bottom:<?php 0;
<?php width:<?php 4px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-radius:<?php 0 4px 4px 0;
<?php transform:<?php scaleY(0);
<?php transition:<?php transform 0.3s cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php }
<?php .nav-item:hover::before,
<?php .nav-item.active::before {
<?php transform:<?php scaleY(1);
<?php }
<?php .nav-item:hover,
<?php .nav-item.active {
<?php color:<?php #ffffff;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.15)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php transform:<?php translateX(4px);
<?php box-shadow:<?php 0 4px 20px rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }
<?php .nav-icon {
<?php width:<?php 24px;
<?php height:<?php 24px;
<?php margin-right:<?php 1rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1rem;
<?php position:<?php relative;
<?php }
<?php .nav-text {
<?php font-size:<?php 0.95rem;
<?php flex:<?php 1;
<?php }
<?php .nav-badge {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php font-size:<?php 0.7rem;
<?php font-weight:<?php 600;
<?php padding:<?php 0.25rem 0.5rem;
<?php border-radius:<?php 6px;
<?php min-width:<?php 20px;
<?php text-align:<?php center;
<?php box-shadow:<?php 0 2px 8px rgba(239,<?php 68,<?php 68,<?php 0.3);
<?php }
<?php .sidebar-footer {
<?php position:<?php absolute;
<?php bottom:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php padding:<?php 2rem;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent 100%);
<?php border-top:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php .user-profile {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php padding:<?php 1rem;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php transition:<?php all 0.3s ease;
<?php }
<?php .user-profile:hover {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }
<?php .user-avatar {
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-radius:<?php 10px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-weight:<?php 700;
<?php color:<?php #ffffff;
<?php font-size:<?php 1rem;
<?php box-shadow:<?php 0 4px 12px rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }
<?php .user-info {
<?php flex:<?php 1;
<?php }
<?php .user-name {
<?php font-weight:<?php 600;
<?php color:<?php #ffffff;
<?php font-size:<?php 0.9rem;
<?php line-height:<?php 1.2;
<?php }
<?php .user-role {
<?php font-size:<?php 0.75rem;
<?php color:<?php #22c55e;
<?php font-weight:<?php 500;
<?php }
<?php /*<?php Main Content */
<?php .main-content {
<?php margin-left:<?php 320px;
<?php min-height:<?php 100vh;
<?php transition:<?php margin-left 0.4s cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php background:<?php radial-gradient(circle at 10%<?php 20%,<?php rgba(34,<?php 197,<?php 94,<?php 0.03)<?php 0%,<?php transparent 50%),
<?php radial-gradient(circle at 80%<?php 80%,<?php rgba(16,<?php 185,<?php 129,<?php 0.02)<?php 0%,<?php transparent 50%),
<?php radial-gradient(circle at 40%<?php 40%,<?php rgba(59,<?php 130,<?php 246,<?php 0.01)<?php 0%,<?php transparent 50%);
<?php }
<?php .main-content.expanded {
<?php margin-left:<?php 0;
<?php }
<?php .header {
<?php position:<?php sticky;
<?php top:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.95);
<?php backdrop-filter:<?php blur(20px);
<?php border-bottom:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php padding:<?php 1.5rem 2.5rem;
<?php z-index:<?php 100;
<?php box-shadow:<?php 0 4px 20px rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php .header-content {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php }
<?php .menu-toggle {
<?php display:<?php none;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php rgba(34,<?php 197,<?php 94,<?php 0.05));
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php color:<?php #22c55e;
<?php padding:<?php 0.75rem;
<?php border-radius:<?php 12px;
<?php font-size:<?php 1.1rem;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php }
<?php .menu-toggle:hover {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php transform:<?php scale(1.05);
<?php }
<?php .header-title {
<?php font-size:<?php 1.75rem;
<?php font-weight:<?php 700;
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #a1a1aa);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
<?php }
<?php .header-actions {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php }
<?php .page-content {
<?php padding:<?php 2.5rem;
<?php }
<?php .welcome-section {
<?php margin-bottom:<?php 3rem;
<?php }
<?php .welcome-title {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 800;
<?php margin-bottom:<?php 0.75rem;
<?php background:<?php linear-gradient(135deg,<?php #ffffff 0%,<?php #fff 50%,<?php #fff 100%);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
<?php line-height:<?php 1.2;
<?php }
<?php .welcome-subtitle {
<?php font-size:<?php 1.25rem;
<?php color:<?php #6b7280;
<?php font-weight:<?php 400;
<?php }
<?php .stats-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php margin-bottom:<?php 3rem;
<?php }
<?php .mini-stat-card {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php padding:<?php 1.5rem;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php transition:<?php all 0.3s ease;
<?php backdrop-filter:<?php blur(20px);
<?php }
<?php .mini-stat-card::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 3px;
<?php background:<?php linear-gradient(90deg,<?php #22c55e,<?php #16a34a);
<?php opacity:<?php 0;
<?php transition:<?php opacity 0.3s ease;
<?php }
<?php .mini-stat-card:hover::before {
<?php opacity:<?php 1;
<?php }
<?php .mini-stat-card:hover {
<?php transform:<?php translateY(-4px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php box-shadow:<?php 0 15px 35px rgba(0,<?php 0,<?php 0,<?php 0.4);
<?php }
<?php .mini-stat-header {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php margin-bottom:<?php 1rem;
<?php }
<?php .mini-stat-icon {
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-radius:<?php 10px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php #22c55e;
<?php font-size:<?php 1rem;
<?php }
<?php .mini-stat-icon.warning {
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2)<?php 0%,<?php rgba(251,<?php 191,<?php 36,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(251,<?php 191,<?php 36,<?php 0.3);
<?php color:<?php #f59e0b;
<?php }
<?php .mini-stat-value {
<?php font-size:<?php 1.75rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.25rem;
<?php }
<?php .mini-stat-label {
<?php color:<?php #a1a1aa;
<?php font-size:<?php 0.875rem;
<?php font-weight:<?php 500;
<?php }
<?php .withdrawals-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(450px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php }
<?php .withdrawal-card {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php transition:<?php all 0.3s ease;
<?php backdrop-filter:<?php blur(20px);
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }
<?php .withdrawal-card::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php right:<?php 0;
<?php width:<?php 100px;
<?php height:<?php 100px;
<?php background:<?php radial-gradient(circle,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent 70%);
<?php opacity:<?php 0;
<?php transition:<?php opacity 0.3s ease;
<?php }
<?php .withdrawal-card:hover::before {
<?php opacity:<?php 1;
<?php }
<?php .withdrawal-card:hover {
<?php transform:<?php translateY(-4px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php box-shadow:<?php 0 20px 40px rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php .withdrawal-header {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php flex-start;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .withdrawal-user {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 700;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php .withdrawal-cpf {
<?php font-size:<?php 0.9rem;
<?php color:<?php #6b7280;
<?php font-family:<?php 'Monaco',<?php 'Consolas',<?php monospace;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php padding:<?php 0.25rem 0.5rem;
<?php border-radius:<?php 6px;
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php display:<?php inline-flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php }
<?php .withdrawal-status {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php padding:<?php 0.5rem 1rem;
<?php border-radius:<?php 12px;
<?php font-size:<?php 0.875rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.5px;
<?php }
<?php .withdrawal-status.approved {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(34,<?php 197,<?php 94,<?php 0.1));
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php color:<?php #22c55e;
<?php }
<?php .withdrawal-status.pending {
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2),<?php rgba(251,<?php 191,<?php 36,<?php 0.1));
<?php border:<?php 1px solid rgba(251,<?php 191,<?php 36,<?php 0.3);
<?php color:<?php #f59e0b;
<?php }
<?php .status-dot {
<?php width:<?php 8px;
<?php height:<?php 8px;
<?php border-radius:<?php 50%;
<?php background:<?php currentColor;
<?php }
<?php .withdrawal-value {
<?php font-size:<?php 2rem;
<?php font-weight:<?php 800;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
<?php margin-bottom:<?php 1rem;
<?php }
<?php .withdrawal-actions {
<?php display:<?php flex;
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1rem;
<?php }
<?php .action-btn {
<?php flex:<?php 1;
<?php padding:<?php 0.875rem 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php border:<?php none;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php }
<?php .action-btn:hover {
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 8px 20px rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php .btn-approve {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php .btn-approve:hover {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);
<?php box-shadow:<?php 0 8px 20px rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }
<?php .btn-reject {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php }
<?php .btn-reject:hover {
<?php background:<?php linear-gradient(135deg,<?php #dc2626,<?php #b91c1c);
<?php box-shadow:<?php 0 8px 20px rgba(239,<?php 68,<?php 68,<?php 0.4);
<?php }
<?php .btn-disabled {
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);
<?php color:<?php #9ca3af;
<?php cursor:<?php not-allowed;
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php .btn-disabled:hover {
<?php transform:<?php none;
<?php box-shadow:<?php none;
<?php }
<?php .withdrawal-date {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.875rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php padding-top:<?php 1rem;
<?php border-top:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php .withdrawal-date i {
<?php color:<?php #6b7280;
<?php }
<?php .empty-state {
<?php text-align:<?php center;
<?php padding:<?php 4rem 2rem;
<?php color:<?php #6b7280;
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.3)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.4)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php border-radius:<?php 20px;
<?php backdrop-filter:<?php blur(10px);
<?php }
<?php .empty-state i {
<?php font-size:<?php 4rem;
<?php margin-bottom:<?php 1.5rem;
<?php opacity:<?php 0.3;
<?php color:<?php #374151;
<?php }
<?php .empty-state h3 {
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 600;
<?php color:<?php #9ca3af;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php .empty-state p {
<?php font-size:<?php 1rem;
<?php font-weight:<?php 400;
<?php }
<?php /*<?php Mobile Styles */
<?php @media (max-width:<?php 1024px)<?php {
<?php .sidebar {
<?php transform:<?php translateX(-100%);
<?php width:<?php 300px;
<?php z-index:<?php 1001;
<?php }
<?php .sidebar:not(.hidden)<?php {
<?php transform:<?php translateX(0);
<?php }
<?php .main-content {
<?php margin-left:<?php 0;
<?php }
<?php .menu-toggle {
<?php display:<?php block;
<?php }
<?php .header-actions span {
<?php display:<?php none !important;
<?php }
<?php .stats-grid {
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
<?php }
<?php .withdrawals-grid {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php }
<?php @media (max-width:<?php 768px)<?php {
<?php .header {
<?php padding:<?php 1rem;
<?php }
<?php .page-content {
<?php padding:<?php 1.5rem;
<?php }
<?php .welcome-title {
<?php font-size:<?php 2.25rem;
<?php }
<?php .withdrawal-card {
<?php padding:<?php 1.5rem;
<?php }
<?php .withdrawal-actions {
<?php flex-direction:<?php column;
<?php }
<?php .sidebar {
<?php width:<?php 280px;
<?php }
<?php }
<?php @media (max-width:<?php 480px)<?php {
<?php .welcome-title {
<?php font-size:<?php 1.875rem;
<?php }
<?php .stats-grid {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php .withdrawal-header {
<?php flex-direction:<?php column;
<?php align-items:<?php flex-start;
<?php gap:<?php 1rem;
<?php }
<?php .withdrawal-value {
<?php font-size:<?php 1.5rem;
<?php }
<?php .sidebar {
<?php width:<?php 260px;
<?php }
<?php }
<?php .overlay {
<?php position:<?php fixed;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php z-index:<?php 1000;
<?php opacity:<?php 0;
<?php visibility:<?php hidden;
<?php transition:<?php all 0.3s ease;
<?php backdrop-filter:<?php blur(4px);
<?php }
<?php .overlay.active {
<?php opacity:<?php 1;
<?php visibility:<?php visible;
<?php }
<?php </style>
</head>
<body>
<?php <!--<?php Notifications -->
<?php <?php if (isset($_SESSION['success'])):<?php ?>
<?php <script>
<?php Notiflix.Notify.success('<?php=<?php $_SESSION['success']<?php ?>');
<?php </script>
<?php <?php unset($_SESSION['success']);<?php ?>
<?php <?php elseif (isset($_SESSION['failure'])):<?php ?>
<?php <script>
<?php Notiflix.Notify.failure('<?php=<?php $_SESSION['failure']<?php ?>');
<?php </script>
<?php <?php unset($_SESSION['failure']);<?php ?>
<?php <?php endif;<?php ?>

<?php <!--<?php Overlay for mobile -->
<?php <div class="overlay"<?php id="overlay"></div>
<?php <!--<?php Sidebar -->
<?php <aside class="sidebar"<?php id="sidebar">
<?php <div class="sidebar-header">
<?php <a href="#"<?php class="logo">
<?php <div class="logo-icon">
<?php <i class="fas fa-bolt"></i>
<?php </div>
<?php <div class="logo-text">
<?php <div class="logo-title">Dashboard</div>
<?php </div>
<?php </a>
<?php </div>
<?php <nav class="nav-menu">
<?php <div class="nav-section">
<?php <div class="nav-section-title">Principal</div>
<?php <a href="index.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-chart-pie"></i></div>
<?php <div class="nav-text">Dashboard</div>
<?php </a>
<?php </div>
<?php <div class="nav-section">
<?php <div class="nav-section-title">Gestão</div>
<?php <a href="usuarios.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-user"></i></div>
<?php <div class="nav-text">Usuários</div>
<?php </a>
<?php <a href="afiliados.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-user-plus"></i></div>
<?php <div class="nav-text">Afiliados</div>
<?php </a>
<?php <a href="depositos.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
<?php <div class="nav-text">Depósitos</div>
<?php </a>
<?php <a href="saques.php"<?php class="nav-item active">
<?php <div class="nav-icon"><i class="fas fa-money-bill-wave"></i></div>
<?php <div class="nav-text">Saques</div>
<?php </a>
<?php </div>
<?php <div class="nav-section">
<?php <div class="nav-section-title">Sistema</div>
<?php <a href="config.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-cogs"></i></div>
<?php <div class="nav-text">Configurações</div>
<?php </a>
<?php <a href="gateway.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-usd"></i></div>
<?php <div class="nav-text">Gateway</div>
<?php </a>
<?php <a href="banners.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-images"></i></div>
<?php <div class="nav-text">Banners</div>
<?php </a>
<?php <a href="cartelas.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-diamond"></i></div>
<?php <div class="nav-text">Raspadinhas</div>
<?php </a>
<?php <a href="../logout"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div>
<?php <div class="nav-text">Sair</div>
<?php </a>
<?php </div>
<?php </nav>
<?php </aside>
<?php <!--<?php Main Content -->
<?php <main class="main-content"<?php id="mainContent">
<?php <!--<?php Header -->
<?php <header class="header">
<?php <div class="header-content">
<?php <div style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 1rem;">
<?php <button class="menu-toggle"<?php id="menuToggle">
<?php <i class="fas fa-bars"></i>
<?php </button>
<?php </div>
<?php <div class="header-actions">
<?php <span style="color:<?php #a1a1aa;<?php font-size:<?php 0.9rem;<?php display:<?php none;">Bem-vindo,<?php <?php=<?php htmlspecialchars($nome)<?php ?></span>
<?php <div class="user-avatar">
<?php <?php=<?php strtoupper(substr($nome,<?php 0,<?php 1))<?php ?>
<?php </div>
<?php </div>
<?php </div>
<?php </header>
<?php <!--<?php Page Content -->
<?php <div class="page-content">
<?php <!--<?php Welcome Section -->
<?php <section class="welcome-section">
<?php <h2 class="welcome-title">Gestão de Saques</h2>
<?php <p class="welcome-subtitle">Aprove ou reprove solicitações de saque via PIX de forma segura</p>
<?php </section>
<?php <!--<?php Stats Grid -->
<?php <section class="stats-grid">
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon">
<?php <i class="fas fa-money-bill-wave"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value"><?php=<?php number_format($total_saques,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Total de Saques</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon">
<?php <i class="fas fa-check-circle"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value"><?php=<?php number_format(count($saques_aprovados),<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Saques Aprovados</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon warning">
<?php <i class="fas fa-clock"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value"><?php=<?php number_format(count($saques_pendentes),<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Saques Pendentes</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon">
<?php <i class="fas fa-dollar-sign"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value">R$<?php <?php=<?php number_format($valor_total_aprovado,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Valor Total Pago</div>
<?php </div>
<?php </section>
<?php <!--<?php Withdrawals Section -->
<?php <section>
<?php <?php if (empty($saques)):<?php ?>
<?php <div class="empty-state">
<?php <i class="fas fa-money-bill-wave"></i>
<?php <h3>Nenhum saque encontrado</h3>
<?php <p>Não há<?php solicitações de saque registradas no sistema ainda</p>
<?php </div>
<?php <?php else:<?php ?>
<?php <div class="withdrawals-grid">
<?php <?php foreach ($saques as $saque):<?php ?>
<?php <div class="withdrawal-card">
<?php <div class="withdrawal-header">
<?php <div>
<?php <h3 class="withdrawal-user"><?php=<?php htmlspecialchars($saque['nome'])<?php ?></h3>
<?php <div class="withdrawal-cpf">
<?php <i class="fas fa-key"></i>
<?php PIX:<?php <?php=<?php htmlspecialchars($saque['cpf'])<?php ?>
<?php </div>
<?php </div>
<?php <div class="withdrawal-status <?php=<?php $saque['status']<?php ==<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>">
<?php <div class="status-dot"></div>
<?php <span><?php=<?php $saque['status']<?php ==<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?></span>
<?php </div>
<?php </div>
<?php <div class="withdrawal-value">
<?php R$<?php <?php=<?php number_format($saque['valor'],<?php 2,<?php ',',<?php '.')<?php ?>
<?php </div>
<?php <?php if ($saque['status']<?php ==<?php 'PENDING'):<?php ?>
<?php <div class="withdrawal-actions">
<?php <form method="POST"<?php style="flex:<?php 1;">
<?php <input type="hidden"<?php name="saque_id"<?php value="<?php=<?php $saque['id']<?php ?>">
<?php <button type="submit"<?php name="aprovar_saque"<?php class="action-btn btn-approve"<?php onclick="openLoading()">
<?php <i class="fas fa-check"></i>
<?php Aprovar Saque </button>
<?php </form>
<?php <form method="POST"<?php style="flex:<?php 1;">
<?php <input type="hidden"<?php name="saque_id"<?php value="<?php=<?php $saque['id']<?php ?>">
<?php <button type="submit"<?php name="reprovar_saque"<?php class="action-btn btn-reject"<?php onclick="openLoading()">
<?php <i class="fas fa-times"></i>
<?php Reprovar </button>
<?php </form>
<?php </div>
<?php <?php else:<?php ?>
<?php <div class="withdrawal-actions">
<?php <button class="action-btn btn-disabled"<?php disabled>
<?php <i class="fas fa-check-double"></i>
<?php Saque Processado </button>
<?php </div>
<?php <?php endif;<?php ?>
<?php <div class="withdrawal-date">
<?php <i class="fas fa-calendar"></i>
<?php <span><?php=<?php date('d/m/Y H:i',<?php strtotime($saque['updated_at']))<?php ?></span>
<?php </div>
<?php </div>
<?php <?php endforeach;<?php ?>
<?php </div>
<?php <?php endif;<?php ?>
<?php </section>
<?php </div>
<?php </main>
<?php <script>
<?php //<?php Loading function function openLoading()<?php {
<?php Notiflix.Loading.standard('Processando solicitação...');
<?php }
<?php //<?php Mobile menu toggle const menuToggle =<?php document.getElementById('menuToggle');
<?php const sidebar =<?php document.getElementById('sidebar');
<?php const mainContent =<?php document.getElementById('mainContent');
<?php const overlay =<?php document.getElementById('overlay');
<?php menuToggle.addEventListener('click',<?php ()<?php =><?php {
<?php const isHidden =<?php sidebar.classList.contains('hidden');
<?php if (isHidden)<?php {
<?php sidebar.classList.remove('hidden');
<?php overlay.classList.add('active');
<?php }<?php else {
<?php sidebar.classList.add('hidden');
<?php overlay.classList.add('active');
<?php }
<?php });
<?php overlay.addEventListener('click',<?php ()<?php =><?php {
<?php sidebar.classList.add('hidden');
<?php overlay.classList.remove('active');
<?php });
<?php //<?php Close sidebar on window resize if it's mobile window.addEventListener('resize',<?php ()<?php =><?php {
<?php if (window.innerWidth <=<?php 1024)<?php {
<?php sidebar.classList.add('hidden');
<?php overlay.classList.remove('active');
<?php }<?php else {
<?php sidebar.classList.remove('hidden');
<?php overlay.classList.remove('active');
<?php }
<?php });
<?php //<?php Enhanced hover effects for nav items document.querySelectorAll('.nav-item').forEach(item =><?php {
<?php item.addEventListener('mouseenter',<?php function()<?php {
<?php this.style.transform =<?php 'translateX(8px)';
<?php });
<?php item.addEventListener('mouseleave',<?php function()<?php {
<?php if (!this.classList.contains('active'))<?php {
<?php this.style.transform =<?php 'translateX(0)';
<?php }
<?php });
<?php });
<?php //<?php Smooth scroll behavior document.documentElement.style.scrollBehavior =<?php 'smooth';
<?php //<?php Initialize document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {
<?php console.log('%c💸<?php Gerenciamento de Saques carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php //<?php Check if mobile on load if (window.innerWidth <=<?php 1024)<?php {
<?php sidebar.classList.add('hidden');
<?php }
<?php //<?php Animate cards on load const withdrawalCards =<?php document.querySelectorAll('.withdrawal-card');
<?php withdrawalCards.forEach((card,<?php index)<?php =><?php {
<?php card.style.opacity =<?php '0';
<?php card.style.transform =<?php 'translateY(20px)';
<?php setTimeout(()<?php =><?php {
<?php card.style.transition =<?php 'all 0.6s ease';
<?php card.style.opacity =<?php '1';
<?php card.style.transform =<?php 'translateY(0)';
<?php },<?php index *<?php 100);
<?php });
<?php //<?php Animate stats cards const statCards =<?php document.querySelectorAll('.mini-stat-card');
<?php statCards.forEach((card,<?php index)<?php =><?php {
<?php card.style.opacity =<?php '0';
<?php card.style.transform =<?php 'translateY(20px)';
<?php setTimeout(()<?php =><?php {
<?php card.style.transition =<?php 'all 0.6s ease';
<?php card.style.opacity =<?php '1';
<?php card.style.transform =<?php 'translateY(0)';
<?php },<?php index *<?php 150);
<?php });
<?php });
<?php </script>
</body>
</html>