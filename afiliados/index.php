<?php
@session_start();

if (file_exists('./conexao.php'))<?php {
<?php include('./conexao.php');
}<?php elseif (file_exists('../conexao.php'))<?php {
<?php include('../conexao.php');
}<?php elseif (file_exists('../../conexao.php'))<?php {
<?php include('../../conexao.php');
}

if (!isset($_SESSION['usuario_id']))<?php {
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php precisa estar logado para acessar esta página!'];
<?php header("Location:<?php /login");
<?php exit;
}

$usuario_id =<?php $_SESSION['usuario_id'];

try {
<?php $link_indicacao =<?php "https://"<?php .<?php $_SERVER['HTTP_HOST']<?php .<?php "/cadastro?ref="<?php .<?php $usuario_id;
<?php $stmt_indicados =<?php $pdo->prepare("SELECT COUNT(*)<?php as total FROM usuarios WHERE indicacao =<?php ?");
<?php $stmt_indicados->execute([$usuario_id]);
<?php $total_indicados =<?php $stmt_indicados->fetch()['total'];
<?php $stmt_depositos =<?php $pdo->prepare("SELECT SUM(d.valor)<?php as total <?php FROM depositos d JOIN usuarios u ON d.user_id =<?php u.id WHERE u.indicacao =<?php ?<?php AND d.status =<?php 'PAID'");
<?php $stmt_depositos->execute([$usuario_id]);
<?php $total_depositado =<?php $stmt_depositos->fetch()['total']<?php ??<?php 0;
<?php //<?php Buscar comissões CPA (se a tabela existir)
<?php $total_comissoes_cpa =<?php 0;
<?php try {
<?php $stmt_comissoes_cpa =<?php $pdo->prepare("SELECT SUM(valor)<?php as total FROM transacoes_afiliados WHERE afiliado_id =<?php ?");
<?php $stmt_comissoes_cpa->execute([$usuario_id]);
<?php $total_comissoes_cpa =<?php $stmt_comissoes_cpa->fetch()['total']<?php ??<?php 0;
<?php }<?php catch (PDOException $e)<?php {
<?php //<?php Tabela transacoes_afiliados não existe $total_comissoes_cpa =<?php 0;
<?php }
<?php //<?php Buscar comissões RevShare (separando ganhos,<?php perdas e saldo líquido)
<?php $total_comissoes_revshare =<?php 0;
<?php $total_deducoes_revshare =<?php 0;
<?php $saldo_revshare_liquido =<?php 0;
<?php try {
<?php //<?php Comissões ganhas (apenas valores positivos)
<?php $stmt_comissoes_revshare =<?php $pdo->prepare("SELECT SUM(valor_revshare)<?php as total FROM historico_revshare WHERE afiliado_id =<?php ?<?php AND valor_revshare ><?php 0");
<?php $stmt_comissoes_revshare->execute([$usuario_id]);
<?php $total_comissoes_revshare =<?php $stmt_comissoes_revshare->fetch()['total']<?php ??<?php 0;
<?php //<?php Deduções (apenas valores negativos,<?php convertidos para positivo para exibição)
<?php $stmt_deducoes =<?php $pdo->prepare("SELECT SUM(ABS(valor_revshare))<?php as total FROM historico_revshare WHERE afiliado_id =<?php ?<?php AND valor_revshare <?php 0");
<?php $stmt_deducoes->execute([$usuario_id]);
<?php $total_deducoes_revshare =<?php $stmt_deducoes->fetch()['total']<?php ??<?php 0;
<?php //<?php Saldo líquido (ganhos -<?php perdas)
<?php $stmt_saldo_liquido =<?php $pdo->prepare("SELECT SUM(valor_revshare)<?php as total FROM historico_revshare WHERE afiliado_id =<?php ?");
<?php $stmt_saldo_liquido->execute([$usuario_id]);
<?php $saldo_revshare_liquido =<?php $stmt_saldo_liquido->fetch()['total']<?php ??<?php 0;
<?php }<?php catch (PDOException $e)<?php {
<?php //<?php Tabela historico_revshare não existe ainda $total_comissoes_revshare =<?php 0;
<?php $total_deducoes_revshare =<?php 0;
<?php $saldo_revshare_liquido =<?php 0;
<?php }
<?php //<?php Total de comissões (CPA +<?php RevShare -<?php apenas valores ganhos,<?php não o saldo líquido)
<?php $total_comissoes =<?php $total_comissoes_cpa +<?php $total_comissoes_revshare;
<?php $stmt_lista =<?php $pdo->prepare("SELECT u.id,<?php u.nome,<?php u.email,<?php u.created_at,
<?php (SELECT SUM(valor)<?php FROM depositos WHERE user_id =<?php u.id AND status =<?php 'PAID')<?php as total_depositado FROM usuarios u WHERE u.indicacao =<?php ?
<?php ORDER BY u.created_at DESC");
<?php $stmt_lista->execute([$usuario_id]);
<?php $indicados =<?php $stmt_lista->fetchAll(PDO::FETCH_ASSOC);
<?php 
}<?php catch (PDOException $e)<?php {
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro ao carregar dados de afiliado'];
<?php $total_indicados =<?php 0;
<?php $total_depositado =<?php 0;
<?php $total_comissoes =<?php 0;
<?php $total_comissoes_cpa =<?php 0;
<?php $total_comissoes_revshare =<?php 0;
<?php $total_deducoes_revshare =<?php 0;
<?php $saldo_revshare_liquido =<?php 0;
<?php $indicados =<?php [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

<?php <!--<?php xTracky Integration -->
<?php <script <?php src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
<?php data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
<?php data-click-id-param="click_id">
<?php </script>
<?php <meta charset="UTF-8">
<?php <meta name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php echo $nomeSite;<?php ?><?php -<?php Programa de Afiliados</title>
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
<?php <!--<?php Fonts -->
<?php <link rel="preconnect"<?php href="https://fonts.googleapis.com">
<?php <link rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin>
<?php <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"<?php rel="stylesheet">
<?php <!--<?php Icons -->
<?php <link rel="stylesheet"<?php href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php <!--<?php Styles -->
<?php <link rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php=<?php time();<?php ?>">
<?php <!--<?php Scripts -->
<?php <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<?php <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet">

<?php <style>
<?php /*<?php Page Styles */
<?php .afiliados-section {
<?php margin-top:<?php 100px;
<?php padding:<?php 4rem 0;
<?php background:<?php #0a0a0a;
<?php min-height:<?php calc(100vh -<?php 200px);
<?php }

<?php .afiliados-container {
<?php max-width:<?php 1200px;
<?php margin:<?php 0 auto;
<?php padding:<?php 0 2rem;
<?php }

<?php /*<?php Header */
<?php .page-header {
<?php text-align:<?php center;
<?php margin-bottom:<?php 4rem;
<?php }

<?php .page-title {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 900;
<?php color:<?php white;
<?php margin-bottom:<?php 1rem;
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #9ca3af);
<?php background-clip:<?php text;
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php }

<?php .page-subtitle {
<?php font-size:<?php 1.2rem;
<?php color:<?php #6b7280;
<?php max-width:<?php 600px;
<?php margin:<?php 0 auto;
<?php line-height:<?php 1.6;
<?php }

<?php .highlight-text {
<?php color:<?php #22c55e;
<?php font-weight:<?php 700;
<?php }

<?php /*<?php Main Card */
<?php .main-card {
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 24px;
<?php padding:<?php 3rem;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0 20px 60px rgba(0,<?php 0,<?php 0,<?php 0.5);
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }

<?php .main-card::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php right:<?php 0;
<?php width:<?php 200px;
<?php height:<?php 200px;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php transparent);
<?php border-radius:<?php 50%;
<?php transform:<?php translate(50%,<?php -50%);
<?php }

<?php .card-header {
<?php text-align:<?php center;
<?php margin-bottom:<?php 3rem;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .card-icon {
<?php width:<?php 80px;
<?php height:<?php 80px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-radius:<?php 20px;
<?php margin:<?php 0 auto 2rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php white;
<?php font-size:<?php 2rem;
<?php box-shadow:<?php 0 8px 24px rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .card-title {
<?php font-size:<?php 2rem;
<?php font-weight:<?php 800;
<?php color:<?php white;
<?php margin-bottom:<?php 0.5rem;
<?php }

<?php .card-description {
<?php color:<?php #9ca3af;
<?php font-size:<?php 1.1rem;
<?php }

<?php /*<?php Link Section */
<?php .link-section {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php margin-bottom:<?php 3rem;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .link-title {
<?php color:<?php #22c55e;
<?php font-weight:<?php 700;
<?php font-size:<?php 1.2rem;
<?php margin-bottom:<?php 1.5rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php }

<?php .link-input-group {
<?php display:<?php flex;
<?php gap:<?php 1rem;
<?php align-items:<?php stretch;
<?php }

<?php .link-input-wrapper {
<?php flex:<?php 1;
<?php position:<?php relative;
<?php }

<?php .link-input {
<?php width:<?php 100%;
<?php padding:<?php 1rem 1rem 1rem 3rem;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php color:<?php white;
<?php font-size:<?php 0.9rem;
<?php font-family:<?php monospace;
<?php transition:<?php all 0.3s ease;
<?php }

<?php .link-input:focus {
<?php outline:<?php none;
<?php border-color:<?php #22c55e;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.08);
<?php box-shadow:<?php 0 0 0 3px rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }

<?php .link-icon {
<?php position:<?php absolute;
<?php left:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #22c55e;
<?php font-size:<?php 1rem;
<?php }

<?php .copy-btn {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php border:<?php none;
<?php padding:<?php 1rem 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php white-space:<?php nowrap;
<?php box-shadow:<?php 0 4px 16px rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .copy-btn:hover {
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 8px 24px rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }

<?php /*<?php Stats Grid */
<?php .stats-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(300px,<?php 1fr));
<?php gap:<?php 2rem;
<?php margin-bottom:<?php 3rem;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .stat-card {
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.02);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php transition:<?php all 0.3s ease;
<?php }

<?php .stat-card:hover {
<?php transform:<?php translateY(-4px);
<?php box-shadow:<?php 0 10px 40px rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }

<?php .stat-card::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 4px;
<?php height:<?php 100%;
<?php background:<?php var(--accent-color);
<?php }

<?php .stat-card.indicados::before {<?php background:<?php #22c55e;<?php }
<?php .stat-card.depositos::before {<?php background:<?php #3b82f6;<?php }
<?php .stat-card.comissoes::before {<?php background:<?php #a855f7;<?php }

<?php .stat-header {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php margin-bottom:<?php 1.5rem;
<?php }

<?php .stat-info h3 {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 500;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.05em;
<?php margin-bottom:<?php 0.5rem;
<?php }

<?php .stat-value {
<?php font-size:<?php 2.5rem;
<?php font-weight:<?php 800;
<?php color:<?php white;
<?php line-height:<?php 1;
<?php }

<?php .stat-icon {
<?php width:<?php 60px;
<?php height:<?php 60px;
<?php border-radius:<?php 16px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.5rem;
<?php color:<?php white;
<?php }

<?php .stat-icon.indicados {<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php color:<?php #22c55e;<?php }
<?php .stat-icon.depositos {<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.2);<?php color:<?php #3b82f6;<?php }
<?php .stat-icon.comissoes {<?php background:<?php rgba(168,<?php 85,<?php 247,<?php 0.2);<?php color:<?php #a855f7;<?php }

<?php .stat-footer {
<?php color:<?php #6b7280;
<?php font-size:<?php 0.85rem;
<?php margin-top:<?php 1rem;
<?php }

<?php /*<?php Detalhamento das comissões */
<?php .commission-breakdown {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php gap:<?php 0.5rem;
<?php margin-top:<?php 1rem;
<?php padding-top:<?php 1rem;
<?php border-top:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }

<?php .commission-item {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php center;
<?php font-size:<?php 0.85rem;
<?php }

<?php .commission-label {
<?php color:<?php #9ca3af;
<?php }

<?php .commission-value {
<?php color:<?php #a855f7;
<?php font-weight:<?php 600;
<?php }

<?php /*<?php Indicados Section */
<?php .indicados-section {
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .section-title {
<?php color:<?php white;
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 700;
<?php margin-bottom:<?php 2rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php }

<?php .empty-state {
<?php text-align:<?php center;
<?php padding:<?php 4rem 2rem;
<?php color:<?php #6b7280;
<?php }

<?php .empty-icon {
<?php font-size:<?php 4rem;
<?php margin-bottom:<?php 2rem;
<?php opacity:<?php 0.5;
<?php }

<?php .empty-title {
<?php font-size:<?php 1.3rem;
<?php font-weight:<?php 600;
<?php color:<?php white;
<?php margin-bottom:<?php 1rem;
<?php }

<?php .empty-description {
<?php font-size:<?php 1rem;
<?php line-height:<?php 1.6;
<?php max-width:<?php 400px;
<?php margin:<?php 0 auto;
<?php }

<?php /*<?php Indicados Cards */
<?php .indicados-grid {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php gap:<?php 1.5rem;
<?php }

<?php .indicado-card {
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.02);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php padding:<?php 2rem;
<?php transition:<?php all 0.3s ease;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }

<?php .indicado-card:hover {
<?php transform:<?php translateY(-2px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php box-shadow:<?php 0 8px 32px rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }

<?php .indicado-card::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 4px;
<?php height:<?php 100%;
<?php background:<?php #22c55e;
<?php }

<?php .indicado-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));
<?php gap:<?php 2rem;
<?php align-items:<?php center;
<?php }

<?php .indicado-field {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php gap:<?php 0.5rem;
<?php }

<?php .field-label {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.85rem;
<?php font-weight:<?php 500;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.05em;
<?php }

<?php .field-value {
<?php color:<?php white;
<?php font-weight:<?php 600;
<?php font-size:<?php 1rem;
<?php }

<?php .field-value.email {
<?php font-family:<?php monospace;
<?php font-size:<?php 0.9rem;
<?php word-break:<?php break-all;
<?php }

<?php .field-value.money {
<?php color:<?php #22c55e;
<?php font-weight:<?php 700;
<?php }

<?php /*<?php Responsive */
<?php @media (max-width:<?php 768px)<?php {
<?php .afiliados-container {
<?php padding:<?php 0 1rem;
<?php }
<?php .page-title {
<?php font-size:<?php 2rem;
<?php }
<?php .main-card {
<?php padding:<?php 2rem 1.5rem;
<?php border-radius:<?php 20px;
<?php }
<?php .link-input-group {
<?php flex-direction:<?php column;
<?php gap:<?php 1rem;
<?php }
<?php .stats-grid {
<?php grid-template-columns:<?php 1fr;
<?php gap:<?php 1.5rem;
<?php }
<?php .stat-value {
<?php font-size:<?php 2rem;
<?php }
<?php .indicado-grid {
<?php grid-template-columns:<?php 1fr;
<?php gap:<?php 1.5rem;
<?php }
<?php }

<?php @media (max-width:<?php 480px)<?php {
<?php .main-card {
<?php padding:<?php 1.5rem 1rem;
<?php }
<?php .link-section {
<?php padding:<?php 1.5rem;
<?php }
<?php .stat-card {
<?php padding:<?php 1.5rem;
<?php }
<?php .indicado-card {
<?php padding:<?php 1.5rem;
<?php }
<?php }

<?php /*<?php Animations */
<?php .fade-in {
<?php animation:<?php fadeIn 0.6s ease-out forwards;
<?php }

<?php @keyframes fadeIn {
<?php from {
<?php opacity:<?php 0;
<?php transform:<?php translateY(20px);
<?php }
<?php to {
<?php opacity:<?php 1;
<?php transform:<?php translateY(0);
<?php }
<?php }

<?php .stats-grid .stat-card {
<?php opacity:<?php 0;
<?php animation:<?php fadeIn 0.6s ease-out forwards;
<?php }

<?php .stats-grid .stat-card:nth-child(1)<?php {<?php animation-delay:<?php 0.1s;<?php }
<?php .stats-grid .stat-card:nth-child(2)<?php {<?php animation-delay:<?php 0.2s;<?php }
<?php .stats-grid .stat-card:nth-child(3)<?php {<?php animation-delay:<?php 0.3s;<?php }

<?php .success-animation {
<?php animation:<?php successPulse 0.6s ease-out;
<?php }

<?php @keyframes successPulse {
<?php 0%<?php {<?php transform:<?php scale(1);<?php }
<?php 50%<?php {<?php transform:<?php scale(1.05);<?php }
<?php 100%<?php {<?php transform:<?php scale(1);<?php }
<?php }
<?php </style>
</head>
<body>
<?php <?php include('../inc/header.php');<?php ?>
<?php <?php include('../components/modals.php');<?php ?>

<?php <section class="afiliados-section">
<?php <div class="afiliados-container">
<?php <!--<?php Page Header -->
<?php <div class="page-header fade-in">
<?php <h1 class="page-title">Programa de Afiliados</h1>
<?php <p class="page-subtitle">
<?php Ganhe <span class="highlight-text">comissões</span><?php indicando amigos para a <?php echo $nomeSite;?>.<?php Quanto mais eles jogarem,<?php mais você<?php ganha!
<?php </p>
<?php </div>

<?php <!--<?php Main Card -->
<?php <div class="main-card">
<?php <!--<?php Card Header -->
<?php <div class="card-header">
<?php <div class="card-icon">
<?php <i class="bi bi-people-fill"></i>
<?php </div>
<?php <h2 class="card-title">Área do Afiliado</h2>
<?php <p class="card-description">
<?php Compartilhe seu link e ganhe comissões por cada indicação </p>
<?php </div>

<?php <!--<?php Link Section -->
<?php <div class="link-section">
<?php <h3 class="link-title">
<?php <i class="bi bi-link-45deg"></i>
<?php Seu Link de Indicação </h3>
<?php <div class="link-input-group">
<?php <div class="link-input-wrapper">
<?php <i class="bi bi-link link-icon"></i>
<?php <input type="text"<?php id="linkIndicacao"<?php class="link-input"
<?php value="<?php=<?php $link_indicacao ?>"<?php readonly>
<?php </div>
<?php <button onclick="copiarLink()"<?php class="copy-btn"<?php id="copyBtn">
<?php <i class="bi bi-clipboard"></i>
<?php Copiar Link </button>
<?php </div>
<?php </div>

<?php <!--<?php Stats Grid -->
<?php <div class="stats-grid">
<?php <div class="stat-card indicados">
<?php <div class="stat-header">
<?php <div class="stat-info">
<?php <h3>Indicados</h3>
<?php <div class="stat-value"><?php=<?php $total_indicados ?></div>
<?php </div>
<?php <div class="stat-icon indicados">
<?php <i class="bi bi-people"></i>
<?php </div>
<?php </div>
<?php <div class="stat-footer">
<?php Pessoas que você<?php indicou </div>
<?php </div>

<?php <div class="stat-card depositos">
<?php <div class="stat-header">
<?php <div class="stat-info">
<?php <h3>Total Depositado</h3>
<?php <div class="stat-value">R$<?php <?php=<?php number_format($total_depositado,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php <div class="stat-icon depositos">
<?php <i class="bi bi-cash-stack"></i>
<?php </div>
<?php </div>
<?php <div class="stat-footer">
<?php Por seus indicados </div>
<?php </div>

<?php <div class="stat-card comissoes">
<?php <div class="stat-header">
<?php <div class="stat-info">
<?php <h3>Suas Comissões</h3>
<?php <div class="stat-value">R$<?php <?php=<?php number_format($total_comissoes,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php <div class="stat-icon comissoes">
<?php <i class="bi bi-wallet2"></i>
<?php </div>
<?php </div>
<?php <?php if ($total_comissoes_cpa ><?php 0 ||<?php $total_comissoes_revshare ><?php 0 ||<?php $total_deducoes_revshare ><?php 0):<?php ?>
<?php <div class="commission-breakdown">
<?php <?php if ($total_comissoes_cpa ><?php 0):<?php ?>
<?php <div class="commission-item">
<?php <span class="commission-label">CPA (Cadastros):</span>
<?php <span class="commission-value">R$<?php <?php=<?php number_format($total_comissoes_cpa,<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <?php endif;<?php ?>
<?php <?php if ($total_comissoes_revshare ><?php 0):<?php ?>
<?php <div class="commission-item">
<?php <span class="commission-label">RevShare (Ganhos):</span>
<?php <span class="commission-value">R$<?php <?php=<?php number_format($total_comissoes_revshare,<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <?php endif;<?php ?>
<?php <?php if ($total_deducoes_revshare ><?php 0):<?php ?>
<?php <div class="commission-item">
<?php <span class="commission-label">Deduções (Perdas):</span>
<?php <span class="commission-value"<?php style="color:<?php #ef4444;">-R$<?php <?php=<?php number_format($total_deducoes_revshare,<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <?php endif;<?php ?>
<?php <?php if ($total_comissoes_revshare ><?php 0 ||<?php $total_deducoes_revshare ><?php 0):<?php ?>
<?php <hr style="border-color:<?php rgba(255,255,255,0.1);<?php margin:<?php 0.5rem 0;">
<?php <div class="commission-item">
<?php <span class="commission-label"><strong>Saldo RevShare:</strong></span>
<?php <span class="commission-value"<?php style="color:<?php <?php=<?php $saldo_revshare_liquido >=<?php 0 ?<?php '#22c55e'<?php :<?php '#ef4444'<?php ?>;">
<?php R$<?php <?php=<?php number_format($saldo_revshare_liquido,<?php 2,<?php ',',<?php '.')<?php ?>
<?php </span>
<?php </div>
<?php <?php endif;<?php ?>
<?php </div>
<?php <?php endif;<?php ?>
<?php <div class="stat-footer">
<?php Total de comissões ganhas </div>
<?php </div>
<?php </div>

<?php <!--<?php Indicados Section -->
<?php <div class="indicados-section">
<?php <h3 class="section-title">
<?php <i class="bi bi-list-ul"></i>
<?php Seus Indicados </h3>
<?php <?php if (empty($indicados)):<?php ?>
<?php <div class="empty-state">
<?php <i class="bi bi-people empty-icon"></i>
<?php <h4 class="empty-title">Nenhum indicado ainda</h4>
<?php <p class="empty-description">
<?php Compartilhe seu link de indicação com amigos e familiares para começar a ganhar comissões!
<?php </p>
<?php </div>
<?php <?php else:<?php ?>
<?php <div class="indicados-grid">
<?php <?php foreach ($indicados as $indicado):<?php ?>
<?php <div class="indicado-card">
<?php <div class="indicado-grid">
<?php <div class="indicado-field">
<?php <span class="field-label">Nome</span>
<?php <span class="field-value"><?php=<?php htmlspecialchars($indicado['nome'])<?php ?></span>
<?php </div>
<?php <div class="indicado-field">
<?php <span class="field-label">E-mail</span>
<?php <span class="field-value email"><?php=<?php htmlspecialchars($indicado['email'])<?php ?></span>
<?php </div>
<?php <div class="indicado-field">
<?php <span class="field-label">Cadastro</span>
<?php <span class="field-value"><?php=<?php date('d/m/Y',<?php strtotime($indicado['created_at']))<?php ?></span>
<?php </div>
<?php <div class="indicado-field">
<?php <span class="field-label">Total Depositado</span>
<?php <span class="field-value money">
<?php R$<?php <?php=<?php number_format($indicado['total_depositado']<?php ??<?php 0,<?php 2,<?php ',',<?php '.')<?php ?>
<?php </span>
<?php </div>
<?php </div>
<?php </div>
<?php <?php endforeach;<?php ?>
<?php </div>
<?php <?php endif;<?php ?>
<?php </div>
<?php </div>
<?php </div>
<?php </section>

<?php <?php include('../inc/footer.php');<?php ?>

<?php <script>
<?php function copiarLink()<?php {
<?php const linkInput =<?php document.getElementById('linkIndicacao');
<?php const copyBtn =<?php document.getElementById('copyBtn');
<?php //<?php Seleciona e copia o texto linkInput.select();
<?php linkInput.setSelectionRange(0,<?php 99999);<?php //<?php Para mobile <?php try {
<?php document.execCommand('copy');
<?php //<?php Feedback visual copyBtn.innerHTML =<?php '<i class="bi bi-check-circle"></i><?php Copiado!';
<?php copyBtn.classList.add('success-animation');
<?php //<?php Notificação Notiflix.Notify.success('Link copiado para a área de transferência!');
<?php //<?php Restaura o botão após 2 segundos setTimeout(()<?php =><?php {
<?php copyBtn.innerHTML =<?php '<i class="bi bi-clipboard"></i><?php Copiar Link';
<?php copyBtn.classList.remove('success-animation');
<?php },<?php 2000);
<?php }<?php catch (err)<?php {
<?php Notiflix.Notify.failure('Erro ao copiar o link');
<?php console.error('Erro ao copiar:',<?php err);
<?php }
<?php }

<?php //<?php Clipboard API moderna (fallback)
<?php async function copiarLinkModerno()<?php {
<?php const linkInput =<?php document.getElementById('linkIndicacao');
<?php try {
<?php await navigator.clipboard.writeText(linkInput.value);
<?php Notiflix.Notify.success('Link copiado!');
<?php }<?php catch (err)<?php {
<?php //<?php Fallback para método antigo copiarLink();
<?php }
<?php }

<?php //<?php Detecta se suporta Clipboard API if (navigator.clipboard)<?php {
<?php document.querySelector('.copy-btn').onclick =<?php copiarLinkModerno;
<?php }

<?php //<?php Notiflix configuration Notiflix.Notify.init({
<?php width:<?php '300px',
<?php position:<?php 'right-top',
<?php distance:<?php '20px',
<?php opacity:<?php 1,
<?php borderRadius:<?php '12px',
<?php timeout:<?php 4000,
<?php success:<?php {
<?php background:<?php '#22c55e',
<?php textColor:<?php '#fff',
<?php }
<?php });

<?php //<?php Console log document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php console.log('%c💰<?php Programa de Afiliados carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php console.log(`Indicados:<?php ${<?php=<?php $total_indicados ?>},<?php Comissões Total:<?php R$<?php ${<?php=<?php $total_comissoes ?>}`);
<?php console.log(`CPA:<?php R$<?php ${<?php=<?php $total_comissoes_cpa ?>},<?php RevShare:<?php R$<?php ${<?php=<?php $total_comissoes_revshare ?>}`);
<?php console.log(`Deduções:<?php R$<?php ${<?php=<?php $total_deducoes_revshare ?>},<?php Saldo RevShare:<?php R$<?php ${<?php=<?php $saldo_revshare_liquido ?>}`);
<?php });
<?php </script>
</body>
</html>