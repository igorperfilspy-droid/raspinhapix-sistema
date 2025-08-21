<?php
include<?php '../includes/session.php';
include<?php '../conexao.php';
include<?php '../includes/notiflix.php';

$usuarioId<?php =<?php $_SESSION['usuario_id'];
$admin<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php admin<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;

if<?php ($admin<?php !=<?php 1)<?php {
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php não<?php é<?php um<?php administrador!'];
<?php header("Location:<?php /");
<?php exit;
}

if<?php (isset($_POST['aprovar_saque']))<?php {
<?php $saque_id<?php =<?php $_POST['saque_id'];
<?php 
<?php try<?php {
<?php $pdo->beginTransaction();
<?php 
<?php //<?php Verificar<?php qual<?php gateway<?php está<?php ativo
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php active<?php FROM<?php gateway<?php LIMIT<?php 1");
<?php $stmt->execute();
<?php $activeGateway<?php =<?php $stmt->fetchColumn();
<?php 
<?php if<?php (!in_array($activeGateway,<?php ['pixup',<?php 'digitopay']))<?php {
<?php throw<?php new<?php Exception('Gateway<?php não<?php configurado<?php ou<?php não<?php suportado.');
<?php }
<?php 
<?php //<?php Buscar<?php dados<?php do<?php saque
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php s.*,<?php u.nome,<?php u.email<?php FROM<?php saques<?php s<?php JOIN<?php usuarios<?php u<?php ON<?php s.user_id<?php =<?php u.id<?php WHERE<?php s.id<?php =<?php ?<?php LIMIT<?php 1<?php FOR<?php UPDATE");
<?php $stmt->execute([$saque_id]);
<?php $saque<?php =<?php $stmt->fetch();
<?php 
<?php if<?php (!$saque)<?php {
<?php throw<?php new<?php Exception("Saque<?php não<?php encontrado");
<?php }
<?php 
<?php if<?php ($activeGateway<?php ===<?php 'pixup')<?php {
<?php //<?php =====<?php PROCESSAR<?php COM<?php PIXUP<?php =====
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php ci,<?php cs,<?php url<?php FROM<?php pixup<?php LIMIT<?php 1");
<?php $stmt->execute();
<?php $pixup<?php =<?php $stmt->fetch();
<?php 
<?php if<?php (!$pixup)<?php {
<?php throw<?php new<?php Exception("Credenciais<?php PIXUP<?php não<?php configuradas");
<?php }
<?php 
<?php $auth<?php =<?php base64_encode($pixup['ci']<?php .<?php ':'<?php .<?php $pixup['cs']);
<?php 
<?php //<?php STEP<?php 1:<?php Obter<?php Token<?php (FORÇANDO<?php IPv4)
<?php $ch<?php =<?php curl_init($pixup['url']<?php .<?php '/v2/oauth/token');
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_POST,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 30);
<?php curl_setopt($ch,<?php CURLOPT_CONNECTTIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php 2);
<?php curl_setopt($ch,<?php CURLOPT_IPRESOLVE,<?php CURL_IPRESOLVE_V4);<?php //<?php Forçar<?php IPv4
<?php curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php [
<?php 'Authorization:<?php Basic<?php '<?php .<?php $auth,
<?php 'Content-Type:<?php application/json',
<?php 'Accept:<?php application/json',
<?php 'User-Agent:<?php PHP-CURL/7.0'
<?php ]);
<?php 
<?php $tokenResponse<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError<?php =<?php curl_error($ch);
<?php curl_close($ch);
<?php 
<?php if<?php ($curlError)<?php {
<?php throw<?php new<?php Exception("Erro<?php cURL<?php no<?php token:<?php "<?php .<?php $curlError);
<?php }
<?php 
<?php $tokenData<?php =<?php json_decode($tokenResponse,<?php true);
<?php 
<?php if<?php ($httpCode<?php !==<?php 200<?php ||<?php !isset($tokenData['access_token']))<?php {
<?php throw<?php new<?php Exception("Falha<?php ao<?php obter<?php token<?php de<?php acesso<?php da<?php PIXUP.<?php HTTP:<?php $httpCode<?php |<?php Response:<?php $tokenResponse");
<?php }
<?php 
<?php $accessToken<?php =<?php $tokenData['access_token'];
<?php 
<?php //<?php STEP<?php 2:<?php Preparar<?php Payload
<?php $external_id<?php =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php $cpf_limpo<?php =<?php preg_replace('/\D/',<?php '',<?php $saque['cpf']);
<?php 
<?php $payload<?php =<?php [
<?php 'amount'<?php =><?php (float)$saque['valor'],
<?php 'description'<?php =><?php 'Saque<?php Raspadinha<?php -<?php ID:<?php '<?php .<?php $saque['id'],
<?php 'external_id'<?php =><?php $external_id,
<?php 'creditParty'<?php =><?php [
<?php 'name'<?php =><?php trim($saque['nome']),
<?php 'keyType'<?php =><?php 'CPF',
<?php 'key'<?php =><?php $cpf_limpo,
<?php 'taxId'<?php =><?php $cpf_limpo
<?php ]
<?php ];
<?php 
<?php //<?php STEP<?php 3:<?php Fazer<?php Pagamento<?php (FORÇANDO<?php IPv4)
<?php $ch<?php =<?php curl_init($pixup['url']<?php .<?php '/v2/pix/payment');
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_POST,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 60);
<?php curl_setopt($ch,<?php CURLOPT_CONNECTTIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php 2);
<?php curl_setopt($ch,<?php CURLOPT_POSTFIELDS,<?php json_encode($payload));
<?php curl_setopt($ch,<?php CURLOPT_IPRESOLVE,<?php CURL_IPRESOLVE_V4);<?php //<?php Forçar<?php IPv4
<?php curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php [
<?php 'Authorization:<?php Bearer<?php '<?php .<?php $accessToken,
<?php 'Content-Type:<?php application/json',
<?php 'Accept:<?php application/json',
<?php 'User-Agent:<?php PHP-CURL/7.0'
<?php ]);
<?php 
<?php $paymentResponse<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError<?php =<?php curl_error($ch);
<?php curl_close($ch);
<?php 
<?php if<?php ($curlError)<?php {
<?php throw<?php new<?php Exception("Erro<?php cURL<?php no<?php pagamento:<?php "<?php .<?php $curlError);
<?php }
<?php 
<?php $paymentData<?php =<?php json_decode($paymentResponse,<?php true);
<?php 
<?php if<?php ($httpCode<?php !==<?php 200<?php &&<?php $httpCode<?php !==<?php 201)<?php {
<?php $errorMsg<?php =<?php "Falha<?php ao<?php processar<?php pagamento<?php na<?php PIXUP.<?php HTTP:<?php $httpCode<?php |<?php Response:<?php $paymentResponse";
<?php 
<?php if<?php ($paymentData<?php &&<?php isset($paymentData['message']))<?php {
<?php $errorMsg<?php .=<?php "<?php |<?php Erro<?php da<?php API:<?php "<?php .<?php $paymentData['message'];
<?php }
<?php if<?php ($paymentData<?php &&<?php isset($paymentData['errors']))<?php {
<?php $errorMsg<?php .=<?php "<?php |<?php Erros:<?php "<?php .<?php json_encode($paymentData['errors']);
<?php }
<?php 
<?php throw<?php new<?php Exception($errorMsg);
<?php }
<?php 
<?php //<?php STEP<?php 4:<?php Atualizar<?php status<?php para<?php PAID<?php usando<?php colunas<?php existentes
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php saques<?php SET<?php 
<?php status<?php =<?php 'PAID',<?php 
<?php gateway<?php =<?php 'pixup',
<?php transaction_id<?php =<?php ?,
<?php webhook_data<?php =<?php ?
<?php WHERE<?php id<?php =<?php ?");
<?php 
<?php $updateResult<?php =<?php $stmt->execute([
<?php $external_id,
<?php json_encode($paymentData),
<?php $saque_id
<?php ]);
<?php 
<?php if<?php (!$updateResult)<?php {
<?php throw<?php new<?php Exception("Erro<?php ao<?php atualizar<?php status<?php do<?php saque<?php no<?php banco<?php de<?php dados");
<?php }
<?php 
<?php if<?php ($stmt->rowCount()<?php ===<?php 0)<?php {
<?php throw<?php new<?php Exception("Nenhuma<?php linha<?php foi<?php atualizada<?php -<?php saque<?php ID<?php $saque_id<?php pode<?php não<?php existir");
<?php }
<?php 
<?php }<?php elseif<?php ($activeGateway<?php ===<?php 'digitopay')<?php {
<?php //<?php =====<?php PROCESSAR<?php COM<?php DIGITOPAY<?php =====
<?php require_once<?php __DIR__<?php .<?php '/../classes/DigitoPay.php';
<?php 
<?php $digitoPay<?php =<?php new<?php DigitoPay($pdo);
<?php 
<?php //<?php Configurar<?php URLs<?php base<?php para<?php callback
<?php $protocol<?php =<?php (!empty($_SERVER['HTTPS'])<?php &&<?php $_SERVER['HTTPS']<?php !==<?php 'off')<?php ?<?php 'https://'<?php :<?php 'http://';
<?php $host<?php =<?php $_SERVER['HTTP_HOST'];
<?php $callbackUrl<?php =<?php $protocol<?php .<?php $host<?php .<?php '/callback/digitopay_withdraw.php';
<?php 
<?php $idempotencyKey<?php =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php 
<?php //<?php Criar<?php saque<?php via<?php DigitoPay
<?php $withdrawData<?php =<?php $digitoPay->createWithdraw(
<?php $saque['valor'],
<?php $saque['cpf'],
<?php $saque['nome'],
<?php $saque['cpf'],<?php //<?php pixKey<?php (usando<?php CPF)
<?php 'CPF',<?php //<?php pixKeyType
<?php $callbackUrl,
<?php $idempotencyKey
<?php );
<?php 
<?php //<?php Atualizar<?php o<?php saque<?php com<?php os<?php dados<?php da<?php transação
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php saques<?php SET<?php 
<?php status<?php =<?php 'PROCESSING',<?php 
<?php gateway<?php =<?php 'digitopay',
<?php transaction_id_digitopay<?php =<?php :transaction_id,
<?php digitopay_idempotency_key<?php =<?php :idempotency_key,
<?php webhook_data<?php =<?php :response,
<?php updated_at<?php =<?php NOW()<?php 
<?php WHERE<?php id<?php =<?php :id");
<?php 
<?php $stmt->execute([
<?php ':id'<?php =><?php $saque_id,
<?php ':transaction_id'<?php =><?php $withdrawData['transactionId']<?php ??<?php null,
<?php ':idempotency_key'<?php =><?php $idempotencyKey,
<?php ':response'<?php =><?php json_encode($withdrawData)
<?php ]);
<?php 
<?php $pdo->commit();
<?php $_SESSION['success']<?php =<?php 'Saque<?php enviado<?php para<?php processamento<?php via<?php DigitoPay!<?php Status:<?php '<?php .<?php ($withdrawData['status']<?php ??<?php 'PROCESSING');
<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
<?php }
<?php 
<?php $pdo->commit();
<?php $_SESSION['success']<?php =<?php 'Saque<?php aprovado<?php e<?php pagamento<?php realizado<?php com<?php sucesso!';
<?php 
<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php $pdo->rollBack();
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php aprovar<?php o<?php saque:<?php '<?php .<?php $e->getMessage();
<?php }
<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

