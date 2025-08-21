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
$config<?php =<?php $pdo->query("SELECT<?php *<?php FROM<?php config<?php LIMIT<?php 1")->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
if<?php (isset($_POST['salvar_config']))<?php {<?php 
<?php $nome_site<?php =<?php $_POST['nome_site'];<?php 
<?php $deposito_min<?php =<?php str_replace(',',<?php '.',<?php $_POST['deposito_min']);<?php 
<?php $saque_min<?php =<?php str_replace(',',<?php '.',<?php $_POST['saque_min']);<?php 
<?php $cpa_padrao<?php =<?php str_replace(',',<?php '.',<?php $_POST['cpa_padrao']);<?php 
<?php $revshare_padrao<?php =<?php str_replace(',',<?php '.',<?php $_POST['revshare_padrao']);<?php //<?php Novo<?php campo<?php 
<?php 
<?php $logo<?php =<?php $config['logo'];<?php 
<?php 
<?php if<?php (isset($_FILES['logo'])<?php &&<?php $_FILES['logo']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['logo']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/upload/';<?php 
<?php if<?php (!file_exists($uploadDir))<?php {<?php 
<?php mkdir($uploadDir,<?php 0777,<?php true);<?php 
<?php }<?php 
<?php 
<?php $newName<?php =<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['logo']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php if<?php ($config['logo']<?php &&<?php file_exists('../'<?php .<?php $config['logo']))<?php {<?php 
<?php unlink('../'<?php .<?php $config['logo']);<?php 
<?php }<?php 
<?php $logo<?php =<?php '/assets/upload/'<?php .<?php $newName;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php da<?php logo!';<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Formato<?php de<?php arquivo<?php inválido!<?php Use<?php apenas<?php JPG<?php ou<?php PNG.';<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php //<?php Query<?php atualizada<?php para<?php incluir<?php revshare_padrao<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php config<?php SET<?php nome_site<?php =<?php ?,<?php logo<?php =<?php ?,<?php deposito_min<?php =<?php ?,<?php saque_min<?php =<?php ?,<?php cpa_padrao<?php =<?php ?,<?php revshare_padrao<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$nome_site,<?php $logo,<?php $deposito_min,<?php $saque_min,<?php $cpa_padrao,<?php $revshare_padrao]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Configurações<?php atualizadas<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php as<?php configurações!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$nome<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php nome<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;<?php 
$nome<?php =<?php $nome<?php ?<?php explode('<?php ',<?php $nome)[0]<?php :<?php null;<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Configurações</title><?php 
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
<?php /*<?php Sidebar<?php Styles<?php */<?php 
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
<?php /*<?php Form<?php Container<?php */<?php 
<?php .form-container<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 3rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .form-container::before<?php {<?php 
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
<?php .form-container:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .form-container:hover<?php {<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php box-shadow:<?php 0<?php 25px<?php 80px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php }<?php 
<?php 
<?php .form-title<?php {<?php 
<?php font-size:<?php 1.75rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php white;<?php 
<?php margin-bottom:<?php 2.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php padding-bottom:<?php 1.5rem;<?php 
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .form-title<?php i<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2)<?php 0%,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 12px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Form<?php Sections<?php */<?php 
<?php .form-section<?php {<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
<?php .section-title<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .section-title<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.125rem;<?php 
<?php }<?php 
<?php 
<?php .form-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));<?php 
<?php gap:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .form-group<?php {<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .form-label<?php {<?php 
<?php display:<?php block;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-label<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php }<?php 
<?php 
<?php .form-input<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1rem<?php 1.25rem;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .form-input.percentage<?php {<?php 
<?php padding-right:<?php 2.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-input:focus<?php {<?php 
<?php outline:<?php none;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.6);<?php 
<?php }<?php 
<?php 
<?php .form-input::placeholder<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .percentage-symbol<?php {<?php 
<?php position:<?php absolute;<?php 
<?php right:<?php 1rem;<?php 
<?php top:<?php 50%;<?php 
<?php transform:<?php translateY(-50%);<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-weight:<?php 600;<?php 
<?php pointer-events:<?php none;<?php 
<?php }<?php 
<?php 
<?php .input-container<?php {<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php /*<?php File<?php Input<?php */<?php 
<?php .file-input-container<?php {<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .file-input<?php {<?php 
<?php position:<?php absolute;<?php 
<?php opacity:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php cursor:<?php pointer;<?php 
<?php }<?php 
<?php 
<?php .file-input-label<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php border:<?php 2px<?php dashed<?php rgba(255,<?php 255,<?php 255,<?php 0.2);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php .file-input-label:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .file-input-label.has-file<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php /*<?php Current<?php Logo<?php */<?php 
<?php .current-logo<?php {<?php 
<?php margin-top:<?php 1rem;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border-radius:<?php 12px;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php }<?php 
<?php 
<?php .current-logo<?php p<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php }<?php 
<?php 
<?php .current-logo<?php img<?php {<?php 
<?php max-height:<?php 80px;<?php 
<?php max-width:<?php 100%;<?php 
<?php object-fit:<?php contain;<?php 
<?php border-radius:<?php 8px;<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php padding:<?php 0.75rem;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php /*<?php Submit<?php Button<?php */<?php 
<?php .submit-button<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border:<?php none;<?php 
<?php padding:<?php 1.25rem<?php 2rem;<?php 
<?php border-radius:<?php 16px;<?php 
<?php font-size:<?php 1.125rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php margin-top:<?php 3rem;<?php 
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .submit-button:hover<?php {<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 12px<?php 35px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .submit-button:active<?php {<?php 
<?php transform:<?php translateY(0);<?php 
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
<?php .form-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1.5rem;<?php 
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
<?php .form-container<?php {<?php 
<?php padding:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .form-title<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
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
<?php .form-container<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-grid<?php {<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .sidebar<?php {<?php 
<?php width:<?php 260px;<?php 
<?php }<?php 
<?php }<?php 
<?php 
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
<?php <!--<?php Sidebar<?php --><?php 
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
<?php <a<?php href="config.php"<?php class="nav-item<?php active"><?php 
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
<?php <!--<?php Header<?php --><?php 
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
<?php <h2<?php class="welcome-title">Configurações<?php do<?php Sistema</h2><?php 
<?php <p<?php class="welcome-subtitle">Gerencie<?php as<?php configurações<?php básicas<?php e<?php personalize<?php sua<?php plataforma</p><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Form<?php Container<?php --><?php 
<?php <div<?php class="form-container"><?php 
<?php <form<?php method="POST"<?php enctype="multipart/form-data"><?php 
<?php <h2<?php class="form-title"><?php 
<?php <i<?php class="fas<?php fa-cogs"></i><?php 
<?php Configurações<?php Gerais<?php 
<?php </h2><?php 
<?php 
<?php <!--<?php Site<?php Configuration<?php Section<?php --><?php 
<?php <div<?php class="form-section"><?php 
<?php <h3<?php class="section-title"><?php 
<?php <i<?php class="fas<?php fa-globe"></i><?php 
<?php Informações<?php do<?php Site<?php 
<?php </h3><?php 
<?php 
<?php <div<?php class="form-grid"><?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-tag"></i><?php 
<?php Nome<?php do<?php Site<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="nome_site"<?php value="<?php=<?php htmlspecialchars($config['nome_site']<?php ??<?php '')<?php ?>"<?php class="form-input"<?php placeholder="Digite<?php o<?php nome<?php do<?php seu<?php site"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-image"></i><?php 
<?php Logo<?php do<?php Site<?php 
<?php </label><?php 
<?php <div<?php class="file-input-container"><?php 
<?php <input<?php type="file"<?php name="logo"<?php accept="image/jpeg,<?php image/png"<?php id="logo-upload"<?php class="file-input"><?php 
<?php <label<?php for="logo-upload"<?php class="file-input-label"<?php id="file-label"><?php 
<?php <i<?php class="fas<?php fa-cloud-upload-alt"></i><?php 
<?php <span>Clique<?php para<?php enviar<?php logo<?php (JPG,<?php PNG)</span><?php 
<?php </label><?php 
<?php </div><?php 
<?php 
<?php <?php<?php if<?php (!empty($config['logo'])):<?php ?><?php 
<?php <div<?php class="current-logo"><?php 
<?php <p><i<?php class="fas<?php fa-image"></i><?php Logo<?php atual:</p><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($config['logo'])<?php ?>"<?php alt="Logo<?php atual"><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Financial<?php Configuration<?php Section<?php --><?php 
<?php <div<?php class="form-section"><?php 
<?php <h3<?php class="section-title"><?php 
<?php <i<?php class="fas<?php fa-dollar-sign"></i><?php 
<?php Configurações<?php Financeiras<?php 
<?php </h3><?php 
<?php 
<?php <div<?php class="form-grid"><?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-plus-circle"></i><?php 
<?php Depósito<?php Mínimo<?php (R$)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="deposito_min"<?php value="<?php=<?php htmlspecialchars($config['deposito_min']<?php ??<?php '0')<?php ?>"<?php class="form-input"<?php placeholder="Ex:<?php 10,00"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-minus-circle"></i><?php 
<?php Saque<?php Mínimo<?php (R$)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="saque_min"<?php value="<?php=<?php htmlspecialchars($config['saque_min']<?php ??<?php '0')<?php ?>"<?php class="form-input"<?php placeholder="Ex:<?php 20,00"<?php required><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Affiliate<?php Configuration<?php Section<?php --><?php 
<?php <div<?php class="form-section"><?php 
<?php <h3<?php class="section-title"><?php 
<?php <i<?php class="fas<?php fa-handshake"></i><?php 
<?php Configurações<?php de<?php Afiliados<?php 
<?php </h3><?php 
<?php 
<?php <div<?php class="form-grid"><?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-user-plus"></i><?php 
<?php CPA<?php Padrão<?php (R$)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="cpa_padrao"<?php value="<?php=<?php htmlspecialchars($config['cpa_padrao']<?php ??<?php '0')<?php ?>"<?php class="form-input"<?php placeholder="Ex:<?php 5,00"<?php required><?php 
<?php <p<?php style="color:<?php #6b7280;<?php font-size:<?php 0.8rem;<?php margin-top:<?php 0.5rem;"><?php 
<?php <i<?php class="fas<?php fa-info-circle"></i><?php 
<?php Comissão<?php fixa<?php paga<?php por<?php cada<?php novo<?php cadastro<?php indicado<?php 
<?php </p><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-chart-line"></i><?php 
<?php RevShare<?php Padrão<?php (%)<?php 
<?php </label><?php 
<?php <div<?php class="input-container"><?php 
<?php <input<?php type="text"<?php name="revshare_padrao"<?php value="<?php=<?php htmlspecialchars($config['revshare_padrao']<?php ??<?php '0')<?php ?>"<?php class="form-input<?php percentage"<?php placeholder="Ex:<?php 10,00"<?php required><?php 
<?php <span<?php class="percentage-symbol">%</span><?php 
<?php </div><?php 
<?php <p<?php style="color:<?php #6b7280;<?php font-size:<?php 0.8rem;<?php margin-top:<?php 0.5rem;"><?php 
<?php <i<?php class="fas<?php fa-info-circle"></i><?php 
<?php Percentual<?php sobre<?php as<?php perdas<?php dos<?php usuários<?php indicados<?php 
<?php </p><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <button<?php type="submit"<?php name="salvar_config"<?php class="submit-button"><?php 
<?php <i<?php class="fas<?php fa-save"></i><?php 
<?php Salvar<?php Configurações<?php 
<?php </button><?php 
<?php </form><?php 
<?php </div><?php 
<?php 
<?php </div><?php 
<?php </main><?php 
<?php 
<?php <script><?php 
<?php //<?php Mobile<?php menu<?php toggle<?php 
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
<?php //<?php File<?php input<?php enhancement<?php 
<?php document.getElementById('logo-upload').addEventListener('change',<?php function(e)<?php {<?php 
<?php const<?php label<?php =<?php document.getElementById('file-label');<?php 
<?php const<?php fileName<?php =<?php e.target.files[0]?.name;<?php 
<?php 
<?php if<?php (fileName)<?php {<?php 
<?php label.innerHTML<?php =<?php `<?php 
<?php <i<?php class="fas<?php fa-check-circle"></i><?php 
<?php <span>${fileName}</span><?php 
<?php `;<?php 
<?php label.classList.add('has-file');<?php 
<?php }<?php else<?php {<?php 
<?php label.innerHTML<?php =<?php `<?php 
<?php <i<?php class="fas<?php fa-cloud-upload-alt"></i><?php 
<?php <span>Clique<?php para<?php enviar<?php logo<?php (JPG,<?php PNG)</span><?php 
<?php `;<?php 
<?php label.classList.remove('has-file');<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Input<?php formatting<?php for<?php currency<?php fields<?php 
<?php function<?php formatCurrency(input)<?php {<?php 
<?php let<?php value<?php =<?php input.value.replace(/\D/g,<?php '');<?php 
<?php if<?php (value<?php ===<?php '')<?php return;<?php 
<?php value<?php =<?php (value<?php /<?php 100).toFixed(2)<?php +<?php '';<?php 
<?php value<?php =<?php value.replace(".",<?php ",");<?php 
<?php value<?php =<?php value.replace(/(\d)(\d{3}),/,<?php "$1.$2,");<?php 
<?php input.value<?php =<?php value;<?php 
<?php }<?php 
<?php 
<?php //<?php Input<?php formatting<?php for<?php percentage<?php fields<?php 
<?php function<?php formatPercentage(input)<?php {<?php 
<?php let<?php value<?php =<?php input.value.replace(/[^\d,]/g,<?php '');<?php 
<?php if<?php (value.includes(','))<?php {<?php 
<?php let<?php parts<?php =<?php value.split(',');<?php 
<?php if<?php (parts[1]<?php &&<?php parts[1].length<?php ><?php 2)<?php {<?php 
<?php parts[1]<?php =<?php parts[1].substring(0,<?php 2);<?php 
<?php }<?php 
<?php value<?php =<?php parts.join(',');<?php 
<?php }<?php 
<?php input.value<?php =<?php value;<?php 
<?php }<?php 
<?php 
<?php //<?php Apply<?php currency<?php formatting<?php to<?php financial<?php inputs<?php 
<?php document.querySelectorAll('input[name="deposito_min"],<?php input[name="saque_min"],<?php input[name="cpa_padrao"]').forEach(input<?php =><?php {<?php 
<?php input.addEventListener('input',<?php function()<?php {<?php 
<?php formatCurrency(this);<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Apply<?php percentage<?php formatting<?php to<?php revshare<?php input<?php 
<?php document.querySelector('input[name="revshare_padrao"]').addEventListener('input',<?php function()<?php {<?php 
<?php formatPercentage(this);<?php 
<?php });<?php 
<?php 
<?php //<?php Smooth<?php scroll<?php behavior<?php 
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';<?php 
<?php 
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {<?php 
<?php console.log('%c⚙️<?php Configurações<?php do<?php Sistema<?php carregadas!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php }<?php 
<?php 
<?php //<?php Animate<?php form<?php container<?php on<?php load<?php 
<?php const<?php formContainer<?php =<?php document.querySelector('.form-container');<?php 
<?php formContainer.style.opacity<?php =<?php '0';<?php 
<?php formContainer.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php formContainer.style.transition<?php =<?php 'all<?php 0.6s<?php ease';<?php 
<?php formContainer.style.opacity<?php =<?php '1';<?php 
<?php formContainer.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php 300);<?php 
<?php });<?php 
<?php </script><?php 
</body><?php 
</html>