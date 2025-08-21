<?php
ob_start();
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

$nome =<?php ($stmt =<?php $pdo->prepare("SELECT nome FROM usuarios WHERE id =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;
$nome =<?php $nome ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;

if (isset($_GET['toggle_banido']))<?php {
<?php $id =<?php $_GET['id'];
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET banido =<?php IF(banido=1,<?php 0,<?php 1)<?php WHERE id =<?php ?");
<?php if ($stmt->execute([$id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Status de banido alterado com sucesso!';
<?php }<?php else {
<?php $_SESSION['failure']<?php =<?php 'Erro ao alterar status!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

if (isset($_GET['toggle_influencer']))<?php {
<?php $id =<?php $_GET['id'];
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET influencer =<?php IF(influencer=1,<?php 0,<?php 1)<?php WHERE id =<?php ?");
<?php if ($stmt->execute([$id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Status de influencer alterado com sucesso!';
<?php }<?php else {
<?php $_SESSION['failure']<?php =<?php 'Erro ao alterar status!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

if (isset($_POST['atualizar_comissao_cpa']))<?php {
<?php $id =<?php $_POST['id'];
<?php $comissao_cpa =<?php str_replace(',',<?php '.',<?php $_POST['comissao_cpa']);
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET comissao_cpa =<?php ?<?php WHERE id =<?php ?");
<?php if ($stmt->execute([$comissao_cpa,<?php $id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Comissão CPA atualizada com sucesso!';
<?php }<?php else {
<?php $_SESSION['failure']<?php =<?php 'Erro ao atualizar comissão CPA!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

//<?php Nova função para atualizar RevShare
if (isset($_POST['atualizar_comissao_revshare']))<?php {
<?php $id =<?php $_POST['id'];
<?php $comissao_revshare =<?php str_replace(',',<?php '.',<?php $_POST['comissao_revshare']);
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET comissao_revshare =<?php ?<?php WHERE id =<?php ?");
<?php if ($stmt->execute([$comissao_revshare,<?php $id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Comissão RevShare atualizada com sucesso!';
<?php }<?php else {
<?php $_SESSION['failure']<?php =<?php 'Erro ao atualizar comissão RevShare!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

//<?php Nova função para buscar detalhes do afiliado via AJAX
if (isset($_GET['ajax'])<?php &&<?php $_GET['ajax']<?php ==<?php 'detalhes_afiliado')<?php {
<?php //<?php Limpar qualquer saída anterior ob_clean();
<?php $afiliado_id =<?php $_GET['afiliado_id'];
<?php try {
<?php //<?php Buscar dados do afiliado $stmt =<?php $pdo->prepare("SELECT *<?php FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$afiliado_id]);
<?php $afiliado =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php if (!$afiliado)<?php {
<?php header('Content-Type:<?php application/json');
<?php echo json_encode(['error'<?php =><?php 'Afiliado não encontrado']);
<?php exit;
<?php }
<?php //<?php Buscar indicados $stmt =<?php $pdo->prepare("
<?php SELECT u.*,<?php COALESCE(SUM(CASE WHEN d.status =<?php 'PAID'<?php THEN d.valor ELSE 0 END),<?php 0)<?php as total_depositado,
<?php COUNT(CASE WHEN d.status =<?php 'PAID'<?php THEN d.id END)<?php as total_depositos,
<?php u.created_at as data_cadastro FROM usuarios u <?php LEFT JOIN depositos d ON u.id =<?php d.user_id WHERE u.indicacao =<?php ?<?php GROUP BY u.id ORDER BY u.created_at DESC ");
<?php $stmt->execute([$afiliado_id]);
<?php $indicados =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
<?php //<?php Buscar histórico de comissões CPA (verificar se tabela existe)
<?php $historico_cpa =<?php [];
<?php try {
<?php $stmt =<?php $pdo->prepare("
<?php SELECT hc.*,<?php u.nome as indicado_nome,<?php u.email as indicado_email FROM historico_comissoes hc JOIN usuarios u ON hc.indicado_id =<?php u.id WHERE hc.afiliado_id =<?php ?
<?php ORDER BY hc.created_at DESC LIMIT 20 ");
<?php $stmt->execute([$afiliado_id]);
<?php $historico_cpa =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
<?php }<?php catch (Exception $e)<?php {
<?php //<?php Tabela historico_comissoes pode não existir $historico_cpa =<?php [];
<?php }
<?php //<?php Buscar histórico RevShare (verificar se tabela existe)
<?php $historico_revshare =<?php [];
<?php try {
<?php $stmt =<?php $pdo->prepare("
<?php SELECT hr.*,<?php u.nome as indicado_nome,<?php u.email as indicado_email,
<?php hr.valor_apostado as valor_perdido,
<?php hr.percentual as percentual_revshare,
<?php 'N/A'<?php as jogo FROM historico_revshare hr JOIN usuarios u ON hr.usuario_id =<?php u.id WHERE hr.afiliado_id =<?php ?
<?php ORDER BY hr.created_at DESC LIMIT 20 ");
<?php $stmt->execute([$afiliado_id]);
<?php $historico_revshare =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
<?php }<?php catch (Exception $e)<?php {
<?php //<?php Tabela historico_revshare pode não existir $historico_revshare =<?php [];
<?php }
<?php //<?php Calcular estatísticas $total_comissao_cpa =<?php 0;
<?php $total_comissao_revshare =<?php 0;
<?php foreach ($historico_cpa as $cpa)<?php {
<?php $total_comissao_cpa +=<?php floatval($cpa['valor_comissao']<?php ??<?php 0);
<?php }
<?php foreach ($historico_revshare as $rev)<?php {
<?php $total_comissao_revshare +=<?php floatval($rev['valor_revshare']<?php ??<?php 0);
<?php }
<?php //<?php Buscar saldo atual do afiliado $stmt =<?php $pdo->prepare("SELECT saldo FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$afiliado_id]);
<?php $saldo_atual =<?php $stmt->fetchColumn()<?php ??<?php 0;
<?php $response =<?php [
<?php 'afiliado'<?php =><?php $afiliado,
<?php 'indicados'<?php =><?php $indicados,
<?php 'historico_cpa'<?php =><?php $historico_cpa,
<?php 'historico_revshare'<?php =><?php $historico_revshare,
<?php 'estatisticas'<?php =><?php [
<?php 'total_indicados'<?php =><?php count($indicados),
<?php 'total_depositado_indicados'<?php =><?php array_sum(array_column($indicados,<?php 'total_depositado')),
<?php 'total_comissao_cpa'<?php =><?php $total_comissao_cpa,
<?php 'total_comissao_revshare'<?php =><?php $total_comissao_revshare,
<?php 'saldo_atual'<?php =><?php floatval($saldo_atual)
<?php ]
<?php ];
<?php header('Content-Type:<?php application/json');
<?php echo json_encode($response,<?php JSON_UNESCAPED_UNICODE);
<?php exit;
<?php }<?php catch (Exception $e)<?php {
<?php header('Content-Type:<?php application/json');
<?php echo json_encode(['error'<?php =><?php 'Erro ao buscar dados:<?php '<?php .<?php $e->getMessage()],<?php JSON_UNESCAPED_UNICODE);
<?php exit;
<?php }
}

$search =<?php isset($_GET['search'])<?php ?<?php $_GET['search']<?php :<?php '';

//<?php Query atualizada para incluir dados de RevShare
$query =<?php "SELECT u.*,<?php (SELECT COUNT(*)<?php FROM usuarios WHERE indicacao =<?php u.id)<?php as total_indicados,
<?php (SELECT COALESCE(SUM(d.valor),<?php 0)<?php FROM depositos d <?php JOIN usuarios u2 ON d.user_id =<?php u2.id <?php WHERE u2.indicacao =<?php u.id AND d.status =<?php 'PAID')<?php as total_depositos,
<?php (SELECT COALESCE(SUM(valor_revshare),<?php 0)<?php FROM historico_revshare <?php WHERE afiliado_id =<?php u.id)<?php as total_revshare FROM usuarios u WHERE EXISTS (SELECT 1 FROM usuarios WHERE indicacao =<?php u.id)";

if (!empty($search))<?php {
<?php $query .=<?php "<?php AND (u.nome LIKE :search OR u.email LIKE :search OR u.telefone LIKE :search)";
}

$query .=<?php "<?php ORDER BY total_depositos DESC,<?php total_indicados DESC";

$stmt =<?php $pdo->prepare($query);

if (!empty($search))<?php {
<?php $searchTerm =<?php "%$search%";
<?php $stmt->bindParam(':search',<?php $searchTerm,<?php PDO::PARAM_STR);
}

$stmt->execute();
$afiliados =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);

//<?php Calculate statistics
$total_afiliados =<?php count($afiliados);
$total_indicados =<?php array_sum(array_column($afiliados,<?php 'total_indicados'));
$total_depositos_afiliados =<?php array_sum(array_column($afiliados,<?php 'total_depositos'));
$total_revshare_pago =<?php array_sum(array_column($afiliados,<?php 'total_revshare'));
$influencers_count =<?php count(array_filter($afiliados,<?php function($a)<?php {<?php return $a['influencer']<?php ==<?php 1;<?php }));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?php <meta charset="UTF-8">
<?php <meta name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php echo $nomeSite ??<?php 'Admin';<?php ?><?php -<?php Gerenciar Afiliados</title>
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
<?php /*<?php Advanced Sidebar Styles -<?php Same as depositos.php */
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
<?php /*<?php Enhanced Sidebar Header */
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
<?php /*<?php Advanced Navigation */
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
<?php /*<?php Enhanced Header */
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
<?php .header-actions {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
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
<?php /*<?php Main Page Content */
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
<?php /*<?php Stats Cards */
<?php .stats-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
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
<?php .mini-stat-icon.purple {
<?php background:<?php linear-gradient(135deg,<?php rgba(147,<?php 51,<?php 234,<?php 0.2)<?php 0%,<?php rgba(147,<?php 51,<?php 234,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(147,<?php 51,<?php 234,<?php 0.3);
<?php color:<?php #9333ea;
<?php }
<?php .mini-stat-icon.blue {
<?php background:<?php linear-gradient(135deg,<?php rgba(59,<?php 130,<?php 246,<?php 0.2)<?php 0%,<?php rgba(59,<?php 130,<?php 246,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(59,<?php 130,<?php 246,<?php 0.3);
<?php color:<?php #3b82f6;
<?php }
<?php .mini-stat-icon.orange {
<?php background:<?php linear-gradient(135deg,<?php rgba(249,<?php 115,<?php 22,<?php 0.2)<?php 0%,<?php rgba(249,<?php 115,<?php 22,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(249,<?php 115,<?php 22,<?php 0.3);
<?php color:<?php #f97316;
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
<?php /*<?php Search Section */
<?php .search-section {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php margin-bottom:<?php 2rem;
<?php backdrop-filter:<?php blur(20px);
<?php }
<?php .search-header {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .search-icon-container {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-radius:<?php 12px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php #22c55e;
<?php font-size:<?php 1.125rem;
<?php }
<?php .search-title {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 600;
<?php color:<?php #ffffff;
<?php }
<?php .search-container {
<?php position:<?php relative;
<?php }
<?php .search-input {
<?php width:<?php 100%;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php padding:<?php 0.875rem 1rem 0.875rem 3rem;
<?php color:<?php white;
<?php font-size:<?php 1rem;
<?php transition:<?php all 0.3s ease;
<?php }
<?php .search-input:focus {
<?php outline:<?php none;
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);
<?php box-shadow:<?php 0 0 0 3px rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }
<?php .search-input::placeholder {
<?php color:<?php #6b7280;
<?php }
<?php .search-icon {
<?php position:<?php absolute;
<?php left:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #9ca3af;
<?php font-size:<?php 1rem;
<?php }
<?php /*<?php Affiliate Cards */
<?php .affiliates-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(500px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php }
<?php .affiliate-card {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php transition:<?php all 0.3s ease;
<?php backdrop-filter:<?php blur(20px);
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }
<?php .affiliate-card::before {
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
<?php .affiliate-card:hover::before {
<?php opacity:<?php 1;
<?php }
<?php .affiliate-card:hover {
<?php transform:<?php translateY(-4px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php box-shadow:<?php 0 20px 40px rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php .affiliate-header {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php flex-start;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .affiliate-name {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 700;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.75rem;
<?php }
<?php .affiliate-badges {
<?php display:<?php flex;
<?php gap:<?php 0.5rem;
<?php flex-wrap:<?php wrap;
<?php }
<?php .badge {
<?php padding:<?php 0.3rem 0.75rem;
<?php border-radius:<?php 20px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.5px;
<?php }
<?php .badge.admin {
<?php background:<?php linear-gradient(135deg,<?php #8b5cf6,<?php #7c3aed);
<?php color:<?php white;
<?php }
<?php .badge.influencer {
<?php background:<?php linear-gradient(135deg,<?php #ec4899,<?php #db2777);
<?php color:<?php white;
<?php }
<?php .badge.banned {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php }
<?php .badge.affiliate {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php /*<?php Contact Info */
<?php .contact-info {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .contact-item {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php color:<?php #e5e7eb;
<?php font-size:<?php 0.9rem;
<?php }
<?php .contact-item i {
<?php color:<?php #22c55e;
<?php width:<?php 16px;
<?php text-align:<?php center;
<?php }
<?php .whatsapp-link {
<?php color:<?php #25d366;
<?php margin-left:<?php 0.5rem;
<?php transition:<?php color 0.3s ease;
<?php font-size:<?php 1rem;
<?php }
<?php .whatsapp-link:hover {
<?php color:<?php #128c7e;
<?php transform:<?php scale(1.1);
<?php }
<?php /*<?php Stats Section in Cards */
<?php .affiliate-stats {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(140px,<?php 1fr));
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .stat-card {
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php padding:<?php 1rem;
<?php transition:<?php all 0.3s ease;
<?php }
<?php .stat-card:hover {
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);
<?php }
<?php .stat-label {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php color:<?php #a1a1aa;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 500;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php .stat-label i {
<?php color:<?php #22c55e;
<?php font-size:<?php 0.8rem;
<?php }
<?php .stat-value {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 800;
<?php color:<?php #22c55e;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php }
<?php .edit-commission {
<?php background:<?php none;
<?php border:<?php none;
<?php color:<?php #60a5fa;
<?php cursor:<?php pointer;
<?php padding:<?php 0.25rem;
<?php border-radius:<?php 4px;
<?php transition:<?php all 0.3s ease;
<?php font-size:<?php 0.8rem;
<?php }
<?php .edit-commission:hover {
<?php color:<?php #3b82f6;
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.1);
<?php transform:<?php scale(1.1);
<?php }
<?php /*<?php Action Buttons */
<?php .action-buttons {
<?php display:<?php flex;
<?php gap:<?php 0.75rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .action-btn {
<?php flex:<?php 1;
<?php padding:<?php 0.75rem 1rem;
<?php border-radius:<?php 12px;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 600;
<?php text-decoration:<?php none;
<?php text-align:<?php center;
<?php transition:<?php all 0.3s ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php cursor:<?php pointer;
<?php border:<?php none;
<?php }
<?php .action-btn:hover {
<?php transform:<?php translateY(-1px);
<?php box-shadow:<?php 0 4px 12px rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php .btn-ban {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php }
<?php .btn-unban {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php .btn-influencer {
<?php background:<?php linear-gradient(135deg,<?php #ec4899,<?php #db2777);
<?php color:<?php white;
<?php }

<?php /*<?php Botão de Detalhes */
<?php .btn-details {
<?php background:<?php linear-gradient(135deg,<?php #3b82f6,<?php #2563eb);
<?php color:<?php white;
<?php }

<?php .btn-details:hover {
<?php background:<?php linear-gradient(135deg,<?php #2563eb,<?php #1d4ed8);
<?php }

<?php /*<?php Modal de Detalhes */
<?php .modal-details {
<?php position:<?php fixed;
<?php inset:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.8);
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php z-index:<?php 1100;
<?php backdrop-filter:<?php blur(8px);
<?php transition:<?php all 0.3s ease;
<?php }

<?php .modal-details.hidden {
<?php display:<?php none;
<?php opacity:<?php 0;
<?php }

<?php .modal-details-content {
<?php background:<?php linear-gradient(135deg,<?php rgba(10,<?php 10,<?php 10,<?php 0.98)<?php 0%,<?php rgba(20,<?php 20,<?php 20,<?php 0.95)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 24px;
<?php width:<?php 95%;
<?php max-width:<?php 1200px;
<?php max-height:<?php 90vh;
<?php overflow:<?php hidden;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0 25px 80px rgba(0,<?php 0,<?php 0,<?php 0.6);
<?php position:<?php relative;
<?php }

<?php .modal-details-header {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php padding:<?php 2rem;
<?php border-bottom:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent 100%);
<?php }

<?php .modal-details-title {
<?php font-size:<?php 1.75rem;
<?php font-weight:<?php 700;
<?php color:<?php white;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php }

<?php .modal-details-title i {
<?php color:<?php #22c55e;
<?php font-size:<?php 1.5rem;
<?php }

<?php .close-btn {
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.1);
<?php border:<?php 1px solid rgba(239,<?php 68,<?php 68,<?php 0.3);
<?php color:<?php #ef4444;
<?php padding:<?php 0.75rem;
<?php border-radius:<?php 12px;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php font-size:<?php 1.1rem;
<?php }

<?php .close-btn:hover {
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.2);
<?php transform:<?php scale(1.05);
<?php }

<?php .modal-details-body {
<?php padding:<?php 2rem;
<?php max-height:<?php calc(90vh -<?php 120px);
<?php overflow-y:<?php auto;
<?php }

<?php /*<?php Loading */
<?php .loading-container {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php padding:<?php 4rem;
<?php color:<?php #9ca3af;
<?php }

<?php .loading-spinner {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php border:<?php 4px solid rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-top:<?php 4px solid #22c55e;
<?php border-radius:<?php 50%;
<?php animation:<?php spin 1s linear infinite;
<?php margin-bottom:<?php 1rem;
<?php }

<?php @keyframes spin {
<?php 0%<?php {<?php transform:<?php rotate(0deg);<?php }
<?php 100%<?php {<?php transform:<?php rotate(360deg);<?php }
<?php }

<?php /*<?php Stats Grid no Modal */
<?php .details-stats-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 2rem;
<?php }

<?php .details-stat-card {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.6)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.8)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php padding:<?php 1.5rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php transition:<?php all 0.3s ease;
<?php }

<?php .details-stat-card:hover {
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php transform:<?php translateY(-2px);
<?php }

<?php .details-stat-icon {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php border-radius:<?php 12px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.25rem;
<?php }

<?php .details-stat-icon.green {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php color:<?php #22c55e;
<?php }

<?php .details-stat-icon.blue {
<?php background:<?php linear-gradient(135deg,<?php rgba(59,<?php 130,<?php 246,<?php 0.2)<?php 0%,<?php rgba(59,<?php 130,<?php 246,<?php 0.1)<?php 100%);
<?php border:<?php 1px solid rgba(59,<?php 130,<?php 246,<?php 0.3);
<?php color:<?php #3b82f6;
<?php }

<?php .details-stat-icon.purple {
<?php background:<?php linear-gradient(135deg,<?php rgba(147,<?php 51,<?php 234,<?php 0.2)<?php 0%,<?php rgba(147,<?php 51,<?php 234,<?php 0.1)<?php 100%);
<?php border:<?php 1px solid rgba(147,<?php 51,<?php 234,<?php 0.3);
<?php color:<?php #9333ea;
<?php }

<?php .details-stat-icon.orange {
<?php background:<?php linear-gradient(135deg,<?php rgba(249,<?php 115,<?php 22,<?php 0.2)<?php 0%,<?php rgba(249,<?php 115,<?php 22,<?php 0.1)<?php 100%);
<?php border:<?php 1px solid rgba(249,<?php 115,<?php 22,<?php 0.3);
<?php color:<?php #f97316;
<?php }

<?php .details-stat-info {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php }

<?php .details-stat-value {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.25rem;
<?php }

<?php .details-stat-label {
<?php font-size:<?php 0.8rem;
<?php color:<?php #9ca3af;
<?php font-weight:<?php 500;
<?php }

<?php /*<?php Tabs */
<?php .details-tabs {
<?php display:<?php flex;
<?php border-bottom:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php margin-bottom:<?php 2rem;
<?php gap:<?php 0.5rem;
<?php }

<?php .tab-btn {
<?php background:<?php transparent;
<?php border:<?php none;
<?php padding:<?php 1rem 1.5rem;
<?php color:<?php #9ca3af;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php cursor:<?php pointer;
<?php border-radius:<?php 12px 12px 0 0;
<?php transition:<?php all 0.3s ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php position:<?php relative;
<?php }

<?php .tab-btn:hover {
<?php color:<?php #22c55e;
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);
<?php }

<?php .tab-btn.active {
<?php color:<?php #22c55e;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-bottom:<?php none;
<?php }

<?php .tab-btn.active::after {
<?php content:<?php '';
<?php position:<?php absolute;
<?php bottom:<?php -1px;
<?php left:<?php 0;
<?php right:<?php 0;
<?php height:<?php 2px;
<?php background:<?php linear-gradient(90deg,<?php #22c55e,<?php #16a34a);
<?php }

<?php /*<?php Tab Content */
<?php .tab-content {
<?php display:<?php none;
<?php }

<?php .tab-content.active {
<?php display:<?php block;
<?php }

<?php .tab-header {
<?php margin-bottom:<?php 1.5rem;
<?php }

<?php .tab-header h3 {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 600;
<?php color:<?php #ffffff;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php }

<?php .tab-header h3 i {
<?php color:<?php #22c55e;
<?php }

<?php /*<?php Table Container */
<?php .table-container {
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.2);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php overflow:<?php hidden;
<?php }

<?php .details-table {
<?php width:<?php 100%;
<?php border-collapse:<?php collapse;
<?php }

<?php .details-table th {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);
<?php color:<?php #22c55e;
<?php padding:<?php 1rem;
<?php text-align:<?php left;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php border-bottom:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }

<?php .details-table td {
<?php padding:<?php 1rem;
<?php color:<?php #e5e7eb;
<?php font-size:<?php 0.9rem;
<?php border-bottom:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php }

<?php .details-table tr:hover {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.02);
<?php }

<?php .details-table tr:last-child td {
<?php border-bottom:<?php none;
<?php }

<?php /*<?php Status badges na tabela */
<?php .status-badge {
<?php padding:<?php 0.25rem 0.5rem;
<?php border-radius:<?php 6px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php }

<?php .status-badge.ativo {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(34,<?php 197,<?php 94,<?php 0.1));
<?php color:<?php #22c55e;
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .status-badge.banido {
<?php background:<?php linear-gradient(135deg,<?php rgba(239,<?php 68,<?php 68,<?php 0.2),<?php rgba(239,<?php 68,<?php 68,<?php 0.1));
<?php color:<?php #ef4444;
<?php border:<?php 1px solid rgba(239,<?php 68,<?php 68,<?php 0.3);
<?php }

<?php /*<?php WhatsApp link na tabela */
<?php .whatsapp-table-link {
<?php color:<?php #25d366;
<?php margin-left:<?php 0.5rem;
<?php font-size:<?php 1rem;
<?php transition:<?php all 0.3s ease;
<?php }

<?php .whatsapp-table-link:hover {
<?php color:<?php #128c7e;
<?php transform:<?php scale(1.1);
<?php }

<?php /*<?php Empty state nas tabelas */
<?php .table-empty {
<?php padding:<?php 3rem;
<?php text-align:<?php center;
<?php color:<?php #6b7280;
<?php }

<?php .table-empty i {
<?php font-size:<?php 2rem;
<?php margin-bottom:<?php 1rem;
<?php opacity:<?php 0.5;
<?php color:<?php #374151;
<?php }
<?php .btn-remove-inf {
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);
<?php color:<?php white;
<?php }
<?php /*<?php Affiliate Meta */
<?php .affiliate-meta {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.875rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php padding-top:<?php 1rem;
<?php border-top:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php .affiliate-meta i {
<?php color:<?php #6b7280;
<?php }
<?php /*<?php Modal Styles */
<?php .modal {
<?php position:<?php fixed;
<?php inset:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php z-index:<?php 1000;
<?php backdrop-filter:<?php blur(4px);
<?php }
<?php .modal.hidden {
<?php display:<?php none;
<?php }
<?php .modal-content {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.95)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.98)<?php 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php width:<?php 90%;
<?php max-width:<?php 500px;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0 20px 60px rgba(0,<?php 0,<?php 0,<?php 0.5);
<?php }
<?php .modal-title {
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 700;
<?php color:<?php white;
<?php margin-bottom:<?php 1.5rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php }
<?php .modal-title i {
<?php color:<?php #22c55e;
<?php }
<?php .modal-form-group {
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php .modal-label {
<?php display:<?php block;
<?php color:<?php #e5e7eb;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 600;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php .modal-input-container {
<?php position:<?php relative;
<?php }
<?php .modal-currency {
<?php position:<?php absolute;
<?php left:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #9ca3af;
<?php font-weight:<?php 600;
<?php }
<?php .modal-percentage {
<?php position:<?php absolute;
<?php right:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #9ca3af;
<?php font-weight:<?php 600;
<?php }
<?php .modal-input {
<?php width:<?php 100%;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php padding:<?php 0.75rem 1rem 0.75rem 2.5rem;
<?php color:<?php white;
<?php font-size:<?php 1rem;
<?php transition:<?php all 0.3s ease;
<?php }
<?php .modal-input.percentage {
<?php padding:<?php 0.75rem 2.5rem 0.75rem 1rem;
<?php }
<?php .modal-input:focus {
<?php outline:<?php none;
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);
<?php box-shadow:<?php 0 0 0 3px rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }
<?php .modal-input::placeholder {
<?php color:<?php #6b7280;
<?php }
<?php .modal-buttons {
<?php display:<?php flex;
<?php gap:<?php 1rem;
<?php }
<?php .modal-btn {
<?php flex:<?php 1;
<?php padding:<?php 0.875rem 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php border:<?php none;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php }
<?php .modal-btn-primary {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php .modal-btn-primary:hover {
<?php transform:<?php translateY(-1px);
<?php box-shadow:<?php 0 4px 16px rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }
<?php .modal-btn-secondary {
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);
<?php color:<?php #e5e7eb;
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php .modal-btn-secondary:hover {
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.4);
<?php }
<?php /*<?php Empty State */
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
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));
<?php }
<?php .affiliates-grid {
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
<?php .affiliate-card {
<?php padding:<?php 1.5rem;
<?php }
<?php .contact-info {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php .affiliate-stats {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php .action-buttons {
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
<?php .sidebar {
<?php width:<?php 260px;
<?php }
<?php .modal-content {
<?php margin:<?php 1rem;
<?php padding:<?php 1.5rem;
<?php }
<?php .modal-buttons {
<?php flex-direction:<?php column;
<?php }
<?php }
<?php /*<?php Overlay for mobile */
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
<?php <!--<?php Advanced Sidebar -->
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
<?php <a href="afiliados.php"<?php class="nav-item active">
<?php <div class="nav-icon"><i class="fas fa-user-plus"></i></div>
<?php <div class="nav-text">Afiliados</div>
<?php </a>
<?php <a href="depositos.php"<?php class="nav-item">
<?php <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
<?php <div class="nav-text">Depósitos</div>
<?php </a>
<?php <a href="saques.php"<?php class="nav-item">
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
<?php <!--<?php Enhanced Header -->
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
<?php <h2 class="welcome-title">Gerenciar Afiliados</h2>
<?php <p class="welcome-subtitle">Visualize e gerencie todos os afiliados e suas comissões na plataforma</p>
<?php </section>
<?php <!--<?php Stats Grid -->
<?php <section class="stats-grid">
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon">
<?php <i class="fas fa-handshake"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value"><?php=<?php number_format($total_afiliados,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Total de Afiliados</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon purple">
<?php <i class="fas fa-users"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value"><?php=<?php number_format($total_indicados,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Total de Indicados</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon">
<?php <i class="fas fa-dollar-sign"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value">R$<?php <?php=<?php number_format($total_depositos_afiliados,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Depósitos dos Indicados</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon orange">
<?php <i class="fas fa-chart-line"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value">R$<?php <?php=<?php number_format($total_revshare_pago,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Total RevShare Pago</div>
<?php </div>
<?php <div class="mini-stat-card">
<?php <div class="mini-stat-header">
<?php <div class="mini-stat-icon blue">
<?php <i class="fas fa-star"></i>
<?php </div>
<?php </div>
<?php <div class="mini-stat-value"><?php=<?php number_format($influencers_count,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div class="mini-stat-label">Influencers Ativos</div>
<?php </div>
<?php </section>
<?php <!--<?php Search Section -->
<?php <section class="search-section">
<?php <div class="search-header">
<?php <div class="search-icon-container">
<?php <i class="fas fa-search"></i>
<?php </div>
<?php <h3 class="search-title">Pesquisar Afiliados</h3>
<?php </div>
<?php <form method="GET">
<?php <div class="search-container">
<?php <i class="fas fa-search search-icon"></i>
<?php <input type="text"<?php name="search"<?php value="<?php=<?php htmlspecialchars($search)<?php ?>"<?php class="search-input"<?php placeholder="Pesquisar por nome,<?php email ou telefone..."<?php onchange="this.form.submit()">
<?php </div>
<?php </form>
<?php </section>
<?php <!--<?php Affiliates Section -->
<?php <section>
<?php <?php if (empty($afiliados)):<?php ?>
<?php <div class="empty-state">
<?php <i class="fas fa-handshake"></i>
<?php <h3>Nenhum afiliado encontrado</h3>
<?php <p>Não há<?php afiliados que correspondam aos critérios de pesquisa</p>
<?php </div>
<?php <?php else:<?php ?>
<?php <div class="affiliates-grid">
<?php <?php foreach ($afiliados as $afiliado):<?php ?>
<?php <?php <?php $telefone =<?php $afiliado['telefone'];
<?php if (strlen($telefone)<?php ==<?php 11)<?php {
<?php $telefoneFormatado =<?php '('.substr($telefone,<?php 0,<?php 2).')<?php '.substr($telefone,<?php 2,<?php 5).'-'.substr($telefone,<?php 7);
<?php }<?php else {
<?php $telefoneFormatado =<?php $telefone;
<?php }
<?php $whatsappLink =<?php 'https://wa.me/55'.preg_replace('/[^0-9]/',<?php '',<?php $afiliado['telefone']);
<?php $comissao_cpa =<?php isset($afiliado['comissao_cpa'])<?php ?<?php number_format($afiliado['comissao_cpa'],<?php 2,<?php ',',<?php '.')<?php :<?php '0,00';
<?php $comissao_revshare =<?php isset($afiliado['comissao_revshare'])<?php ?<?php number_format($afiliado['comissao_revshare'],<?php 2,<?php ',',<?php '.')<?php :<?php '0,00';
<?php ?>
<?php <div class="affiliate-card">
<?php <div class="affiliate-header">
<?php <div>
<?php <h3 class="affiliate-name"><?php=<?php htmlspecialchars($afiliado['nome'])<?php ?></h3>
<?php <div class="affiliate-badges">
<?php <span class="badge affiliate">Afiliado</span>
<?php <?php if ($afiliado['admin']<?php ==<?php 1):<?php ?>
<?php <span class="badge admin">Admin</span>
<?php <?php endif;<?php ?>
<?php <?php if ($afiliado['influencer']<?php ==<?php 1):<?php ?>
<?php <span class="badge influencer">Influencer</span>
<?php <?php endif;<?php ?>
<?php <?php if ($afiliado['banido']<?php ==<?php 1):<?php ?>
<?php <span class="badge banned">Banido</span>
<?php <?php endif;<?php ?>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="contact-info">
<?php <div class="contact-item">
<?php <i class="fas fa-envelope"></i>
<?php <span><?php=<?php htmlspecialchars($afiliado['email'])<?php ?></span>
<?php </div>
<?php <div class="contact-item">
<?php <i class="fas fa-phone"></i>
<?php <span><?php=<?php $telefoneFormatado ?></span>
<?php <a href="<?php=<?php $whatsappLink ?>"<?php target="_blank"<?php class="whatsapp-link">
<?php <i class="fab fa-whatsapp"></i>
<?php </a>
<?php </div>
<?php </div>
<?php <div class="affiliate-stats">
<?php <div class="stat-card">
<?php <div class="stat-label">
<?php <i class="fas fa-users"></i>
<?php Indicados </div>
<?php <div class="stat-value"><?php=<?php $afiliado['total_indicados']<?php ?></div>
<?php </div>
<?php <div class="stat-card">
<?php <div class="stat-label">
<?php <i class="fas fa-money-bill-wave"></i>
<?php Depósitos </div>
<?php <div class="stat-value">R$<?php <?php=<?php number_format($afiliado['total_depositos'],<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php <div class="stat-card">
<?php <div class="stat-label">
<?php <i class="fas fa-percentage"></i>
<?php CPA </div>
<?php <div class="stat-value">
<?php R$<?php <?php=<?php $comissao_cpa ?>
<?php <button onclick="abrirModalComissao('<?php=<?php $afiliado['id']<?php ?>',<?php '<?php=<?php isset($afiliado['comissao_cpa'])<?php ?<?php $afiliado['comissao_cpa']<?php :<?php '0'<?php ?>',<?php 'cpa')"
<?php class="edit-commission">
<?php <i class="fas fa-edit"></i>
<?php </button>
<?php </div>
<?php </div>
<?php <div class="stat-card">
<?php <div class="stat-label">
<?php <i class="fas fa-chart-line"></i>
<?php RevShare </div>
<?php <div class="stat-value">
<?php <?php=<?php $comissao_revshare ?>%
<?php <button onclick="abrirModalComissao('<?php=<?php $afiliado['id']<?php ?>',<?php '<?php=<?php isset($afiliado['comissao_revshare'])<?php ?<?php $afiliado['comissao_revshare']<?php :<?php '0'<?php ?>',<?php 'revshare')"
<?php class="edit-commission">
<?php <i class="fas fa-edit"></i>
<?php </button>
<?php </div>
<?php </div>
<?php <div class="stat-card">
<?php <div class="stat-label">
<?php <i class="fas fa-wallet"></i>
<?php Rev.<?php Ganho </div>
<?php <div class="stat-value">R$<?php <?php=<?php number_format($afiliado['total_revshare']<?php ??<?php 0,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php </div>
<?php <div class="action-buttons">
<?php <a href="?toggle_banido&id=<?php=<?php $afiliado['id']<?php ?>"<?php class="action-btn <?php=<?php $afiliado['banido']<?php ?<?php 'btn-unban'<?php :<?php 'btn-ban'<?php ?>">
<?php <i class="fas fa-<?php=<?php $afiliado['banido']<?php ?<?php 'user-check'<?php :<?php 'user-slash'<?php ?>"></i>
<?php <?php=<?php $afiliado['banido']<?php ?<?php 'Desbanir'<?php :<?php 'Banir'<?php ?>
<?php </a>
<?php <a href="?toggle_influencer&id=<?php=<?php $afiliado['id']<?php ?>"<?php class="action-btn <?php=<?php $afiliado['influencer']<?php ?<?php 'btn-remove-inf'<?php :<?php 'btn-influencer'<?php ?>">
<?php <i class="fas fa-<?php=<?php $afiliado['influencer']<?php ?<?php 'user-minus'<?php :<?php 'star'<?php ?>"></i>
<?php <?php=<?php $afiliado['influencer']<?php ?<?php 'Remover Inf.'<?php :<?php 'Tornar Inf.'<?php ?>
<?php </a>
<?php <button onclick="abrirDetalhesAfiliado(<?php=<?php $afiliado['id']<?php ?>)"<?php class="action-btn btn-details">
<?php <i class="fas fa-eye"></i>
<?php Detalhes </button>
<?php </div>
<?php <div class="affiliate-meta">
<?php <i class="fas fa-calendar"></i>
<?php <span>Cadastrado em:<?php <?php=<?php date('d/m/Y H:i',<?php strtotime($afiliado['created_at']))<?php ?></span>
<?php </div>
<?php </div>
<?php <?php endforeach;<?php ?>
<?php </div>
<?php <?php endif;<?php ?>
<?php </section>
<?php </div>
<?php </main>

<?php <!--<?php Modal Editar Comissão CPA -->
<?php <div id="editarComissaoModal"<?php class="modal hidden">
<?php <div class="modal-content">
<?php <h2 class="modal-title"<?php id="modalTitle">
<?php <i class="fas fa-percentage"></i>
<?php Editar Comissão CPA </h2>
<?php <form method="POST"<?php id="formEditarComissao">
<?php <input type="hidden"<?php name="id"<?php id="afiliadoId">
<?php <div class="modal-form-group">
<?php <label class="modal-label"<?php id="modalLabel">
<?php <i class="fas fa-dollar-sign"></i>
<?php Valor da Comissão CPA </label>
<?php <div class="modal-input-container">
<?php <span class="modal-currency"<?php id="modalCurrency">R$</span>
<?php <input type="text"<?php name="comissao_cpa"<?php id="afiliadoComissao"<?php class="modal-input"<?php placeholder="0,00"<?php required>
<?php <span class="modal-percentage hidden"<?php id="modalPercentage">%</span>
<?php </div>
<?php </div>
<?php <div class="modal-buttons">
<?php <button type="submit"<?php name="atualizar_comissao_cpa"<?php class="modal-btn modal-btn-primary"<?php id="submitBtn">
<?php <i class="fas fa-save"></i>
<?php Salvar </button>
<?php <button type="button"<?php onclick="fecharModalComissao()"<?php class="modal-btn modal-btn-secondary">
<?php <i class="fas fa-times"></i>
<?php Cancelar </button>
<?php </div>
<?php </form>
<?php </div>
<?php </div>
<?php <!--<?php Modal Editar Comissão RevShare -->
<?php <div id="editarRevshareModal"<?php class="modal hidden">
<?php <div class="modal-content">
<?php <h2 class="modal-title">
<?php <i class="fas fa-chart-line"></i>
<?php Editar Comissão RevShare </h2>
<?php <form method="POST"<?php id="formEditarRevshare">
<?php <input type="hidden"<?php name="id"<?php id="afiliadoIdRevshare">
<?php <div class="modal-form-group">
<?php <label class="modal-label">
<?php <i class="fas fa-percentage"></i>
<?php Percentual da Comissão RevShare </label>
<?php <div class="modal-input-container">
<?php <input type="text"<?php name="comissao_revshare"<?php id="afiliadoComissaoRevshare"<?php class="modal-input percentage"<?php placeholder="0,00"<?php required>
<?php <span class="modal-percentage">%</span>
<?php </div>
<?php </div>
<?php <div class="modal-buttons">
<?php <button type="submit"<?php name="atualizar_comissao_revshare"<?php class="modal-btn modal-btn-primary">
<?php <i class="fas fa-save"></i>
<?php Salvar </button>
<?php <button type="button"<?php onclick="fecharModalRevshare()"<?php class="modal-btn modal-btn-secondary">
<?php <i class="fas fa-times"></i>
<?php Cancelar </button>
<?php </div>
<?php </form>
<?php </div>
<?php </div>

<?php <!--<?php Modal Detalhes do Afiliado -->
<?php <div id="detalhesAfiliadoModal"<?php class="modal-details hidden">
<?php <div class="modal-details-content">
<?php <div class="modal-details-header">
<?php <h2 class="modal-details-title">
<?php <i class="fas fa-user-circle"></i>
<?php <span id="nomeAfiliadoModal">Detalhes do Afiliado</span>
<?php </h2>
<?php <button onclick="fecharDetalhesAfiliado()"<?php class="close-btn">
<?php <i class="fas fa-times"></i>
<?php </button>
<?php </div>
<?php <div class="modal-details-body">
<?php <!--<?php Loading state -->
<?php <div id="detalhesLoading"<?php class="loading-container">
<?php <div class="loading-spinner"></div>
<?php <p>Carregando detalhes...</p>
<?php </div>
<?php <!--<?php Content container -->
<?php <div id="detalhesContent"<?php class="hidden">
<?php <!--<?php Estatísticas rápidas -->
<?php <div class="details-stats-grid">
<?php <div class="details-stat-card">
<?php <div class="details-stat-icon green">
<?php <i class="fas fa-users"></i>
<?php </div>
<?php <div class="details-stat-info">
<?php <span class="details-stat-value"<?php id="totalIndicados">0</span>
<?php <span class="details-stat-label">Total de Indicados</span>
<?php </div>
<?php </div>
<?php <div class="details-stat-card">
<?php <div class="details-stat-icon blue">
<?php <i class="fas fa-dollar-sign"></i>
<?php </div>
<?php <div class="details-stat-info">
<?php <span class="details-stat-value"<?php id="totalDepositado">R$<?php 0,00</span>
<?php <span class="details-stat-label">Total Depositado</span>
<?php </div>
<?php </div>
<?php <div class="details-stat-card">
<?php <div class="details-stat-icon purple">
<?php <i class="fas fa-percentage"></i>
<?php </div>
<?php <div class="details-stat-info">
<?php <span class="details-stat-value"<?php id="totalCPA">R$<?php 0,00</span>
<?php <span class="details-stat-label">Total CPA</span>
<?php </div>
<?php </div>
<?php <div class="details-stat-card">
<?php <div class="details-stat-icon orange">
<?php <i class="fas fa-chart-line"></i>
<?php </div>
<?php <div class="details-stat-info">
<?php <span class="details-stat-value"<?php id="totalRevShare">R$<?php 0,00</span>
<?php <span class="details-stat-label">Total RevShare</span>
<?php </div>
<?php </div>
<?php <div class="details-stat-card">
<?php <div class="details-stat-icon green">
<?php <i class="fas fa-wallet"></i>
<?php </div>
<?php <div class="details-stat-info">
<?php <span class="details-stat-value"<?php id="saldoAtual">R$<?php 0,00</span>
<?php <span class="details-stat-label">Saldo Atual</span>
<?php </div>
<?php </div>
<?php </div>
<?php <!--<?php Tabs -->
<?php <div class="details-tabs">
<?php <button class="tab-btn active"<?php onclick="abrirTab('indicados')">
<?php <i class="fas fa-users"></i>
<?php Indicados </button>
<?php <button class="tab-btn"<?php onclick="abrirTab('historico-cpa')">
<?php <i class="fas fa-percentage"></i>
<?php Histórico CPA </button>
<?php <button class="tab-btn"<?php onclick="abrirTab('historico-revshare')">
<?php <i class="fas fa-chart-line"></i>
<?php Histórico RevShare </button>
<?php </div>
<?php <!--<?php Tab Content -<?php Indicados -->
<?php <div id="tab-indicados"<?php class="tab-content active">
<?php <div class="tab-header">
<?php <h3><i class="fas fa-users"></i><?php Lista de Indicados</h3>
<?php </div>
<?php <div id="listaIndicados"<?php class="table-container">
<?php <!--<?php Conteúdo será<?php preenchido via JavaScript -->
<?php </div>
<?php </div>
<?php <!--<?php Tab Content -<?php Histórico CPA -->
<?php <div id="tab-historico-cpa"<?php class="tab-content">
<?php <div class="tab-header">
<?php <h3><i class="fas fa-percentage"></i><?php Histórico de Comissões CPA</h3>
<?php </div>
<?php <div id="historicoCPA"<?php class="table-container">
<?php <!--<?php Conteúdo será<?php preenchido via JavaScript -->
<?php </div>
<?php </div>
<?php <!--<?php Tab Content -<?php Histórico RevShare -->
<?php <div id="tab-historico-revshare"<?php class="tab-content">
<?php <div class="tab-header">
<?php <h3><i class="fas fa-chart-line"></i><?php Histórico RevShare</h3>
<?php </div>
<?php <div id="historicoRevShare"<?php class="table-container">
<?php <!--<?php Conteúdo será<?php preenchido via JavaScript -->
<?php </div>
<?php </div>
<?php </div>
<?php </div>
<?php </div>
<?php </div>
<?php 
<script>
<?php //<?php Mobile menu toggle with smooth animations const menuToggle =<?php document.getElementById('menuToggle');
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
<?php //<?php Modal functions function abrirModalComissao(id,<?php comissao,<?php tipo)<?php {
<?php if (tipo ===<?php 'revshare')<?php {
<?php abrirModalRevshare(id,<?php comissao);
<?php return;
<?php }
<?php document.getElementById('afiliadoId').value =<?php id;
<?php document.getElementById('afiliadoComissao').value =<?php comissao;
<?php document.getElementById('editarComissaoModal').classList.remove('hidden');
<?php }
<?php function abrirModalRevshare(id,<?php comissao)<?php {
<?php document.getElementById('afiliadoIdRevshare').value =<?php id;
<?php document.getElementById('afiliadoComissaoRevshare').value =<?php comissao;
<?php document.getElementById('editarRevshareModal').classList.remove('hidden');
<?php }
<?php function fecharModalComissao()<?php {
<?php document.getElementById('editarComissaoModal').classList.add('hidden');
<?php }
<?php function fecharModalRevshare()<?php {
<?php document.getElementById('editarRevshareModal').classList.add('hidden');
<?php }
<?php //<?php Close modal when clicking outside document.getElementById('editarComissaoModal').addEventListener('click',<?php function(e)<?php {
<?php if (e.target ===<?php this)<?php {
<?php fecharModalComissao();
<?php }
<?php });
<?php document.getElementById('editarRevshareModal').addEventListener('click',<?php function(e)<?php {
<?php if (e.target ===<?php this)<?php {
<?php fecharModalRevshare();
<?php }
<?php });

<?php //<?php Variável global para controlar o modal let detalhesModalAberto =<?php false;

<?php //<?php Close modal with ESC key document.addEventListener('keydown',<?php function(e)<?php {
<?php if (e.key ===<?php 'Escape')<?php {
<?php fecharModalComissao();
<?php fecharModalRevshare();
<?php if (detalhesModalAberto)<?php {
<?php fecharDetalhesAfiliado();
<?php }
<?php }
<?php });

<?php //<?php Função para abrir detalhes do afiliado async function abrirDetalhesAfiliado(afiliadoId)<?php {
<?php const modal =<?php document.getElementById('detalhesAfiliadoModal');
<?php const loading =<?php document.getElementById('detalhesLoading');
<?php const content =<?php document.getElementById('detalhesContent');
<?php //<?php Mostrar modal com loading modal.classList.remove('hidden');
<?php loading.classList.remove('hidden');
<?php content.classList.add('hidden');
<?php detalhesModalAberto =<?php true;
<?php try {
<?php //<?php Fazer requisição AJAX const response =<?php await fetch(`?ajax=detalhes_afiliado&afiliado_id=${afiliadoId}`);
<?php const data =<?php await response.json();
<?php if (data.error)<?php {
<?php throw new Error(data.error);
<?php }
<?php //<?php Preencher dados do modal preencherDetalhesModal(data);
<?php //<?php Esconder loading e mostrar content loading.classList.add('hidden');
<?php content.classList.remove('hidden');
<?php }<?php catch (error)<?php {
<?php console.error('Erro ao carregar detalhes:',<?php error);
<?php Notiflix.Notify.failure('Erro ao carregar detalhes do afiliado:<?php '<?php +<?php error.message);
<?php fecharDetalhesAfiliado();
<?php }
<?php }

<?php //<?php Função para preencher o modal com os dados function preencherDetalhesModal(data)<?php {
<?php const {<?php afiliado,<?php indicados,<?php historico_cpa,<?php historico_revshare,<?php estatisticas }<?php =<?php data;
<?php //<?php Atualizar título com nome do afiliado document.getElementById('nomeAfiliadoModal').textContent =<?php `Detalhes de ${afiliado.nome}`;
<?php //<?php Atualizar estatísticas document.getElementById('totalIndicados').textContent =<?php estatisticas.total_indicados;
<?php document.getElementById('totalDepositado').textContent =<?php `R$<?php ${formatarMoeda(estatisticas.total_depositado_indicados)}`;
<?php document.getElementById('totalCPA').textContent =<?php `R$<?php ${formatarMoeda(estatisticas.total_comissao_cpa)}`;
<?php document.getElementById('totalRevShare').textContent =<?php `R$<?php ${formatarMoeda(estatisticas.total_comissao_revshare)}`;
<?php document.getElementById('saldoAtual').textContent =<?php `R$<?php ${formatarMoeda(estatisticas.saldo_atual)}`;
<?php //<?php Preencher tabela de indicados preencherTabelaIndicados(indicados);
<?php //<?php Preencher histórico CPA preencherHistoricoCPA(historico_cpa);
<?php //<?php Preencher histórico RevShare preencherHistoricoRevShare(historico_revshare);
<?php }

<?php //<?php Função para preencher tabela de indicados function preencherTabelaIndicados(indicados)<?php {
<?php const container =<?php document.getElementById('listaIndicados');
<?php if (indicados.length ===<?php 0)<?php {
<?php container.innerHTML =<?php `
<?php <div class="table-empty">
<?php <i class="fas fa-users"></i>
<?php <h4>Nenhum indicado encontrado</h4>
<?php <p>Este afiliado ainda não possui indicados</p>
<?php </div>
<?php `;
<?php return;
<?php }
<?php let html =<?php `
<?php <table class="details-table">
<?php <thead>
<?php <tr>
<?php <th><i class="fas fa-user"></i><?php Nome</th>
<?php <th><i class="fas fa-envelope"></i><?php Email</th>
<?php <th><i class="fas fa-phone"></i><?php Telefone</th>
<?php <th><i class="fas fa-dollar-sign"></i><?php Total Depositado</th>
<?php <th><i class="fas fa-credit-card"></i><?php Nº<?php Depósitos</th>
<?php <th><i class="fas fa-calendar"></i><?php Cadastro</th>
<?php <th><i class="fas fa-info-circle"></i><?php Status</th>
<?php </tr>
<?php </thead>
<?php <tbody>
<?php `;
<?php indicados.forEach(indicado =><?php {
<?php const telefoneFormatado =<?php formatarTelefone(indicado.telefone);
<?php const whatsappLink =<?php `https://wa.me/55${indicado.telefone.replace(/[^0-9]/g,<?php '')}`;
<?php const statusClass =<?php indicado.banido ==<?php 1 ?<?php 'banido'<?php :<?php 'ativo';
<?php const statusText =<?php indicado.banido ==<?php 1 ?<?php 'Banido'<?php :<?php 'Ativo';
<?php const dataCadastro =<?php formatarData(indicado.data_cadastro);
<?php html +=<?php `
<?php <tr>
<?php <td>
<?php <div style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 0.5rem;">
<?php <div style="width:<?php 32px;<?php height:<?php 32px;<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php border-radius:<?php 8px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php center;<?php color:<?php white;<?php font-weight:<?php 600;<?php font-size:<?php 0.8rem;">
<?php ${indicado.nome.charAt(0).toUpperCase()}
<?php </div>
<?php ${indicado.nome}
<?php </div>
<?php </td>
<?php <td>${indicado.email}</td>
<?php <td>
<?php ${telefoneFormatado}
<?php <a href="${whatsappLink}"<?php target="_blank"<?php class="whatsapp-table-link">
<?php <i class="fab fa-whatsapp"></i>
<?php </a>
<?php </td>
<?php <td style="font-weight:<?php 600;<?php color:<?php #22c55e;">R$<?php ${formatarMoeda(indicado.total_depositado)}</td>
<?php <td>
<?php <span style="background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php color:<?php #22c55e;<?php padding:<?php 0.25rem 0.5rem;<?php border-radius:<?php 6px;<?php font-size:<?php 0.8rem;<?php font-weight:<?php 600;">
<?php ${indicado.total_depositos}
<?php </span>
<?php </td>
<?php <td style="color:<?php #9ca3af;">${dataCadastro}</td>
<?php <td>
<?php <span class="status-badge ${statusClass}">${statusText}</span>
<?php </td>
<?php </tr>
<?php `;
<?php });
<?php html +=<?php `
<?php </tbody>
<?php </table>
<?php `;
<?php container.innerHTML =<?php html;
<?php }

<?php //<?php Função para preencher histórico CPA function preencherHistoricoCPA(historico)<?php {
<?php const container =<?php document.getElementById('historicoCPA');
<?php if (historico.length ===<?php 0)<?php {
<?php container.innerHTML =<?php `
<?php <div class="table-empty">
<?php <i class="fas fa-percentage"></i>
<?php <h4>Nenhuma comissão CPA encontrada</h4>
<?php <p>Ainda não há<?php histórico de comissões CPA para este afiliado</p>
<?php </div>
<?php `;
<?php return;
<?php }
<?php let html =<?php `
<?php <table class="details-table">
<?php <thead>
<?php <tr>
<?php <th><i class="fas fa-user"></i><?php Indicado</th>
<?php <th><i class="fas fa-envelope"></i><?php Email</th>
<?php <th><i class="fas fa-dollar-sign"></i><?php Valor Depósito</th>
<?php <th><i class="fas fa-percentage"></i><?php Comissão</th>
<?php <th><i class="fas fa-calendar"></i><?php Data</th>
<?php </tr>
<?php </thead>
<?php <tbody>
<?php `;
<?php historico.forEach(item =><?php {
<?php const dataFormatada =<?php formatarDataHora(item.created_at);
<?php html +=<?php `
<?php <tr>
<?php <td>
<?php <div style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 0.5rem;">
<?php <div style="width:<?php 28px;<?php height:<?php 28px;<?php background:<?php linear-gradient(135deg,<?php #9333ea,<?php #7c3aed);<?php border-radius:<?php 6px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php center;<?php color:<?php white;<?php font-weight:<?php 600;<?php font-size:<?php 0.75rem;">
<?php ${item.indicado_nome.charAt(0).toUpperCase()}
<?php </div>
<?php ${item.indicado_nome}
<?php </div>
<?php </td>
<?php <td style="color:<?php #9ca3af;">${item.indicado_email}</td>
<?php <td style="font-weight:<?php 600;<?php color:<?php #3b82f6;">R$<?php ${formatarMoeda(item.valor_deposito ||<?php 0)}</td>
<?php <td style="font-weight:<?php 700;<?php color:<?php #22c55e;">R$<?php ${formatarMoeda(item.valor_comissao)}</td>
<?php <td style="color:<?php #9ca3af;">${dataFormatada}</td>
<?php </tr>
<?php `;
<?php });
<?php html +=<?php `
<?php </tbody>
<?php </table>
<?php `;
<?php container.innerHTML =<?php html;
<?php }

<?php //<?php Função para preencher histórico RevShare function preencherHistoricoRevShare(historico)<?php {
<?php const container =<?php document.getElementById('historicoRevShare');
<?php if (historico.length ===<?php 0)<?php {
<?php container.innerHTML =<?php `
<?php <div class="table-empty">
<?php <i class="fas fa-chart-line"></i>
<?php <h4>Nenhum RevShare encontrado</h4>
<?php <p>Ainda não há<?php histórico de RevShare para este afiliado</p>
<?php </div>
<?php `;
<?php return;
<?php }
<?php let html =<?php `
<?php <table class="details-table">
<?php <thead>
<?php <tr>
<?php <th><i class="fas fa-user"></i><?php Indicado</th>
<?php <th><i class="fas fa-envelope"></i><?php Email</th>
<?php <th><i class="fas fa-gamepad"></i><?php Jogo</th>
<?php <th><i class="fas fa-money-bill-wave"></i><?php Valor Perdido</th>
<?php <th><i class="fas fa-percentage"></i><?php %<?php RevShare</th>
<?php <th><i class="fas fa-chart-line"></i><?php Valor RevShare</th>
<?php <th><i class="fas fa-calendar"></i><?php Data</th>
<?php </tr>
<?php </thead>
<?php <tbody>
<?php `;
<?php historico.forEach(item =><?php {
<?php const dataFormatada =<?php formatarDataHora(item.created_at);
<?php html +=<?php `
<?php <tr>
<?php <td>
<?php <div style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 0.5rem;">
<?php <div style="width:<?php 28px;<?php height:<?php 28px;<?php background:<?php linear-gradient(135deg,<?php #f97316,<?php #ea580c);<?php border-radius:<?php 6px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php center;<?php color:<?php white;<?php font-weight:<?php 600;<?php font-size:<?php 0.75rem;">
<?php ${item.indicado_nome.charAt(0).toUpperCase()}
<?php </div>
<?php ${item.indicado_nome}
<?php </div>
<?php </td>
<?php <td style="color:<?php #9ca3af;">${item.indicado_email}</td>
<?php <td>
<?php <span style="background:<?php rgba(59,<?php 130,<?php 246,<?php 0.1);<?php color:<?php #3b82f6;<?php padding:<?php 0.25rem 0.5rem;<?php border-radius:<?php 6px;<?php font-size:<?php 0.8rem;<?php font-weight:<?php 600;">
<?php ${item.jogo ||<?php 'N/A'}
<?php </span>
<?php </td>
<?php <td style="font-weight:<?php 600;<?php color:<?php #ef4444;">R$<?php ${formatarMoeda(item.valor_perdido ||<?php 0)}</td>
<?php <td style="color:<?php #f97316;<?php font-weight:<?php 600;">${formatarMoeda(item.percentual_revshare ||<?php 0)}%</td>
<?php <td style="font-weight:<?php 700;<?php color:<?php #22c55e;">R$<?php ${formatarMoeda(item.valor_revshare)}</td>
<?php <td style="color:<?php #9ca3af;">${dataFormatada}</td>
<?php </tr>
<?php `;
<?php });
<?php html +=<?php `
<?php </tbody>
<?php </table>
<?php `;
<?php container.innerHTML =<?php html;
<?php }

<?php //<?php Função para fechar modal de detalhes function fecharDetalhesAfiliado()<?php {
<?php const modal =<?php document.getElementById('detalhesAfiliadoModal');
<?php modal.classList.add('hidden');
<?php detalhesModalAberto =<?php false;
<?php //<?php Reset do modal para próxima abertura document.getElementById('detalhesLoading').classList.remove('hidden');
<?php document.getElementById('detalhesContent').classList.add('hidden');
<?php //<?php Reset das tabs abrirTab('indicados');
<?php }

<?php //<?php Função para alternar entre tabs function abrirTab(tabName)<?php {
<?php //<?php Esconder todos os conteúdos document.querySelectorAll('.tab-content').forEach(tab =><?php {
<?php tab.classList.remove('active');
<?php });
<?php //<?php Remover classe active de todos os botões document.querySelectorAll('.tab-btn').forEach(btn =><?php {
<?php btn.classList.remove('active');
<?php });
<?php //<?php Mostrar conteúdo da tab selecionada document.getElementById(`tab-${tabName}`).classList.add('active');
<?php //<?php Adicionar classe active ao botão correspondente event.target.classList.add('active');
<?php }

<?php //<?php Funções auxiliares para formatação function formatarMoeda(valor)<?php {
<?php const numero =<?php parseFloat(valor)<?php ||<?php 0;
<?php return numero.toLocaleString('pt-BR',<?php {
<?php minimumFractionDigits:<?php 2,
<?php maximumFractionDigits:<?php 2 });
<?php }

<?php function formatarTelefone(telefone)<?php {
<?php if (!telefone)<?php return 'N/A';
<?php const apenasNumeros =<?php telefone.replace(/[^0-9]/g,<?php '');
<?php if (apenasNumeros.length ===<?php 11)<?php {
<?php return `(${apenasNumeros.substring(0,<?php 2)})<?php ${apenasNumeros.substring(2,<?php 7)}-${apenasNumeros.substring(7)}`;
<?php }<?php else if (apenasNumeros.length ===<?php 10)<?php {
<?php return `(${apenasNumeros.substring(0,<?php 2)})<?php ${apenasNumeros.substring(2,<?php 6)}-${apenasNumeros.substring(6)}`;
<?php }
<?php return telefone;
<?php }

<?php function formatarData(dataString)<?php {
<?php if (!dataString)<?php return 'N/A';
<?php const data =<?php new Date(dataString);
<?php return data.toLocaleDateString('pt-BR');
<?php }

<?php function formatarDataHora(dataString)<?php {
<?php if (!dataString)<?php return 'N/A';
<?php const data =<?php new Date(dataString);
<?php return data.toLocaleString('pt-BR');
<?php }

<?php //<?php Initialize document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {
<?php console.log('%c🤝<?php Afiliados carregados!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php //<?php Check if mobile on load if (window.innerWidth <=<?php 1024)<?php {
<?php sidebar.classList.add('hidden');
<?php }
<?php //<?php Animate cards on load const affiliateCards =<?php document.querySelectorAll('.affiliate-card');
<?php affiliateCards.forEach((card,<?php index)<?php =><?php {
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

<?php //<?php Event listeners para fechar modal de detalhes const detalhesModal =<?php document.getElementById('detalhesAfiliadoModal');
<?php if (detalhesModal)<?php {
<?php detalhesModal.addEventListener('click',<?php function(e)<?php {
<?php if (e.target ===<?php this)<?php {
<?php fecharDetalhesAfiliado();
<?php }
<?php });
<?php }
<?php });
<?php //<?php Smooth scroll behavior document.documentElement.style.scrollBehavior =<?php 'smooth';
<?php </script>

</body>
</html>