if<?php (isset($_POST['reprovar_saque']))<?php {
<?php $saque_id<?php =<?php $_POST['saque_id'];
<?php 
<?php try<?php {
<?php $pdo->beginTransaction();
<?php 
<?php //<?php Buscar<?php dados<?php do<?php saque<?php antes<?php de<?php deletar
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php user_id,<?php valor<?php FROM<?php saques<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$saque_id]);
<?php $saque<?php =<?php $stmt->fetch();
<?php 
<?php if<?php ($saque)<?php {
<?php //<?php Buscar<?php saldo<?php atual<?php do<?php usuário
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$saque['user_id']]);
<?php $saldoAtual<?php =<?php $stmt->fetchColumn();
<?php 
<?php //<?php Devolver<?php o<?php valor<?php para<?php o<?php saldo<?php do<?php usuário
<?php $novoSaldo<?php =<?php $saldoAtual<?php +<?php $saque['valor'];
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$novoSaldo,<?php $saque['user_id']]);
<?php 
<?php //<?php Registrar<?php transação<?php de<?php estorno<?php com<?php saldos<?php corretos
<?php $stmt<?php =<?php $pdo->prepare("
<?php INSERT<?php INTO<?php transacoes<?php (user_id,<?php tipo,<?php valor,<?php saldo_anterior,<?php saldo_posterior,<?php status,<?php descricao,<?php created_at)<?php 
<?php VALUES<?php (?,<?php 'REFUND',<?php ?,<?php ?,<?php ?,<?php 'COMPLETED',<?php 'Estorno<?php de<?php saque<?php reprovado',<?php NOW())
<?php ");
<?php $stmt->execute([$saque['user_id'],<?php $saque['valor'],<?php $saldoAtual,<?php $novoSaldo]);
<?php }
<?php 
<?php //<?php Deletar<?php o<?php saque
<?php $stmt<?php =<?php $pdo->prepare("DELETE<?php FROM<?php saques<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$saque_id]);
<?php 
<?php $pdo->commit();
<?php $_SESSION['success']<?php =<?php 'Saque<?php reprovado<?php e<?php valor<?php devolvido<?php ao<?php usuário!';
<?php 
<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php $pdo->rollBack();
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php reprovar<?php o<?php saque:<?php '<?php .<?php $e->getMessage();
<?php }
<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

