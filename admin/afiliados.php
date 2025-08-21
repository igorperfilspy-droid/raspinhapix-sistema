<?php
ob_start();
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

$nome<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php nome<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;
$nome<?php =<?php $nome<?php ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;

if<?php (isset($_GET['toggle_banido']))<?php {
<?php $id<?php =<?php $_GET['id'];
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php banido<?php =<?php IF(banido=1,<?php 0,<?php 1)<?php WHERE<?php id<?php =<?php ?");
<?php if<?php ($stmt->execute([$id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Status<?php de<?php banido<?php alterado<?php com<?php sucesso!';
<?php }<?php else<?php {
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php alterar<?php status!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

if<?php (isset($_GET['toggle_influencer']))<?php {
<?php $id<?php =<?php $_GET['id'];
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php influencer<?php =<?php IF(influencer=1,<?php 0,<?php 1)<?php WHERE<?php id<?php =<?php ?");
<?php if<?php ($stmt->execute([$id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Status<?php de<?php influencer<?php alterado<?php com<?php sucesso!';
<?php }<?php else<?php {
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php alterar<?php status!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

if<?php (isset($_POST['atualizar_comissao_cpa']))<?php {
<?php $id<?php =<?php $_POST['id'];
<?php $comissao_cpa<?php =<?php str_replace(',',<?php '.',<?php $_POST['comissao_cpa']);
<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php comissao_cpa<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");
<?php if<?php ($stmt->execute([$comissao_cpa,<?php $id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Comissão<?php CPA<?php atualizada<?php com<?php sucesso!';
<?php }<?php else<?php {
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php comissão<?php CPA!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

//<?php Nova<?php função<?php para<?php atualizar<?php RevShare
if<?php (isset($_POST['atualizar_comissao_revshare']))<?php {
<?php $id<?php =<?php $_POST['id'];
<?php $comissao_revshare<?php =<?php str_replace(',',<?php '.',<?php $_POST['comissao_revshare']);
<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php comissao_revshare<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");
<?php if<?php ($stmt->execute([$comissao_revshare,<?php $id]))<?php {
<?php $_SESSION['success']<?php =<?php 'Comissão<?php RevShare<?php atualizada<?php com<?php sucesso!';
<?php }<?php else<?php {
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php comissão<?php RevShare!';
<?php }
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);
<?php exit;
}

//<?php Nova<?php função<?php para<?php buscar<?php detalhes<?php do<?php afiliado<?php via<?php AJAX
if<?php (isset($_GET['ajax'])<?php &&<?php $_GET['ajax']<?php ==<?php 'detalhes_afiliado')<?php {
<?php //<?php Limpar<?php qualquer<?php saída<?php anterior
<?php ob_clean();
<?php 
<?php $afiliado_id<?php =<?php $_GET['afiliado_id'];
<?php 
<?php try<?php {
<?php //<?php Buscar<?php dados<?php do<?php afiliado
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$afiliado_id]);
<?php $afiliado<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php 
<?php if<?php (!$afiliado)<?php {
<?php header('Content-Type:<?php application/json');
<?php echo<?php json_encode(['error'<?php =><?php 'Afiliado<?php não<?php encontrado']);
<?php exit;
<?php }
<?php 
<?php //<?php Buscar<?php indicados
<?php $stmt<?php =<?php $pdo->prepare("
<?php SELECT<?php u.*,<?php 
<?php COALESCE(SUM(CASE<?php WHEN<?php d.status<?php =<?php 'PAID'<?php THEN<?php d.valor<?php ELSE<?php 0<?php END),<?php 0)<?php as<?php total_depositado,
<?php COUNT(CASE<?php WHEN<?php d.status<?php =<?php 'PAID'<?php THEN<?php d.id<?php END)<?php as<?php total_depositos,
<?php u.created_at<?php as<?php data_cadastro
<?php FROM<?php usuarios<?php u<?php 
<?php LEFT<?php JOIN<?php depositos<?php d<?php ON<?php u.id<?php =<?php d.user_id
<?php WHERE<?php u.indicacao<?php =<?php ?<?php 
<?php GROUP<?php BY<?php u.id
<?php ORDER<?php BY<?php u.created_at<?php DESC
<?php ");
<?php $stmt->execute([$afiliado_id]);
<?php $indicados<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
<?php 
<?php //<?php Buscar<?php histórico<?php de<?php comissões<?php CPA<?php (verificar<?php se<?php tabela<?php existe)
<?php $historico_cpa<?php =<?php [];
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("
<?php SELECT<?php hc.*,<?php u.nome<?php as<?php indicado_nome,<?php u.email<?php as<?php indicado_email
<?php FROM<?php historico_comissoes<?php hc
<?php JOIN<?php usuarios<?php u<?php ON<?php hc.indicado_id<?php =<?php u.id
<?php WHERE<?php hc.afiliado_id<?php =<?php ?
<?php ORDER<?php BY<?php hc.created_at<?php DESC
<?php LIMIT<?php 20
<?php ");
<?php $stmt->execute([$afiliado_id]);
<?php $historico_cpa<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php //<?php Tabela<?php historico_comissoes<?php pode<?php não<?php existir
<?php $historico_cpa<?php =<?php [];
<?php }
<?php 
<?php //<?php Buscar<?php histórico<?php RevShare<?php (verificar<?php se<?php tabela<?php existe)
<?php $historico_revshare<?php =<?php [];
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("
<?php SELECT<?php hr.*,<?php 
<?php u.nome<?php as<?php indicado_nome,<?php 
<?php u.email<?php as<?php indicado_email,
<?php hr.valor_apostado<?php as<?php valor_perdido,
<?php hr.percentual<?php as<?php percentual_revshare,
<?php 'N/A'<?php as<?php jogo
<?php FROM<?php historico_revshare<?php hr
<?php JOIN<?php usuarios<?php u<?php ON<?php hr.usuario_id<?php =<?php u.id
<?php WHERE<?php hr.afiliado_id<?php =<?php ?
<?php ORDER<?php BY<?php hr.created_at<?php DESC
<?php LIMIT<?php 20
<?php ");
<?php $stmt->execute([$afiliado_id]);
<?php $historico_revshare<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php //<?php Tabela<?php historico_revshare<?php pode<?php não<?php existir
<?php $historico_revshare<?php =<?php [];
<?php }
<?php 
<?php //<?php Calcular<?php estatísticas
<?php $total_comissao_cpa<?php =<?php 0;
<?php $total_comissao_revshare<?php =<?php 0;
<?php 
<?php foreach<?php ($historico_cpa<?php as<?php $cpa)<?php {
<?php $total_comissao_cpa<?php +=<?php floatval($cpa['valor_comissao']<?php ??<?php 0);
<?php }
<?php 
<?php foreach<?php ($historico_revshare<?php as<?php $rev)<?php {
<?php $total_comissao_revshare<?php +=<?php floatval($rev['valor_revshare']<?php ??<?php 0);
<?php }
<?php 
<?php //<?php Buscar<?php saldo<?php atual<?php do<?php afiliado
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$afiliado_id]);
<?php $saldo_atual<?php =<?php $stmt->fetchColumn()<?php ??<?php 0;
<?php 
<?php $response<?php =<?php [
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
<?php 
<?php header('Content-Type:<?php application/json');
<?php echo<?php json_encode($response,<?php JSON_UNESCAPED_UNICODE);
<?php exit;
<?php 
<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php header('Content-Type:<?php application/json');
<?php echo<?php json_encode(['error'<?php =><?php 'Erro<?php ao<?php buscar<?php dados:<?php '<?php .<?php $e->getMessage()],<?php JSON_UNESCAPED_UNICODE);
<?php exit;
<?php }
}

$search<?php =<?php isset($_GET['search'])<?php ?<?php $_GET['search']<?php :<?php '';

//<?php Query<?php atualizada<?php para<?php incluir<?php dados<?php de<?php RevShare
$query<?php =<?php "SELECT<?php u.*,<?php 
<?php (SELECT<?php COUNT(*)<?php FROM<?php usuarios<?php WHERE<?php indicacao<?php =<?php u.id)<?php as<?php total_indicados,
<?php (SELECT<?php COALESCE(SUM(d.valor),<?php 0)<?php FROM<?php depositos<?php d<?php 
<?php JOIN<?php usuarios<?php u2<?php ON<?php d.user_id<?php =<?php u2.id<?php 
<?php WHERE<?php u2.indicacao<?php =<?php u.id<?php AND<?php d.status<?php =<?php 'PAID')<?php as<?php total_depositos,
<?php (SELECT<?php COALESCE(SUM(valor_revshare),<?php 0)<?php FROM<?php historico_revshare<?php 
<?php WHERE<?php afiliado_id<?php =<?php u.id)<?php as<?php total_revshare
<?php FROM<?php usuarios<?php u
<?php WHERE<?php EXISTS<?php (SELECT<?php 1<?php FROM<?php usuarios<?php WHERE<?php indicacao<?php =<?php u.id)";

if<?php (!empty($search))<?php {
<?php $query<?php .=<?php "<?php AND<?php (u.nome<?php LIKE<?php :search<?php OR<?php u.email<?php LIKE<?php :search<?php OR<?php u.telefone<?php LIKE<?php :search)";
}

$query<?php .=<?php "<?php ORDER<?php BY<?php total_depositos<?php DESC,<?php total_indicados<?php DESC";

$stmt<?php =<?php $pdo->prepare($query);

if<?php (!empty($search))<?php {
<?php $searchTerm<?php =<?php "%$search%";
<?php $stmt->bindParam(':search',<?php $searchTerm,<?php PDO::PARAM_STR);
}

$stmt->execute();
$afiliados<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);

//<?php Calculate<?php statistics
$total_afiliados<?php =<?php count($afiliados);
$total_indicados<?php =<?php array_sum(array_column($afiliados,<?php 'total_indicados'));
$total_depositos_afiliados<?php =<?php array_sum(array_column($afiliados,<?php 'total_depositos'));
$total_revshare_pago<?php =<?php array_sum(array_column($afiliados,<?php 'total_revshare'));
$influencers_count<?php =<?php count(array_filter($afiliados,<?php function($a)<?php {<?php return<?php $a['influencer']<?php ==<?php 1;<?php }));
?>

<!DOCTYPE<?php html>
<html<?php lang="pt-BR">
<head>
<?php <meta<?php charset="UTF-8">
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Gerenciar<?php Afiliados</title>
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
<?php /*<?php Advanced<?php Sidebar<?php Styles<?php -<?php Same<?php as<?php depositos.php<?php */
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
<?php /*<?php Enhanced<?php Sidebar<?php Header<?php */
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
<?php /*<?php Advanced<?php Navigation<?php */
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
<?php /*<?php Enhanced<?php Header<?php */
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
<?php .header-actions<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
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
<?php /*<?php Main<?php Page<?php Content<?php */
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
<?php /*<?php Stats<?php Cards<?php */
<?php .stats-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
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
<?php .mini-stat-icon.purple<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(147,<?php 51,<?php 234,<?php 0.2)<?php 0%,<?php rgba(147,<?php 51,<?php 234,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(147,<?php 51,<?php 234,<?php 0.3);
<?php color:<?php #9333ea;
<?php }
<?php 
<?php .mini-stat-icon.blue<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(59,<?php 130,<?php 246,<?php 0.2)<?php 0%,<?php rgba(59,<?php 130,<?php 246,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(59,<?php 130,<?php 246,<?php 0.3);
<?php color:<?php #3b82f6;
<?php }
<?php 
<?php .mini-stat-icon.orange<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(249,<?php 115,<?php 22,<?php 0.2)<?php 0%,<?php rgba(249,<?php 115,<?php 22,<?php 0.1)<?php 100%);
<?php border-color:<?php rgba(249,<?php 115,<?php 22,<?php 0.3);
<?php color:<?php #f97316;
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
<?php /*<?php Search<?php Section<?php */
<?php .search-section<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php margin-bottom:<?php 2rem;
<?php backdrop-filter:<?php blur(20px);
<?php }
<?php 
<?php .search-header<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .search-icon-container<?php {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-radius:<?php 12px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php #22c55e;
<?php font-size:<?php 1.125rem;
<?php }
<?php 
<?php .search-title<?php {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 600;
<?php color:<?php #ffffff;
<?php }
<?php 
<?php .search-container<?php {
<?php position:<?php relative;
<?php }
<?php 
<?php .search-input<?php {
<?php width:<?php 100%;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php padding:<?php 0.875rem<?php 1rem<?php 0.875rem<?php 3rem;
<?php color:<?php white;
<?php font-size:<?php 1rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }
<?php 
<?php .search-input:focus<?php {
<?php outline:<?php none;
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }
<?php 
<?php .search-input::placeholder<?php {
<?php color:<?php #6b7280;
<?php }
<?php 
<?php .search-icon<?php {
<?php position:<?php absolute;
<?php left:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #9ca3af;
<?php font-size:<?php 1rem;
<?php }
<?php 
<?php /*<?php Affiliate<?php Cards<?php */
<?php .affiliates-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(500px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php }
<?php 
<?php .affiliate-card<?php {
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
<?php .affiliate-card::before<?php {
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
<?php .affiliate-card:hover::before<?php {
<?php opacity:<?php 1;
<?php }
<?php 
<?php .affiliate-card:hover<?php {
<?php transform:<?php translateY(-4px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php 
<?php .affiliate-header<?php {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php flex-start;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .affiliate-name<?php {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 700;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.75rem;
<?php }
<?php 
<?php .affiliate-badges<?php {
<?php display:<?php flex;
<?php gap:<?php 0.5rem;
<?php flex-wrap:<?php wrap;
<?php }
<?php 
<?php .badge<?php {
<?php padding:<?php 0.3rem<?php 0.75rem;
<?php border-radius:<?php 20px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.5px;
<?php }
<?php 
<?php .badge.admin<?php {
<?php background:<?php linear-gradient(135deg,<?php #8b5cf6,<?php #7c3aed);
<?php color:<?php white;
<?php }
<?php 
<?php .badge.influencer<?php {
<?php background:<?php linear-gradient(135deg,<?php #ec4899,<?php #db2777);
<?php color:<?php white;
<?php }
<?php 
<?php .badge.banned<?php {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php }
<?php 
<?php .badge.affiliate<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php 
<?php /*<?php Contact<?php Info<?php */
<?php .contact-info<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .contact-item<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php color:<?php #e5e7eb;
<?php font-size:<?php 0.9rem;
<?php }
<?php 
<?php .contact-item<?php i<?php {
<?php color:<?php #22c55e;
<?php width:<?php 16px;
<?php text-align:<?php center;
<?php }
<?php 
<?php .whatsapp-link<?php {
<?php color:<?php #25d366;
<?php margin-left:<?php 0.5rem;
<?php transition:<?php color<?php 0.3s<?php ease;
<?php font-size:<?php 1rem;
<?php }
<?php 
<?php .whatsapp-link:hover<?php {
<?php color:<?php #128c7e;
<?php transform:<?php scale(1.1);
<?php }
<?php 
<?php /*<?php Stats<?php Section<?php in<?php Cards<?php */
<?php .affiliate-stats<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(140px,<?php 1fr));
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .stat-card<?php {
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php padding:<?php 1rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }
<?php 
<?php .stat-card:hover<?php {
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);
<?php }
<?php 
<?php .stat-label<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php color:<?php #a1a1aa;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 500;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php 
<?php .stat-label<?php i<?php {
<?php color:<?php #22c55e;
<?php font-size:<?php 0.8rem;
<?php }
<?php 
<?php .stat-value<?php {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 800;
<?php color:<?php #22c55e;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php }
<?php 
<?php .edit-commission<?php {
<?php background:<?php none;
<?php border:<?php none;
<?php color:<?php #60a5fa;
<?php cursor:<?php pointer;
<?php padding:<?php 0.25rem;
<?php border-radius:<?php 4px;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php font-size:<?php 0.8rem;
<?php }
<?php 
<?php .edit-commission:hover<?php {
<?php color:<?php #3b82f6;
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.1);
<?php transform:<?php scale(1.1);
<?php }
<?php 
<?php /*<?php Action<?php Buttons<?php */
<?php .action-buttons<?php {
<?php display:<?php flex;
<?php gap:<?php 0.75rem;
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .action-btn<?php {
<?php flex:<?php 1;
<?php padding:<?php 0.75rem<?php 1rem;
<?php border-radius:<?php 12px;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 600;
<?php text-decoration:<?php none;
<?php text-align:<?php center;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php cursor:<?php pointer;
<?php border:<?php none;
<?php }
<?php 
<?php .action-btn:hover<?php {
<?php transform:<?php translateY(-1px);
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }
<?php 
<?php .btn-ban<?php {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php color:<?php white;
<?php }
<?php 
<?php .btn-unban<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php 
<?php .btn-influencer<?php {
<?php background:<?php linear-gradient(135deg,<?php #ec4899,<?php #db2777);
<?php color:<?php white;
<?php }

<?php /*<?php Botão<?php de<?php Detalhes<?php */
<?php .btn-details<?php {
<?php background:<?php linear-gradient(135deg,<?php #3b82f6,<?php #2563eb);
<?php color:<?php white;
<?php }

<?php .btn-details:hover<?php {
<?php background:<?php linear-gradient(135deg,<?php #2563eb,<?php #1d4ed8);
<?php }

<?php /*<?php Modal<?php de<?php Detalhes<?php */
<?php .modal-details<?php {
<?php position:<?php fixed;
<?php inset:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.8);
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php z-index:<?php 1100;
<?php backdrop-filter:<?php blur(8px);
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }

<?php .modal-details.hidden<?php {
<?php display:<?php none;
<?php opacity:<?php 0;
<?php }

<?php .modal-details-content<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(10,<?php 10,<?php 10,<?php 0.98)<?php 0%,<?php rgba(20,<?php 20,<?php 20,<?php 0.95)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 24px;
<?php width:<?php 95%;
<?php max-width:<?php 1200px;
<?php max-height:<?php 90vh;
<?php overflow:<?php hidden;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0<?php 25px<?php 80px<?php rgba(0,<?php 0,<?php 0,<?php 0.6);
<?php position:<?php relative;
<?php }

<?php .modal-details-header<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php padding:<?php 2rem;
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 100%);
<?php }

<?php .modal-details-title<?php {
<?php font-size:<?php 1.75rem;
<?php font-weight:<?php 700;
<?php color:<?php white;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php }

<?php .modal-details-title<?php i<?php {
<?php color:<?php #22c55e;
<?php font-size:<?php 1.5rem;
<?php }

<?php .close-btn<?php {
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.1);
<?php border:<?php 1px<?php solid<?php rgba(239,<?php 68,<?php 68,<?php 0.3);
<?php color:<?php #ef4444;
<?php padding:<?php 0.75rem;
<?php border-radius:<?php 12px;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php font-size:<?php 1.1rem;
<?php }

<?php .close-btn:hover<?php {
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.2);
<?php transform:<?php scale(1.05);
<?php }

<?php .modal-details-body<?php {
<?php padding:<?php 2rem;
<?php max-height:<?php calc(90vh<?php -<?php 120px);
<?php overflow-y:<?php auto;
<?php }

<?php /*<?php Loading<?php */
<?php .loading-container<?php {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php padding:<?php 4rem;
<?php color:<?php #9ca3af;
<?php }

<?php .loading-spinner<?php {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php border:<?php 4px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-top:<?php 4px<?php solid<?php #22c55e;
<?php border-radius:<?php 50%;
<?php animation:<?php spin<?php 1s<?php linear<?php infinite;
<?php margin-bottom:<?php 1rem;
<?php }

<?php @keyframes<?php spin<?php {
<?php 0%<?php {<?php transform:<?php rotate(0deg);<?php }
<?php 100%<?php {<?php transform:<?php rotate(360deg);<?php }
<?php }

<?php /*<?php Stats<?php Grid<?php no<?php Modal<?php */
<?php .details-stats-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));
<?php gap:<?php 1rem;
<?php margin-bottom:<?php 2rem;
<?php }

<?php .details-stat-card<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.6)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.8)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php padding:<?php 1.5rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }

<?php .details-stat-card:hover<?php {
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php transform:<?php translateY(-2px);
<?php }

<?php .details-stat-icon<?php {
<?php width:<?php 48px;
<?php height:<?php 48px;
<?php border-radius:<?php 12px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.25rem;
<?php }

<?php .details-stat-icon.green<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php color:<?php #22c55e;
<?php }

<?php .details-stat-icon.blue<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(59,<?php 130,<?php 246,<?php 0.2)<?php 0%,<?php rgba(59,<?php 130,<?php 246,<?php 0.1)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(59,<?php 130,<?php 246,<?php 0.3);
<?php color:<?php #3b82f6;
<?php }

