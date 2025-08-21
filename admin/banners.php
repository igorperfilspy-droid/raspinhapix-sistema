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
$banners<?php =<?php $pdo->query("SELECT<?php *<?php FROM<?php banners<?php ORDER<?php BY<?php ordem<?php ASC")->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
//<?php Editar<?php banner<?php 
if<?php (isset($_POST['editar_banner']))<?php {<?php 
<?php $banner_id<?php =<?php $_POST['banner_id'];<?php 
<?php $banner_atual<?php =<?php $pdo->prepare("SELECT<?php banner_img<?php FROM<?php banners<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $banner_atual->execute([$banner_id]);<?php 
<?php $banner_data<?php =<?php $banner_atual->fetch();<?php 
<?php 
<?php if<?php ($banner_data)<?php {<?php 
<?php $nova_imagem<?php =<?php $banner_data['banner_img'];<?php 
<?php 
<?php if<?php (isset($_FILES['nova_banner_img'])<?php &&<?php $_FILES['nova_banner_img']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['nova_banner_img']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/banners/';<?php 
<?php if<?php (!file_exists($uploadDir))<?php {<?php 
<?php mkdir($uploadDir,<?php 0777,<?php true);<?php 
<?php }<?php 
<?php 
<?php $newName<?php =<?php 'banner_'<?php .<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['nova_banner_img']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php if<?php (file_exists('../'<?php .<?php $banner_data['banner_img']))<?php {<?php 
<?php unlink('../'<?php .<?php $banner_data['banner_img']);<?php 
<?php }<?php 
<?php $nova_imagem<?php =<?php '/assets/banners/'<?php .<?php $newName;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php da<?php nova<?php imagem!';<?php 
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
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php banners<?php SET<?php banner_img<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$nova_imagem,<?php $banner_id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Banner<?php atualizado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php banner!';<?php 
<?php }<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
//<?php Adicionar<?php banner<?php 
if<?php (isset($_POST['adicionar_banner']))<?php {<?php 
<?php if<?php (isset($_FILES['banner_img'])<?php &&<?php $_FILES['banner_img']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['banner_img']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/banners/';<?php 
<?php if<?php (!file_exists($uploadDir))<?php {<?php 
<?php mkdir($uploadDir,<?php 0777,<?php true);<?php 
<?php }<?php 
<?php 
<?php $newName<?php =<?php 'banner_'<?php .<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['banner_img']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php $ordem<?php =<?php $pdo->query("SELECT<?php COALESCE(MAX(ordem),<?php 0)<?php +<?php 1<?php FROM<?php banners")->fetchColumn();<?php 
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php banners<?php (banner_img,<?php ativo,<?php ordem)<?php VALUES<?php (?,<?php 1,<?php ?)");<?php 
<?php 
<?php if<?php ($stmt->execute(['/assets/banners/'<?php .<?php $newName,<?php $ordem]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Banner<?php adicionado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php salvar<?php banner<?php no<?php banco!';<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php do<?php banner!';<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Formato<?php de<?php arquivo<?php inválido!<?php Use<?php apenas<?php JPG<?php ou<?php PNG.';<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Nenhum<?php arquivo<?php selecionado!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
//<?php Deletar<?php banner<?php 
if<?php (isset($_POST['deletar_banner']))<?php {<?php 
<?php $banner_id<?php =<?php $_POST['banner_id'];<?php 
<?php $banner<?php =<?php $pdo->prepare("SELECT<?php banner_img<?php FROM<?php banners<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $banner->execute([$banner_id]);<?php 
<?php $banner_data<?php =<?php $banner->fetch();<?php 
<?php 
<?php if<?php ($banner_data)<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("DELETE<?php FROM<?php banners<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$banner_id]))<?php {<?php 
<?php if<?php (file_exists('../'<?php .<?php $banner_data['banner_img']))<?php {<?php 
<?php unlink('../'<?php .<?php $banner_data['banner_img']);<?php 
<?php }<?php 
<?php $_SESSION['success']<?php =<?php 'Banner<?php deletado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php deletar<?php banner!';<?php 
<?php }<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
//<?php Atualizar<?php status<?php do<?php banner<?php 
if<?php (isset($_POST['toggle_banner']))<?php {<?php 
<?php $banner_id<?php =<?php $_POST['banner_id'];<?php 
<?php $novo_status<?php =<?php $_POST['novo_status'];<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php banners<?php SET<?php ativo<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$novo_status,<?php $banner_id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Status<?php do<?php banner<?php atualizado!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php status!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
//<?php Atualizar<?php ordem<?php dos<?php banners<?php 
if<?php (isset($_POST['atualizar_ordem']))<?php {<?php 
<?php $ordens<?php =<?php $_POST['ordem'];<?php 
<?php 
<?php foreach<?php ($ordens<?php as<?php $id<?php =><?php $ordem)<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php banners<?php SET<?php ordem<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $stmt->execute([$ordem,<?php $id]);<?php 
<?php }<?php 
<?php 
<?php $_SESSION['success']<?php =<?php 'Ordem<?php dos<?php banners<?php atualizada!';<?php 
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
<?php <title>Dashboard<?php -<?php Gestão<?php de<?php Banners</title><?php 
<?php 
<?php <script<?php src="https://cdn.tailwindcss.com"></script><?php 
<?php <link<?php rel="stylesheet"<?php href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"><?php 
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
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
<?php box-shadow:<?php 0<?php 0<?php 50px<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php inset<?php 1px<?php 0<?php 0<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
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
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.3),<?php 0<?php 4px<?php 8px<?php rgba(0,<?php 0,<?php 0,<?php 0.2);<?php 
<?php position:<?php relative;<?php 
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
<?php .content-container<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 3rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .content-title<?php {<?php 
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
<?php .content-title<?php i<?php {<?php 
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
<?php .upload-section<?php {<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php padding:<?php 2rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 2px<?php dashed<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 16px;<?php 
<?php text-align:<?php center;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php cursor:<?php pointer;<?php 
<?php }<?php 
<?php 
<?php .upload-section:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php }<?php 
<?php 
<?php .upload-icon<?php {<?php 
<?php width:<?php 80px;<?php 
<?php height:<?php 80px;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 50%;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 2rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php margin:<?php 0<?php auto<?php 1.5rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .upload-text<?php {<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .upload-subtitle<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.95rem;<?php 
<?php }<?php 
<?php 
<?php .file-input<?php {<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php 
<?php .banners-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(350px,<?php 1fr));<?php 
<?php gap:<?php 2rem;<?php 
<?php margin-top:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .banner-card<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .banner-card:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php box-shadow:<?php 0<?php 12px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .banner-image<?php {<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 180px;<?php 
<?php object-fit:<?php cover;<?php 
<?php border-radius:<?php 12px;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .banner-info<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php space-between;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .banner-status<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .status-badge<?php {<?php 
<?php padding:<?php 0.5rem<?php 1rem;<?php 
<?php border-radius:<?php 8px;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .status-badge.ativo<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php color:<?php #22c55e;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .status-badge.inativo<?php {<?php 
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.2);<?php 
<?php color:<?php #ef4444;<?php 
<?php border:<?php 1px<?php solid<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .ordem-badge<?php {<?php 
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.2);<?php 
<?php color:<?php #3b82f6;<?php 
<?php border:<?php 1px<?php solid<?php rgba(59,<?php 130,<?php 246,<?php 0.3);<?php 
<?php padding:<?php 0.5rem<?php 0.75rem;<?php 
<?php border-radius:<?php 8px;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php }<?php 
<?php 
<?php .banner-actions<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .btn-action<?php {<?php 
<?php flex:<?php 1;<?php 
<?php padding:<?php 0.75rem<?php 1rem;<?php 
<?php border-radius:<?php 10px;<?php 
<?php border:<?php none;<?php 
<?php cursor:<?php pointer;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .btn-edit<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php color:<?php #22c55e;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-edit:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .btn-toggle<?php {<?php 
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.2);<?php 
<?php color:<?php #3b82f6;<?php 
<?php border:<?php 1px<?php solid<?php rgba(59,<?php 130,<?php 246,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-toggle:hover<?php {<?php 
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(59,<?php 130,<?php 246,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .btn-delete<?php {<?php 
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.2);<?php 
<?php color:<?php #ef4444;<?php 
<?php border:<?php 1px<?php solid<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-delete:hover<?php {<?php 
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 20px<?php rgba(239,<?php 68,<?php 68,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .order-section<?php {<?php 
<?php margin-top:<?php 3rem;<?php 
<?php padding:<?php 2rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 16px;<?php 
<?php }<?php 
<?php 
<?php .order-title<?php {<?php 
<?php color:<?php #ffffff;<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .order-title<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .order-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .order-item<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php padding:<?php 1rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.4);<?php 
<?php border-radius:<?php 12px;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php }<?php 
<?php 
<?php .order-input<?php {<?php 
<?php width:<?php 70px;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.6);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.2);<?php 
<?php border-radius:<?php 8px;<?php 
<?php padding:<?php 0.75rem;<?php 
<?php color:<?php white;<?php 
<?php text-align:<?php center;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .order-input:focus<?php {<?php 
<?php outline:<?php none;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .order-label<?php {<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 0.95rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php flex:<?php 1;<?php 
<?php }<?php 
<?php 
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
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .submit-button:hover<?php {<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 12px<?php 35px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php {<?php 
<?php text-align:<?php center;<?php 
<?php padding:<?php 4rem<?php 2rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .empty-icon<?php {<?php 
<?php width:<?php 120px;<?php 
<?php height:<?php 120px;<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php border-radius:<?php 50%;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 3rem;<?php 
<?php color:<?php #4b5563;<?php 
<?php margin:<?php 0<?php auto<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .empty-title<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #9ca3af;<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .empty-subtitle<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .modal<?php {<?php 
<?php position:<?php fixed;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.8);<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php z-index:<?php 2000;<?php 
<?php opacity:<?php 0;<?php 
<?php visibility:<?php hidden;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php backdrop-filter:<?php blur(8px);<?php 
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
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2.5rem;<?php 
<?php max-width:<?php 500px;<?php 
<?php width:<?php 90%;<?php 
<?php max-height:<?php 90vh;<?php 
<?php overflow-y:<?php auto;<?php 
<?php position:<?php relative;<?php 
<?php box-shadow:<?php 0<?php 25px<?php 80px<?php rgba(0,<?php 0,<?php 0,<?php 0.6);<?php 
<?php }<?php 
<?php 
<?php .modal-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php space-between;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php padding-bottom:<?php 1rem;<?php 
<?php border-bottom:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .modal-title<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #ffffff;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .modal-title<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .modal-close<?php {<?php 
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.2);<?php 
<?php border:<?php 1px<?php solid<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php color:<?php #ef4444;<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php border-radius:<?php 10px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .modal-close:hover<?php {<?php 
<?php background:<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php transform:<?php scale(1.05);<?php 
<?php }<?php 
<?php 
<?php .modal-body<?php {<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .current-image<?php {<?php 
<?php width:<?php 100%;<?php 
<?php max-height:<?php 200px;<?php 
<?php object-fit:<?php cover;<?php 
<?php border-radius:<?php 12px;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .file-upload-area<?php {<?php 
<?php border:<?php 2px<?php dashed<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 2rem;<?php 
<?php text-align:<?php center;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .file-upload-area:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .modal-actions<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .btn-modal<?php {<?php 
<?php flex:<?php 1;<?php 
<?php padding:<?php 1rem<?php 1.5rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php border:<?php none;<?php 
<?php cursor:<?php pointer;<?php 
<?php font-weight:<?php 600;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .btn-save<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-save:hover<?php {<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-cancel<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.2);<?php 
<?php color:<?php #9ca3af;<?php 
<?php border:<?php 1px<?php solid<?php rgba(107,<?php 114,<?php 128,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-cancel:hover<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);<?php 
<?php color:<?php #ffffff;<?php 
<?php }<?php 
<?php 
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
<?php .banners-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .order-grid<?php {<?php 
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
<?php .content-container<?php {<?php 
<?php padding:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .content-title<?php {<?php 
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
<?php .content-container<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .sidebar<?php {<?php 
<?php width:<?php 260px;<?php 
<?php }<?php 
<?php 
<?php .banners-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .banner-actions<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php gap:<?php 0.5rem;<?php 
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
<?php <div<?php class="overlay"<?php id="overlay"></div><?php 
<?php 
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
<?php <a<?php href="config.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-cogs"></i></div><?php 
<?php <div<?php class="nav-text">Configurações</div><?php 
<?php </a><?php 
<?php <a<?php href="gateway.php"<?php class="nav-item"><?php 
<?php <div<?php class="nav-icon"><i<?php class="fas<?php fa-dollar-sign"></i></div><?php 
<?php <div<?php class="nav-text">Gateway</div><?php 
<?php </a><?php 
<?php <a<?php href="banners.php"<?php class="nav-item<?php active"><?php 
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
<?php <main<?php class="main-content"<?php id="mainContent"><?php 
<?php <header<?php class="header"><?php 
<?php <div<?php class="header-content"><?php 
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 1rem;"><?php 
<?php <button<?php class="menu-toggle"<?php id="menuToggle"><?php 
<?php <i<?php class="fas<?php fa-bars"></i><?php 
<?php </button><?php 
<?php </div><?php 
<?php 
<?php <div<?php style="display:<?php flex;<?php align-items:<?php center;<?php gap:<?php 1rem;"><?php 
<?php <span<?php style="color:<?php #a1a1aa;<?php font-size:<?php 0.9rem;<?php display:<?php none;">Bem-vindo,<?php <?php=<?php htmlspecialchars($nome)<?php ?></span><?php 
<?php <div<?php class="user-avatar"><?php 
<?php <?php=<?php strtoupper(substr($nome,<?php 0,<?php 1))<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </header><?php 
<?php 
<?php <div<?php class="page-content"><?php 
<?php <section<?php class="welcome-section"><?php 
<?php <h2<?php class="welcome-title">Gestão<?php de<?php Banners</h2><?php 
<?php <p<?php class="welcome-subtitle">Gerencie<?php os<?php banners<?php exibidos<?php na<?php página<?php principal<?php da<?php sua<?php plataforma</p><?php 
<?php </section><?php 
<?php 
<?php <div<?php class="content-container"><?php 
<?php <h2<?php class="content-title"><?php 
<?php <i<?php class="fas<?php fa-cloud-upload-alt"></i><?php 
<?php Adicionar<?php Novo<?php Banner<?php 
<?php </h2><?php 
<?php 
<?php <div<?php class="upload-section"<?php onclick="document.getElementById('banner-upload').click()"><?php 
<?php <div<?php class="upload-icon"><?php 
<?php <i<?php class="fas<?php fa-plus"></i><?php 
<?php </div><?php 
<?php <div<?php class="upload-text">Clique<?php para<?php adicionar<?php um<?php novo<?php banner</div><?php 
<?php <div<?php class="upload-subtitle">Formatos<?php aceitos:<?php JPG,<?php PNG<?php (máx.<?php 5MB)</div><?php 
<?php </div><?php 
<?php 
<?php <form<?php method="POST"<?php enctype="multipart/form-data"<?php style="display:<?php none;"><?php 
<?php <input<?php type="file"<?php name="banner_img"<?php accept="image/jpeg,image/png,image/jpg"<?php id="banner-upload"><?php 
<?php <input<?php type="hidden"<?php name="adicionar_banner"<?php value="1"><?php 
<?php </form><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="content-container"><?php 
<?php <h2<?php class="content-title"><?php 
<?php <i<?php class="fas<?php fa-images"></i><?php 
<?php Banners<?php Cadastrados<?php 
<?php <span<?php style="background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php color:<?php #22c55e;<?php padding:<?php 0.25rem<?php 0.75rem;<?php border-radius:<?php 6px;<?php font-size:<?php 0.8rem;<?php margin-left:<?php auto;"><?php 
<?php <?php=<?php count($banners)<?php ?><?php banner<?php=<?php count($banners)<?php !=<?php 1<?php ?<?php 's'<?php :<?php ''<?php ?><?php 
<?php </span><?php 
<?php </h2><?php 
<?php 
<?php <?php<?php if<?php (empty($banners)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <div<?php class="empty-icon"><?php 
<?php <i<?php class="fas<?php fa-images"></i><?php 
<?php </div><?php 
<?php <div<?php class="empty-title">Nenhum<?php banner<?php cadastrado</div><?php 
<?php <div<?php class="empty-subtitle">Adicione<?php seu<?php primeiro<?php banner<?php usando<?php a<?php seção<?php acima</div><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="banners-grid"><?php 
<?php <?php<?php foreach<?php ($banners<?php as<?php $banner):<?php ?><?php 
<?php <div<?php class="banner-card"><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($banner['banner_img'])<?php ?>"<?php 
<?php alt="Banner<?php #<?php=<?php $banner['id']<?php ?>"<?php 
<?php class="banner-image"<?php 
<?php onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzNTAiIGhlaWdodD0iMTgwIiB2aWV3Qm94PSIwIDAgMzUwIDE4MCI+PHJlY3Qgd2lkdGg9IjM1MCIgaGVpZ2h0PSIxODAiIGZpbGw9IiMzNzQxNTEiLz48dGV4dCB4PSIxNzUiIHk9IjkwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjZDFkNWRiIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiPkltYWdlbSBuw6NvIGVuY29udHJhZGE8L3RleHQ+PC9zdmc+'"<?php 
<?php loading="lazy"><?php 
<?php 
<?php <div<?php class="banner-info"><?php 
<?php <div<?php class="banner-status"><?php 
<?php <span<?php class="status-badge<?php <?php=<?php $banner['ativo']<?php ?<?php 'ativo'<?php :<?php 'inativo'<?php ?>"><?php 
<?php <i<?php class="fas<?php fa-<?php=<?php $banner['ativo']<?php ?<?php 'check-circle'<?php :<?php 'times-circle'<?php ?>"></i><?php 
<?php <?php=<?php $banner['ativo']<?php ?<?php 'Ativo'<?php :<?php 'Inativo'<?php ?><?php 
<?php </span><?php 
<?php </div><?php 
<?php <span<?php class="ordem-badge"><?php 
<?php <i<?php class="fas<?php fa-sort"></i><?php 
<?php Ordem:<?php <?php=<?php $banner['ordem']<?php ?><?php 
<?php </span><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="banner-actions"><?php 
<?php <button<?php type="button"<?php class="btn-action<?php btn-edit"<?php onclick="openEditModal(<?php=<?php $banner['id']<?php ?>,<?php '<?php=<?php htmlspecialchars($banner['banner_img'])<?php ?>')"><?php 
<?php <i<?php class="fas<?php fa-edit"></i><?php 
<?php Editar<?php 
<?php </button><?php 
<?php 
<?php <form<?php method="POST"<?php style="flex:<?php 1;"><?php 
<?php <input<?php type="hidden"<?php name="banner_id"<?php value="<?php=<?php $banner['id']<?php ?>"><?php 
<?php <input<?php type="hidden"<?php name="novo_status"<?php value="<?php=<?php $banner['ativo']<?php ?<?php 0<?php :<?php 1<?php ?>"><?php 
<?php <button<?php type="submit"<?php name="toggle_banner"<?php class="btn-action<?php btn-toggle"><?php 
<?php <i<?php class="fas<?php fa-<?php=<?php $banner['ativo']<?php ?<?php 'eye-slash'<?php :<?php 'eye'<?php ?>"></i><?php 
<?php <?php=<?php $banner['ativo']<?php ?<?php 'Desativar'<?php :<?php 'Ativar'<?php ?><?php 
<?php </button><?php 
<?php </form><?php 
<?php 
<?php <form<?php method="POST"<?php style="flex:<?php 1;"<?php onsubmit="return<?php confirm('Tem<?php certeza<?php que<?php deseja<?php deletar<?php este<?php banner?')"><?php 
<?php <input<?php type="hidden"<?php name="banner_id"<?php value="<?php=<?php $banner['id']<?php ?>"><?php 
<?php <button<?php type="submit"<?php name="deletar_banner"<?php class="btn-action<?php btn-delete"><?php 
<?php <i<?php class="fas<?php fa-trash"></i><?php 
<?php Deletar<?php 
<?php </button><?php 
<?php </form><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="order-section"><?php 
<?php <h3<?php class="order-title"><?php 
<?php <i<?php class="fas<?php fa-sort"></i><?php 
<?php Reordenar<?php Banners<?php 
<?php </h3><?php 
<?php 
<?php <form<?php method="POST"><?php 
<?php <div<?php class="order-grid"><?php 
<?php <?php<?php foreach<?php ($banners<?php as<?php $banner):<?php ?><?php 
<?php <div<?php class="order-item"><?php 
<?php <input<?php type="number"<?php 
<?php name="ordem[<?php=<?php $banner['id']<?php ?>]"<?php 
<?php value="<?php=<?php $banner['ordem']<?php ?>"<?php 
<?php min="1"<?php 
<?php class="order-input"<?php 
<?php title="Ordem<?php do<?php banner"><?php 
<?php <span<?php class="order-label"><?php 
<?php <i<?php class="fas<?php fa-image"></i><?php 
<?php Banner<?php #<?php=<?php $banner['id']<?php ?><?php 
<?php <?php=<?php $banner['ativo']<?php ?<?php '(Ativo)'<?php :<?php '(Inativo)'<?php ?><?php 
<?php </span><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <button<?php type="submit"<?php name="atualizar_ordem"<?php class="submit-button"><?php 
<?php <i<?php class="fas<?php fa-save"></i><?php 
<?php Salvar<?php Nova<?php Ordem<?php 
<?php </button><?php 
<?php </form><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </main><?php 
<?php 
<?php <div<?php class="modal"<?php id="editModal"><?php 
<?php <div<?php class="modal-content"><?php 
<?php <div<?php class="modal-header"><?php 
<?php <h3<?php class="modal-title"><?php 
<?php <i<?php class="fas<?php fa-edit"></i><?php 
<?php Editar<?php Banner<?php 
<?php </h3><?php 
<?php <button<?php type="button"<?php class="modal-close"<?php onclick="closeEditModal()"><?php 
<?php <i<?php class="fas<?php fa-times"></i><?php 
<?php </button><?php 
<?php </div><?php 
<?php 
<?php <form<?php method="POST"<?php enctype="multipart/form-data"<?php id="editForm"><?php 
<?php <div<?php class="modal-body"><?php 
<?php <input<?php type="hidden"<?php name="banner_id"<?php id="editBannerId"><?php 
<?php 
<?php <div<?php style="margin-bottom:<?php 1.5rem;"><?php 
<?php <label<?php style="color:<?php #e5e7eb;<?php font-weight:<?php 600;<?php margin-bottom:<?php 0.75rem;<?php display:<?php block;"><?php 
<?php <i<?php class="fas<?php fa-image"<?php style="color:<?php #22c55e;<?php margin-right:<?php 0.5rem;"></i><?php 
<?php Imagem<?php Atual<?php 
<?php </label><?php 
<?php <img<?php id="currentImage"<?php src=""<?php alt="Banner<?php atual"<?php class="current-image"><?php 
<?php </div><?php 
<?php 
<?php <div<?php style="margin-bottom:<?php 1.5rem;"><?php 
<?php <label<?php style="color:<?php #e5e7eb;<?php font-weight:<?php 600;<?php margin-bottom:<?php 0.75rem;<?php display:<?php block;"><?php 
<?php <i<?php class="fas<?php fa-upload"<?php style="color:<?php #22c55e;<?php margin-right:<?php 0.5rem;"></i><?php 
<?php Nova<?php Imagem<?php (opcional)<?php 
<?php </label><?php 
<?php <div<?php class="file-upload-area"<?php onclick="document.getElementById('editBannerInput').click()"><?php 
<?php <div<?php class="upload-text">Clique<?php para<?php selecionar<?php nova<?php imagem</div><?php 
<?php <div<?php class="upload-subtitle">JPG,<?php PNG<?php (máx.<?php 5MB)</div><?php 
<?php </div><?php 
<?php <input<?php type="file"<?php name="nova_banner_img"<?php accept="image/jpeg,image/png,image/jpg"<?php id="editBannerInput"<?php style="display:<?php none;"><?php 
<?php <div<?php id="selectedFileName"<?php style="color:<?php #22c55e;<?php font-size:<?php 0.9rem;<?php margin-top:<?php 0.5rem;<?php display:<?php none;"></div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="modal-actions"><?php 
<?php <button<?php type="button"<?php class="btn-modal<?php btn-cancel"<?php onclick="closeEditModal()"><?php 
<?php <i<?php class="fas<?php fa-times"></i><?php 
<?php Cancelar<?php 
<?php </button><?php 
<?php <button<?php type="submit"<?php name="editar_banner"<?php class="btn-modal<?php btn-save"><?php 
<?php <i<?php class="fas<?php fa-save"></i><?php 
<?php Salvar<?php Alterações<?php 
<?php </button><?php 
<?php </div><?php 
<?php </form><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <script><?php 
<?php const<?php menuToggle<?php =<?php document.getElementById('menuToggle');<?php 
<?php const<?php sidebar<?php =<?php document.getElementById('sidebar');<?php 
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
<?php document.getElementById('banner-upload').addEventListener('change',<?php function()<?php {<?php 
<?php if<?php (this.files<?php &&<?php this.files[0])<?php {<?php 
<?php const<?php allowedTypes<?php =<?php ['image/jpeg',<?php 'image/png',<?php 'image/jpg'];<?php 
<?php if<?php (!allowedTypes.includes(this.files[0].type))<?php {<?php 
<?php Notiflix.Notify.failure('Formato<?php de<?php arquivo<?php inválido!<?php Use<?php apenas<?php JPG<?php ou<?php PNG.');<?php 
<?php this.value<?php =<?php '';<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php if<?php (this.files[0].size<?php ><?php 5<?php *<?php 1024<?php *<?php 1024)<?php {<?php 
<?php Notiflix.Notify.failure('Arquivo<?php muito<?php grande!<?php Tamanho<?php máximo:<?php 5MB');<?php 
<?php this.value<?php =<?php '';<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php Notiflix.Loading.circle('Enviando<?php banner...');<?php 
<?php this.closest('form').submit();<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php function<?php openEditModal(bannerId,<?php bannerImg)<?php {<?php 
<?php document.getElementById('editBannerId').value<?php =<?php bannerId;<?php 
<?php document.getElementById('currentImage').src<?php =<?php bannerImg;<?php 
<?php document.getElementById('editModal').classList.add('active');<?php 
<?php document.body.style.overflow<?php =<?php 'hidden';<?php 
<?php }<?php 
<?php 
<?php function<?php closeEditModal()<?php {<?php 
<?php document.getElementById('editModal').classList.remove('active');<?php 
<?php document.body.style.overflow<?php =<?php 'auto';<?php 
<?php document.getElementById('editForm').reset();<?php 
<?php document.getElementById('selectedFileName').style.display<?php =<?php 'none';<?php 
<?php }<?php 
<?php 
<?php document.getElementById('editBannerInput').addEventListener('change',<?php function()<?php {<?php 
<?php const<?php fileNameDiv<?php =<?php document.getElementById('selectedFileName');<?php 
<?php if<?php (this.files<?php &&<?php this.files[0])<?php {<?php 
<?php const<?php allowedTypes<?php =<?php ['image/jpeg',<?php 'image/png',<?php 'image/jpg'];<?php 
<?php if<?php (!allowedTypes.includes(this.files[0].type))<?php {<?php 
<?php Notiflix.Notify.failure('Formato<?php de<?php arquivo<?php inválido!<?php Use<?php apenas<?php JPG<?php ou<?php PNG.');<?php 
<?php this.value<?php =<?php '';<?php 
<?php fileNameDiv.style.display<?php =<?php 'none';<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php if<?php (this.files[0].size<?php ><?php 5<?php *<?php 1024<?php *<?php 1024)<?php {<?php 
<?php Notiflix.Notify.failure('Arquivo<?php muito<?php grande!<?php Tamanho<?php máximo:<?php 5MB');<?php 
<?php this.value<?php =<?php '';<?php 
<?php fileNameDiv.style.display<?php =<?php 'none';<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php fileNameDiv.textContent<?php =<?php '✓<?php '<?php +<?php this.files[0].name;<?php 
<?php fileNameDiv.style.display<?php =<?php 'block';<?php 
<?php }<?php else<?php {<?php 
<?php fileNameDiv.style.display<?php =<?php 'none';<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php document.getElementById('editModal').addEventListener('click',<?php function(e)<?php {<?php 
<?php if<?php (e.target<?php ===<?php this)<?php {<?php 
<?php closeEditModal();<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {<?php 
<?php console.log('%c🖼️<?php Gestão<?php de<?php Banners<?php carregada!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php }<?php 
<?php 
<?php const<?php containers<?php =<?php document.querySelectorAll('.content-container');<?php 
<?php containers.forEach((container,<?php index)<?php =><?php {<?php 
<?php container.style.opacity<?php =<?php '0';<?php 
<?php container.style.transform<?php =<?php 'translateY(30px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php container.style.transition<?php =<?php 'all<?php 0.8s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1)';<?php 
<?php container.style.opacity<?php =<?php '1';<?php 
<?php container.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php 200<?php +<?php (index<?php *<?php 150));<?php 
<?php });<?php 
<?php 
<?php const<?php bannerCards<?php =<?php document.querySelectorAll('.banner-card');<?php 
<?php bannerCards.forEach((card,<?php index)<?php =><?php {<?php 
<?php card.style.opacity<?php =<?php '0';<?php 
<?php card.style.transform<?php =<?php 'translateY(20px)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php card.style.transition<?php =<?php 'all<?php 0.6s<?php ease';<?php 
<?php card.style.opacity<?php =<?php '1';<?php 
<?php card.style.transform<?php =<?php 'translateY(0)';<?php 
<?php },<?php 600<?php +<?php (index<?php *<?php 100));<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';<?php 
<?php </script><?php 
</body><?php 
</html>