$nome<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php nome<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;
$nome<?php =<?php $nome<?php ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;

$stmt<?php =<?php $pdo->query("SELECT<?php saques.id,<?php saques.user_id,<?php saques.valor,<?php saques.cpf,<?php saques.status,<?php saques.updated_at,<?php saques.gateway,<?php usuarios.nome<?php 
<?php FROM<?php saques<?php 
<?php JOIN<?php usuarios<?php ON<?php saques.user_id<?php =<?php usuarios.id
<?php ORDER<?php BY<?php saques.updated_at<?php DESC");
$saques<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);

//<?php Calculate<?php statistics
$total_saques<?php =<?php count($saques);
$saques_aprovados<?php =<?php array_filter($saques,<?php function($s)<?php {<?php 
<?php return<?php in_array($s['status'],<?php ['PAID',<?php 'REALIZADO']);<?php 
});
$saques_pendentes<?php =<?php array_filter($saques,<?php function($s)<?php {<?php 
<?php return<?php in_array($s['status'],<?php ['PENDING',<?php 'PROCESSING',<?php 'EM<?php PROCESSAMENTO',<?php 'ANALISE']);<?php 
});
$valor_total_aprovado<?php =<?php array_sum(array_column($saques_aprovados,<?php 'valor'));
$valor_total_pendente<?php =<?php array_sum(array_column($saques_pendentes,<?php 'valor'));

//<?php Verificar<?php gateway<?php ativo<?php para<?php exibir<?php na<?php interface
$stmt<?php =<?php $pdo->prepare("SELECT<?php active<?php FROM<?php gateway<?php LIMIT<?php 1");
$stmt->execute();
$activeGateway<?php =<?php $stmt->fetchColumn();
?>