<?php .details-stat-icon.purple<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(147,<?php 51,<?php 234,<?php 0.2)<?php 0%,<?php rgba(147,<?php 51,<?php 234,<?php 0.1)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(147,<?php 51,<?php 234,<?php 0.3);
<?php color:<?php #9333ea;
<?php }

<?php .details-stat-icon.orange<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(249,<?php 115,<?php 22,<?php 0.2)<?php 0%,<?php rgba(249,<?php 115,<?php 22,<?php 0.1)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(249,<?php 115,<?php 22,<?php 0.3);
<?php color:<?php #f97316;
<?php }

<?php .details-stat-info<?php {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php }

<?php .details-stat-value<?php {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php margin-bottom:<?php 0.25rem;
<?php }

<?php .details-stat-label<?php {
<?php font-size:<?php 0.8rem;
<?php color:<?php #9ca3af;
<?php font-weight:<?php 500;
<?php }

<?php /*<?php Tabs<?php */
<?php .details-tabs<?php {
<?php display:<?php flex;
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php margin-bottom:<?php 2rem;
<?php gap:<?php 0.5rem;
<?php }

<?php .tab-btn<?php {
<?php background:<?php transparent;
<?php border:<?php none;
<?php padding:<?php 1rem<?php 1.5rem;
<?php color:<?php #9ca3af;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php cursor:<?php pointer;
<?php border-radius:<?php 12px<?php 12px<?php 0<?php 0;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php position:<?php relative;
<?php }

