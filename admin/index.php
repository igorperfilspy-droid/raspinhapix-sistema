<?php<?php 
include<?php '../includes/session.php';<?php 
include<?php '../conexao.php';<?php 
include<?php '../includes/notiflix.php';<?php 
<?php 
$usuarioId<?php =<?php $_SESSION['usuario_id'];<?php 
$admin<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php admin<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;<?php 
<?php 
if(<?php $admin<?php !=<?php 1){<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php não<?php é<?php um<?php administrador!'];<?php 
<?php header("Location:<?php /");<?php 
<?php exit;<?php 
}<?php 
<?php 
$nome<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php nome<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;<?php 
$nome<?php =<?php $nome<?php ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;<?php 
<?php 
$total_usuarios<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php FROM<?php usuarios"))->execute()<?php ?<?php $stmt->fetchColumn()<?php :<?php 0;<?php 
$total_depositos<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php FROM<?php depositos<?php WHERE<?php status<?php =<?php 1"))->execute()<?php ?<?php $stmt->fetchColumn()<?php :<?php 0;<?php 
$total_saldo<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php SUM(saldo)<?php FROM<?php usuarios"))->execute()<?php ?<?php $stmt->fetchColumn()<?php :<?php 0;<?php 
<?php 
$sql<?php =<?php "<?php 
<?php SELECT<?php 
<?php u.nome,<?php 
<?php d.valor,<?php 
<?php d.updated_at<?php 
<?php FROM<?php 
<?php depositos<?php d<?php 
<?php INNER<?php JOIN<?php 
<?php usuarios<?php u<?php ON<?php d.user_id<?php =<?php u.id<?php 
<?php WHERE<?php 
<?php d.status<?php =<?php 'PAID'<?php 
<?php ORDER<?php BY<?php 
<?php d.updated_at<?php DESC<?php 
<?php LIMIT<?php 5<?php 
";<?php 
<?php 
$stmt<?php =<?php $pdo->prepare($sql);<?php 
$stmt->execute();<?php 
<?php 
$depositos_recentes<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
$sql<?php =<?php "<?php 
<?php SELECT<?php 
<?php u.nome,<?php 
<?php s.valor,<?php 
<?php s.updated_at<?php 
<?php FROM<?php 
<?php saques<?php s<?php 
<?php INNER<?php JOIN<?php 
<?php usuarios<?php u<?php ON<?php s.user_id<?php =<?php u.id<?php 
<?php WHERE<?php 
<?php s.status<?php =<?php 'PENDING'<?php 
<?php ORDER<?php BY<?php 
<?php s.updated_at<?php DESC<?php 
<?php LIMIT<?php 5<?php 
";<?php 
<?php 
$stmt<?php =<?php $pdo->prepare($sql);<?php 
$stmt->execute();<?php 
<?php 
$saques_recentes<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Dashboard<?php Administrativo</title><?php 
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
<?php .notification-btn<?php {<?php 
<?php position:<?php relative;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php color:<?php #22c55e;<?php 
<?php padding:<?php 0.75rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-size:<?php 1rem;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .notification-btn:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php transform:<?php scale(1.05);<?php 
<?php }<?php 
<?php 
<?php .notification-badge<?php {<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php -4px;<?php 
<?php right:<?php -4px;<?php 
<?php background:<?php #ef4444;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 0.6rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php padding:<?php 0.2rem<?php 0.4rem;<?php 
<?php border-radius:<?php 6px;<?php 
<?php min-width:<?php 16px;<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php /*<?php Enhanced<?php Stats<?php Cards<?php */<?php 
<?php .dashboard-content<?php {<?php 
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
<?php .stats-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(350px,<?php 1fr));<?php 
<?php gap:<?php 2rem;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
<?php .stat-card<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2.5rem;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php transition:<?php all<?php 0.4s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php }<?php 
<?php 
<?php .stat-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 4px;<?php 
<?php background:<?php linear-gradient(90deg,<?php #22c55e,<?php #16a34a,<?php #22c55e);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .stat-card::after<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 50%;<?php 
<?php right:<?php -50px;<?php 
<?php width:<?php 200px;<?php 
<?php height:<?php 200px;<?php 
<?php background:<?php radial-gradient(circle,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 70%);<?php 
<?php transform:<?php translateY(-50%);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .stat-card:hover::before,<?php 
<?php .stat-card:hover::after<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .stat-card:hover<?php {<?php 
<?php transform:<?php translateY(-8px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php box-shadow:<?php 
<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.4),<?php 
<?php 0<?php 0<?php 40px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .stat-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php space-between;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .stat-icon<?php {<?php 
<?php width:<?php 64px;<?php 
<?php height:<?php 64px;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);<?php 
<?php border:<?php 2px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 16px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php position:<?php relative;<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .stat-value<?php {<?php 
<?php font-size:<?php 2.75rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php line-height:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .stat-label<?php {<?php 
<?php color:<?php #a1a1aa;<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php }<?php 
<?php 
<?php .stat-trend<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php /*<?php Enhanced<?php Activity<?php Cards<?php */<?php 
<?php .activity-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(450px,<?php 1fr));<?php 
<?php gap:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .activity-card<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2.5rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .activity-card::before<?php {<?php 
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
<?php .activity-card:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .activity-card:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .activity-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php padding-bottom:<?php 1rem;<?php 
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .activity-icon<?php {<?php 
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
<?php .activity-title<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php }<?php 
<?php 
<?php .activity-item<?php {<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.02);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .activity-item::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php left:<?php 0;<?php 
<?php top:<?php 0;<?php 
<?php bottom:<?php 0;<?php 
<?php width:<?php 3px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .activity-item:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .activity-item:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php transform:<?php translateX(4px);<?php 
<?php }<?php 
<?php 
<?php .activity-item:last-child<?php {<?php 
<?php margin-bottom:<?php 0;<?php 
<?php }<?php 
<?php 
<?php .activity-item-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .activity-name<?php {<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .activity-value<?php {<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
<?php 
<?php .activity-meta<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .activity-status<?php {<?php 
<?php display:<?php inline-flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.25rem;<?php 
<?php padding:<?php 0.25rem<?php 0.75rem;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 6px;<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .status-dot<?php {<?php 
<?php width:<?php 6px;<?php 
<?php height:<?php 6px;<?php 
<?php background:<?php #22c55e;<?php 
<?php border-radius:<?php 50%;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php {<?php 
<?php text-align:<?php center;<?php 
<?php padding:<?php 4rem<?php 2rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php i<?php {<?php 
<?php font-size:<?php 3rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php opacity:<?php 0.3;<?php 
<?php color:<?php #374151;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php p<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 500;<?php 
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
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .activity-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .header<?php {<?php 
<?php padding:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .dashboard-content<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .welcome-title<?php {<?php 
<?php font-size:<?php 2.25rem;<?php 
<?php }<?php 
<?php 
<?php .stat-card,<?php 
<?php .activity-card<?php {<?php 
<?php padding:<?php 2rem;<?php 
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
<?php .stat-value<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .activity-item<?php {<?php 
<?php padding:<?php 1.25rem;<?php 
<?php }<?php 
<?php 
<?php .activity-item-header<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php align-items:<?php flex-start;<?php 
<?php gap:<?php 0.5rem;<?php 
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
<?php <!--<?php Overlay<?php for<?php mobile<?php --><?php 
<?php <div<?php class="overlay"<?php id="overlay"></div><?php 
<?php 
<?php <!--<?php Advanced<?php Sidebar<?php --><?php 
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
<?php <a<?php href="index.php"<?php class="nav-item<?php active"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-chart-pie"></i></div><?php 
<?php <div<?php class="nav-text">Dashboard</div><?php 
<?php </a><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="nav-section"><?php 
<?php <div<?php class="nav-section-title">Gestão</div><?php 
<?php <a<?php href="usuarios.php"<?php class="nav-item"><?php 
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
<?php <!--<?php Dashboard<?php Content<?php --><?php 
<?php <div<?php class="dashboard-content"><?php 
<?php <!--<?php Welcome<?php Section<?php --><?php 
<?php <section<?php class="welcome-section"><?php 
<?php <h2<?php class="welcome-title">Olá,<?php <?php=<?php htmlspecialchars($nome)<?php ?>!</h2><?php 
<?php <p<?php class="welcome-subtitle">Aqui<?php está<?php um<?php resumo<?php das<?php principais<?php métricas<?php e<?php atividades<?php do<?php sistema</p><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Enhanced<?php Stats<?php Grid<?php --><?php 
<?php <section<?php class="stats-grid"><?php 
<?php <div<?php class="stat-card"><?php 
<?php <div<?php class="stat-header"><?php 
<?php <div<?php class="stat-icon"><?php 
<?php <i<?php class="fas<?php fa-users"></i><?php 
<?php </div><?php 
<?php <div<?php class="stat-trend"><?php 
<?php <i<?php class="fas<?php fa-arrow-up"></i><?php 
<?php <span>+12%</span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="stat-value"><?php=<?php number_format($total_usuarios,<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="stat-label">Total<?php de<?php Usuários<?php Ativos</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="stat-card"><?php 
<?php <div<?php class="stat-header"><?php 
<?php <div<?php class="stat-icon"><?php 
<?php <i<?php class="fas<?php fa-chart-line"></i><?php 
<?php </div><?php 
<?php <div<?php class="stat-trend"><?php 
<?php <i<?php class="fas<?php fa-arrow-up"></i><?php 
<?php <span>+8%</span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="stat-value"><?php=<?php number_format($total_depositos,<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="stat-label">Depósitos<?php Confirmados</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="stat-card"><?php 
<?php <div<?php class="stat-header"><?php 
<?php <div<?php class="stat-icon"><?php 
<?php <i<?php class="fas<?php fa-wallet"></i><?php 
<?php </div><?php 
<?php <div<?php class="stat-trend"><?php 
<?php <i<?php class="fas<?php fa-arrow-up"></i><?php 
<?php <span>+24%</span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($total_saldo,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="stat-label">Saldo<?php Total<?php em<?php Carteiras</div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Enhanced<?php Activity<?php Section<?php --><?php 
<?php <section<?php class="activity-grid"><?php 
<?php <!--<?php Recent<?php Deposits<?php --><?php 
<?php <div<?php class="activity-card"><?php 
<?php <div<?php class="activity-header"><?php 
<?php <div<?php class="activity-icon"><?php 
<?php <i<?php class="fas<?php fa-money-bill-transfer"></i><?php 
<?php </div><?php 
<?php <h3<?php class="activity-title">Depósitos<?php Recentes</h3><?php 
<?php </div><?php 
<?php 
<?php <?php<?php if<?php (!empty($depositos_recentes)):<?php ?><?php 
<?php <?php<?php foreach<?php ($depositos_recentes<?php as<?php $deposito):<?php ?><?php 
<?php <div<?php class="activity-item"><?php 
<?php <div<?php class="activity-item-header"><?php 
<?php <span<?php class="activity-name"><?php=<?php htmlspecialchars($deposito['nome'])<?php ?></span><?php 
<?php <span<?php class="activity-value">R$<?php <?php=<?php number_format($deposito['valor'],<?php 2,<?php ',',<?php '.')<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="activity-meta"><?php 
<?php <div<?php class="activity-status"><?php 
<?php <div<?php class="status-dot"></div><?php 
<?php <span>Confirmado</span><?php 
<?php </div><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($deposito['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="fas<?php fa-inbox"></i><?php 
<?php <p>Nenhum<?php depósito<?php confirmado<?php recentemente</p><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Pending<?php Withdrawals<?php --><?php 
<?php <div<?php class="activity-card"><?php 
<?php <div<?php class="activity-header"><?php 
<?php <div<?php class="activity-icon"><?php 
<?php <i<?php class="fas<?php fa-money-bill-wave"></i><?php 
<?php </div><?php 
<?php <h3<?php class="activity-title">Saques<?php Pendentes</h3><?php 
<?php </div><?php 
<?php 
<?php <?php<?php if<?php (!empty($saques_recentes)):<?php ?><?php 
<?php <?php<?php foreach<?php ($saques_recentes<?php as<?php $saque):<?php ?><?php 
<?php <div<?php class="activity-item"><?php 
<?php <div<?php class="activity-item-header"><?php 
<?php <span<?php class="activity-name"><?php=<?php htmlspecialchars($saque['nome'])<?php ?></span><?php 
<?php <span<?php class="activity-value">R$<?php <?php=<?php number_format($saque['valor'],<?php 2,<?php ',',<?php '.')<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="activity-meta"><?php 
<?php <div<?php class="activity-status"<?php style="background:<?php rgba(251,<?php 191,<?php 36,<?php 0.1);<?php border-color:<?php rgba(251,<?php 191,<?php 36,<?php 0.2);<?php color:<?php #f59e0b;"><?php 
<?php <div<?php class="status-dot"<?php style="background:<?php #f59e0b;"></div><?php 
<?php <span>Pendente</span><?php 
<?php </div><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($saque['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="fas<?php fa-check-circle"></i><?php 
<?php <p>Nenhum<?php saque<?php pendente<?php no<?php momento</p><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </section><?php 
<?php </div><?php 
<?php </main><?php 
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
<?php overlay.classList.remove('active');<?php 
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
<?php //<?php Smooth<?php scroll<?php behavior<?php 
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';<?php 
<?php 
<?php //<?php Add<?php loading<?php animation<?php on<?php page<?php load<?php 
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {<?php 
<?php console.log('%c⚡<?php Dashboard<?php Admin<?php Pro<?php carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php }<?php 
<?php 
<?php //<?php Animate<?php stats<?php cards<?php on<?php load<?php 
<?php const<?php statCards<?php =<?php document.querySelectorAll('.stat-card');<?php 
<?php statCards.forEach((card,<?php index)<?php =><?php {<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';<?php 
<?php card.style.opacity<?php =<?php '1';<?php 
<?php card.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php index<?php *<?php 150);<?php 
<?php });<?php 
<?php 
<?php //<?php Animate<?php activity<?php cards<?php 
<?php const<?php activityCards<?php =<?php document.querySelectorAll('.activity-card');<?php 
<?php activityCards.forEach((card,<?php index)<?php =><?php {<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';<?php 
<?php card.style.opacity<?php =<?php '1';<?php 
<?php card.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php (statCards.length<?php *<?php 150)<?php +<?php (index<?php *<?php 200));<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Add<?php click<?php ripple<?php effect<?php to<?php cards<?php 
<?php document.querySelectorAll('.stat-card,<?php .activity-card').forEach(card<?php =><?php {<?php 
<?php card.addEventListener('click',<?php function(e)<?php {<?php 
<?php const<?php ripple<?php =<?php document.createElement('div');<?php 
<?php const<?php rect<?php =<?php this.getBoundingClientRect();<?php 
<?php const<?php size<?php =<?php 60;<?php 
<?php const<?php x<?php =<?php e.clientX<?php -<?php rect.left<?php -<?php size<?php /<?php 2;<?php 
<?php const<?php y<?php =<?php e.clientY<?php -<?php rect.top<?php -<?php size<?php /<?php 2;<?php 
<?php 
<?php ripple.style.width<?php =<?php ripple.style.height<?php =<?php size<?php +<?php 'px';<?php 
<?php ripple.style.left<?php =<?php x<?php +<?php 'px';<?php 
<?php ripple.style.top<?php =<?php y<?php +<?php 'px';<?php 
<?php ripple.style.position<?php =<?php 'absolute';<?php 
<?php ripple.style.background<?php =<?php 'rgba(34,<?php 197,<?php 94,<?php 0.3)';<?php 
<?php ripple.style.borderRadius<?php =<?php '50%';<?php 
<?php ripple.style.transform<?php =<?php 'scale(0)';<?php 
<?php ripple.style.animation<?php =<?php 'ripple<?php 0.6s<?php linear';<?php 
<?php ripple.style.pointerEvents<?php =<?php 'none';<?php 
<?php 
<?php this.style.position<?php =<?php 'relative';<?php 
<?php this.style.overflow<?php =<?php 'hidden';<?php 
<?php this.appendChild(ripple);<?php 
<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php ripple.remove();<?php 
<?php },<?php 600);<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Add<?php CSS<?php animation<?php for<?php ripple<?php effect<?php 
<?php const<?php style<?php =<?php document.createElement('style');<?php 
<?php style.textContent<?php =<?php `<?php 
<?php @keyframes<?php ripple<?php {<?php 
<?php to<?php {<?php 
<?php transform:<?php scale(4);<?php 
<?php opacity:<?php 0;<?php 
<?php }<?php 
<?php }<?php 
<?php `;<?php 
<?php document.head.appendChild(style);<?php 
<?php </script><?php 
</body><?php 
</html>