<!DOCTYPE<?php html>
<html<?php lang="pt-BR">
<head>
<?php <meta<?php charset="UTF-8">
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Gerenciar<?php Saques</title>
<?php <?php<?php 
<?php //<?php Se<?php as<?php variáveis<?php não<?php estiverem<?php definidas,<?php buscar<?php do<?php banco
<?php if<?php (!isset($faviconSite))<?php {
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php favicon<?php FROM<?php config<?php WHERE<?php id<?php =<?php 1<?php LIMIT<?php 1");
<?php $stmt->execute();
<?php $config_favicon<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $faviconSite<?php =<?php $config_favicon['favicon']<?php ??<?php null;
<?php 
<?php //<?php Se<?php $nomeSite<?php não<?php estiver<?php definido,<?php buscar<?php também
<?php if<?php (!isset($nomeSite))<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php nome_site<?php FROM<?php config<?php WHERE<?php id<?php =<?php 1<?php LIMIT<?php 1");
<?php $stmt->execute();
<?php $config_nome<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $nomeSite<?php =<?php $config_nome['nome_site']<?php ??<?php 'Raspadinha';
<?php }
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php $faviconSite<?php =<?php null;
<?php $nomeSite<?php =<?php $nomeSite<?php ??<?php 'Raspadinha';
<?php }
<?php }
<?php ?>
<?php <?php<?php if<?php ($faviconSite<?php &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $faviconSite)):<?php ?>
<?php <link<?php rel="icon"<?php type="image/x-icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <link<?php rel="shortcut<?php icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <link<?php rel="apple-touch-icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <?php<?php else:<?php ?>
<?php <link<?php rel="icon"<?php href="data:image/svg+xml,<?php=<?php urlencode('<svg<?php xmlns="http://www.w3.org/2000/svg"<?php viewBox="0<?php 0<?php 100<?php 100"><rect<?php width="100"<?php height="100"<?php fill="#22c55e"/><text<?php x="50"<?php y="50"<?php text-anchor="middle"<?php dominant-baseline="middle"<?php fill="white"<?php font-family="Arial"<?php font-size="40"<?php font-weight="bold">'<?php .<?php strtoupper(substr($nomeSite,<?php 0,<?php 1))<?php .<?php '</text></svg>')<?php ?>"/>
<?php <?php<?php endif;<?php ?>
<?php <!--<?php TailwindCSS<?php -->
<?php <script<?php src="https://cdn.tailwindcss.com"></script>
<?php 
<?php <!--<?php Font<?php Awesome<?php -->
<?php <link<?php rel="stylesheet"<?php href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php 
<?php <!--<?php Notiflix<?php -->
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet">
<?php 
<?php <!--<?php Google<?php Fonts<?php -->
<?php <link<?php href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"<?php rel="stylesheet">
<?php 
<?php <style>
<?php *<?php {
<?php margin:<?php 0;
<?php padding:<?php 0;
<?php box-sizing:<?php border-box;
<?php }
<?php 
<?php body<?php {
<?php font-family:<?php 'Inter',<?php -apple-system,<?php BlinkMacSystemFont,<?php sans-serif;
<?php background:<?php #000000;
<?php color:<?php #ffffff;
<?php min-height:<?php 100vh;
<?php overflow-x:<?php hidden;
<?php }
<?php 
<?php /*<?php Sidebar<?php Styles<?php */
<?php .sidebar<?php {
<?php position:<?php fixed;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 320px;
<?php height:<?php 100vh;
<?php background:<?php linear-gradient(145deg,<?php #0a0a0a<?php 0%,<?php #141414<?php 25%,<?php #1a1a1a<?php 50%,<?php #0f0f0f<?php 100%);
<?php backdrop-filter:<?php blur(20px);
<?php border-right:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php transition:<?php all<?php 0.4s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php z-index:<?php 1000;
<?php box-shadow:<?php 
<?php 0<?php 0<?php 50px<?php rgba(34,<?php 197,<?php 94,<?php 0.1),
<?php inset<?php 1px<?php 0<?php 0<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php }
<?php 
<?php .sidebar::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background:<?php 
<?php radial-gradient(circle<?php at<?php 20%<?php 20%,<?php rgba(34,<?php 197,<?php 94,<?php 0.15)<?php 0%,<?php transparent<?php 50%),
<?php radial-gradient(circle<?php at<?php 80%<?php 80%,<?php rgba(16,<?php 185,<?php 129,<?php 0.1)<?php 0%,<?php transparent<?php 50%),
<?php radial-gradient(circle<?php at<?php 40%<?php 60%,<?php rgba(59,<?php 130,<?php 246,<?php 0.05)<?php 0%,<?php transparent<?php 50%);
<?php opacity:<?php 0.8;
<?php pointer-events:<?php none;
<?php }
<?php 
<?php .sidebar.hidden<?php {
<?php transform:<?php translateX(-100%);
<?php }
<?php 
<?php .sidebar-header<?php {
<?php position:<?php relative;
<?php padding:<?php 2.5rem<?php 2rem;
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 100%);
<?php }
<?php 
<?php .logo<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php text-decoration:<?php none;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }
<?php 
<?php .logo-icon<?php {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e<?php 0%,<?php #16a34a<?php 100%);
<?php border-radius:<?php 16px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.5rem;
<?php color:<?php #ffffff;
<?php box-shadow:<?php 
<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.3),
<?php 0<?php 4px<?php 8px<?php rgba(0,<?php 0,<?php 0,<?php 0.2);
<?php position:<?php relative;
<?php }
<?php 
<?php .logo-icon::after<?php {
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
<?php transition:<?php opacity<?php 0.3s<?php ease;
<?php }
<?php 
<?php .logo:hover<?php .logo-icon::after<?php {
<?php opacity:<?php 1;
<?php }
<?php 
<?php .logo-text<?php {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php }
<?php 
<?php .logo-title<?php {
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php line-height:<?php 1.2;
<?php }
<?php 
<?php .logo-subtitle<?php {
<?php font-size:<?php 0.75rem;
<?php color:<?php #22c55e;
<?php font-weight:<?php 500;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 1px;
<?php }
<?php 
<?php .nav-menu<?php {
<?php padding:<?php 2rem<?php 0;
<?php position:<?php relative;
<?php }
<?php 
<?php .nav-section<?php {
<?php margin-bottom:<?php 2rem;
<?php }
<?php 
<?php .nav-section-title<?php {
<?php padding:<?php 0<?php 2rem<?php 0.75rem<?php 2rem;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php color:<?php #6b7280;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 1px;
<?php position:<?php relative;
<?php }
<?php 
<?php .nav-item<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php padding:<?php 1rem<?php 2rem;
<?php color:<?php #a1a1aa;
<?php text-decoration:<?php none;
<?php transition:<?php all<?php 0.3s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php position:<?php relative;
<?php margin:<?php 0.25rem<?php 1rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 500;
<?php }
<?php 
<?php .nav-item::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php left:<?php 0;
<?php top:<?php 0;
<?php bottom:<?php 0;
<?php width:<?php 4px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-radius:<?php 0<?php 4px<?php 4px<?php 0;
<?php transform:<?php scaleY(0);
<?php transition:<?php transform<?php 0.3s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php }
<?php 
<?php .nav-item:hover::before,
<?php .nav-item.active::before<?php {
<?php transform:<?php scaleY(1);
<?php }
<?php 
<?php .nav-item:hover,
<?php .nav-item.active<?php {
<?php color:<?php #ffffff;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.15)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php transform:<?php translateX(4px);
<?php box-shadow:<?php 0<?php 4px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }
<?php 
<?php .nav-icon<?php {
<?php width:<?php 24px;
<?php height:<?php 24px;
<?php margin-right:<?php 1rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1rem;
<?php position:<?php relative;
<?php }
<?php 
<?php .nav-text<?php {
<?php font-size:<?php 0.95rem;
<?php flex:<?php 1;
<?php }
<?php 
<?php .nav-badge<?php {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php font-size:<?php 0.7rem;
<?php font-weight:<?php 600;
<?php padding:<?php 0.25rem<?php 0.5rem;
<?php border-radius:<?php 6px;
<?php min-width:<?php 20px;
<?php text-align:<?php center;
<?php box-shadow:<?php 0<?php 2px<?php 8px<?php rgba(239,<?php 68,<?php 68,<?php 0.3);
<?php }
<?php 
<?php .sidebar-footer<?php {
<?php position:<?php absolute;
<?php bottom:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php padding:<?php 2rem;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 100%);
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php 
<?php .user-profile<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php padding:<?php 1rem;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }
<?php 
<?php .user-profile:hover<?php {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }
<?php 
<?php .user-avatar<?php {
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
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }
<?php 
<?php .user-info<?php {
<?php flex:<?php 1;
<?php }
<?php 
<?php .user-name<?php {
<?php font-weight:<?php 600;
<?php color:<?php #ffffff;
<?php font-size:<?php 0.9rem;
<?php line-height:<?php 1.2;
<?php }
<?php 
<?php .user-role<?php {
<?php font-size:<?php 0.75rem;
<?php color:<?php #22c55e;
<?php font-weight:<?php 500;
<?php }
<?php 
<?php /*<?php Main<?php Content<?php */
<?php .main-content<?php {
<?php margin-left:<?php 320px;
<?php min-height:<?php 100vh;
<?php transition:<?php margin-left<?php 0.4s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php background:<?php 
<?php radial-gradient(circle<?php at<?php 10%<?php 20%,<?php rgba(34,<?php 197,<?php 94,<?php 0.03)<?php 0%,<?php transparent<?php 50%),
<?php radial-gradient(circle<?php at<?php 80%<?php 80%,<?php rgba(16,<?php 185,<?php 129,<?php 0.02)<?php 0%,<?php transparent<?php 50%),
<?php radial-gradient(circle<?php at<?php 40%<?php 40%,<?php rgba(59,<?php 130,<?php 246,<?php 0.01)<?php 0%,<?php transparent<?php 50%);
<?php }
<?php 
<?php .main-content.expanded<?php {
<?php margin-left:<?php 0;
<?php }
<?php 
<?php .header<?php {
<?php position:<?php sticky;
<?php top:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.95);
<?php backdrop-filter:<?php blur(20px);
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php padding:<?php 1.5rem<?php 2.5rem;
<?php z-index:<?php 100;
<?php box-shadow:<?php 0<?php 4px<?php 20px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php 
<?php .header-content<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php }
<?php 
<?php .menu-toggle<?php {
<?php display:<?php none;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php rgba(34,<?php 197,<?php 94,<?php 0.05));
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php color:<?php #22c55e;
<?php padding:<?php 0.75rem;
<?php border-radius:<?php 12px;
<?php font-size:<?php 1.1rem;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }
<?php 
<?php .menu-toggle:hover<?php {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php transform:<?php scale(1.05);
<?php }
<?php 
<?php .header-title<?php {
<?php font-size:<?php 1.75rem;
<?php font-weight:<?php 700;
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #a1a1aa);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
<?php }
<?php 
<?php .header-actions<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php }
<?php 
<?php .page-content<?php {
<?php padding:<?php 2.5rem;
<?php }
<?php 
<?php .welcome-section<?php {
<?php margin-bottom:<?php 3rem;
<?php }
<?php 
<?php .welcome-title<?php {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 800;
<?php margin-bottom:<?php 0.75rem;
<?php background:<?php linear-gradient(135deg,<?php #ffffff<?php 0%,<?php #fff<?php 50%,<?php #fff<?php 100%);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
<?php line-height:<?php 1.2;
<?php }
<?php 
<?php .welcome-subtitle<?php {
<?php font-size:<?php 1.25rem;
<?php color:<?php #6b7280;
<?php font-weight:<?php 400;
<?php }
<?php 
<?php .stats-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php margin-bottom:<?php 3rem;
<?php }
<?php 
<?php .mini-stat-card<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php padding:<?php 1.5rem;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php backdrop-filter:<?php blur(20px);
<?php }
<?php 
<?php .mini-stat-card::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 3px;
<?php background:<?php linear-gradient(90deg,<?php #22c55e,<?php #16a34a);
<?php opacity:<?php 0;
<?php transition:<?php opacity<?php 0.3s<?php ease;
<?php }
<?php 
<?php .mini-stat-card:hover::before<?php {
<?php opacity:<?php 1;
<?php }
<?php 
<?php .mini-stat-card:hover<?php {
<?php transform:<?php translateY(-4px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php box-shadow:<?php 0<?php 15px<?php 35px<?php rgba(0,<?php 0,<?php 0,<?php 0.4);
<?php }
<?php 
<?php .mini-stat-header<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php margin-bottom:<?php 1rem;
<?php }
<?php 
<?php .mini-stat-icon<?php {
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-radius:<?php 10px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php #22c55e;
<?php font-size:<?php 1rem;
<?php }
<?php 
<?php .mini-stat-icon.warning<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2)<?php 0%,<?php rgba(251,<?php 191,<?php 36,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(251,<?php 191,<?php 36,<?php 0.3);
<?php color:<?php #f59e0b;
<?php }
<?php 
<?php .mini-stat-value<?php {
<?php font-size:<?php 1.75rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.25rem;
<?php }
<?php 
<?php .mini-stat-label<?php {
<?php color:<?php #a1a1aa;
<?php font-size:<?php 0.875rem;
<?php font-weight:<?php 500;
<?php }
<?php 
<?php .withdrawals-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(450px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php }
<?php 
<?php .withdrawal-card<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php backdrop-filter:<?php blur(20px);
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }
<?php 
<?php .withdrawal-card::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php right:<?php 0;
<?php width:<?php 100px;
<?php height:<?php 100px;
<?php background:<?php radial-gradient(circle,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 70%);
<?php opacity:<?php 0;
<?php transition:<?php opacity<?php 0.3s<?php ease;
<?php }
<?php 
<?php .withdrawal-card:hover::before<?php {
<?php opacity:<?php 1;
<?php }
<?php 
<?php .withdrawal-card:hover<?php {
<?php transform:<?php translateY(-4px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php 
<?php .withdrawal-header<?php {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php flex-start;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .withdrawal-user<?php {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 700;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php 
<?php .withdrawal-cpf<?php {
<?php font-size:<?php 0.9rem;
<?php color:<?php #6b7280;
<?php font-family:<?php 'Monaco',<?php 'Consolas',<?php monospace;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php padding:<?php 0.25rem<?php 0.5rem;
<?php border-radius:<?php 6px;
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php display:<?php inline-flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php }
<?php 
<?php .withdrawal-status<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php padding:<?php 0.5rem<?php 1rem;
<?php border-radius:<?php 12px;
<?php font-size:<?php 0.875rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.5px;
<?php }
<?php 
<?php .withdrawal-status.approved<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(34,<?php 197,<?php 94,<?php 0.1));
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php color:<?php #22c55e;
<?php }
<?php 
<?php .withdrawal-status.pending<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2),<?php rgba(251,<?php 191,<?php 36,<?php 0.1));
<?php border:<?php 1px<?php solid<?php rgba(251,<?php 191,<?php 36,<?php 0.3);
<?php color:<?php #f59e0b;
<?php }
<?php 
<?php .status-dot<?php {
<?php width:<?php 8px;
<?php height:<?php 8px;
<?php border-radius:<?php 50%;
<?php background:<?php currentColor;
<?php }
<?php 
<?php .withdrawal-value<?php {
<?php font-size:<?php 2rem;
<?php font-weight:<?php 800;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
<?php margin-bottom:<?php 1rem;
<?php }
<?php 
<?php .withdrawal-actions<?php {
<?php display:<?php flex;
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1rem;
<?php }
<?php 
<?php .action-btn<?php {
<?php flex:<?php 1;
<?php padding:<?php 0.875rem<?php 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php border:<?php none;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php }
<?php 
<?php .action-btn:hover<?php {
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php 
<?php .btn-approve<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php 
<?php .btn-approve:hover<?php {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }
<?php 
<?php .btn-reject<?php {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php }
<?php 
<?php .btn-reject:hover<?php {
<?php background:<?php linear-gradient(135deg,<?php #dc2626,<?php #b91c1c);
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(239,<?php 68,<?php 68,<?php 0.4);
<?php }
<?php 
<?php .btn-disabled<?php {
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);
<?php color:<?php #9ca3af;
<?php cursor:<?php not-allowed;
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php 
<?php .btn-disabled:hover<?php {
<?php transform:<?php none;
<?php box-shadow:<?php none;
<?php }
<?php 
<?php .withdrawal-date<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.875rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php padding-top:<?php 1rem;
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php 
<?php .withdrawal-date<?php i<?php {
<?php color:<?php #6b7280;
<?php }
<?php 
<?php .empty-state<?php {
<?php text-align:<?php center;
<?php padding:<?php 4rem<?php 2rem;
<?php color:<?php #6b7280;
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.3)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.4)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php border-radius:<?php 20px;
<?php backdrop-filter:<?php blur(10px);
<?php }
<?php 
<?php .empty-state<?php i<?php {
<?php font-size:<?php 4rem;
<?php margin-bottom:<?php 1.5rem;
<?php opacity:<?php 0.3;
<?php color:<?php #374151;
<?php }
<?php 
<?php .empty-state<?php h3<?php {
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 600;
<?php color:<?php #9ca3af;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php 
<?php .empty-state<?php p<?php {
<?php font-size:<?php 1rem;
<?php font-weight:<?php 400;
<?php }
<?php 
<?php /*<?php Mobile<?php Styles<?php */
<?php @media<?php (max-width:<?php 1024px)<?php {
<?php .sidebar<?php {
<?php transform:<?php translateX(-100%);
<?php width:<?php 300px;
<?php z-index:<?php 1001;
<?php }
<?php 
<?php .sidebar:not(.hidden)<?php {
<?php transform:<?php translateX(0);
<?php }
<?php 
<?php .main-content<?php {
<?php margin-left:<?php 0;
<?php }
<?php 
<?php .menu-toggle<?php {
<?php display:<?php block;
<?php }
<?php 
<?php .header-actions<?php span<?php {
<?php display:<?php none<?php !important;
<?php }
<?php 
<?php .stats-grid<?php {
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
<?php }
<?php 
<?php .withdrawals-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php }
<?php 
<?php @media<?php (max-width:<?php 768px)<?php {
<?php .header<?php {
<?php padding:<?php 1rem;
<?php }
<?php 
<?php .page-content<?php {
<?php padding:<?php 1.5rem;
<?php }
<?php 
<?php .welcome-title<?php {
<?php font-size:<?php 2.25rem;
<?php }
<?php 
<?php .withdrawal-card<?php {
<?php padding:<?php 1.5rem;
<?php }
<?php 
<?php .withdrawal-actions<?php {
<?php flex-direction:<?php column;
<?php }
<?php 
<?php .sidebar<?php {
<?php width:<?php 280px;
<?php }
<?php }
<?php 
<?php @media<?php (max-width:<?php 480px)<?php {
<?php .welcome-title<?php {
<?php font-size:<?php 1.875rem;
<?php }
<?php 
<?php .stats-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php 
<?php .withdrawal-header<?php {
<?php flex-direction:<?php column;
<?php align-items:<?php flex-start;
<?php gap:<?php 1rem;
<?php }
<?php 
<?php .withdrawal-value<?php {
<?php font-size:<?php 1.5rem;
<?php }
<?php 
<?php .sidebar<?php {
<?php width:<?php 260px;
<?php }
<?php }
<?php 
<?php .overlay<?php {
<?php position:<?php fixed;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php z-index:<?php 1000;
<?php opacity:<?php 0;
<?php visibility:<?php hidden;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php backdrop-filter:<?php blur(4px);
<?php }
<?php 
<?php .overlay.active<?php {
<?php opacity:<?php 1;
<?php visibility:<?php visible;
<?php }
<?php </style>
</head>
<body>
<?php <!--<?php Notifications<?php -->
<?php <?php<?php if<?php (isset($_SESSION['success'])):<?php ?>
<?php <script>
<?php Notiflix.Notify.success('<?php=<?php $_SESSION['success']<?php ?>');
<?php </script>
<?php <?php<?php unset($_SESSION['success']);<?php ?>
<?php <?php<?php elseif<?php (isset($_SESSION['failure'])):<?php ?>
<?php <script>
<?php Notiflix.Notify.failure('<?php=<?php $_SESSION['failure']<?php ?>');
<?php </script>
<?php <?php<?php unset($_SESSION['failure']);<?php ?>
<?php <?php<?php endif;<?php ?>

<?php <!--<?php Overlay<?php for<?php mobile<?php -->
<?php <div<?php class="overlay"<?php id="overlay"></div>
<?php 
<?php <!--<?php Sidebar<?php -->
<?php <aside<?php class="sidebar"<?php id="sidebar">
<?php <div<?php class="sidebar-header">
<?php <a<?php href="#"<?php class="logo">
<?php <div<?php class="logo-icon">
<?php <i<?php class="fas<?php fa-bolt"></i>
<?php </div>
<?php <div<?php class="logo-text">
<?php <div<?php class="logo-title">Dashboard</div>
<?php </div>
<?php </a>
<?php </div>
<?php 
<?php <nav<?php class="nav-menu">
<?php <div<?php class="nav-section">
<?php <div<?php class="nav-section-title">Principal</div>
<?php <a<?php href="index.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-chart-pie"></i></div>
<?php <div<?php class="nav-text">Dashboard</div>
<?php </a>
<?php </div>
<?php 
<?php <div<?php class="nav-section">
<?php <div<?php class="nav-section-title">Gestão</div>
<?php <a<?php href="usuarios.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user"></i></div>
<?php <div<?php class="nav-text">Usuários</div>
<?php </a>
<?php <a<?php href="afiliados.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user-plus"></i></div>
<?php <div<?php class="nav-text">Afiliados</div>
<?php </a>
<?php <a<?php href="depositos.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-credit-card"></i></div>
<?php <div<?php class="nav-text">Depósitos</div>
<?php </a>
<?php <a<?php href="saques.php"<?php class="nav-item<?php active">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-money-bill-wave"></i></div>
<?php <div<?php class="nav-text">Saques</div>
<?php </a>
<?php </div>
<?php 
<?php <div<?php class="nav-section">
<?php <div<?php class="nav-section-title">Sistema</div>
<?php <a<?php href="config.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-cogs"></i></div>
<?php <div<?php class="nav-text">Configurações</div>
<?php </a>
<?php <a<?php href="gateway.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-usd"></i></div>
<?php <div<?php class="nav-text">Gateway</div>
<?php </a>
<?php <a<?php href="banners.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-images"></i></div>
<?php <div<?php class="nav-text">Banners</div>
<?php </a>
<?php <a<?php href="cartelas.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-diamond"></i></div>
<?php <div<?php class="nav-text">Raspadinhas</div>
<?php </a>
<?php <a<?php href="../logout"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-sign-out-alt"></i></div>
<?php <div<?php class="nav-text">Sair</div>
<?php </a>
<?php </div>
<?php </nav>
<?php </aside>
<?php 
<?php <!--<?php Main<?php Content<?php -->
<?php <main<?php class="main-content"<?php id="mainContent">
<?php <!--<?php Header<?php -->
<?php <header<?php class="header">
<?php <div<?php class="header-content">
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 1rem;">
<?php <button<?php class="menu-toggle"<?php id="menuToggle">
<?php <i<?php class="fas<?php fa-bars"></i>
<?php </button>
<?php </div>
<?php 
<?php <div<?php class="header-actions">
<?php <span<?php style="color:<?php #a1a1aa;<?php font-size:<?php 0.9rem;<?php display:<?php none;">Bem-vindo,<?php <?php=<?php htmlspecialchars($nome)<?php ?></span>
<?php <div<?php class="user-avatar">
<?php <?php=<?php strtoupper(substr($nome,<?php 0,<?php 1))<?php ?>
<?php </div>
<?php </div>
<?php </div>
<?php </header>
<?php 
<?php <!--<?php Page<?php Content<?php -->
<?php <div<?php class="page-content">
<?php <!--<?php Welcome<?php Section<?php -->
<?php <section<?php class="welcome-section">
<?php <h2<?php class="welcome-title">Gestão<?php de<?php Saques</h2>
<?php <p<?php class="welcome-subtitle">Aprove<?php ou<?php reprove<?php solicitações<?php de<?php saque<?php via<?php PIX<?php de<?php forma<?php segura</p>
<?php </section>
<?php 
<?php <!--<?php Stats<?php Grid<?php -->
<?php <section<?php class="stats-grid">
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon">
<?php <i<?php class="fas<?php fa-money-bill-wave"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_saques,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Total<?php de<?php Saques</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon">
<?php <i<?php class="fas<?php fa-check-circle"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value"><?php=<?php number_format(count($saques_aprovados),<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Saques<?php Aprovados</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon<?php warning">
<?php <i<?php class="fas<?php fa-clock"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value"><?php=<?php number_format(count($saques_pendentes),<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Saques<?php Pendentes</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon">
<?php <i<?php class="fas<?php fa-dollar-sign"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($valor_total_aprovado,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Valor<?php Total<?php Pago</div>
<?php </div>
<?php </section>
<?php 
<?php <!--<?php Withdrawals<?php Section<?php -->
<?php <section>
<?php <?php<?php if<?php (empty($saques)):<?php ?>
<?php <div<?php class="empty-state">
<?php <i<?php class="fas<?php fa-money-bill-wave"></i>
<?php <h3>Nenhum<?php saque<?php encontrado</h3>
<?php <p>Não<?php há<?php solicitações<?php de<?php saque<?php registradas<?php no<?php sistema<?php ainda</p>
<?php </div>
<?php <?php<?php else:<?php ?>
<?php <div<?php class="withdrawals-grid">
<?php <?php<?php foreach<?php ($saques<?php as<?php $saque):<?php ?>
<?php <div<?php class="withdrawal-card">
<?php <div<?php class="withdrawal-header">
<?php <div>
<?php <h3<?php class="withdrawal-user"><?php=<?php htmlspecialchars($saque['nome'])<?php ?></h3>
<?php <div<?php class="withdrawal-cpf">
<?php <i<?php class="fas<?php fa-key"></i>
<?php PIX:<?php <?php=<?php htmlspecialchars($saque['cpf'])<?php ?>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="withdrawal-status<?php <?php=<?php $saque['status']<?php ==<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>">
<?php <div<?php class="status-dot"></div>
<?php <span><?php=<?php $saque['status']<?php ==<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?></span>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="withdrawal-value">
<?php R$<?php <?php=<?php number_format($saque['valor'],<?php 2,<?php ',',<?php '.')<?php ?>
<?php </div>
<?php 
<?php <?php<?php if<?php ($saque['status']<?php ==<?php 'PENDING'):<?php ?>
<?php <div<?php class="withdrawal-actions">
<?php <form<?php method="POST"<?php style="flex:<?php 1;">
<?php <input<?php type="hidden"<?php name="saque_id"<?php value="<?php=<?php $saque['id']<?php ?>">
<?php <button<?php type="submit"<?php name="aprovar_saque"<?php class="action-btn<?php btn-approve"<?php onclick="openLoading()">
<?php <i<?php class="fas<?php fa-check"></i>
<?php Aprovar<?php Saque
<?php </button>
<?php </form>
<?php <form<?php method="POST"<?php style="flex:<?php 1;">
<?php <input<?php type="hidden"<?php name="saque_id"<?php value="<?php=<?php $saque['id']<?php ?>">
<?php <button<?php type="submit"<?php name="reprovar_saque"<?php class="action-btn<?php btn-reject"<?php onclick="openLoading()">
<?php <i<?php class="fas<?php fa-times"></i>
<?php Reprovar
<?php </button>
<?php </form>
<?php </div>
<?php <?php<?php else:<?php ?>
<?php <div<?php class="withdrawal-actions">
<?php <button<?php class="action-btn<?php btn-disabled"<?php disabled>
<?php <i<?php class="fas<?php fa-check-double"></i>
<?php Saque<?php Processado
<?php </button>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php 
<?php <div<?php class="withdrawal-date">
<?php <i<?php class="fas<?php fa-calendar"></i>
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($saque['updated_at']))<?php ?></span>
<?php </div>
<?php </div>
<?php <?php<?php endforeach;<?php ?>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </section>
<?php </div>
<?php </main>
<?php 
<?php <script>
<?php //<?php Loading<?php function
<?php function<?php openLoading()<?php {
<?php Notiflix.Loading.standard('Processando<?php solicitação...');
<?php }
<?php 
<?php //<?php Mobile<?php menu<?php toggle
<?php const<?php menuToggle<?php =<?php document.getElementById('menuToggle');
<?php const<?php sidebar<?php =<?php document.getElementById('sidebar');
<?php const<?php mainContent<?php =<?php document.getElementById('mainContent');
<?php const<?php overlay<?php =<?php document.getElementById('overlay');
<?php 
<?php menuToggle.addEventListener('click',<?php ()<?php =><?php {
<?php const<?php isHidden<?php =<?php sidebar.classList.contains('hidden');
<?php 
<?php if<?php (isHidden)<?php {
<?php sidebar.classList.remove('hidden');
<?php overlay.classList.add('active');
<?php }<?php else<?php {
<?php sidebar.classList.add('hidden');
<?php overlay.classList.add('active');
<?php }
<?php });
<?php 
<?php overlay.addEventListener('click',<?php ()<?php =><?php {
<?php sidebar.classList.add('hidden');
<?php overlay.classList.remove('active');
<?php });
<?php 
<?php //<?php Close<?php sidebar<?php on<?php window<?php resize<?php if<?php it's<?php mobile
<?php window.addEventListener('resize',<?php ()<?php =><?php {
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {
<?php sidebar.classList.add('hidden');
<?php overlay.classList.remove('active');
<?php }<?php else<?php {
<?php sidebar.classList.remove('hidden');
<?php overlay.classList.remove('active');
<?php }
<?php });
<?php 
<?php //<?php Enhanced<?php hover<?php effects<?php for<?php nav<?php items
<?php document.querySelectorAll('.nav-item').forEach(item<?php =><?php {
<?php item.addEventListener('mouseenter',<?php function()<?php {
<?php this.style.transform<?php =<?php 'translateX(8px)';
<?php });
<?php 
<?php item.addEventListener('mouseleave',<?php function()<?php {
<?php if<?php (!this.classList.contains('active'))<?php {
<?php this.style.transform<?php =<?php 'translateX(0)';
<?php }
<?php });
<?php });
<?php 
<?php //<?php Smooth<?php scroll<?php behavior
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';
<?php 
<?php //<?php Initialize
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {
<?php console.log('%c💸<?php Gerenciamento<?php de<?php Saques<?php carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {
<?php sidebar.classList.add('hidden');
<?php }
<?php 
<?php //<?php Animate<?php cards<?php on<?php load
<?php const<?php withdrawalCards<?php =<?php document.querySelectorAll('.withdrawal-card');
<?php withdrawalCards.forEach((card,<?php index)<?php =><?php {
<?php card.style.opacity<?php =<?php '0';
<?php card.style.transform<?php =<?php 'translateY(20px)';
<?php setTimeout(()<?php =><?php {
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';
<?php card.style.opacity<?php =<?php '1';
<?php card.style.transform<?php =<?php 'translateY(0)';
<?php },<?php index<?php *<?php 100);
<?php });
<?php 
<?php //<?php Animate<?php stats<?php cards
<?php const<?php statCards<?php =<?php document.querySelectorAll('.mini-stat-card');
<?php statCards.forEach((card,<?php index)<?php =><?php {
<?php card.style.opacity<?php =<?php '0';
<?php card.style.transform<?php =<?php 'translateY(20px)';
<?php setTimeout(()<?php =><?php {
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';
<?php card.style.opacity<?php =<?php '1';
<?php card.style.transform<?php =<?php 'translateY(0)';
<?php },<?php index<?php *<?php 150);
<?php });
<?php });
<?php </script>
</body>
</html>