<?php .tab-btn:hover<?php {
<?php color:<?php #22c55e;
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);
<?php }

<?php .tab-btn.active<?php {
<?php color:<?php #22c55e;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-bottom:<?php none;
<?php }

<?php .tab-btn.active::after<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php bottom:<?php -1px;
<?php left:<?php 0;
<?php right:<?php 0;
<?php height:<?php 2px;
<?php background:<?php linear-gradient(90deg,<?php #22c55e,<?php #16a34a);
<?php }

<?php /*<?php Tab<?php Content<?php */
<?php .tab-content<?php {
<?php display:<?php none;
<?php }

<?php .tab-content.active<?php {
<?php display:<?php block;
<?php }

<?php .tab-header<?php {
<?php margin-bottom:<?php 1.5rem;
<?php }

<?php .tab-header<?php h3<?php {
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 600;
<?php color:<?php #ffffff;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php }

<?php .tab-header<?php h3<?php i<?php {
<?php color:<?php #22c55e;
<?php }

<?php /*<?php Table<?php Container<?php */
<?php .table-container<?php {
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.2);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php overflow:<?php hidden;
<?php }

<?php .details-table<?php {
<?php width:<?php 100%;
<?php border-collapse:<?php collapse;
<?php }

<?php .details-table<?php th<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);
<?php color:<?php #22c55e;
<?php padding:<?php 1rem;
<?php text-align:<?php left;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }

<?php .details-table<?php td<?php {
<?php padding:<?php 1rem;
<?php color:<?php #e5e7eb;
<?php font-size:<?php 0.9rem;
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php }

<?php .details-table<?php tr:hover<?php {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.02);
<?php }

<?php .details-table<?php tr:last-child<?php td<?php {
<?php border-bottom:<?php none;
<?php }

<?php /*<?php Status<?php badges<?php na<?php tabela<?php */
<?php .status-badge<?php {
<?php padding:<?php 0.25rem<?php 0.5rem;
<?php border-radius:<?php 6px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php }

<?php .status-badge.ativo<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(34,<?php 197,<?php 94,<?php 0.1));
<?php color:<?php #22c55e;
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .status-badge.banido<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(239,<?php 68,<?php 68,<?php 0.2),<?php rgba(239,<?php 68,<?php 68,<?php 0.1));
<?php color:<?php #ef4444;
<?php border:<?php 1px<?php solid<?php rgba(239,<?php 68,<?php 68,<?php 0.3);
<?php }

<?php /*<?php WhatsApp<?php link<?php na<?php tabela<?php */
<?php .whatsapp-table-link<?php {
<?php color:<?php #25d366;
<?php margin-left:<?php 0.5rem;
<?php font-size:<?php 1rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }

<?php .whatsapp-table-link:hover<?php {
<?php color:<?php #128c7e;
<?php transform:<?php scale(1.1);
<?php }

<?php /*<?php Empty<?php state<?php nas<?php tabelas<?php */
<?php .table-empty<?php {
<?php padding:<?php 3rem;
<?php text-align:<?php center;
<?php color:<?php #6b7280;
<?php }

