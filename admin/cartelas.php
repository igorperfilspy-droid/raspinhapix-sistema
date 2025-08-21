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
if<?php (isset($_POST['adicionar_raspadinha']))<?php {<?php 
<?php $nome<?php =<?php $_POST['nome'];<?php 
<?php $descricao<?php =<?php $_POST['descricao'];<?php 
<?php $valor<?php =<?php str_replace(',',<?php '.',<?php $_POST['valor']);<?php 
<?php 
<?php $banner<?php =<?php '';<?php 
<?php if<?php (isset($_FILES['banner'])<?php &&<?php $_FILES['banner']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['banner']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/img/banners/';<?php 
<?php if<?php (!file_exists($uploadDir))<?php {<?php 
<?php mkdir($uploadDir,<?php 0777,<?php true);<?php 
<?php }<?php 
<?php 
<?php $newName<?php =<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['banner']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php $banner<?php =<?php '/assets/img/banners/'<?php .<?php $newName;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php do<?php banner!';<?php 
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
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php raspadinhas<?php (nome,<?php descricao,<?php banner,<?php valor)<?php VALUES<?php (?,<?php ?,<?php ?,<?php ?)");<?php 
<?php if<?php ($stmt->execute([$nome,<?php $descricao,<?php $banner,<?php $valor]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Raspadinha<?php adicionada<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php adicionar<?php raspadinha!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_POST['editar_raspadinha']))<?php {<?php 
<?php $id<?php =<?php $_POST['id'];<?php 
<?php $nome<?php =<?php $_POST['nome'];<?php 
<?php $descricao<?php =<?php $_POST['descricao'];<?php 
<?php $valor<?php =<?php str_replace(',',<?php '.',<?php $_POST['valor']);<?php 
<?php 
<?php $raspadinha<?php =<?php $pdo->prepare("SELECT<?php banner<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $raspadinha->execute([$id]);<?php 
<?php $raspadinha<?php =<?php $raspadinha->fetch(PDO::FETCH_ASSOC);<?php 
<?php $banner<?php =<?php $raspadinha['banner'];<?php 
<?php 
<?php if<?php (isset($_FILES['banner'])<?php &&<?php $_FILES['banner']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['banner']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/img/banners/';<?php 
<?php $newName<?php =<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['banner']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php if<?php ($banner<?php &&<?php file_exists('../'<?php .<?php $banner))<?php {<?php 
<?php unlink('../'<?php .<?php $banner);<?php 
<?php }<?php 
<?php $banner<?php =<?php '/assets/img/banners/'<?php .<?php $newName;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php do<?php novo<?php banner!';<?php 
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
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php raspadinhas<?php SET<?php nome<?php =<?php ?,<?php descricao<?php =<?php ?,<?php banner<?php =<?php ?,<?php valor<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$nome,<?php $descricao,<?php $banner,<?php $valor,<?php $id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Raspadinha<?php atualizada<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php raspadinha!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_GET['excluir_raspadinha']))<?php {<?php 
<?php $id<?php =<?php $_GET['id'];<?php 
<?php 
<?php $raspadinha<?php =<?php $pdo->prepare("SELECT<?php banner<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $raspadinha->execute([$id]);<?php 
<?php $raspadinha<?php =<?php $raspadinha->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
<?php $pdo->prepare("DELETE<?php FROM<?php raspadinha_premios<?php WHERE<?php raspadinha_id<?php =<?php ?")->execute([$id]);<?php 
<?php 
<?php if<?php ($pdo->prepare("DELETE<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?")->execute([$id]))<?php {<?php 
<?php if<?php ($raspadinha['banner']<?php &&<?php file_exists('../'<?php .<?php $raspadinha['banner']))<?php {<?php 
<?php unlink('../'<?php .<?php $raspadinha['banner']);<?php 
<?php }<?php 
<?php $_SESSION['success']<?php =<?php 'Raspadinha<?php excluída<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php excluir<?php raspadinha!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF']);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_POST['adicionar_premio']))<?php {<?php 
<?php $raspadinha_id<?php =<?php $_POST['raspadinha_id'];<?php 
<?php $nome<?php =<?php $_POST['nome'];<?php 
<?php $valor<?php =<?php str_replace(',',<?php '.',<?php $_POST['valor']);<?php 
<?php $probabilidade<?php =<?php str_replace(',',<?php '.',<?php $_POST['probabilidade']);<?php 
<?php 
<?php $icone<?php =<?php '';<?php 
<?php if<?php (isset($_FILES['icone'])<?php &&<?php $_FILES['icone']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['icone']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/img/icons/';<?php 
<?php if<?php (!file_exists($uploadDir))<?php {<?php 
<?php mkdir($uploadDir,<?php 0777,<?php true);<?php 
<?php }<?php 
<?php 
<?php $newName<?php =<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['icone']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php $icone<?php =<?php '/assets/img/icons/'<?php .<?php $newName;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php do<?php ícone!';<?php 
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
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php raspadinha_premios<?php (raspadinha_id,<?php nome,<?php icone,<?php valor,<?php probabilidade)<?php VALUES<?php (?,<?php ?,<?php ?,<?php ?,<?php ?)");<?php 
<?php if<?php ($stmt->execute([$raspadinha_id,<?php $nome,<?php $icone,<?php $valor,<?php $probabilidade]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Prêmio<?php adicionado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php adicionar<?php prêmio!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF'].'?raspadinha_id='.$raspadinha_id);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_POST['editar_premio']))<?php {<?php 
<?php $id<?php =<?php $_POST['id'];<?php 
<?php $raspadinha_id<?php =<?php $_POST['raspadinha_id'];<?php 
<?php $nome<?php =<?php $_POST['nome'];<?php 
<?php $valor<?php =<?php str_replace(',',<?php '.',<?php $_POST['valor']);<?php 
<?php $probabilidade<?php =<?php str_replace(',',<?php '.',<?php $_POST['probabilidade']);<?php 
<?php 
<?php $premio<?php =<?php $pdo->prepare("SELECT<?php icone<?php FROM<?php raspadinha_premios<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $premio->execute([$id]);<?php 
<?php $premio<?php =<?php $premio->fetch(PDO::FETCH_ASSOC);<?php 
<?php $icone<?php =<?php $premio['icone'];<?php 
<?php 
<?php if<?php (isset($_FILES['icone'])<?php &&<?php $_FILES['icone']['error']<?php ==<?php 0)<?php {<?php 
<?php $allowed<?php =<?php ['jpg',<?php 'jpeg',<?php 'png'];<?php 
<?php $ext<?php =<?php pathinfo($_FILES['icone']['name'],<?php PATHINFO_EXTENSION);<?php 
<?php 
<?php if<?php (in_array(strtolower($ext),<?php $allowed))<?php {<?php 
<?php $uploadDir<?php =<?php '../assets/img/icons/';<?php 
<?php $newName<?php =<?php uniqid()<?php .<?php '.'<?php .<?php $ext;<?php 
<?php $uploadPath<?php =<?php $uploadDir<?php .<?php $newName;<?php 
<?php 
<?php if<?php (move_uploaded_file($_FILES['icone']['tmp_name'],<?php $uploadPath))<?php {<?php 
<?php if<?php ($icone<?php &&<?php file_exists('../'<?php .<?php $icone))<?php {<?php 
<?php unlink('../'<?php .<?php $icone);<?php 
<?php }<?php 
<?php $icone<?php =<?php '/assets/img/icons/'<?php .<?php $newName;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php fazer<?php upload<?php do<?php novo<?php ícone!';<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF'].'?raspadinha_id='.$raspadinha_id);<?php 
<?php exit;<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Formato<?php de<?php arquivo<?php inválido!<?php Use<?php apenas<?php JPG<?php ou<?php PNG.';<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF'].'?raspadinha_id='.$raspadinha_id);<?php 
<?php exit;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php raspadinha_premios<?php SET<?php nome<?php =<?php ?,<?php icone<?php =<?php ?,<?php valor<?php =<?php ?,<?php probabilidade<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php if<?php ($stmt->execute([$nome,<?php $icone,<?php $valor,<?php $probabilidade,<?php $id]))<?php {<?php 
<?php $_SESSION['success']<?php =<?php 'Prêmio<?php atualizado<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php atualizar<?php prêmio!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF'].'?raspadinha_id='.$raspadinha_id);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php (isset($_GET['excluir_premio']))<?php {<?php 
<?php $id<?php =<?php $_GET['id'];<?php 
<?php $raspadinha_id<?php =<?php $_GET['raspadinha_id'];<?php 
<?php 
<?php $premio<?php =<?php $pdo->prepare("SELECT<?php icone<?php FROM<?php raspadinha_premios<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $premio->execute([$id]);<?php 
<?php $premio<?php =<?php $premio->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
<?php if<?php ($pdo->prepare("DELETE<?php FROM<?php raspadinha_premios<?php WHERE<?php id<?php =<?php ?")->execute([$id]))<?php {<?php 
<?php if<?php ($premio['icone']<?php &&<?php file_exists('../'<?php .<?php $premio['icone']))<?php {<?php 
<?php unlink('../'<?php .<?php $premio['icone']);<?php 
<?php }<?php 
<?php $_SESSION['success']<?php =<?php 'Prêmio<?php excluído<?php com<?php sucesso!';<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['failure']<?php =<?php 'Erro<?php ao<?php excluir<?php prêmio!';<?php 
<?php }<?php 
<?php header('Location:<?php '.$_SERVER['PHP_SELF'].'?raspadinha_id='.$raspadinha_id);<?php 
<?php exit;<?php 
}<?php 
<?php 
$raspadinhas<?php =<?php $pdo->query("SELECT<?php *<?php FROM<?php raspadinhas<?php ORDER<?php BY<?php created_at<?php DESC")->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
//<?php Calculate<?php statistics<?php 
$total_raspadinhas<?php =<?php count($raspadinhas);<?php 
$valor_total_raspadinhas<?php =<?php array_sum(array_column($raspadinhas,<?php 'valor'));<?php 
<?php 
$premios<?php =<?php [];<?php 
$raspadinha_selecionada<?php =<?php null;<?php 
if<?php (isset($_GET['raspadinha_id']))<?php {<?php 
<?php $raspadinha_id<?php =<?php $_GET['raspadinha_id'];<?php 
<?php $premios<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinha_premios<?php WHERE<?php raspadinha_id<?php =<?php ?<?php ORDER<?php BY<?php probabilidade<?php DESC");<?php 
<?php $premios->execute([$raspadinha_id]);<?php 
<?php $premios<?php =<?php $premios->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
<?php $raspadinha_selecionada<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $raspadinha_selecionada->execute([$raspadinha_id]);<?php 
<?php $raspadinha_selecionada<?php =<?php $raspadinha_selecionada->fetch(PDO::FETCH_ASSOC);<?php 
}<?php 
<?php 
$total_premios<?php =<?php 0;<?php 
$valor_total_premios<?php =<?php 0;<?php 
if<?php (!empty($premios))<?php {<?php 
<?php $total_premios<?php =<?php count($premios);<?php 
<?php $valor_total_premios<?php =<?php array_sum(array_column($premios,<?php 'valor'));<?php 
}<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite<?php ??<?php 'Admin';<?php ?><?php -<?php Gerenciar<?php Raspadinhas</title><?php 
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
<?php /*<?php Advanced<?php Sidebar<?php Styles<?php -<?php Same<?php as<?php depositos.php<?php */<?php 
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
<?php .header-actions<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
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
<?php .mini-stat-icon.purple<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(147,<?php 51,<?php 234,<?php 0.2)<?php 0%,<?php rgba(147,<?php 51,<?php 234,<?php 0.1)<?php 100%);<?php 
<?php border-color:<?php rgba(147,<?php 51,<?php 234,<?php 0.3);<?php 
<?php color:<?php #9333ea;<?php 
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
<?php /*<?php Form<?php Section<?php */<?php 
<?php .form-section<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php }<?php 
<?php 
<?php .form-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-icon-container<?php {<?php 
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
<?php .form-title<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php }<?php 
<?php 
<?php .form-group<?php {<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-label<?php {<?php 
<?php display:<?php block;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-input<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 0.75rem<?php 1rem;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .form-input:focus<?php {<?php 
<?php outline:<?php none;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .form-input::placeholder<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .form-button<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border:<?php none;<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 0.875rem<?php 1.5rem;<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-button:hover<?php {<?php 
<?php transform:<?php translateY(-1px);<?php 
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .cancel-button<?php {<?php 
<?php width:<?php 100%;<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);<?php 
<?php color:<?php #e5e7eb;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 0.75rem<?php 1.5rem;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-decoration:<?php none;<?php 
<?php text-align:<?php center;<?php 
<?php display:<?php block;<?php 
<?php margin-top:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .cancel-button:hover<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php /*<?php Raspadinha<?php Cards<?php */<?php 
<?php .raspadinha-card<?php {<?php 
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
<?php .raspadinha-card::before<?php {<?php 
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
<?php .raspadinha-card:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .raspadinha-card:hover<?php {<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .raspadinha-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php flex-start;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .raspadinha-name<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .raspadinha-description<?php {<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php color:<?php #9ca3af;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .raspadinha-banner<?php {<?php 
<?php width:<?php 80px;<?php 
<?php height:<?php 50px;<?php 
<?php border-radius:<?php 8px;<?php 
<?php object-fit:<?php cover;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .raspadinha-value<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php -webkit-background-clip:<?php text;<?php 
<?php -webkit-text-fill-color:<?php transparent;<?php 
<?php background-clip:<?php text;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .raspadinha-date<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.875rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php padding-top:<?php 1rem;<?php 
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .raspadinha-date<?php i<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .action-buttons<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php margin-top:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .action-btn<?php {<?php 
<?php flex:<?php 1;<?php 
<?php padding:<?php 0.75rem<?php 1rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-decoration:<?php none;<?php 
<?php text-align:<?php center;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .action-btn:hover<?php {<?php 
<?php transform:<?php translateY(-1px);<?php 
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .btn-manage<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #3b82f6,<?php #2563eb);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-edit<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .btn-delete<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php /*<?php Prize<?php Cards<?php */<?php 
<?php .prize-card<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .prize-card:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.15);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php }<?php 
<?php 
<?php .prize-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .prize-name<?php {<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php white;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .prize-icon<?php {<?php 
<?php width:<?php 32px;<?php 
<?php height:<?php 32px;<?php 
<?php border-radius:<?php 8px;<?php 
<?php object-fit:<?php cover;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .prize-info<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .prize-stat<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .prize-stat<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php width:<?php 16px;<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php .prize-actions<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .prize-action-btn<?php {<?php 
<?php flex:<?php 1;<?php 
<?php padding:<?php 0.5rem<?php 1rem;<?php 
<?php border-radius:<?php 8px;<?php 
<?php font-size:<?php 0.85rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-decoration:<?php none;<?php 
<?php text-align:<?php center;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .prize-edit-btn<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .prize-delete-btn<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);<?php 
<?php color:<?php white;<?php 
<?php }<?php 
<?php 
<?php .prize-action-btn:hover<?php {<?php 
<?php transform:<?php translateY(-1px);<?php 
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(0,<?php 0,<?php 0,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php /*<?php Selected<?php Raspadinha<?php Header<?php */<?php 
<?php .selected-raspadinha<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php space-between;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .selected-raspadinha<?php h3<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.3rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php margin:<?php 0;<?php 
<?php }<?php 
<?php 
<?php .selected-raspadinha<?php p<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php margin:<?php 0.5rem<?php 0<?php 0<?php 0;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .back-btn<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.3);<?php 
<?php color:<?php #e5e7eb;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 8px;<?php 
<?php padding:<?php 0.75rem<?php 1.5rem;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-decoration:<?php none;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .back-btn:hover<?php {<?php 
<?php background:<?php rgba(107,<?php 114,<?php 128,<?php 0.4);<?php 
<?php transform:<?php translateY(-1px);<?php 
<?php }<?php 
<?php 
<?php /*<?php Content<?php Grid<?php */<?php 
<?php .content-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(400px,<?php 1fr));<?php 
<?php gap:<?php 2rem;<?php /*<?php Aumentar<?php de<?php 1.5rem<?php para<?php 2rem<?php ou<?php mais<?php */<?php 
<?php }<?php 
<?php 
<?php .raspadinha-card<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2rem;<?php 
<?php margin-bottom:<?php 2rem;<?php /*<?php Adicionar<?php esta<?php linha<?php */<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php /*<?php Current<?php Image<?php Preview<?php */<?php 
<?php .current-image<?php {<?php 
<?php margin-top:<?php 0.5rem;<?php 
<?php padding:<?php 0.75rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.2);<?php 
<?php border-radius:<?php 8px;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .current-image<?php p<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .current-image<?php img<?php {<?php 
<?php max-width:<?php 100%;<?php 
<?php height:<?php auto;<?php 
<?php border-radius:<?php 6px;<?php 
<?php max-height:<?php 100px;<?php 
<?php }<?php 
<?php 
<?php /*<?php Empty<?php States<?php */<?php 
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
<?php /*<?php Scroll<?php Container<?php */<?php 
<?php .scroll-container<?php {<?php 
<?php max-height:<?php 500px;<?php 
<?php overflow-y:<?php auto;<?php 
<?php overflow-x:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .scroll-container::-webkit-scrollbar<?php {<?php 
<?php width:<?php 8px;<?php 
<?php }<?php 
<?php 
<?php .scroll-container::-webkit-scrollbar-track<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.1);<?php 
<?php border-radius:<?php 4px;<?php 
<?php }<?php 
<?php 
<?php .scroll-container::-webkit-scrollbar-thumb<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 4px;<?php 
<?php }<?php 
<?php 
<?php .scroll-container::-webkit-scrollbar-thumb:hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
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
<?php .content-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php 
<?php .raspadinhas-grid<?php {<?php 
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
<?php .raspadinha-card<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .raspadinha-header<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php align-items:<?php flex-start;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .action-buttons<?php {<?php 
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
<?php .raspadinha-value<?php {<?php 
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
<?php <a<?php href="cartelas.php"<?php class="nav-item<?php active"><?php 
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
<?php <h2<?php class="welcome-title">Gerenciar<?php Raspadinhas</h2><?php 
<?php <p<?php class="welcome-subtitle">Crie<?php e<?php configure<?php raspadinhas<?php e<?php seus<?php prêmios<?php de<?php forma<?php fácil<?php e<?php intuitiva</p><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Stats<?php Grid<?php --><?php 
<?php <section<?php class="stats-grid"><?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-ticket"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_raspadinhas,<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Total<?php de<?php Raspadinhas</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon<?php purple"><?php 
<?php <i<?php class="fas<?php fa-gift"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value"><?php=<?php number_format($total_premios,<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Total<?php de<?php Prêmios</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon"><?php 
<?php <i<?php class="fas<?php fa-dollar-sign"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($valor_total_raspadinhas,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Valor<?php Total<?php Raspadinhas</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="mini-stat-card"><?php 
<?php <div<?php class="mini-stat-header"><?php 
<?php <div<?php class="mini-stat-icon<?php purple"><?php 
<?php <i<?php class="fas<?php fa-trophy"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="mini-stat-value">R$<?php <?php=<?php number_format($valor_total_premios,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="mini-stat-label">Valor<?php Total<?php Prêmios</div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <?php<?php if<?php (isset($_GET['raspadinha_id'])):<?php ?><?php 
<?php <!--<?php Selected<?php Raspadinha<?php Header<?php --><?php 
<?php <div<?php class="selected-raspadinha"><?php 
<?php <div<?php class="flex-1"><?php 
<?php <h3>🎯<?php Gerenciando:<?php <?php=<?php htmlspecialchars($raspadinha_selecionada['nome'])<?php ?></h3><?php 
<?php <p>Configure<?php os<?php prêmios<?php desta<?php raspadinha</p><?php 
<?php </div><?php 
<?php <a<?php href="?"<?php class="back-btn"><?php 
<?php <i<?php class="fas<?php fa-arrow-left"></i><?php 
<?php Voltar<?php 
<?php </a><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php 
<?php <!--<?php Main<?php Content<?php Grid<?php --><?php 
<?php <div<?php class="content-grid"><?php 
<?php <!--<?php Form<?php Section<?php --><?php 
<?php <section<?php class="form-section"><?php 
<?php <div<?php class="form-header"><?php 
<?php <div<?php class="form-icon-container"><?php 
<?php <i<?php class="fas<?php fa-<?php=<?php isset($_GET['editar_raspadinha'])<?php ?<?php 'edit'<?php :<?php 'plus'<?php ?>"></i><?php 
<?php </div><?php 
<?php <h3<?php class="form-title"><?php 
<?php <?php=<?php isset($_GET['editar_raspadinha'])<?php ?<?php 'Editar'<?php :<?php 'Adicionar'<?php ?><?php Raspadinha<?php 
<?php </h3><?php 
<?php </div><?php 
<?php 
<?php <?php<?php 
<?php $raspadinha_edit<?php =<?php null;<?php 
<?php if<?php (isset($_GET['editar_raspadinha']))<?php {<?php 
<?php $id<?php =<?php $_GET['id'];<?php 
<?php $raspadinha_edit<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $raspadinha_edit->execute([$id]);<?php 
<?php $raspadinha_edit<?php =<?php $raspadinha_edit->fetch(PDO::FETCH_ASSOC);<?php 
<?php }<?php 
<?php ?><?php 
<?php 
<?php <form<?php method="POST"<?php enctype="multipart/form-data"><?php 
<?php <?php<?php if<?php ($raspadinha_edit):<?php ?><?php 
<?php <input<?php type="hidden"<?php name="id"<?php value="<?php=<?php $raspadinha_edit['id']<?php ?>"><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-signature"></i><?php 
<?php Nome<?php da<?php Raspadinha<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="nome"<?php value="<?php=<?php $raspadinha_edit<?php ?<?php htmlspecialchars($raspadinha_edit['nome'])<?php :<?php ''<?php ?>"<?php class="form-input"<?php placeholder="Digite<?php o<?php nome<?php da<?php raspadinha..."<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-align-left"></i><?php 
<?php Descrição<?php 
<?php </label><?php 
<?php <textarea<?php name="descricao"<?php class="form-input"<?php rows="3"<?php placeholder="Descreva<?php a<?php raspadinha..."<?php required><?php=<?php $raspadinha_edit<?php ?<?php htmlspecialchars($raspadinha_edit['descricao'])<?php :<?php ''<?php ?></textarea><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-dollar-sign"></i><?php 
<?php Valor<?php (R$)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="valor"<?php value="<?php=<?php $raspadinha_edit<?php ?<?php htmlspecialchars($raspadinha_edit['valor'])<?php :<?php ''<?php ?>"<?php class="form-input"<?php placeholder="0,00"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-image"></i><?php 
<?php Banner<?php da<?php Raspadinha<?php 
<?php </label><?php 
<?php <input<?php type="file"<?php name="banner"<?php accept="image/jpeg,<?php image/png"<?php class="form-input"><?php 
<?php <?php<?php if<?php ($raspadinha_edit<?php &&<?php $raspadinha_edit['banner']):<?php ?><?php 
<?php <div<?php class="current-image"><?php 
<?php <p>Banner<?php atual:</p><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($raspadinha_edit['banner'])<?php ?>"<?php alt="Banner<?php atual"><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <button<?php type="submit"<?php name="<?php=<?php $raspadinha_edit<?php ?<?php 'editar_raspadinha'<?php :<?php 'adicionar_raspadinha'<?php ?>"<?php class="form-button"><?php 
<?php <i<?php class="fas<?php fa-save"></i><?php 
<?php <?php=<?php $raspadinha_edit<?php ?<?php 'Atualizar'<?php :<?php 'Adicionar'<?php ?><?php Raspadinha<?php 
<?php </button><?php 
<?php 
<?php <?php<?php if<?php ($raspadinha_edit):<?php ?><?php 
<?php <a<?php href="?"<?php class="cancel-button"><?php 
<?php <i<?php class="fas<?php fa-times"></i><?php 
<?php Cancelar<?php 
<?php </a><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </form><?php 
<?php </section><?php 
<?php 
<?php <!--<?php List<?php Section<?php --><?php 
<?php <section<?php class="form-section"><?php 
<?php <div<?php class="form-header"><?php 
<?php <div<?php class="form-icon-container"><?php 
<?php <i<?php class="fas<?php fa-list"></i><?php 
<?php </div><?php 
<?php <h3<?php class="form-title">Raspadinhas<?php Cadastradas</h3><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="scroll-container"><?php 
<?php <?php<?php if<?php (empty($raspadinhas)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="fas<?php fa-ticket"></i><?php 
<?php <h3>Nenhuma<?php raspadinha<?php cadastrada</h3><?php 
<?php <p>Comece<?php criando<?php sua<?php primeira<?php raspadinha</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <?php<?php foreach<?php ($raspadinhas<?php as<?php $raspadinha):<?php ?><?php 
<?php <div<?php class="raspadinha-card"><?php 
<?php <div<?php class="raspadinha-header"><?php 
<?php <div<?php class="flex-1"><?php 
<?php <h3<?php class="raspadinha-name"><?php=<?php htmlspecialchars($raspadinha['nome'])<?php ?></h3><?php 
<?php <p<?php class="raspadinha-description"><?php=<?php htmlspecialchars($raspadinha['descricao'])<?php ?></p><?php 
<?php </div><?php 
<?php <?php<?php if<?php ($raspadinha['banner']):<?php ?><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($raspadinha['banner'])<?php ?>"<?php alt="Banner"<?php class="raspadinha-banner"><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="raspadinha-value"><?php 
<?php R$<?php <?php=<?php number_format($raspadinha['valor'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="raspadinha-date"><?php 
<?php <i<?php class="fas<?php fa-calendar"></i><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($raspadinha['created_at']))<?php ?></span><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="action-buttons"><?php 
<?php <a<?php href="?raspadinha_id=<?php=<?php $raspadinha['id']<?php ?>"<?php class="action-btn<?php btn-manage"><?php 
<?php <i<?php class="fas<?php fa-cog"></i><?php 
<?php Gerenciar<?php 
<?php </a><?php 
<?php <a<?php href="?editar_raspadinha&id=<?php=<?php $raspadinha['id']<?php ?>"<?php class="action-btn<?php btn-edit"><?php 
<?php <i<?php class="fas<?php fa-edit"></i><?php 
<?php Editar<?php 
<?php </a><?php 
<?php <a<?php href="?excluir_raspadinha&id=<?php=<?php $raspadinha['id']<?php ?>"<?php onclick="return<?php confirm('Tem<?php certeza<?php que<?php deseja<?php excluir<?php esta<?php raspadinha<?php e<?php todos<?php os<?php seus<?php prêmios?')"<?php class="action-btn<?php btn-delete"><?php 
<?php <i<?php class="fas<?php fa-trash"></i><?php 
<?php Excluir<?php 
<?php </a><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </section><?php 
<?php </div><?php 
<?php 
<?php <?php<?php if<?php (isset($_GET['raspadinha_id'])):<?php ?><?php 
<?php <!--<?php Prêmios<?php Section<?php --><?php 
<?php <div<?php class="content-grid"><?php 
<?php <!--<?php Add<?php Prize<?php Form<?php --><?php 
<?php <section<?php class="form-section"><?php 
<?php <div<?php class="form-header"><?php 
<?php <div<?php class="form-icon-container"><?php 
<?php <i<?php class="fas<?php fa-<?php=<?php isset($_GET['editar_premio'])<?php ?<?php 'edit'<?php :<?php 'gift'<?php ?>"></i><?php 
<?php </div><?php 
<?php <h3<?php class="form-title"><?php 
<?php <?php=<?php isset($_GET['editar_premio'])<?php ?<?php 'Editar'<?php :<?php 'Adicionar'<?php ?><?php Prêmio<?php 
<?php </h3><?php 
<?php </div><?php 
<?php 
<?php <?php<?php 
<?php $premio_edit<?php =<?php null;<?php 
<?php if<?php (isset($_GET['editar_premio']))<?php {<?php 
<?php $id<?php =<?php $_GET['id'];<?php 
<?php $premio_edit<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinha_premios<?php WHERE<?php id<?php =<?php ?");<?php 
<?php $premio_edit->execute([$id]);<?php 
<?php $premio_edit<?php =<?php $premio_edit->fetch(PDO::FETCH_ASSOC);<?php 
<?php }<?php 
<?php ?><?php 
<?php 
<?php <form<?php method="POST"<?php enctype="multipart/form-data"><?php 
<?php <input<?php type="hidden"<?php name="raspadinha_id"<?php value="<?php=<?php $_GET['raspadinha_id']<?php ?>"><?php 
<?php <?php<?php if<?php ($premio_edit):<?php ?><?php 
<?php <input<?php type="hidden"<?php name="id"<?php value="<?php=<?php $premio_edit['id']<?php ?>"><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-tag"></i><?php 
<?php Nome<?php do<?php Prêmio<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="nome"<?php value="<?php=<?php $premio_edit<?php ?<?php htmlspecialchars($premio_edit['nome'])<?php :<?php ''<?php ?>"<?php class="form-input"<?php placeholder="Digite<?php o<?php nome<?php do<?php prêmio..."<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-dollar-sign"></i><?php 
<?php Valor<?php (R$)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="valor"<?php value="<?php=<?php $premio_edit<?php ?<?php htmlspecialchars($premio_edit['valor'])<?php :<?php ''<?php ?>"<?php class="form-input"<?php placeholder="0,00"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-percentage"></i><?php 
<?php Probabilidade<?php (0.00<?php -<?php 100.00)<?php 
<?php </label><?php 
<?php <input<?php type="text"<?php name="probabilidade"<?php value="<?php=<?php $premio_edit<?php ?<?php htmlspecialchars($premio_edit['probabilidade'])<?php :<?php ''<?php ?>"<?php class="form-input"<?php placeholder="5.00"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <label<?php class="form-label"><?php 
<?php <i<?php class="fas<?php fa-image"></i><?php 
<?php Ícone<?php do<?php Prêmio<?php 
<?php </label><?php 
<?php <input<?php type="file"<?php name="icone"<?php accept="image/jpeg,<?php image/png"<?php class="form-input"><?php 
<?php <?php<?php if<?php ($premio_edit<?php &&<?php $premio_edit['icone']):<?php ?><?php 
<?php <div<?php class="current-image"><?php 
<?php <p>Ícone<?php atual:</p><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($premio_edit['icone'])<?php ?>"<?php alt="Ícone<?php atual"><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <button<?php type="submit"<?php name="<?php=<?php $premio_edit<?php ?<?php 'editar_premio'<?php :<?php 'adicionar_premio'<?php ?>"<?php class="form-button"><?php 
<?php <i<?php class="fas<?php fa-save"></i><?php 
<?php <?php=<?php $premio_edit<?php ?<?php 'Atualizar'<?php :<?php 'Adicionar'<?php ?><?php Prêmio<?php 
<?php </button><?php 
<?php 
<?php <?php<?php if<?php ($premio_edit):<?php ?><?php 
<?php <a<?php href="?raspadinha_id=<?php=<?php $_GET['raspadinha_id']<?php ?>"<?php class="cancel-button"><?php 
<?php <i<?php class="fas<?php fa-times"></i><?php 
<?php Cancelar<?php 
<?php </a><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </form><?php 
<?php </section><?php 
<?php 
<?php <!--<?php Prizes<?php List<?php --><?php 
<?php <section<?php class="form-section"><?php 
<?php <div<?php class="form-header"><?php 
<?php <div<?php class="form-icon-container"><?php 
<?php <i<?php class="fas<?php fa-gift"></i><?php 
<?php </div><?php 
<?php <h3<?php class="form-title">Prêmios<?php Cadastrados</h3><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="scroll-container"><?php 
<?php <?php<?php if<?php (empty($premios)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="fas<?php fa-gift"></i><?php 
<?php <h3>Nenhum<?php prêmio<?php cadastrado</h3><?php 
<?php <p>Adicione<?php prêmios<?php para<?php esta<?php raspadinha</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <?php<?php foreach<?php ($premios<?php as<?php $premio):<?php ?><?php 
<?php <div<?php class="prize-card"><?php 
<?php <div<?php class="prize-header"><?php 
<?php <div<?php class="prize-name"><?php 
<?php <?php<?php if<?php ($premio['icone']):<?php ?><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($premio['icone'])<?php ?>"<?php alt="Ícone"<?php class="prize-icon"><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php <?php=<?php htmlspecialchars($premio['nome'])<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="prize-info"><?php 
<?php <div<?php class="prize-stat"><?php 
<?php <i<?php class="fas<?php fa-dollar-sign"></i><?php 
<?php <span>R$<?php <?php=<?php number_format($premio['valor'],<?php 2,<?php ',',<?php '.')<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="prize-stat"><?php 
<?php <i<?php class="fas<?php fa-percentage"></i><?php 
<?php <span><?php=<?php number_format($premio['probabilidade'],<?php 2,<?php ',',<?php '.')<?php ?>%</span><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="prize-actions"><?php 
<?php <a<?php href="?raspadinha_id=<?php=<?php $_GET['raspadinha_id']<?php ?>&editar_premio&id=<?php=<?php $premio['id']<?php ?>"<?php class="prize-action-btn<?php prize-edit-btn"><?php 
<?php <i<?php class="fas<?php fa-edit"></i><?php 
<?php Editar<?php 
<?php </a><?php 
<?php <a<?php href="?raspadinha_id=<?php=<?php $_GET['raspadinha_id']<?php ?>&excluir_premio&id=<?php=<?php $premio['id']<?php ?>"<?php onclick="return<?php confirm('Tem<?php certeza<?php que<?php deseja<?php excluir<?php este<?php prêmio?')"<?php class="prize-action-btn<?php prize-delete-btn"><?php 
<?php <i<?php class="fas<?php fa-trash"></i><?php 
<?php Excluir<?php 
<?php </a><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </section><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
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
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php ()<?php =><?php {<?php 
<?php console.log('%c🎯<?php Raspadinhas<?php carregadas!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Auto-format<?php currency<?php inputs<?php 
<?php const<?php currencyInputs<?php =<?php document.querySelectorAll('input[name="valor"]');<?php 
<?php currencyInputs.forEach(input<?php =><?php {<?php 
<?php input.addEventListener('input',<?php function(e)<?php {<?php 
<?php let<?php value<?php =<?php e.target.value.replace(/[^\d,]/g,<?php '');<?php 
<?php e.target.value<?php =<?php value;<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Auto-format<?php percentage<?php inputs<?php 
<?php const<?php percentageInputs<?php =<?php document.querySelectorAll('input[name="probabilidade"]');<?php 
<?php percentageInputs.forEach(input<?php =><?php {<?php 
<?php input.addEventListener('input',<?php function(e)<?php {<?php 
<?php let<?php value<?php =<?php e.target.value.replace(/[^\d,]/g,<?php '');<?php 
<?php e.target.value<?php =<?php value;<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Check<?php if<?php mobile<?php on<?php load<?php 
<?php if<?php (window.innerWidth<?php <=<?php 1024)<?php {<?php 
<?php sidebar.classList.add('hidden');<?php 
<?php }<?php 
<?php 
<?php //<?php Animate<?php cards<?php on<?php load<?php 
<?php const<?php raspadinhaCards<?php =<?php document.querySelectorAll('.raspadinha-card,<?php .prize-card');<?php 
<?php raspadinhaCards.forEach((card,<?php index)<?php =><?php {<?php 
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
<?php 
<?php //<?php Scroll<?php to<?php top<?php when<?php editing<?php (smooth<?php behavior)<?php 
<?php const<?php currentUrl<?php =<?php new<?php URL(window.location.href);<?php 
<?php const<?php hasEditParams<?php =<?php currentUrl.searchParams.has('editar_raspadinha')<?php ||<?php 
<?php currentUrl.searchParams.has('editar_premio');<?php 
<?php 
<?php if<?php (hasEditParams)<?php {<?php 
<?php window.scrollTo({<?php top:<?php 0,<?php behavior:<?php 'smooth'<?php });<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Smooth<?php scroll<?php behavior<?php 
<?php document.documentElement.style.scrollBehavior<?php =<?php 'smooth';<?php 
<?php </script><?php 
</body><?php 
</html>