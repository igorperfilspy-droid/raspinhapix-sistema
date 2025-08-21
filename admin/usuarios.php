<?php<?php 
include<?php '../includes/session.php';<?php 
include<?php '../conexao.php';<?php 
include<?php '../includes/notiflix.php';<?php 
<?php 
$usuarioId<?php =<?php $_SESSION['usuario_id'];<?php 
$admin<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php admin<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;<?php 
<?php 
if<?php ($admin<?php !=<?php 1)<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php não<?php é<?php um<?php administrador!'];<?php 
<?php header("Location:<?php /");<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_POST['atualizar_saldo']))<?php {<?php 
<?php $id<?php =<?php $_POST['id'];<?php 
<?php $saldo<?php =<?php str_replace(',',<?php '.',<?php $_POST['saldo']);<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$saldo,<?php $id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Saldo<?php atualizado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php saldo!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_GET['toggle_banido']))<?php {<?php 
<?php $id<?php =<?php $_GET['id'];<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php banido<?php =<?php IF(banido=1,<?php 0,<?php 1)<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Status<?php de<?php banido<?php alterado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php alterar<?php status!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_GET['toggle_influencer']))<?php {<?php 
<?php $id<?php =<?php $_GET['id'];<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php influencer<?php =<?php IF(influencer=1,<?php 0,<?php 1)<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Status<?php de<?php influencer<?php alterado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php alterar<?php status!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$nome<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php nome<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;<?php 
$nome<?php =<?php $nome<?php ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;<?php 
<?php 
$search<?php =<?php isset($_GET['search'])<?php ?<?php $_GET['search']<?php :<?php '';<?php 
$query<?php =<?php "SELECT<?php u.*,<?php ui.email<?php as<?php email_indicador<?php FROM<?php usuarios<?php u<?php LEFT<?php JOIN<?php usuarios<?php ui<?php ON<?php u.indicacao<?php =<?php ui.id<?php WHERE<?php 1=1";<?php 
<?php 
if<?php (!empty($search))<?php {<?php 
<?php $query<?php .=<?php "<?php AND<?php (u.nome<?php LIKE<?php :search<?php OR<?php u.email<?php LIKE<?php :search<?php OR<?php u.telefone<?php LIKE<?php :search)";<?php 
}<?php 
<?php 
$query<?php .=<?php "<?php ORDER<?php BY<?php u.created_at<?php DESC";<?php 
<?php 
$stmt<?php =<?php $pdo->prepare($query);<?php 
<?php 
if<?php (!empty($search))<?php {<?php 
<?php $searchTerm<?php =<?php "%$search%";<?php 
<?php $stmt->bindParam(':search',<?php $searchTerm,<?php PDO::PARAM_STR);<?php 
}<?php 
<?php 
$stmt->execute();<?php 
$usuarios<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
//<?php Count<?php stats<?php 
$total_usuarios<?php =<?php count($usuarios);<?php 
$usuarios_ativos<?php =<?php array_filter($usuarios,<?php function($u)<?php {<?php return<?php $u['banido']<?php ==<?php 0;<?php });<?php 
$influencers<?php =<?php array_filter($usuarios,<?php function($u)<?php {<?php return<?php $u['influencer']<?php ==<?php 1;<?php });<?php 
$total_saldo<?php =<?php array_sum(array_column($usuarios,<?php 'saldo'));<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Gerenciar<?php Usuários</title><?php 
<?php 
<?php <!--<?php TailwindCSS<?php --><?php 
<?php <script<?php src="https://cdn.tailwindcss.com"></script><?php 
<?php 
<?php <!--<?php Font<?php Awesome<?php --><?php 
<?php <link<?php rel="stylesheet"<?php href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"><?php 
<?php 
<?php <!--<?php Notiflix<?php --><?php 
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
<?php 
<?php <!--<?php Google<?php Fonts<?php --><?php 
<?php <link<?php href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"<?php rel="stylesheet"><?php 
<?php 
<?php <style><?php 
<?php *<?php {<?php 
<?php margin:<?php 0;<?php 
<?php padding:<?php 0;<?php 
<?php box-sizing:<?php border-box;<?php 
<?php }<?php 
<?php 
<?php body<?php {<?php 
<?php font-family:<?php 'Inter',<?php -apple-system,<?php BlinkMacSystemFont,<?php sans-serif;<?php 
<?php background:<?php #000000;<?php 
<?php color:<?php #ffffff;<?php 
<?php min-height:<?php 100vh;<?php 
<?php overflow-x:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php /*<?php Advanced<?php Sidebar<?php Styles<?php */<?php 
<?php .sidebar<?php {<?php 
<?php position:<?php fixed;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 320px;<?php 
<?php height:<?php 100vh;<?php 
<?php background:<?php linear-gradient(145deg,<?php #0a0a0a<?php 0%,<?php #141414<?php 25%,<?php #1a1a1a<?php 50%,<?php #0f0f0f<?php 100%);<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php border-right:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php transition:<?php all<?php 0.4s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);<?php 
<?php z-index:<?php 1000;<?php 
<?php box-shadow:<?php 
<?php 0<?php 0<?php 50px<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php 
<?php inset<?php 1px<?php 0<?php 0<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php }<?php 
<?php 
<?php .sidebar::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php 
<?php radial-gradient(circle<?php at<?php 20%<?php 20%,<?php rgba(34,<?php 197,<?php 94,<?php 0.15)<?php 0%,<?php transparent<?php 50%),<?php 
<?php radial-gradient(circle<?php at<?php 80%<?php 80%,<?php rgba(16,<?php 185,<?php 129,<?php 0.1)<?php 0%,<?php transparent<?php 50%),<?php 
<?php radial-gradient(circle<?php at<?php 40%<?php 60%,<?php rgba(59,<?php 130,<?php 246,<?php 0.05)<?php 0%,<?php transparent<?php 50%);<?php 
<?php opacity:<?php 0.8;<?php 
<?php pointer-events:<?php none;<?php 
<?php }<?php 
<?php 
<?php .sidebar.hidden<?php {<?php 
<?php transform:<?php translateX(-100%);<?php 
<?php }<?php 
<?php 
<?php /*<?php Enhanced<?php Sidebar<?php Header<?php */<?php 
<?php .sidebar-header<?php {<?php 
<?php position:<?php relative;<?php 
<?php padding:<?php 2.5rem<?php 2rem;<?php 
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 100%);<?php 
<?php }<?php 
<?php 
<?php .logo<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php text-decoration:<?php none;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .logo-icon<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e<?php 0%,<?php #16a34a<?php 100%);<?php 
<?php border-radius:<?php 16px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php color:<?php #ffffff;<?php 
<?php box-shadow:<?php 
<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.3),<?php 
<?php 0<?php 4px<?php 8px<?php rgba(0,<?php 0,<?php 0,<?php 0.2);<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .logo-icon::after<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php -2px;<?php 
<?php left:<?php -2px;<?php 
<?php right:<?php -2px;<?php 
<?php bottom:<?php -2px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a,<?php #22c55e);<?php 
<?php border-radius:<?php 18px;<?php 
<?php z-index:<?php -1;<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .logo:hover<?php .logo-icon::after<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .logo-text<?php {<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php }<?php 
<?php 
<?php .logo-title<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php color:<?php #ffffff;<?php 
<?php line-height:<?php 1.2;<?php 
<?php }<?php 
<?php 
<?php .logo-subtitle<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-weight:<?php 500;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 1px;<?php 
<?php }<?php 
<?php 
<?php /*<?php Advanced<?php Navigation<?php */<?php 
<?php .nav-menu<?php {<?php 
<?php padding:<?php 2rem<?php 0;<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .nav-section<?php {<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .nav-section-title<?php {<?php 
<?php padding:<?php 0<?php 2rem<?php 0.75rem<?php 2rem;<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #6b7280;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 1px;<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .nav-item<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php padding:<?php 1rem<?php 2rem;<?php 
<?php color:<?php #a1a1aa;<?php 
<?php text-decoration:<?php none;<?php 
<?php transition:<?php all<?php 0.3s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);<?php 
<?php position:<?php relative;<?php 
<?php margin:<?php 0.25rem<?php 1rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-weight:<?php 500;<?php 
<?php }<?php 
<?php 
<?php .nav-item::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php left:<?php 0;<?php 
<?php top:<?php 0;<?php 
<?php bottom:<?php 0;<?php 
<?php width:<?php 4px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 0<?php 4px<?php 4px<?php 0;<?php 
<?php transform:<?php scaleY(0);<?php 
<?php transition:<?php transform<?php 0.3s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);<?php 
<?php }<?php 
<?php 
<?php .nav-item:hover::before,<?php 
<?php .nav-item.active::before<?php {<?php 
<?php transform:<?php scaleY(1);<?php 
<?php }<?php 
<?php 
<?php .nav-item:hover,<?php 
<?php .nav-item.active<?php {<?php 
<?php color:<?php #ffffff;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.15)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.05)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php transform:<?php translateX(4px);<?php 
<?php box-shadow:<?php 0<?php 4px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .nav-icon<?php {<?php 
<?php width:<?php 24px;<?php 
<?php height:<?php 24px;<?php 
<?php margin-right:<?php 1rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 1rem;<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .nav-text<?php {<?php 
<?php font-size:<?php 0.95rem;<?php 
<?php flex:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .nav-badge<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 0.7rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php padding:<?php 0.25rem<?php 0.5rem;<?php 
<?php border-radius:<?php 6px;<?php 
<?php min-width:<?php 20px;<?php 
<?php text-align:<?php center;<?php 
<?php box-shadow:<?php 0<?php 2px<?php 8px<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php /*<?php Sidebar<?php Footer<?php */<?php 
<?php .sidebar-footer<?php {<?php 
<?php position:<?php absolute;<?php 
<?php bottom:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php right:<?php 0;<?php 
<?php padding:<?php 2rem;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 100%);<?php 
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .user-profile<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php padding:<?php 1rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .user-profile:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .user-avatar<?php {<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 10px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #ffffff;<?php 
<?php font-size:<?php 1rem;<?php 
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .user-info<?php {<?php 
<?php flex:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .user-name<?php {<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php line-height:<?php 1.2;<?php 
<?php }<?php 
<?php 
<?php .user-role<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-weight:<?php 500;<?php 
<?php }<?php 
<?php 
<?php /*<?php Main<?php Content<?php */<?php 
<?php .main-content<?php {<?php 
<?php margin-left:<?php 320px;<?php 
<?php min-height:<?php 100vh;<?php 
<?php transition:<?php margin-left<?php 0.4s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);<?php 
<?php background:<?php 
<?php radial-gradient(circle<?php at<?php 10%<?php 20%,<?php rgba(34,<?php 197,<?php 94,<?php 0.03)<?php 0%,<?php transparent<?php 50%),<?php 
<?php radial-gradient(circle<?php at<?php 80%<?php 80%,<?php rgba(16,<?php 185,<?php 129,<?php 0.02)<?php 0%,<?php transparent<?php 50%),<?php 
<?php radial-gradient(circle<?php at<?php 40%<?php 40%,<?php rgba(59,<?php 130,<?php 246,<?php 0.01)<?php 0%,<?php transparent<?php 50%);<?php 
<?php }<?php 
<?php 
<?php .main-content.expanded<?php {<?php 
<?php margin-left:<?php 0;<?php 
<?php }<?php 
<?php 
<?php /*<?php Enhanced<?php Header<?php */<?php 
<?php .header<?php {<?php 
<?php position:<?php sticky;<?php 
<?php top:<?php 0;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.95);<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php padding:<?php 1.5rem<?php 2.5rem;<?php 
<?php z-index:<?php 100;<?php 
<?php box-shadow:<?php 0<?php 4px<?php 20px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .header-content<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php space-between;<?php 
<?php }<?php 
<?php 
<?php .menu-toggle<?php {<?php 
<?php display:<?php none;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php rgba(34,<?php 197,<?php 94,<?php 0.05));<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php color:<?php #22c55e;<?php 
<?php padding:<?php 0.75rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .menu-toggle:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php transform:<?php scale(1.05);<?php 
<?php }<?php 
<?php 
<?php .header-title<?php {<?php 
<?php font-size:<?php 1.75rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #a1a1aa);<?php 
<?php -webkit-background-clip:<?php text;<?php 
<?php -webkit-text-fill-color:<?php transparent;<?php 
<?php background-clip:<?php text;<?php 
<?php }<?php 
<?php 
<?php .header-actions<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Main<?php Page<?php Content<?php */<?php 
<?php .page-content<?php {<?php 
<?php padding:<?php 2.5rem;<?php 
<?php }<?php 
<?php 
<?php .welcome-section<?php {<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
<?php .welcome-title<?php {<?php 
<?php font-size:<?php 3rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
<?php background:<?php linear-gradient(135deg,<?php #ffffff<?php 0%,<?php #fff<?php 50%,<?php #fff<?php 100%);<?php 
<?php -webkit-background-clip:<?php text;<?php 
<?php -webkit-text-fill-color:<?php transparent;<?php 
<?php background-clip:<?php text;<?php 
<?php line-height:<?php 1.2;<?php 
<?php }<?php 
<?php 
<?php .welcome-subtitle<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php font-weight:<?php 400;<?php 
<?php }<?php 
<?php 
<?php /*<?php Stats<?php Cards<?php */<?php 
<?php .stats-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));<?php 
<?php gap:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
<?php .mini-stat-card<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php }<?php 
<?php 
<?php .mini-stat-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 3px;<?php 
<?php background:<?php linear-gradient(90deg,<?php #22c55e,<?php #16a34a);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .mini-stat-card:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .mini-stat-card:hover<?php {<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php box-shadow:<?php 0<?php 15px<?php 35px<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .mini-stat-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php space-between;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .mini-stat-icon<?php {<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 10px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .mini-stat-value<?php {<?php 
<?php font-size:<?php 1.75rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php }<?php 
<?php 
<?php .mini-stat-label<?php {<?php 
<?php color:<?php #a1a1aa;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php }<?php 
<?php 
<?php /*<?php Search<?php Section<?php */<?php 
<?php .search-section<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php }<?php 
<?php 
<?php .search-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .search-icon-container<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 12px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.125rem;<?php 
<?php }<?php 
<?php 
<?php .search-title<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php }<?php 
<?php 
<?php .search-container<?php {<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .search-input<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1rem<?php 1rem<?php 1rem<?php 3rem;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .search-input:focus<?php {<?php 
<?php outline:<?php none;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php }<?php 
<?php 
<?php .search-input::placeholder<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .search-icon<?php {<?php 
<?php position:<?php absolute;<?php 
<?php left:<?php 1rem;<?php 
<?php top:<?php 50%;<?php 
<?php transform:<?php translateY(-50%);<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php User<?php Cards<?php */<?php 
<?php .users-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(450px,<?php 1fr));<?php 
<?php gap:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .user-card<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .user-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php right:<?php 0;<?php 
<?php width:<?php 100px;<?php 
<?php height:<?php 100px;<?php 
<?php background:<?php radial-gradient(circle,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 70%);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .user-card:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .user-card:hover<?php {<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .user-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php flex-start;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .user-name<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .user-badges<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php flex-wrap:<?php wrap;<?php 
<?php }<?php 
<?php 
<?php .badge<?php {<?php 
<?php padding:<?php 0.25rem<?php 0.75rem;<?php 
<?php border-radius:<?php 20px;<?php 
<?php font-size:<?php 0.7rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.5px;<?php 
<?php }<?php 
<?php 
<?php .badge.admin<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #8b5cf6,<?php #7c3aed);<?php 
<?php color:<?php white;<?php 
<?php box-shadow:<?php 0<?php 2px<?php 8px<?php rgba(139,<?php 92,<?php 246,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .badge.influencer<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ec4899,<?php #db2777);<?php 
<?php color:<?php white;<?php 
<?php box-shadow:<?php 0<?php 2px<?php 8px<?php rgba(236,<?php 72,<?php 153,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .badge.banned<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);<?php 
<?php color:<?php white;<?php 
<?php box-shadow:<?php 0<?php 2px<?php 8px<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .user-info<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .info-item<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php padding:<?php 0.5rem<?php 0;<?php 
<?php }<?php 
<?php 
<?php .info-icon<?php {<?php 
<?php width:<?php 20px;<?php 
<?php color:<?php #22c55e;<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php .whatsapp-link<?php {<?php 
<?php color:<?php #25d366;<?php 
<?php margin-left:<?php 0.5rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php padding:<?php 0.25rem;<?php 
<?php border-radius:<?php 6px;<?php 
<?php }<?php 
<?php 
<?php .whatsapp-link:hover<?php {<?php 
<?php color:<?php #128c7e;<?php 
<?php background:<?php rgba(37,<?php 211,<?php 102,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php /*<?php Action<?php Buttons<?php */<?php 
<?php .action-buttons<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(140px,<?php 1fr));<?php 
<?php gap:<?php 0.75rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .action-btn<?php {<?php 
<?php padding:<?php 0.75rem<?php 1rem;<?php 
<?php border-radius:<?php 10px;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-decoration:<?php none;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php cursor:<?php pointer;<?php 
<?php border:<?php none;<?php 
<?php }<?php 
<?php 
<?php .action-btn:hover<?php {<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-balance<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #3b82f6,<?php #2563eb);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-balance:hover<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #2563eb,<?php #1d4ed8);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(59,<?php 130,<?php 246,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .btn-ban<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-ban:hover<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #dc2626,<?php #b91c1c);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(239,<?php 68,<?php 68,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .btn-unban<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-unban:hover<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .btn-influencer<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-influencer:hover<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .btn-remove-inf<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-remove-inf:hover<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #d97706,<?php #b45309);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(245,<?php 158,<?php 11,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .user-meta<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php padding-top:<?php 1rem;<?php 
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .user-meta<?php i<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php /*<?php Modal<?php Styles<?php */<?php 
<?php .modal<?php {<?php 
<?php position:<?php fixed;<?php 
<?php inset:<?php 0;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.8);<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php z-index:<?php 2000;<?php 
<?php backdrop-filter:<?php blur(8px);<?php 
<?php opacity:<?php 0;<?php 
<?php visibility:<?php hidden;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .modal.active<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php visibility:<?php visible;<?php 
<?php }<?php 
<?php 
<?php .modal-content<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.95)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.98)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 2.5rem;<?php 
<?php width:<?php 90%;<?php 
<?php max-width:<?php 500px;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 
<?php 0<?php 25px<?php 80px<?php rgba(0,<?php 0,<?php 0,<?php 0.8),<?php 
<?php 0<?php 0<?php 0<?php 1px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php transform:<?php scale(0.9);<?php 
<?php transition:<?php transform<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .modal.active<?php .modal-content<?php {<?php 
<?php transform:<?php scale(1);<?php 
<?php }<?php 
<?php 
<?php .modal-title<?php {<?php 
<?php font-size:<?php 1.75rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php white;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .modal-title<?php i<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 12px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.125rem;<?php 
<?php }<?php 
<?php 
<?php .modal-label<?php {<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .modal-label<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .modal-input<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1rem<?php 1.25rem;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .modal-input:focus<?php {<?php 
<?php outline:<?php none;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.6);<?php 
<?php }<?php 
<?php 
<?php .modal-input::placeholder<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .modal-buttons<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .modal-btn<?php {<?php 
<?php flex:<?php 1;<?php 
<?php padding:<?php 1rem<?php 1.5rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 1rem;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php border:<?php none;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .modal-btn-primary<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .modal-btn-primary:hover<?php {<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .modal-btn-secondary<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);<?php 
<?php color:<?php #e5e7eb;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .modal-btn-secondary:hover<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.4);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php }<?php 
<?php 
<?php /*<?php Empty<?php State<?php */<?php 
<?php .empty-state<?php {<?php 
<?php text-align:<?php center;<?php 
<?php padding:<?php 4rem<?php 2rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.3)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.4)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php border-radius:<?php 20px;<?php 
<?php backdrop-filter:<?php blur(10px);<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php i<?php {<?php 
<?php font-size:<?php 4rem;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php opacity:<?php 0.3;<?php 
<?php color:<?php #374151;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php h3<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #9ca3af;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php p<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 400;<?php 
<?php }<?php 
<?php 
<?php /*<?php Mobile<?php Styles<?php */<?php 
<?php @media<?php (max-width:<?php 1024px)<?php {<?php 
<?php .sidebar<?php {<?php 
<?php transform:<?php translateX(-100%);<?php 
<?php width:<?php 300px;<?php 
<?php z-index:<?php 1001;<?php 
<?php }<?php 
<?php 
<?php .sidebar:not(.hidden)<?php {<?php 
<?php transform:<?php translateX(0);<?php 
<?php }<?php 
<?php 
<?php .main-content<?php {<?php 
<?php margin-left:<?php 0;<?php 
<?php }<?php 
<?php 
<?php .menu-toggle<?php {<?php 
<?php display:<?php block;<?php 
<?php }<?php 
<?php 
<?php .header-actions<?php span<?php {<?php 
<?php display:<?php none<?php !important;<?php 
<?php }<?php 
<?php 
<?php .stats-grid<?php {<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));<?php 
<?php }<?php 
<?php 
<?php .users-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .header<?php {<?php 
<?php padding:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .page-content<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .welcome-title<?php {<?php 
<?php font-size:<?php 2.25rem;<?php 
<?php }<?php 
<?php 
<?php .user-card<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .action-buttons<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php 
<?php .modal-content<?php {<?php 
<?php margin:<?php 1rem;<?php 
<?php padding:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .modal-buttons<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php }<?php 
<?php 
<?php .sidebar<?php {<?php 
<?php width:<?php 280px;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 480px)<?php {<?php 
<?php .welcome-title<?php {<?php 
<?php font-size:<?php 1.875rem;<?php 
<?php }<?php 
<?php 
<?php .stats-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php 
<?php .user-info<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php 
<?php .user-header<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php align-items:<?php flex-start;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .sidebar<?php {<?php 
<?php width:<?php 260px;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Overlay<?php for<?php mobile<?php */<?php 
<?php .overlay<?php {<?php 
<?php position:<?php fixed;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);<?php 
<?php z-index:<?php 1000;<?php 
<?php opacity:<?php 0;<?php 
<?php visibility:<?php hidden;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php backdrop-filter:<?php blur(4px);<?php 
<?php }<?php 
<?php 
<?php .overlay.active<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php visibility:<?php visible;<?php 
<?php }<?php 
<?php </style><?php 
</head><?php 
<body><?php 
<?php <!--<?php Notifications<?php --><?php 
<?php <?php<?php if<?php (isset($_SESSION['success'])):<?php ?><?php 
<?php <script><?php 
<?php Notiflix.Notify.success('<?php=<?php $_SESSION['success']<?php ?>');<?php 
<?php </script><?php 
<?php <?php<?php unset($_SESSION['success']);<?php ?><?php 
<?php <?php<?php elseif<?php (isset($_SESSION['failure'])):<?php ?><?php 
<?php <script><?php 
<?php Notiflix.Notify.failure('<?php=<?php $_SESSION['failure']<?php ?>');<?php 
<?php </script><?php 
<?php <?php<?php unset($_SESSION['failure']);<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php 
<?php <!--<?php Overlay<?php for<?php mobile<?php --><?php 
<?php <div<?php class="overlay"<?php id="overlay"></div><?php 
<?php 
<?php <!--<?php Advanced<?php Sidebar<?php --><?php 
<?php <aside<?php class="sidebar"<?php id="sidebar"><?php 
<?php <div<?php class="sidebar-header"><?php 
<?php <a<?php href="#"<?php class="logo"><?php 
<?php <div<?php class="logo-icon"><?php 
<?php <i<?php class="fas<?php fa-bolt"></i><?php 
<?php </div><?php 
<?php <div<?php class="logo-text"><?php 
<?php <div<?php class="logo-title">Dashboard</div><?php 
<?php </div><?php 
<?php </a><?php 
<?php </div><?php 
<?php 
<?php <nav<?php class="nav-menu"><?php 
<?php <div<?php class="nav-section"><?php 
<?php <div<?php class="nav-section-title">Principal</div><?php 
<?php <a<?php href="index.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-chart-pie"></i></div><?php 
<?php <div<?php class="nav-text">Dashboard</div><?php 
<?php </a><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="nav-section"><?php 
<?php <div<?php class="nav-section-title">Gestão</div><?php 
<?php <a<?php href="usuarios.php"<?php class="nav-item<?php active"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user"></i></div><?php 
<?php <div<?php class="nav-text">Usuários</div><?php 
<?php </a><?php 
<?php <a<?php href="afiliados.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user-plus"></i></div><?php 
<?php <div<?php class="nav-text">Afiliados</div><?php 
<?php </a><?php 
<?php <a<?php href="depositos.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-credit-card"></i></div><?php 
<?php <div<?php class="nav-text">Depósitos</div><?php 
<?php </a><?php 
<?php <a<?php href="saques.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-money-bill-wave"></i></div><?php 
<?php <div<?php class="nav-text">Saques</div><?php 
<?php </a><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="nav-section"><?php 
<?php <div<?php class="nav-section-title">Sistema</div><?php 
<?php <a<?php href="config.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-cogs"></i></div><?php 
<?php <div<?php class="nav-text">Configurações</div><?php 
<?php </a><?php 
<?php <a<?php href="gateway.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-usd"></i></div><?php 
<?php <div<?php class="nav-text">Gateway</div><?php 
<?php </a><?php 
<?php <a<?php href="banners.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-images"></i></div><?php 
<?php <div<?php class="nav-text">Banners</div><?php 
<?php </a><?php 
<?php <a<?php href="cartelas.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-diamond"></i></div><?php 
<?php <div<?php class="nav-text">Raspadinhas</div><?php 
<?php </a><?php 
<?php <a<?php href="../logout"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-sign-out-alt"></i></div><?php 
<?php <div<?php class="nav-text">Sair</div><?php 
<?php </a><?php 
<?php </div><?php 
<?php </nav><?php 
<?php </aside><?php 
<?php 
<?php <!--<?php Main<?php Content<?php --><?php 
<?php <main<?php class="main-content"<?php id="mainContent"><?php 
<?php <!--<?php Enhanced<?php Header<?php --><?php 
<?php <header<?php class="header"><?php 
<?php <div<?php class="header-content"><?php 
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 1rem;"><?php 
<?php <button<?php class="menu-toggle"<?php id="menuToggle"><?php 
<?php <i<?php class="fas<?php fa-bars"></i><?php 
<?php </button><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="header-actions"><?php 
<?php <span<?php style="color:<?php #a1a1aa;<?php font-size:<?php 0.9rem;<?php display:<?php none;">Bem-vindo,<?php <?php=<?php htmlspecialchars($nome)<?php ?></span><?php 
<?php <div<?php class="user-avatar"><?php 
<?php <?php=<?php strtoupper(substr($nome,<?php 0,<?php 1))<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </header><?php 
<?php 
<?php <!--<?php Page<?php Content<?php --><?php 
<?php <div<?php class="page-content"><?php 
<?php <!--<?php Welcome<?php Section<?php --><?php 
<?php <section<?php class="welcome-section"><?php 
<?php <h2<?php class="welcome-title">Usuários<?php do<?php Sistema</h2><?php 
<?php <p<?php class="welcome-subtitle">Gerencie<?php todos<?php os<?php usuários<?php cadastrados<?php na<?php plataforma</p><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Stats<?php Grid<?php --><?php 
<?php <section<?php class="stats-grid"><?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-users"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_usuarios,<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Total<?php de<?php Usuários</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-user-check"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format(count($usuarios_ativos),<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Usuários<?php Ativos</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-star"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format(count($influencers),<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Influencers</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-wallet"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($total_saldo,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Saldo<?php Total</div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Search<?php Section<?php --><?php 
<?php <section<?php class="search-section"><?php 
<?php <div<?php class="search-header"><?php 
<?php <div<?php class="search-icon-container"><?php 
<?php <i<?php class="fas<?php fa-search"></i><?php 
<?php </div><?php 
<?php <h3<?php class="search-title">Buscar<?php Usuários</h3><?php 
<?php </div><?php 
<?php 
<?php <form<?php method="GET"><?php 
<?php <div<?php class="search-container"><?php 
<?php <i<?php class="fa-solid<?php fa-search<?php search-icon"></i><?php 
<?php <input<?php type="text"<?php name="search"<?php value="<?php=<?php htmlspecialchars($search)<?php ?>"<?php 
<?php class="search-input"<?php 
<?php placeholder="Pesquisar<?php por<?php nome,<?php email<?php ou<?php telefone..."<?php 
<?php onchange="this.form.submit()"><?php 
<?php </div><?php 
<?php </form><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Users<?php Section<?php --><?php 
<?php <section><?php 
<?php <?php<?php if<?php (empty($usuarios)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="fas<?php fa-users"></i><?php 
<?php <h3>Nenhum<?php usuário<?php encontrado</h3><?php 
<?php <p>Tente<?php ajustar<?php os<?php filtros<?php de<?php busca<?php ou<?php verificar<?php se<?php há<?php usuários<?php cadastrados</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="users-grid"><?php 
<?php <?php<?php foreach<?php ($usuarios<?php as<?php $usuario):<?php ?><?php 
<?php <?php<?php 
<?php $telefone<?php =<?php $usuario['telefone'];<?php 
<?php if<?php (strlen($telefone)<?php ==<?php 11)<?php {<?php 
<?php $telefoneFormatado<?php =<?php '('.substr($telefone,<?php 0,<?php 2).')<?php '.substr($telefone,<?php 2,<?php 5).'-'.substr($telefone,<?php 7);<?php 
<?php }<?php else<?php {<?php 
<?php $telefoneFormatado<?php =<?php $telefone;<?php 
<?php }<?php 
<?php 
<?php $whatsappLink<?php =<?php 'https://wa.me/55'.preg_replace('/[^0-9]/',<?php '',<?php $usuario['telefone']);<?php 
<?php ?><?php 
<?php 
<?php <div<?php class="user-card"><?php 
<?php <div<?php class="user-header"><?php 
<?php <div><?php 
<?php <h3<?php class="user-name"><?php=<?php htmlspecialchars($usuario['nome'])<?php ?></h3><?php 
<?php <div<?php class="user-badges"><?php 
<?php <?php<?php if<?php ($usuario['admin']<?php ==<?php 1):<?php ?><?php 
<?php <span<?php class="badge<?php admin">Admin</span><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php <?php<?php if<?php ($usuario['influencer']<?php ==<?php 1):<?php ?><?php 
<?php <span<?php class="badge<?php influencer">Influencer</span><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php <?php<?php if<?php ($usuario['banido']<?php ==<?php 1):<?php ?><?php 
<?php <span<?php class="badge<?php banned">Banido</span><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="user-info"><?php 
<?php <div<?php class="info-item"><?php 
<?php <i<?php class="fa-solid<?php fa-envelope<?php info-icon"></i><?php 
<?php <span><?php=<?php htmlspecialchars($usuario['email'])<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="info-item"><?php 
<?php <i<?php class="fa-solid<?php fa-phone<?php info-icon"></i><?php 
<?php <span><?php=<?php $telefoneFormatado<?php ?></span><?php 
<?php <a<?php href="<?php=<?php $whatsappLink<?php ?>"<?php target="_blank"<?php class="whatsapp-link"><?php 
<?php <i<?php class="fa-brands<?php fa-whatsapp"></i><?php 
<?php </a><?php 
<?php </div><?php 
<?php <div<?php class="info-item"><?php 
<?php <i<?php class="fa-solid<?php fa-wallet<?php info-icon"></i><?php 
<?php <span>R$<?php <?php=<?php number_format($usuario['saldo'],<?php 2,<?php ',',<?php '.')<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="info-item"><?php 
<?php <i<?php class="fa-solid<?php fa-user-plus<?php info-icon"></i><?php 
<?php <span>Indicado<?php por:<?php <?php=<?php $usuario['email_indicador']<?php ?<?php htmlspecialchars($usuario['email_indicador'])<?php :<?php 'Ninguém'<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="action-buttons"><?php 
<?php <button<?php onclick="abrirModalEditarSaldo('<?php=<?php $usuario['id']<?php ?>',<?php '<?php=<?php number_format($usuario['saldo'],<?php 2,<?php '.',<?php '')<?php ?>')"<?php 
<?php class="action-btn<?php btn-balance"><?php 
<?php <i<?php class="fa-solid<?php fa-edit"></i><?php 
<?php Editar<?php Saldo<?php 
<?php </button><?php 
<?php 
<?php <a<?php href="?toggle_banido&id=<?php=<?php $usuario['id']<?php ?>"<?php 
<?php class="action-btn<?php <?php=<?php $usuario['banido']<?php ?<?php 'btn-unban'<?php :<?php 'btn-ban'<?php ?>"><?php 
<?php <i<?php class="fa-solid<?php fa-<?php=<?php $usuario['banido']<?php ?<?php 'user-check'<?php :<?php 'user-slash'<?php ?>"></i><?php 
<?php <?php=<?php $usuario['banido']<?php ?<?php 'Desbanir'<?php :<?php 'Banir'<?php ?><?php 
<?php </a><?php 
<?php 
<?php <a<?php href="?toggle_influencer&id=<?php=<?php $usuario['id']<?php ?>"<?php 
<?php class="action-btn<?php <?php=<?php $usuario['influencer']<?php ?<?php 'btn-remove-inf'<?php :<?php 'btn-influencer'<?php ?>"><?php 
<?php <i<?php class="fa-solid<?php fa-<?php=<?php $usuario['influencer']<?php ?<?php 'user-minus'<?php :<?php 'star'<?php ?>"></i><?php 
<?php <?php=<?php $usuario['influencer']<?php ?<?php 'Remover<?php Inf.'<?php :<?php 'Tornar<?php Inf.'<?php ?><?php 
<?php </a><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="user-meta"><?php 
<?php <i<?php class="fa-solid<?php fa-calendar"></i><?php 
<?php <span>Cadastrado<?php em:<?php <?php=<?php date('d/m/Y<?php H:i',<?php strtotime($usuario['created_at']))<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </section><?php 
<?php </div><?php 
<?php </main><?php 
<?php 
<?php <!--<?php Modal<?php Editar<?php Saldo<?php --><?php 
<?php <div<?php id="editarSaldoModal"<?php class="modal"><?php 
<?php <div<?php class="modal-content"><?php 
<?php <h2<?php class="modal-title"><?php 
<?php <i<?php class="fa-solid<?php fa-wallet"></i><?php 
<?php Editar<?php Saldo<?php do<?php Usuário<?php 
<?php </h2><?php 
<?php <form<?php method="POST"<?php id="formEditarSaldo"><?php 
<?php <input<?php type="hidden"<?php name="id"<?php id="usuarioId"><?php 
<?php <div><?php 
<?php <label<?php class="modal-label"><?php 
<?php <i<?php class="fa-solid<?php fa-dollar-sign"></i><?php 
<?php Novo<?php Saldo<?php (R$)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="saldo"<?php id="usuarioSaldo"<?php class="modal-input"<?php 
<?php placeholder="0,00"<?php required><?php 
<?php </div><?php 
<?php <div<?php class="modal-buttons"><?php 
<?php <button<?php type="submit"<?php name="atualizar_saldo"<?php class="modal-btn<?php modal-btn-primary"><?php 
<?php <i<?php class="fa-solid<?php fa-save"></i><?php 
<?php Salvar<?php Alterações<?php 
<?php </button><?php 
<?php <button<?php type="button"<?php onclick="fecharModal()"<?php class="modal-btn<?php modal-btn-secondary"><?php 
<?php <i<?php class="fa-solid<?php fa-times"></i><?php 
<?php Cancelar<?php 
<?php </button><?php 
<?php </div><?php 
<?php </form><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <script><?php 
<?php //<?php Mobile<?php menu<?php toggle<?php with<?php smooth<?php animations<?php 
<?php const<?php menuToggle<?php =<?php document.getElementById('menuToggle');<?php 
<?php const<?php sidebar<?php =<?php document.getElementById('sidebar');<?php 
<?php const<?php mainContent<?php =<?php document.getElementById('mainContent');<?php 
<?php const<?php overlay<?php =<?php document.getElementById('overlay');<?php 
<?php 
<?php menuToggle.addEventListener('click',<?php ()<?php =><?php {<?php 
<?php const<?php isHidden<?php =<?php sidebar.classList.contains('hidden');<?php 
<?php 
<?php if<?php (isHidden)<?php {<?php 
<?php sidebar.classList.remove('hidden');<?php 
<?php overlay.classList.add('active');<?php 
<?php }<?php else<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php overlay.classList.add('active');<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php overlay.addEventListener('click',<?php ()<?php =><?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php overlay.classList.remove('active');<?php 
<?php });<?php 
<?php 
<?php //<?php Close<?php sidebar<?php on<?php window<?php resize<?php if<?php it's<?php mobile<?php 
<?php window.addEventListener('resize',<?php ()<?php =><?php {<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php overlay.classList.remove('active');<?php 
<?php }<?php else<?php {<?php 
<?php sidebar.classList.remove('hidden');<?php 
<?php overlay.classList.remove('active');<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Enhanced<?php hover<?php effects<?php for<?php nav<?php items<?php 
<?php document.querySelectorAll('.nav-item').forEach(item<?php =><?php {<?php 
<?php item.addEventListener('mouseenter',<?php function()<?php {<?php 
<?php this.style.transform<?php =<?php 'translateX(8px)';<?php 
<?php });<?php 
<?php 
<?php item.addEventListener('mouseleave',<?php function()<?php {<?php 
<?php if<?php (!this.classList.contains('active'))<?php {<?php 
<?php this.style.transform<?php =<?php 'translateX(0)';<?php 
<?php }<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Modal<?php functions<?php 
<?php function<?php abrirModalEditarSaldo(id,<?php saldo)<?php {<?php 
<?php document.getElementById('usuarioId').value<?php =<?php id;<?php 
<?php document.getElementById('usuarioSaldo').value<?php =<?php saldo;<?php 
<?php document.getElementById('editarSaldoModal').classList.add('active');<?php 
<?php }<?php 
<?php 
<?php function<?php fecharModal()<?php {<?php 
<?php document.getElementById('editarSaldoModal').classList.remove('active');<?php 
<?php }<?php 
<?php 
<?php //<?php Close<?php modal<?php when<?php clicking<?php outside<?php 
<?php document.getElementById('editarSaldoModal').addEventListener('click',<?php function(e)<?php {<?php 
<?php if<?php (e.target<?php ===<?php this)<?php {<?php 
<?php fecharModal();<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Close<?php modal<?php with<?php ESC<?php key<?php 
<?php document.addEventListener('keydown',<?php function(e)<?php {<?php 
<?php if<?php (e.key<?php ===<?php 'Escape')<?php {<?php 
<?php fecharModal();<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Smooth<?php scroll<?php behavior<?php 
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';<?php 
<?php 
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {<?php 
<?php console.log('%c👥<?php Gerenciamento<?php de<?php Usuários<?php carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php }<?php 
<?php 
<?php //<?php Animate<?php cards<?php on<?php load<?php 
<?php const<?php userCards<?php =<?php document.querySelectorAll('.user-card');<?php 
<?php userCards.forEach((card,<?php index)<?php =><?php {<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';<?php 
<?php card.style.opacity<?php =<?php '1';<?php 
<?php card.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php index<?php *<?php 100);<?php 
<?php });<?php 
<?php 
<?php //<?php Animate<?php stats<?php cards<?php 
<?php const<?php statCards<?php =<?php document.querySelectorAll('.mini-stat-card');<?php 
<?php statCards.forEach((card,<?php index)<?php =><?php {<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';<?php 
<?php card.style.opacity<?php =<?php '1';<?php 
<?php card.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php index<?php *<?php 150);<?php 
<?php });<?php 
<?php });<?php 
<?php </script><?php 
</body><?php 
</html>