<?php .table-empty<?php i<?php {
<?php font-size:<?php 2rem;
<?php margin-bottom:<?php 1rem;
<?php opacity:<?php 0.5;
<?php color:<?php #374151;
<?php }
<?php 
<?php .btn-remove-inf<?php {
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);
<?php color:<?php white;
<?php }
<?php 
<?php /*<?php Affiliate<?php Meta<?php */
<?php .affiliate-meta<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.875rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php padding-top:<?php 1rem;
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php 
<?php .affiliate-meta<?php i<?php {
<?php color:<?php #6b7280;
<?php }
<?php 
<?php /*<?php Modal<?php Styles<?php */
<?php .modal<?php {
<?php position:<?php fixed;
<?php inset:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php z-index:<?php 1000;
<?php backdrop-filter:<?php blur(4px);
<?php }
<?php 
<?php .modal.hidden<?php {
<?php display:<?php none;
<?php }
<?php 
<?php .modal-content<?php {
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.95)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.98)<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php width:<?php 90%;
<?php max-width:<?php 500px;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);
<?php }
<?php 
<?php .modal-title<?php {
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 700;
<?php color:<?php white;
<?php margin-bottom:<?php 1.5rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.75rem;
<?php }
<?php 
<?php .modal-title<?php i<?php {
<?php color:<?php #22c55e;
<?php }
<?php 
<?php .modal-form-group<?php {
<?php margin-bottom:<?php 1.5rem;
<?php }
<?php 
<?php .modal-label<?php {
<?php display:<?php block;
<?php color:<?php #e5e7eb;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 600;
<?php margin-bottom:<?php 0.5rem;
<?php }
<?php 
<?php .modal-input-container<?php {
<?php position:<?php relative;
<?php }
<?php 
<?php .modal-currency<?php {
<?php position:<?php absolute;
<?php left:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #9ca3af;
<?php font-weight:<?php 600;
<?php }
<?php 
<?php .modal-percentage<?php {
<?php position:<?php absolute;
<?php right:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #9ca3af;
<?php font-weight:<?php 600;
<?php }
<?php 
<?php .modal-input<?php {
<?php width:<?php 100%;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php padding:<?php 0.75rem<?php 1rem<?php 0.75rem<?php 2.5rem;
<?php color:<?php white;
<?php font-size:<?php 1rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }
<?php 
<?php .modal-input.percentage<?php {
<?php padding:<?php 0.75rem<?php 2.5rem<?php 0.75rem<?php 1rem;
<?php }
<?php 
<?php .modal-input:focus<?php {
<?php outline:<?php none;
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }
<?php 
<?php .modal-input::placeholder<?php {
<?php color:<?php #6b7280;
<?php }
<?php 
<?php .modal-buttons<?php {
<?php display:<?php flex;
<?php gap:<?php 1rem;
<?php }
<?php 
<?php .modal-btn<?php {
<?php flex:<?php 1;
<?php padding:<?php 0.875rem<?php 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php border:<?php none;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php }
<?php 
<?php .modal-btn-primary<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php }
<?php 
<?php .modal-btn-primary:hover<?php {
<?php transform:<?php translateY(-1px);
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }
<?php 
<?php .modal-btn-secondary<?php {
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);
<?php color:<?php #e5e7eb;
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }
<?php 
<?php .modal-btn-secondary:hover<?php {
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.4);
<?php }
<?php 
<?php /*<?php Empty<?php State<?php */
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
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));
<?php }
<?php 
<?php .affiliates-grid<?php {
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
<?php .affiliate-card<?php {
<?php padding:<?php 1.5rem;
<?php }
<?php 
<?php .contact-info<?php {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php 
<?php .affiliate-stats<?php {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php 
<?php .action-buttons<?php {
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
<?php .sidebar<?php {
<?php width:<?php 260px;
<?php }
<?php 
<?php .modal-content<?php {
<?php margin:<?php 1rem;
<?php padding:<?php 1.5rem;
<?php }
<?php 
<?php .modal-buttons<?php {
<?php flex-direction:<?php column;
<?php }
<?php }
<?php 
<?php /*<?php Overlay<?php for<?php mobile<?php */
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
<?php <!--<?php Advanced<?php Sidebar<?php -->
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
<?php <a<?php href="afiliados.php"<?php class="nav-item<?php active">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user-plus"></i></div>
<?php <div<?php class="nav-text">Afiliados</div>
<?php </a>
<?php <a<?php href="depositos.php"<?php class="nav-item">
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-credit-card"></i></div>
<?php <div<?php class="nav-text">Depósitos</div>
<?php </a>
<?php <a<?php href="saques.php"<?php class="nav-item">
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
<?php <!--<?php Enhanced<?php Header<?php -->
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
<?php <h2<?php class="welcome-title">Gerenciar<?php Afiliados</h2>
<?php <p<?php class="welcome-subtitle">Visualize<?php e<?php gerencie<?php todos<?php os<?php afiliados<?php e<?php suas<?php comissões<?php na<?php plataforma</p>
<?php </section>
<?php 
<?php <!--<?php Stats<?php Grid<?php -->
<?php <section<?php class="stats-grid">
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon">
<?php <i<?php class="fas<?php fa-handshake"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_afiliados,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Total<?php de<?php Afiliados</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon<?php purple">
<?php <i<?php class="fas<?php fa-users"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_indicados,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Total<?php de<?php Indicados</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon">
<?php <i<?php class="fas<?php fa-dollar-sign"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($total_depositos_afiliados,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Depósitos<?php dos<?php Indicados</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon<?php orange">
<?php <i<?php class="fas<?php fa-chart-line"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($total_revshare_pago,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Total<?php RevShare<?php Pago</div>
<?php </div>
<?php 
<?php <div<?php class="mini-stat-card">
<?php <div<?php class="mini-stat-header">
<?php <div<?php class="mini-stat-icon<?php blue">
<?php <i<?php class="fas<?php fa-star"></i>
<?php </div>
<?php </div>
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($influencers_count,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php <div<?php class="mini-stat-label">Influencers<?php Ativos</div>
<?php </div>
<?php </section>
<?php 
<?php <!--<?php Search<?php Section<?php -->
<?php <section<?php class="search-section">
<?php <div<?php class="search-header">
<?php <div<?php class="search-icon-container">
<?php <i<?php class="fas<?php fa-search"></i>
<?php </div>
<?php <h3<?php class="search-title">Pesquisar<?php Afiliados</h3>
<?php </div>
<?php 
<?php <form<?php method="GET">
<?php <div<?php class="search-container">
<?php <i<?php class="fas<?php fa-search<?php search-icon"></i>
<?php <input<?php type="text"<?php name="search"<?php value="<?php=<?php htmlspecialchars($search)<?php ?>"<?php 
<?php class="search-input"<?php 
<?php placeholder="Pesquisar<?php por<?php nome,<?php email<?php ou<?php telefone..."<?php 
<?php onchange="this.form.submit()">
<?php </div>
<?php </form>
<?php </section>
<?php 
<?php <!--<?php Affiliates<?php Section<?php -->
<?php <section>
<?php <?php<?php if<?php (empty($afiliados)):<?php ?>
<?php <div<?php class="empty-state">
<?php <i<?php class="fas<?php fa-handshake"></i>
<?php <h3>Nenhum<?php afiliado<?php encontrado</h3>
<?php <p>Não<?php há<?php afiliados<?php que<?php correspondam<?php aos<?php critérios<?php de<?php pesquisa</p>
<?php </div>
<?php <?php<?php else:<?php ?>
<?php <div<?php class="affiliates-grid">
<?php <?php<?php foreach<?php ($afiliados<?php as<?php $afiliado):<?php ?>
<?php <?php<?php 
<?php $telefone<?php =<?php $afiliado['telefone'];
<?php if<?php (strlen($telefone)<?php ==<?php 11)<?php {
<?php $telefoneFormatado<?php =<?php '('.substr($telefone,<?php 0,<?php 2).')<?php '.substr($telefone,<?php 2,<?php 5).'-'.substr($telefone,<?php 7);
<?php }<?php else<?php {
<?php $telefoneFormatado<?php =<?php $telefone;
<?php }
<?php 
<?php $whatsappLink<?php =<?php 'https://wa.me/55'.preg_replace('/[^0-9]/',<?php '',<?php $afiliado['telefone']);
<?php $comissao_cpa<?php =<?php isset($afiliado['comissao_cpa'])<?php ?<?php number_format($afiliado['comissao_cpa'],<?php 2,<?php ',',<?php '.')<?php :<?php '0,00';
<?php $comissao_revshare<?php =<?php isset($afiliado['comissao_revshare'])<?php ?<?php number_format($afiliado['comissao_revshare'],<?php 2,<?php ',',<?php '.')<?php :<?php '0,00';
<?php ?>
<?php 
<?php <div<?php class="affiliate-card">
<?php <div<?php class="affiliate-header">
<?php <div>
<?php <h3<?php class="affiliate-name"><?php=<?php htmlspecialchars($afiliado['nome'])<?php ?></h3>
<?php <div<?php class="affiliate-badges">
<?php <span<?php class="badge<?php affiliate">Afiliado</span>
<?php <?php<?php if<?php ($afiliado['admin']<?php ==<?php 1):<?php ?>
<?php <span<?php class="badge<?php admin">Admin</span>
<?php <?php<?php endif;<?php ?>
<?php <?php<?php if<?php ($afiliado['influencer']<?php ==<?php 1):<?php ?>
<?php <span<?php class="badge<?php influencer">Influencer</span>
<?php <?php<?php endif;<?php ?>
<?php <?php<?php if<?php ($afiliado['banido']<?php ==<?php 1):<?php ?>
<?php <span<?php class="badge<?php banned">Banido</span>
<?php <?php<?php endif;<?php ?>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="contact-info">
<?php <div<?php class="contact-item">
<?php <i<?php class="fas<?php fa-envelope"></i>
<?php <span><?php=<?php htmlspecialchars($afiliado['email'])<?php ?></span>
<?php </div>
<?php <div<?php class="contact-item">
<?php <i<?php class="fas<?php fa-phone"></i>
<?php <span><?php=<?php $telefoneFormatado<?php ?></span>
<?php <a<?php href="<?php=<?php $whatsappLink<?php ?>"<?php target="_blank"<?php class="whatsapp-link">
<?php <i<?php class="fab<?php fa-whatsapp"></i>
<?php </a>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="affiliate-stats">
<?php <div<?php class="stat-card">
<?php <div<?php class="stat-label">
<?php <i<?php class="fas<?php fa-users"></i>
<?php Indicados
<?php </div>
<?php <div<?php class="stat-value"><?php=<?php $afiliado['total_indicados']<?php ?></div>
<?php </div>
<?php <div<?php class="stat-card">
<?php <div<?php class="stat-label">
<?php <i<?php class="fas<?php fa-money-bill-wave"></i>
<?php Depósitos
<?php </div>
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($afiliado['total_depositos'],<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php <div<?php class="stat-card">
<?php <div<?php class="stat-label">
<?php <i<?php class="fas<?php fa-percentage"></i>
<?php CPA
<?php </div>
<?php <div<?php class="stat-value">
<?php R$<?php <?php=<?php $comissao_cpa<?php ?>
<?php <button<?php onclick="abrirModalComissao('<?php=<?php $afiliado['id']<?php ?>',<?php '<?php=<?php isset($afiliado['comissao_cpa'])<?php ?<?php $afiliado['comissao_cpa']<?php :<?php '0'<?php ?>',<?php 'cpa')"
<?php class="edit-commission">
<?php <i<?php class="fas<?php fa-edit"></i>
<?php </button>
<?php </div>
<?php </div>
<?php <div<?php class="stat-card">
<?php <div<?php class="stat-label">
<?php <i<?php class="fas<?php fa-chart-line"></i>
<?php RevShare
<?php </div>
<?php <div<?php class="stat-value">
<?php <?php=<?php $comissao_revshare<?php ?>%
<?php <button<?php onclick="abrirModalComissao('<?php=<?php $afiliado['id']<?php ?>',<?php '<?php=<?php isset($afiliado['comissao_revshare'])<?php ?<?php $afiliado['comissao_revshare']<?php :<?php '0'<?php ?>',<?php 'revshare')"
<?php class="edit-commission">
<?php <i<?php class="fas<?php fa-edit"></i>
<?php </button>
<?php </div>
<?php </div>
<?php <div<?php class="stat-card">
<?php <div<?php class="stat-label">
<?php <i<?php class="fas<?php fa-wallet"></i>
<?php Rev.<?php Ganho
<?php </div>
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($afiliado['total_revshare']<?php ??<?php 0,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="action-buttons">
<?php <a<?php href="?toggle_banido&id=<?php=<?php $afiliado['id']<?php ?>"<?php 
<?php class="action-btn<?php <?php=<?php $afiliado['banido']<?php ?<?php 'btn-unban'<?php :<?php 'btn-ban'<?php ?>">
<?php <i<?php class="fas<?php fa-<?php=<?php $afiliado['banido']<?php ?<?php 'user-check'<?php :<?php 'user-slash'<?php ?>"></i>
<?php <?php=<?php $afiliado['banido']<?php ?<?php 'Desbanir'<?php :<?php 'Banir'<?php ?>
<?php </a>
<?php 
<?php <a<?php href="?toggle_influencer&id=<?php=<?php $afiliado['id']<?php ?>"<?php 
<?php class="action-btn<?php <?php=<?php $afiliado['influencer']<?php ?<?php 'btn-remove-inf'<?php :<?php 'btn-influencer'<?php ?>">
<?php <i<?php class="fas<?php fa-<?php=<?php $afiliado['influencer']<?php ?<?php 'user-minus'<?php :<?php 'star'<?php ?>"></i>
<?php <?php=<?php $afiliado['influencer']<?php ?<?php 'Remover<?php Inf.'<?php :<?php 'Tornar<?php Inf.'<?php ?>
<?php </a>
<?php 
<?php <button<?php onclick="abrirDetalhesAfiliado(<?php=<?php $afiliado['id']<?php ?>)"<?php 
<?php class="action-btn<?php btn-details">
<?php <i<?php class="fas<?php fa-eye"></i>
<?php Detalhes
<?php </button>
<?php </div>
<?php 
<?php <div<?php class="affiliate-meta">
<?php <i<?php class="fas<?php fa-calendar"></i>
<?php <span>Cadastrado<?php em:<?php <?php=<?php date('d/m/Y<?php H:i',<?php strtotime($afiliado['created_at']))<?php ?></span>
<?php </div>
<?php </div>
<?php <?php<?php endforeach;<?php ?>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </section>
<?php </div>
<?php </main>

<?php <!--<?php Modal<?php Editar<?php Comissão<?php CPA<?php -->
<?php <div<?php id="editarComissaoModal"<?php class="modal<?php hidden">
<?php <div<?php class="modal-content">
<?php <h2<?php class="modal-title"<?php id="modalTitle">
<?php <i<?php class="fas<?php fa-percentage"></i>
<?php Editar<?php Comissão<?php CPA
<?php </h2>
<?php <form<?php method="POST"<?php id="formEditarComissao">
<?php <input<?php type="hidden"<?php name="id"<?php id="afiliadoId">
<?php <div<?php class="modal-form-group">
<?php <label<?php class="modal-label"<?php id="modalLabel">
<?php <i<?php class="fas<?php fa-dollar-sign"></i>
<?php Valor<?php da<?php Comissão<?php CPA
<?php </label>
<?php <div<?php class="modal-input-container">
<?php <span<?php class="modal-currency"<?php id="modalCurrency">R$</span>
<?php <input<?php type="text"<?php name="comissao_cpa"<?php id="afiliadoComissao"<?php 
<?php class="modal-input"<?php 
<?php placeholder="0,00"<?php required>
<?php <span<?php class="modal-percentage<?php hidden"<?php id="modalPercentage">%</span>
<?php </div>
<?php </div>
<?php <div<?php class="modal-buttons">
<?php <button<?php type="submit"<?php name="atualizar_comissao_cpa"<?php class="modal-btn<?php modal-btn-primary"<?php id="submitBtn">
<?php <i<?php class="fas<?php fa-save"></i>
<?php Salvar
<?php </button>
<?php <button<?php type="button"<?php onclick="fecharModalComissao()"<?php class="modal-btn<?php modal-btn-secondary">
<?php <i<?php class="fas<?php fa-times"></i>
<?php Cancelar
<?php </button>
<?php </div>
<?php </form>
<?php </div>
<?php </div>
<?php 
<?php <!--<?php Modal<?php Editar<?php Comissão<?php RevShare<?php -->
<?php <div<?php id="editarRevshareModal"<?php class="modal<?php hidden">
<?php <div<?php class="modal-content">
<?php <h2<?php class="modal-title">
<?php <i<?php class="fas<?php fa-chart-line"></i>
<?php Editar<?php Comissão<?php RevShare
<?php </h2>
<?php <form<?php method="POST"<?php id="formEditarRevshare">
<?php <input<?php type="hidden"<?php name="id"<?php id="afiliadoIdRevshare">
<?php <div<?php class="modal-form-group">
<?php <label<?php class="modal-label">
<?php <i<?php class="fas<?php fa-percentage"></i>
<?php Percentual<?php da<?php Comissão<?php RevShare
<?php </label>
<?php <div<?php class="modal-input-container">
<?php <input<?php type="text"<?php name="comissao_revshare"<?php id="afiliadoComissaoRevshare"<?php 
<?php class="modal-input<?php percentage"<?php 
<?php placeholder="0,00"<?php required>
<?php <span<?php class="modal-percentage">%</span>
<?php </div>
<?php </div>
<?php <div<?php class="modal-buttons">
<?php <button<?php type="submit"<?php name="atualizar_comissao_revshare"<?php class="modal-btn<?php modal-btn-primary">
<?php <i<?php class="fas<?php fa-save"></i>
<?php Salvar
<?php </button>
<?php <button<?php type="button"<?php onclick="fecharModalRevshare()"<?php class="modal-btn<?php modal-btn-secondary">
<?php <i<?php class="fas<?php fa-times"></i>
<?php Cancelar
<?php </button>
<?php </div>
<?php </form>
<?php </div>
<?php </div>

<?php <!--<?php Modal<?php Detalhes<?php do<?php Afiliado<?php -->
<?php <div<?php id="detalhesAfiliadoModal"<?php class="modal-details<?php hidden">
<?php <div<?php class="modal-details-content">
<?php <div<?php class="modal-details-header">
<?php <h2<?php class="modal-details-title">
<?php <i<?php class="fas<?php fa-user-circle"></i>
<?php <span<?php id="nomeAfiliadoModal">Detalhes<?php do<?php Afiliado</span>
<?php </h2>
<?php <button<?php onclick="fecharDetalhesAfiliado()"<?php class="close-btn">
<?php <i<?php class="fas<?php fa-times"></i>
<?php </button>
<?php </div>
<?php 
<?php <div<?php class="modal-details-body">
<?php <!--<?php Loading<?php state<?php -->
<?php <div<?php id="detalhesLoading"<?php class="loading-container">
<?php <div<?php class="loading-spinner"></div>
<?php <p>Carregando<?php detalhes...</p>
<?php </div>
<?php 
<?php <!--<?php Content<?php container<?php -->
<?php <div<?php id="detalhesContent"<?php class="hidden">
<?php <!--<?php Estatísticas<?php rápidas<?php -->
<?php <div<?php class="details-stats-grid">
<?php <div<?php class="details-stat-card">
<?php <div<?php class="details-stat-icon<?php green">
<?php <i<?php class="fas<?php fa-users"></i>
<?php </div>
<?php <div<?php class="details-stat-info">
<?php <span<?php class="details-stat-value"<?php id="totalIndicados">0</span>
<?php <span<?php class="details-stat-label">Total<?php de<?php Indicados</span>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="details-stat-card">
<?php <div<?php class="details-stat-icon<?php blue">
<?php <i<?php class="fas<?php fa-dollar-sign"></i>
<?php </div>
<?php <div<?php class="details-stat-info">
<?php <span<?php class="details-stat-value"<?php id="totalDepositado">R$<?php 0,00</span>
<?php <span<?php class="details-stat-label">Total<?php Depositado</span>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="details-stat-card">
<?php <div<?php class="details-stat-icon<?php purple">
<?php <i<?php class="fas<?php fa-percentage"></i>
<?php </div>
<?php <div<?php class="details-stat-info">
<?php <span<?php class="details-stat-value"<?php id="totalCPA">R$<?php 0,00</span>
<?php <span<?php class="details-stat-label">Total<?php CPA</span>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="details-stat-card">
<?php <div<?php class="details-stat-icon<?php orange">
<?php <i<?php class="fas<?php fa-chart-line"></i>
<?php </div>
<?php <div<?php class="details-stat-info">
<?php <span<?php class="details-stat-value"<?php id="totalRevShare">R$<?php 0,00</span>
<?php <span<?php class="details-stat-label">Total<?php RevShare</span>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="details-stat-card">
<?php <div<?php class="details-stat-icon<?php green">
<?php <i<?php class="fas<?php fa-wallet"></i>
<?php </div>
<?php <div<?php class="details-stat-info">
<?php <span<?php class="details-stat-value"<?php id="saldoAtual">R$<?php 0,00</span>
<?php <span<?php class="details-stat-label">Saldo<?php Atual</span>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <!--<?php Tabs<?php -->
<?php <div<?php class="details-tabs">
<?php <button<?php class="tab-btn<?php active"<?php onclick="abrirTab('indicados')">
<?php <i<?php class="fas<?php fa-users"></i>
<?php Indicados
<?php </button>
<?php <button<?php class="tab-btn"<?php onclick="abrirTab('historico-cpa')">
<?php <i<?php class="fas<?php fa-percentage"></i>
<?php Histórico<?php CPA
<?php </button>
<?php <button<?php class="tab-btn"<?php onclick="abrirTab('historico-revshare')">
<?php <i<?php class="fas<?php fa-chart-line"></i>
<?php Histórico<?php RevShare
<?php </button>
<?php </div>
<?php 
<?php <!--<?php Tab<?php Content<?php -<?php Indicados<?php -->
<?php <div<?php id="tab-indicados"<?php class="tab-content<?php active">
<?php <div<?php class="tab-header">
<?php <h3><i<?php class="fas<?php fa-users"></i><?php Lista<?php de<?php Indicados</h3>
<?php </div>
<?php <div<?php id="listaIndicados"<?php class="table-container">
<?php <!--<?php Conteúdo<?php será<?php preenchido<?php via<?php JavaScript<?php -->
<?php </div>
<?php </div>
<?php 
<?php <!--<?php Tab<?php Content<?php -<?php Histórico<?php CPA<?php -->
<?php <div<?php id="tab-historico-cpa"<?php class="tab-content">
<?php <div<?php class="tab-header">
<?php <h3><i<?php class="fas<?php fa-percentage"></i><?php Histórico<?php de<?php Comissões<?php CPA</h3>
<?php </div>
<?php <div<?php id="historicoCPA"<?php class="table-container">
<?php <!--<?php Conteúdo<?php será<?php preenchido<?php via<?php JavaScript<?php -->
<?php </div>
<?php </div>
<?php 
<?php <!--<?php Tab<?php Content<?php -<?php Histórico<?php RevShare<?php -->
<?php <div<?php id="tab-historico-revshare"<?php class="tab-content">
<?php <div<?php class="tab-header">
<?php <h3><i<?php class="fas<?php fa-chart-line"></i><?php Histórico<?php RevShare</h3>
<?php </div>
<?php <div<?php id="historicoRevShare"<?php class="table-container">
<?php <!--<?php Conteúdo<?php será<?php preenchido<?php via<?php JavaScript<?php -->
<?php </div>
<?php </div>
<?php </div>
<?php </div>
<?php </div>
<?php </div>
<?php 
<script>
<?php //<?php Mobile<?php menu<?php toggle<?php with<?php smooth<?php animations
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
<?php //<?php Modal<?php functions
<?php function<?php abrirModalComissao(id,<?php comissao,<?php tipo)<?php {
<?php if<?php (tipo<?php ===<?php 'revshare')<?php {
<?php abrirModalRevshare(id,<?php comissao);
<?php return;
<?php }
<?php 
<?php document.getElementById('afiliadoId').value<?php =<?php id;
<?php document.getElementById('afiliadoComissao').value<?php =<?php comissao;
<?php document.getElementById('editarComissaoModal').classList.remove('hidden');
<?php }
<?php 
<?php function<?php abrirModalRevshare(id,<?php comissao)<?php {
<?php document.getElementById('afiliadoIdRevshare').value<?php =<?php id;
<?php document.getElementById('afiliadoComissaoRevshare').value<?php =<?php comissao;
<?php document.getElementById('editarRevshareModal').classList.remove('hidden');
<?php }
<?php 
<?php function<?php fecharModalComissao()<?php {
<?php document.getElementById('editarComissaoModal').classList.add('hidden');
<?php }
<?php 
<?php function<?php fecharModalRevshare()<?php {
<?php document.getElementById('editarRevshareModal').classList.add('hidden');
<?php }
<?php 
<?php //<?php Close<?php modal<?php when<?php clicking<?php outside
<?php document.getElementById('editarComissaoModal').addEventListener('click',<?php function(e)<?php {
<?php if<?php (e.target<?php ===<?php this)<?php {
<?php fecharModalComissao();
<?php }
<?php });
<?php 
<?php document.getElementById('editarRevshareModal').addEventListener('click',<?php function(e)<?php {
<?php if<?php (e.target<?php ===<?php this)<?php {
<?php fecharModalRevshare();
<?php }
<?php });

<?php //<?php Variável<?php global<?php para<?php controlar<?php o<?php modal
<?php let<?php detalhesModalAberto<?php =<?php false;

<?php //<?php Close<?php modal<?php with<?php ESC<?php key
<?php document.addEventListener('keydown',<?php function(e)<?php {
<?php if<?php (e.key<?php ===<?php 'Escape')<?php {
<?php fecharModalComissao();
<?php fecharModalRevshare();
<?php if<?php (detalhesModalAberto)<?php {
<?php fecharDetalhesAfiliado();
<?php }
<?php }
<?php });

<?php //<?php Função<?php para<?php abrir<?php detalhes<?php do<?php afiliado
<?php async<?php function<?php abrirDetalhesAfiliado(afiliadoId)<?php {
<?php const<?php modal<?php =<?php document.getElementById('detalhesAfiliadoModal');
<?php const<?php loading<?php =<?php document.getElementById('detalhesLoading');
<?php const<?php content<?php =<?php document.getElementById('detalhesContent');
<?php 
<?php //<?php Mostrar<?php modal<?php com<?php loading
<?php modal.classList.remove('hidden');
<?php loading.classList.remove('hidden');
<?php content.classList.add('hidden');
<?php detalhesModalAberto<?php =<?php true;
<?php 
<?php try<?php {
<?php //<?php Fazer<?php requisição<?php AJAX
<?php const<?php response<?php =<?php await<?php fetch(`?ajax=detalhes_afiliado&afiliado_id=${afiliadoId}`);
<?php const<?php data<?php =<?php await<?php response.json();
<?php 
<?php if<?php (data.error)<?php {
<?php throw<?php new<?php Error(data.error);
<?php }
<?php 
<?php //<?php Preencher<?php dados<?php do<?php modal
<?php preencherDetalhesModal(data);
<?php 
<?php //<?php Esconder<?php loading<?php e<?php mostrar<?php content
<?php loading.classList.add('hidden');
<?php content.classList.remove('hidden');
<?php 
<?php }<?php catch<?php (error)<?php {
<?php console.error('Erro<?php ao<?php carregar<?php detalhes:',<?php error);
<?php Notiflix.Notify.failure('Erro<?php ao<?php carregar<?php detalhes<?php do<?php afiliado:<?php '<?php +<?php error.message);
<?php fecharDetalhesAfiliado();
<?php }
<?php }

<?php //<?php Função<?php para<?php preencher<?php o<?php modal<?php com<?php os<?php dados
<?php function<?php preencherDetalhesModal(data)<?php {
<?php const<?php {<?php afiliado,<?php indicados,<?php historico_cpa,<?php historico_revshare,<?php estatisticas<?php }<?php =<?php data;
<?php 
<?php //<?php Atualizar<?php título<?php com<?php nome<?php do<?php afiliado
<?php document.getElementById('nomeAfiliadoModal').textContent<?php =<?php `Detalhes<?php de<?php ${afiliado.nome}`;
<?php 
<?php //<?php Atualizar<?php estatísticas
<?php document.getElementById('totalIndicados').textContent<?php =<?php estatisticas.total_indicados;
<?php document.getElementById('totalDepositado').textContent<?php =<?php `R$<?php ${formatarMoeda(estatisticas.total_depositado_indicados)}`;
<?php document.getElementById('totalCPA').textContent<?php =<?php `R$<?php ${formatarMoeda(estatisticas.total_comissao_cpa)}`;
<?php document.getElementById('totalRevShare').textContent<?php =<?php `R$<?php ${formatarMoeda(estatisticas.total_comissao_revshare)}`;
<?php document.getElementById('saldoAtual').textContent<?php =<?php `R$<?php ${formatarMoeda(estatisticas.saldo_atual)}`;
<?php 
<?php //<?php Preencher<?php tabela<?php de<?php indicados
<?php preencherTabelaIndicados(indicados);
<?php 
<?php //<?php Preencher<?php histórico<?php CPA
<?php preencherHistoricoCPA(historico_cpa);
<?php 
<?php //<?php Preencher<?php histórico<?php RevShare
<?php preencherHistoricoRevShare(historico_revshare);
<?php }

<?php //<?php Função<?php para<?php preencher<?php tabela<?php de<?php indicados
<?php function<?php preencherTabelaIndicados(indicados)<?php {
<?php const<?php container<?php =<?php document.getElementById('listaIndicados');
<?php 
<?php if<?php (indicados.length<?php ===<?php 0)<?php {
<?php container.innerHTML<?php =<?php `
<?php <div<?php class="table-empty">
<?php <i<?php class="fas<?php fa-users"></i>
<?php <h4>Nenhum<?php indicado<?php encontrado</h4>
<?php <p>Este<?php afiliado<?php ainda<?php não<?php possui<?php indicados</p>
<?php </div>
<?php `;
<?php return;
<?php }
<?php 
<?php let<?php html<?php =<?php `
<?php <table<?php class="details-table">
<?php <thead>
<?php <tr>
<?php <th><i<?php class="fas<?php fa-user"></i><?php Nome</th>
<?php <th><i<?php class="fas<?php fa-envelope"></i><?php Email</th>
<?php <th><i<?php class="fas<?php fa-phone"></i><?php Telefone</th>
<?php <th><i<?php class="fas<?php fa-dollar-sign"></i><?php Total<?php Depositado</th>
<?php <th><i<?php class="fas<?php fa-credit-card"></i><?php Nº<?php Depósitos</th>
<?php <th><i<?php class="fas<?php fa-calendar"></i><?php Cadastro</th>
<?php <th><i<?php class="fas<?php fa-info-circle"></i><?php Status</th>
<?php </tr>
<?php </thead>
<?php <tbody>
<?php `;
<?php 
<?php indicados.forEach(indicado<?php =><?php {
<?php const<?php telefoneFormatado<?php =<?php formatarTelefone(indicado.telefone);
<?php const<?php whatsappLink<?php =<?php `https://wa.me/55${indicado.telefone.replace(/[^0-9]/g,<?php '')}`;
<?php const<?php statusClass<?php =<?php indicado.banido<?php ==<?php 1<?php ?<?php 'banido'<?php :<?php 'ativo';
<?php const<?php statusText<?php =<?php indicado.banido<?php ==<?php 1<?php ?<?php 'Banido'<?php :<?php 'Ativo';
<?php const<?php dataCadastro<?php =<?php formatarData(indicado.data_cadastro);
<?php 
<?php html<?php +=<?php `
<?php <tr>
<?php <td>
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 0.5rem;">
<?php <div<?php style="width:<?php 32px;<?php height:<?php 32px;<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php border-radius:<?php 8px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php center;<?php color:<?php white;<?php font-weight:<?php 600;<?php font-size:<?php 0.8rem;">
<?php ${indicado.nome.charAt(0).toUpperCase()}
<?php </div>
<?php ${indicado.nome}
<?php </div>
<?php </td>
<?php <td>${indicado.email}</td>
<?php <td>
<?php ${telefoneFormatado}
<?php <a<?php href="${whatsappLink}"<?php target="_blank"<?php class="whatsapp-table-link">
<?php <i<?php class="fab<?php fa-whatsapp"></i>
<?php </a>
<?php </td>
<?php <td<?php style="font-weight:<?php 600;<?php color:<?php #22c55e;">R$<?php ${formatarMoeda(indicado.total_depositado)}</td>
<?php <td>
<?php <span<?php style="background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php color:<?php #22c55e;<?php padding:<?php 0.25rem<?php 0.5rem;<?php border-radius:<?php 6px;<?php font-size:<?php 0.8rem;<?php font-weight:<?php 600;">
<?php ${indicado.total_depositos}
<?php </span>
<?php </td>
<?php <td<?php style="color:<?php #9ca3af;">${dataCadastro}</td>
<?php <td>
<?php <span<?php class="status-badge<?php ${statusClass}">${statusText}</span>
<?php </td>
<?php </tr>
<?php `;
<?php });
<?php 
<?php html<?php +=<?php `
<?php </tbody>
<?php </table>
<?php `;
<?php 
<?php container.innerHTML<?php =<?php html;
<?php }

<?php //<?php Função<?php para<?php preencher<?php histórico<?php CPA
<?php function<?php preencherHistoricoCPA(historico)<?php {
<?php const<?php container<?php =<?php document.getElementById('historicoCPA');
<?php 
<?php if<?php (historico.length<?php ===<?php 0)<?php {
<?php container.innerHTML<?php =<?php `
<?php <div<?php class="table-empty">
<?php <i<?php class="fas<?php fa-percentage"></i>
<?php <h4>Nenhuma<?php comissão<?php CPA<?php encontrada</h4>
<?php <p>Ainda<?php não<?php há<?php histórico<?php de<?php comissões<?php CPA<?php para<?php este<?php afiliado</p>
<?php </div>
<?php `;
<?php return;
<?php }
<?php 
<?php let<?php html<?php =<?php `
<?php <table<?php class="details-table">
<?php <thead>
<?php <tr>
<?php <th><i<?php class="fas<?php fa-user"></i><?php Indicado</th>
<?php <th><i<?php class="fas<?php fa-envelope"></i><?php Email</th>
<?php <th><i<?php class="fas<?php fa-dollar-sign"></i><?php Valor<?php Depósito</th>
<?php <th><i<?php class="fas<?php fa-percentage"></i><?php Comissão</th>
<?php <th><i<?php class="fas<?php fa-calendar"></i><?php Data</th>
<?php </tr>
<?php </thead>
<?php <tbody>
<?php `;
<?php 
<?php historico.forEach(item<?php =><?php {
<?php const<?php dataFormatada<?php =<?php formatarDataHora(item.created_at);
<?php 
<?php html<?php +=<?php `
<?php <tr>
<?php <td>
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 0.5rem;">
<?php <div<?php style="width:<?php 28px;<?php height:<?php 28px;<?php background:<?php linear-gradient(135deg,<?php #9333ea,<?php #7c3aed);<?php border-radius:<?php 6px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php center;<?php color:<?php white;<?php font-weight:<?php 600;<?php font-size:<?php 0.75rem;">
<?php ${item.indicado_nome.charAt(0).toUpperCase()}
<?php </div>
<?php ${item.indicado_nome}
<?php </div>
<?php </td>
<?php <td<?php style="color:<?php #9ca3af;">${item.indicado_email}</td>
<?php <td<?php style="font-weight:<?php 600;<?php color:<?php #3b82f6;">R$<?php ${formatarMoeda(item.valor_deposito<?php ||<?php 0)}</td>
<?php <td<?php style="font-weight:<?php 700;<?php color:<?php #22c55e;">R$<?php ${formatarMoeda(item.valor_comissao)}</td>
<?php <td<?php style="color:<?php #9ca3af;">${dataFormatada}</td>
<?php </tr>
<?php `;
<?php });
<?php 
<?php html<?php +=<?php `
<?php </tbody>
<?php </table>
<?php `;
<?php 
<?php container.innerHTML<?php =<?php html;
<?php }

<?php //<?php Função<?php para<?php preencher<?php histórico<?php RevShare
<?php function<?php preencherHistoricoRevShare(historico)<?php {
<?php const<?php container<?php =<?php document.getElementById('historicoRevShare');
<?php 
<?php if<?php (historico.length<?php ===<?php 0)<?php {
<?php container.innerHTML<?php =<?php `
<?php <div<?php class="table-empty">
<?php <i<?php class="fas<?php fa-chart-line"></i>
<?php <h4>Nenhum<?php RevShare<?php encontrado</h4>
<?php <p>Ainda<?php não<?php há<?php histórico<?php de<?php RevShare<?php para<?php este<?php afiliado</p>
<?php </div>
<?php `;
<?php return;
<?php }
<?php 
<?php let<?php html<?php =<?php `
<?php <table<?php class="details-table">
<?php <thead>
<?php <tr>
<?php <th><i<?php class="fas<?php fa-user"></i><?php Indicado</th>
<?php <th><i<?php class="fas<?php fa-envelope"></i><?php Email</th>
<?php <th><i<?php class="fas<?php fa-gamepad"></i><?php Jogo</th>
<?php <th><i<?php class="fas<?php fa-money-bill-wave"></i><?php Valor<?php Perdido</th>
<?php <th><i<?php class="fas<?php fa-percentage"></i><?php %<?php RevShare</th>
<?php <th><i<?php class="fas<?php fa-chart-line"></i><?php Valor<?php RevShare</th>
<?php <th><i<?php class="fas<?php fa-calendar"></i><?php Data</th>
<?php </tr>
<?php </thead>
<?php <tbody>
<?php `;
<?php 
<?php historico.forEach(item<?php =><?php {
<?php const<?php dataFormatada<?php =<?php formatarDataHora(item.created_at);
<?php 
<?php html<?php +=<?php `
<?php <tr>
<?php <td>
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 0.5rem;">
<?php <div<?php style="width:<?php 28px;<?php height:<?php 28px;<?php background:<?php linear-gradient(135deg,<?php #f97316,<?php #ea580c);<?php border-radius:<?php 6px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php center;<?php color:<?php white;<?php font-weight:<?php 600;<?php font-size:<?php 0.75rem;">
<?php ${item.indicado_nome.charAt(0).toUpperCase()}
<?php </div>
<?php ${item.indicado_nome}
<?php </div>
<?php </td>
<?php <td<?php style="color:<?php #9ca3af;">${item.indicado_email}</td>
<?php <td>
<?php <span<?php style="background:<?php rgba(59,<?php 130,<?php 246,<?php 0.1);<?php color:<?php #3b82f6;<?php padding:<?php 0.25rem<?php 0.5rem;<?php border-radius:<?php 6px;<?php font-size:<?php 0.8rem;<?php font-weight:<?php 600;">
<?php ${item.jogo<?php ||<?php 'N/A'}
<?php </span>
<?php </td>
<?php <td<?php style="font-weight:<?php 600;<?php color:<?php #ef4444;">R$<?php ${formatarMoeda(item.valor_perdido<?php ||<?php 0)}</td>
<?php <td<?php style="color:<?php #f97316;<?php font-weight:<?php 600;">${formatarMoeda(item.percentual_revshare<?php ||<?php 0)}%</td>
<?php <td<?php style="font-weight:<?php 700;<?php color:<?php #22c55e;">R$<?php ${formatarMoeda(item.valor_revshare)}</td>
<?php <td<?php style="color:<?php #9ca3af;">${dataFormatada}</td>
<?php </tr>
<?php `;
<?php });
<?php 
<?php html<?php +=<?php `
<?php </tbody>
<?php </table>
<?php `;
<?php 
<?php container.innerHTML<?php =<?php html;
<?php }

<?php //<?php Função<?php para<?php fechar<?php modal<?php de<?php detalhes
<?php function<?php fecharDetalhesAfiliado()<?php {
<?php const<?php modal<?php =<?php document.getElementById('detalhesAfiliadoModal');
<?php modal.classList.add('hidden');
<?php detalhesModalAberto<?php =<?php false;
<?php 
<?php //<?php Reset<?php do<?php modal<?php para<?php próxima<?php abertura
<?php document.getElementById('detalhesLoading').classList.remove('hidden');
<?php document.getElementById('detalhesContent').classList.add('hidden');
<?php 
<?php //<?php Reset<?php das<?php tabs
<?php abrirTab('indicados');
<?php }

<?php //<?php Função<?php para<?php alternar<?php entre<?php tabs
<?php function<?php abrirTab(tabName)<?php {
<?php //<?php Esconder<?php todos<?php os<?php conteúdos
<?php document.querySelectorAll('.tab-content').forEach(tab<?php =><?php {
<?php tab.classList.remove('active');
<?php });
<?php 
<?php //<?php Remover<?php classe<?php active<?php de<?php todos<?php os<?php botões
<?php document.querySelectorAll('.tab-btn').forEach(btn<?php =><?php {
<?php btn.classList.remove('active');
<?php });
<?php 
<?php //<?php Mostrar<?php conteúdo<?php da<?php tab<?php selecionada
<?php document.getElementById(`tab-${tabName}`).classList.add('active');
<?php 
<?php //<?php Adicionar<?php classe<?php active<?php ao<?php botão<?php correspondente
<?php event.target.classList.add('active');
<?php }

<?php //<?php Funções<?php auxiliares<?php para<?php formatação
<?php function<?php formatarMoeda(valor)<?php {
<?php const<?php numero<?php =<?php parseFloat(valor)<?php ||<?php 0;
<?php return<?php numero.toLocaleString('pt-BR',<?php {
<?php minimumFractionDigits:<?php 2,
<?php maximumFractionDigits:<?php 2
<?php });
<?php }

<?php function<?php formatarTelefone(telefone)<?php {
<?php if<?php (!telefone)<?php return<?php 'N/A';
<?php 
<?php const<?php apenasNumeros<?php =<?php telefone.replace(/[^0-9]/g,<?php '');
<?php 
<?php if<?php (apenasNumeros.length<?php ===<?php 11)<?php {
<?php return<?php `(${apenasNumeros.substring(0,<?php 2)})<?php ${apenasNumeros.substring(2,<?php 7)}-${apenasNumeros.substring(7)}`;
<?php }<?php else<?php if<?php (apenasNumeros.length<?php ===<?php 10)<?php {
<?php return<?php `(${apenasNumeros.substring(0,<?php 2)})<?php ${apenasNumeros.substring(2,<?php 6)}-${apenasNumeros.substring(6)}`;
<?php }
<?php 
<?php return<?php telefone;
<?php }

<?php function<?php formatarData(dataString)<?php {
<?php if<?php (!dataString)<?php return<?php 'N/A';
<?php 
<?php const<?php data<?php =<?php new<?php Date(dataString);
<?php return<?php data.toLocaleDateString('pt-BR');
<?php }

<?php function<?php formatarDataHora(dataString)<?php {
<?php if<?php (!dataString)<?php return<?php 'N/A';
<?php 
<?php const<?php data<?php =<?php new<?php Date(dataString);
<?php return<?php data.toLocaleString('pt-BR');
<?php }

<?php //<?php Initialize
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {
<?php console.log('%c🤝<?php Afiliados<?php carregados!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {
<?php sidebar.classList.add('hidden');
<?php }
<?php 
<?php //<?php Animate<?php cards<?php on<?php load
<?php const<?php affiliateCards<?php =<?php document.querySelectorAll('.affiliate-card');
<?php affiliateCards.forEach((card,<?php index)<?php =><?php {
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

<?php //<?php Event<?php listeners<?php para<?php fechar<?php modal<?php de<?php detalhes
<?php const<?php detalhesModal<?php =<?php document.getElementById('detalhesAfiliadoModal');
<?php if<?php (detalhesModal)<?php {
<?php detalhesModal.addEventListener('click',<?php function(e)<?php {
<?php if<?php (e.target<?php ===<?php this)<?php {
<?php fecharDetalhesAfiliado();
<?php }
<?php });
<?php }
<?php });
<?php 
<?php //<?php Smooth<?php scroll<?php behavior
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';
<?php </script>

</body>
</html>