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
$nome<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php nome<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;<?php 
$nome<?php =<?php $nome<?php ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;<?php 
<?php 
$stmt<?php =<?php $pdo->query("SELECT<?php depositos.id,<?php depositos.user_id,<?php depositos.transactionId,<?php depositos.valor,<?php depositos.status,<?php depositos.updated_at,<?php usuarios.nome<?php 
<?php FROM<?php depositos<?php 
<?php JOIN<?php usuarios<?php ON<?php depositos.user_id<?php =<?php usuarios.id<?php 
<?php ORDER<?php BY<?php depositos.updated_at<?php DESC");<?php 
$depositos<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
//<?php Calculate<?php statistics<?php 
$total_depositos<?php =<?php count($depositos);<?php 
$depositos_aprovados<?php =<?php array_filter($depositos,<?php function($d)<?php {<?php return<?php $d['status']<?php ==<?php 'PAID';<?php });<?php 
$depositos_pendentes<?php =<?php array_filter($depositos,<?php function($d)<?php {<?php return<?php $d['status']<?php !=<?php 'PAID';<?php });<?php 
$valor_total_aprovado<?php =<?php array_sum(array_column($depositos_aprovados,<?php 'valor'));<?php 
$valor_total_pendente<?php =<?php array_sum(array_column($depositos_pendentes,<?php 'valor'));<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Gerenciar<?php Depósitos</title><?php 
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
<?php .mini-stat-icon.warning<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2)<?php 0%,<?php rgba(251,<?php 191,<?php 36,<?php 0.1)<?php 100%);<?php 
<?php border-color:<?php rgba(251,<?php 191,<?php 36,<?php 0.3);<?php 
<?php color:<?php #f59e0b;<?php 
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
<?php /*<?php Filter<?php Section<?php */<?php 
<?php .filter-section<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php }<?php 
<?php 
<?php .filter-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .filter-icon-container<?php {<?php 
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
<?php .filter-title<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php }<?php 
<?php 
<?php .filter-buttons<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 1rem;<?php 
<?php flex-wrap:<?php wrap;<?php 
<?php }<?php 
<?php 
<?php .filter-btn<?php {<?php 
<?php padding:<?php 0.75rem<?php 1.5rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php color:<?php #a1a1aa;<?php 
<?php }<?php 
<?php 
<?php .filter-btn.active,<?php 
<?php .filter-btn:hover<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 4px<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php /*<?php Deposit<?php Cards<?php */<?php 
<?php .deposits-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(400px,<?php 1fr));<?php 
<?php gap:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .deposit-card<?php {<?php 
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
<?php .deposit-card::before<?php {<?php 
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
<?php .deposit-card:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .deposit-card:hover<?php {<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .deposit-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php flex-start;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .deposit-user<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .deposit-transaction<?php {<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php font-family:<?php 'Monaco',<?php 'Consolas',<?php monospace;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php padding:<?php 0.25rem<?php 0.5rem;<?php 
<?php border-radius:<?php 6px;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .deposit-status<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php padding:<?php 0.5rem<?php 1rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.5px;<?php 
<?php }<?php 
<?php 
<?php .deposit-status.approved<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(34,<?php 197,<?php 94,<?php 0.1));<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .deposit-status.pending<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2),<?php rgba(251,<?php 191,<?php 36,<?php 0.1));<?php 
<?php border:<?php 1px<?php solid<?php rgba(251,<?php 191,<?php 36,<?php 0.3);<?php 
<?php color:<?php #f59e0b;<?php 
<?php }<?php 
<?php 
<?php .status-dot<?php {<?php 
<?php width:<?php 8px;<?php 
<?php height:<?php 8px;<?php 
<?php border-radius:<?php 50%;<?php 
<?php background:<?php currentColor;<?php 
<?php }<?php 
<?php 
<?php .deposit-value<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php -webkit-background-clip:<?php text;<?php 
<?php -webkit-text-fill-color:<?php transparent;<?php 
<?php background-clip:<?php text;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .deposit-date<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php padding-top:<?php 1rem;<?php 
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .deposit-date<?php i<?php {<?php 
<?php color:<?php #6b7280;<?php 
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
<?php .deposits-grid<?php {<?php 
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
<?php .deposit-card<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .filter-buttons<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php }<?php 
<?php 
<?php .filter-btn<?php {<?php 
<?php text-align:<?php center;<?php 
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
<?php .deposit-header<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php align-items:<?php flex-start;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .deposit-value<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
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
<?php <a<?php href="usuarios.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user"></i></div><?php 
<?php <div<?php class="nav-text">Usuários</div><?php 
<?php </a><?php 
<?php <a<?php href="afiliados.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-user-plus"></i></div><?php 
<?php <div<?php class="nav-text">Afiliados</div><?php 
<?php </a><?php 
<?php <a<?php href="depositos.php"<?php class="nav-item<?php active"><?php 
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
<?php <h2<?php class="welcome-title">Controle<?php de<?php Depósitos</h2><?php 
<?php <p<?php class="welcome-subtitle">Monitore<?php e<?php gerencie<?php todos<?php os<?php depósitos<?php realizados<?php na<?php plataforma</p><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Stats<?php Grid<?php --><?php 
<?php <section<?php class="stats-grid"><?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-receipt"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_depositos,<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Total<?php de<?php Depósitos</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-check-circle"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format(count($depositos_aprovados),<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Depósitos<?php Aprovados</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon<?php warning"><?php 
<?php <i<?php class="fas<?php fa-clock"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format(count($depositos_pendentes),<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Depósitos<?php Pendentes</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-dollar-sign"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($valor_total_aprovado,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Valor<?php Total<?php Aprovado</div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Filter<?php Section<?php --><?php 
<?php <section<?php class="filter-section"><?php 
<?php <div<?php class="filter-header"><?php 
<?php <div<?php class="filter-icon-container"><?php 
<?php <i<?php class="fas<?php fa-filter"></i><?php 
<?php </div><?php 
<?php <h3<?php class="filter-title">Filtrar<?php Depósitos</h3><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="filter-buttons"><?php 
<?php <button<?php class="filter-btn<?php active"<?php onclick="filterDeposits('all')"><?php 
<?php <i<?php class="fas<?php fa-list"></i><?php 
<?php Todos<?php os<?php Depósitos<?php 
<?php </button><?php 
<?php <button<?php class="filter-btn"<?php onclick="filterDeposits('PAID')"><?php 
<?php <i<?php class="fas<?php fa-check-circle"></i><?php 
<?php Aprovados<?php 
<?php </button><?php 
<?php <button<?php class="filter-btn"<?php onclick="filterDeposits('PENDING')"><?php 
<?php <i<?php class="fas<?php fa-clock"></i><?php 
<?php Pendentes<?php 
<?php </button><?php 
<?php <button<?php class="filter-btn"<?php onclick="filterDeposits('today')"><?php 
<?php <i<?php class="fas<?php fa-calendar-day"></i><?php 
<?php Hoje<?php 
<?php </button><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Deposits<?php Section<?php --><?php 
<?php <section><?php 
<?php <?php<?php if<?php (empty($depositos)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="fas<?php fa-receipt"></i><?php 
<?php <h3>Nenhum<?php depósito<?php encontrado</h3><?php 
<?php <p>Não<?php há<?php depósitos<?php registrados<?php no<?php sistema<?php ainda</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="deposits-grid"<?php id="depositsGrid"><?php 
<?php <?php<?php foreach<?php ($depositos<?php as<?php $deposito):<?php ?><?php 
<?php <div<?php class="deposit-card"<?php 
<?php data-status="<?php=<?php $deposito['status']<?php ?>"<?php 
<?php data-date="<?php=<?php date('Y-m-d',<?php strtotime($deposito['updated_at']))<?php ?>"><?php 
<?php <div<?php class="deposit-header"><?php 
<?php <div><?php 
<?php <h3<?php class="deposit-user"><?php=<?php htmlspecialchars($deposito['nome'])<?php ?></h3><?php 
<?php <?php<?php if<?php (!empty($deposito['transactionId'])):<?php ?><?php 
<?php <div<?php class="deposit-transaction"><?php 
<?php ID:<?php <?php=<?php htmlspecialchars($deposito['transactionId'])<?php ?><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="deposit-status<?php <?php=<?php $deposito['status']<?php ==<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>"><?php 
<?php <div<?php class="status-dot"></div><?php 
<?php <span><?php=<?php $deposito['status']<?php ==<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="deposit-value"><?php 
<?php R$<?php <?php=<?php number_format($deposito['valor'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="deposit-date"><?php 
<?php <i<?php class="fas<?php fa-calendar"></i><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($deposito['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
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
<?php //<?php Filter<?php functionality<?php 
<?php function<?php filterDeposits(filter)<?php {<?php 
<?php const<?php cards<?php =<?php document.querySelectorAll('.deposit-card');<?php 
<?php const<?php buttons<?php =<?php document.querySelectorAll('.filter-btn');<?php 
<?php const<?php today<?php =<?php new<?php Date().toISOString().split('T')[0];<?php 
<?php 
<?php //<?php Update<?php active<?php button<?php 
<?php buttons.forEach(btn<?php =><?php btn.classList.remove('active'));<?php 
<?php event.target.classList.add('active');<?php 
<?php 
<?php cards.forEach(card<?php =><?php {<?php 
<?php let<?php show<?php =<?php false;<?php 
<?php 
<?php switch(filter)<?php {<?php 
<?php case<?php 'all':<?php 
<?php show<?php =<?php true;<?php 
<?php break;<?php 
<?php case<?php 'PAID':<?php 
<?php show<?php =<?php card.dataset.status<?php ===<?php 'PAID';<?php 
<?php break;<?php 
<?php case<?php 'PENDING':<?php 
<?php show<?php =<?php card.dataset.status<?php !==<?php 'PAID';<?php 
<?php break;<?php 
<?php case<?php 'today':<?php 
<?php show<?php =<?php card.dataset.date<?php ===<?php today;<?php 
<?php break;<?php 
<?php }<?php 
<?php 
<?php if<?php (show)<?php {<?php 
<?php card.style.display<?php =<?php 'block';<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.transition<?php =<?php 'all<?php 0.3s<?php ease';<?php 
<?php card.style.opacity<?php =<?php '1';<?php 
<?php card.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php 50);<?php 
<?php }<?php else<?php {<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(-20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.display<?php =<?php 'none';<?php 
<?php },<?php 300);<?php 
<?php }<?php 
<?php });<?php 
<?php }<?php 
<?php 
<?php //<?php Smooth<?php scroll<?php behavior<?php 
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';<?php 
<?php 
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {<?php 
<?php console.log('%c💳<?php Gerenciamento<?php de<?php Depósitos<?php carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php }<?php 
<?php 
<?php //<?php Animate<?php cards<?php on<?php load<?php 
<?php const<?php depositCards<?php =<?php document.querySelectorAll('.deposit-card');<?php 
<?php depositCards.forEach((card,<?php index)<?php =><?php {<?php 
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