<?php<?php 
@session_start();<?php 
<?php 
if<?php (file_exists('./conexao.php'))<?php {<?php 
<?php include('./conexao.php');<?php 
}<?php elseif<?php (file_exists('../conexao.php'))<?php {<?php 
<?php include('../conexao.php');<?php 
}<?php elseif<?php (file_exists('../../conexao.php'))<?php {<?php 
<?php include('../../conexao.php');<?php 
}<?php 
<?php 
if<?php (!isset($_SESSION['usuario_id']))<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php precisa<?php estar<?php logado<?php para<?php acessar<?php esta<?php página!'];<?php 
<?php header("Location:<?php /login");<?php 
<?php exit;<?php 
}<?php 
<?php 
$usuario_id<?php =<?php $_SESSION['usuario_id'];<?php 
<?php 
try<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :id<?php LIMIT<?php 1");<?php 
<?php $stmt->bindParam(':id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt->execute();<?php 
<?php $usuario<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
<?php if<?php (!$usuario)<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Usuário<?php não<?php encontrado!'];<?php 
<?php header("Location:<?php /login");<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php $stmt_depositos<?php =<?php $pdo->prepare("SELECT<?php SUM(valor)<?php as<?php total_depositado<?php FROM<?php depositos<?php WHERE<?php user_id<?php =<?php :user_id<?php AND<?php status<?php =<?php 'PAID'");<?php 
<?php $stmt_depositos->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt_depositos->execute();<?php 
<?php $total_depositado<?php =<?php $stmt_depositos->fetch(PDO::FETCH_ASSOC)['total_depositado']<?php ??<?php 0;<?php 
<?php 
<?php $stmt_saques<?php =<?php $pdo->prepare("SELECT<?php SUM(valor)<?php as<?php total_sacado<?php FROM<?php saques<?php WHERE<?php user_id<?php =<?php :user_id<?php AND<?php status<?php =<?php 'PAID'");<?php 
<?php $stmt_saques->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt_saques->execute();<?php 
<?php $total_sacado<?php =<?php $stmt_saques->fetch(PDO::FETCH_ASSOC)['total_sacado']<?php ??<?php 0;<?php 
<?php 
}<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php carregar<?php dados<?php do<?php usuário!'];<?php 
}<?php 
<?php 
if<?php ($_SERVER['REQUEST_METHOD']<?php ===<?php 'POST')<?php {<?php 
<?php $senha_atual<?php =<?php $_POST['senha_atual']<?php ??<?php '';<?php 
<?php $nome<?php =<?php trim($_POST['nome']<?php ??<?php '');<?php 
<?php $telefone<?php =<?php trim($_POST['telefone']<?php ??<?php '');<?php 
<?php $email<?php =<?php trim($_POST['email']<?php ??<?php '');<?php 
<?php $nova_senha<?php =<?php $_POST['nova_senha']<?php ??<?php '';<?php 
<?php $confirmar_senha<?php =<?php $_POST['confirmar_senha']<?php ??<?php '';<?php 
<?php 
<?php if<?php (!password_verify($senha_atual,<?php $usuario['senha']))<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Senha<?php atual<?php incorreta!'];<?php 
<?php }<?php else<?php {<?php 
<?php try<?php {<?php 
<?php $dados<?php =<?php [<?php 
<?php 'id'<?php =><?php $usuario_id,<?php 
<?php 'nome'<?php =><?php $nome,<?php 
<?php 'telefone'<?php =><?php $telefone,<?php 
<?php 'email'<?php =><?php $email<?php 
<?php ];<?php 
<?php 
<?php if<?php (!empty($nova_senha))<?php {<?php 
<?php if<?php ($nova_senha<?php ===<?php $confirmar_senha)<?php {<?php 
<?php $dados['senha']<?php =<?php password_hash($nova_senha,<?php PASSWORD_BCRYPT);<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'As<?php novas<?php senhas<?php não<?php coincidem!'];<?php 
<?php header("Location:<?php /perfil");<?php 
<?php exit;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php $setParts<?php =<?php [];<?php 
<?php foreach<?php ($dados<?php as<?php $key<?php =><?php $value)<?php {<?php 
<?php if<?php ($key<?php !==<?php 'id')<?php {<?php 
<?php $setParts[]<?php =<?php "$key<?php =<?php :$key";<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php $query<?php =<?php "UPDATE<?php usuarios<?php SET<?php "<?php .<?php implode(',<?php ',<?php $setParts)<?php .<?php "<?php WHERE<?php id<?php =<?php :id";<?php 
<?php $stmt<?php =<?php $pdo->prepare($query);<?php 
<?php 
<?php if<?php ($stmt->execute($dados))<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'success',<?php 'text'<?php =><?php 'Perfil<?php atualizado<?php com<?php sucesso!'];<?php 
<?php header("Location:<?php /perfil");<?php 
<?php exit;<?php 
<?php }<?php else<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php atualizar<?php perfil!'];<?php 
<?php }<?php 
<?php 
<?php }<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php atualizar<?php perfil:<?php '<?php .<?php $e->getMessage()];<?php 
<?php }<?php 
<?php }<?php 
}<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Meu<?php Perfil</title><?php 
<?php 
<?php <!--<?php Fonts<?php --><?php 
<?php <link<?php rel="preconnect"<?php href="https://fonts.googleapis.com"><?php 
<?php <link<?php rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin><?php 
<?php <link<?php href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"<?php rel="stylesheet"><?php 
<?php 
<?php <!--<?php Icons<?php --><?php 
<?php <link<?php rel="stylesheet"<?php href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"><?php 
<?php 
<?php <!--<?php Styles<?php --><?php 
<?php <link<?php rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php=<?php time();<?php ?>"><?php 
<?php 
<?php <!--<?php Scripts<?php --><?php 
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
<?php 
<?php <style><?php 
<?php /*<?php Page<?php Styles<?php */<?php 
<?php .perfil-section<?php {<?php 
<?php margin-top:<?php 100px;<?php 
<?php padding:<?php 4rem<?php 0;<?php 
<?php background:<?php #0a0a0a;<?php 
<?php min-height:<?php calc(100vh<?php -<?php 200px);<?php 
<?php }<?php 
<?php 
<?php .perfil-container<?php {<?php 
<?php max-width:<?php 900px;<?php 
<?php margin:<?php 0<?php auto;<?php 
<?php padding:<?php 0<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Header<?php */<?php 
<?php .page-header<?php {<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
<?php .page-title<?php {<?php 
<?php font-size:<?php 2.5rem;<?php 
<?php font-weight:<?php 900;<?php 
<?php color:<?php white;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #9ca3af);<?php 
<?php background-clip:<?php text;<?php 
<?php -webkit-background-clip:<?php text;<?php 
<?php -webkit-text-fill-color:<?php transparent;<?php 
<?php }<?php 
<?php 
<?php .page-subtitle<?php {<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php max-width:<?php 500px;<?php 
<?php margin:<?php 0<?php auto;<?php 
<?php }<?php 
<?php 
<?php /*<?php User<?php Avatar<?php */<?php 
<?php .user-avatar<?php {<?php 
<?php width:<?php 100px;<?php 
<?php height:<?php 100px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 50%;<?php 
<?php margin:<?php 0<?php auto<?php 2rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 2.5rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php box-shadow:<?php 0<?php 8px<?php 32px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .user-avatar::after<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php inset:<?php -4px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 50%;<?php 
<?php z-index:<?php -1;<?php 
<?php opacity:<?php 0.3;<?php 
<?php animation:<?php pulse<?php 2s<?php infinite;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php pulse<?php {<?php 
<?php 0%,<?php 100%<?php {<?php transform:<?php scale(1);<?php opacity:<?php 0.3;<?php }<?php 
<?php 50%<?php {<?php transform:<?php scale(1.1);<?php opacity:<?php 0.1;<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Stats<?php Cards<?php */<?php 
<?php .stats-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));<?php 
<?php gap:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
/*<?php Ajustes<?php específicos<?php para<?php os<?php cards<?php de<?php estatísticas<?php */<?php 
<?php 
.stat-card<?php {<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.02);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 20px;<?php 
<?php padding:<?php 2rem;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php min-height:<?php 140px;<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php justify-content:<?php space-between;<?php 
}<?php 
<?php 
.stat-card:hover<?php {<?php 
<?php transform:<?php translateY(-4px);<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php box-shadow:<?php 0<?php 10px<?php 40px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
}<?php 
<?php 
.stat-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 4px;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php var(--accent-color);<?php 
}<?php 
<?php 
.stat-card.saldo::before<?php {<?php 
<?php background:<?php linear-gradient(180deg,<?php #22c55e,<?php #16a34a);<?php 
}<?php 
<?php 
.stat-card.depositos::before<?php {<?php 
<?php background:<?php linear-gradient(180deg,<?php #3b82f6,<?php #2563eb);<?php 
}<?php 
<?php 
.stat-card.saques::before<?php {<?php 
<?php background:<?php linear-gradient(180deg,<?php #f59e0b,<?php #d97706);<?php 
}<?php 
<?php 
.stat-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php flex-start;<?php 
<?php justify-content:<?php space-between;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php gap:<?php 1.25rem;<?php 
}<?php 
<?php 
.stat-info<?php {<?php 
<?php flex:<?php 1;<?php 
<?php min-width:<?php 0;<?php 
}<?php 
<?php 
.stat-info<?php h3<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.1em;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php line-height:<?php 1.2;<?php 
}<?php 
<?php 
.stat-value<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php font-weight:<?php 900;<?php 
<?php color:<?php white;<?php 
<?php line-height:<?php 1.1;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
}<?php 
<?php 
.stat-icon<?php {<?php 
<?php width:<?php 52px;<?php 
<?php height:<?php 52px;<?php 
<?php border-radius:<?php 14px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 1.3rem;<?php 
<?php flex-shrink:<?php 0;<?php 
<?php margin-top:<?php 0.25rem;<?php 
}<?php 
<?php 
.stat-icon.saldo<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.15);<?php 
<?php color:<?php #22c55e;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
}<?php 
<?php 
.stat-icon.depositos<?php {<?php 
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.15);<?php 
<?php color:<?php #3b82f6;<?php 
<?php border:<?php 1px<?php solid<?php rgba(59,<?php 130,<?php 246,<?php 0.2);<?php 
}<?php 
<?php 
.stat-icon.saques<?php {<?php 
<?php background:<?php rgba(245,<?php 158,<?php 11,<?php 0.15);<?php 
<?php color:<?php #f59e0b;<?php 
<?php border:<?php 1px<?php solid<?php rgba(245,<?php 158,<?php 11,<?php 0.2);<?php 
}<?php 
<?php 
.stat-footer<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php margin-top:<?php auto;<?php 
<?php padding-top:<?php 0.75rem;<?php 
}<?php 
<?php 
/*<?php Ajustes<?php responsivos<?php melhorados<?php */<?php 
@media<?php (max-width:<?php 768px)<?php {<?php 
<?php .stat-card<?php {<?php 
<?php padding:<?php 1.75rem;<?php 
<?php min-height:<?php 120px;<?php 
<?php }<?php 
<?php 
<?php .stat-header<?php {<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php }<?php 
<?php 
<?php .stat-info<?php h3<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .stat-value<?php {<?php 
<?php font-size:<?php 1.7rem;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php }<?php 
<?php 
<?php .stat-icon<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php }<?php 
<?php 
<?php .stat-footer<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php padding-top:<?php 0.5rem;<?php 
<?php }<?php 
}<?php 
<?php 
@media<?php (max-width:<?php 480px)<?php {<?php 
<?php .stat-card<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php min-height:<?php 110px;<?php 
<?php }<?php 
<?php 
<?php .stat-header<?php {<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .stat-value<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .stat-icon<?php {<?php 
<?php width:<?php 44px;<?php 
<?php height:<?php 44px;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php margin-top:<?php 0;<?php 
<?php }<?php 
}<?php 
<?php 
/*<?php Melhorias<?php no<?php grid<?php das<?php estatísticas<?php */<?php 
.stats-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));<?php 
<?php gap:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 3rem;<?php 
}<?php 
<?php 
@media<?php (max-width:<?php 900px)<?php {<?php 
<?php .stats-grid<?php {<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));<?php 
<?php gap:<?php 1.25rem;<?php 
<?php }<?php 
}<?php 
<?php 
@media<?php (max-width:<?php 768px)<?php {<?php 
<?php .stats-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
}<?php 
<?php 
/*<?php Animações<?php melhoradas<?php */<?php 
.stat-card<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php animation:<?php slideInUp<?php 0.6s<?php ease-out<?php forwards;<?php 
}<?php 
<?php 
.stat-card:nth-child(1)<?php {<?php animation-delay:<?php 0.1s;<?php }<?php 
.stat-card:nth-child(2)<?php {<?php animation-delay:<?php 0.2s;<?php }<?php 
.stat-card:nth-child(3)<?php {<?php animation-delay:<?php 0.3s;<?php }<?php 
<?php 
@keyframes<?php slideInUp<?php {<?php 
<?php from<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php transform:<?php translateY(30px);<?php 
<?php }<?php 
<?php to<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php transform:<?php translateY(0);<?php 
<?php }<?php 
}<?php 
<?php 
<?php /*<?php Main<?php Form<?php Card<?php */<?php 
<?php .form-card<?php {<?php 
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 3rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .form-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php right:<?php 0;<?php 
<?php width:<?php 150px;<?php 
<?php height:<?php 150px;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php transparent);<?php 
<?php border-radius:<?php 50%;<?php 
<?php transform:<?php translate(50%,<?php -50%);<?php 
<?php }<?php 
<?php 
<?php .form-header<?php {<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .form-icon<?php {<?php 
<?php width:<?php 60px;<?php 
<?php height:<?php 60px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 16px;<?php 
<?php margin:<?php 0<?php auto<?php 1.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php box-shadow:<?php 0<?php 8px<?php 24px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .form-title<?php {<?php 
<?php font-size:<?php 1.8rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php white;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-description<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Form<?php Styles<?php */<?php 
<?php .form-grid<?php {<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php gap:<?php 1.5rem;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .form-group<?php {<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .form-input<?php {<?php 
<?php width:<?php 100%;<?php 
<?php padding:<?php 1rem<?php 1rem<?php 1rem<?php 3rem;<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .form-input:focus<?php {<?php 
<?php outline:<?php none;<?php 
<?php border-color:<?php #22c55e;<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.08);<?php 
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .form-input::placeholder<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php .input-icon<?php {<?php 
<?php position:<?php absolute;<?php 
<?php left:<?php 1rem;<?php 
<?php top:<?php 50%;<?php 
<?php transform:<?php translateY(-50%);<?php 
<?php color:<?php #6b7280;<?php 
<?php font-size:<?php 1rem;<?php 
<?php transition:<?php color<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .form-group:focus-within<?php .input-icon<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php /*<?php Password<?php Toggle<?php */<?php 
<?php .password-toggle<?php {<?php 
<?php background:<?php none;<?php 
<?php border:<?php none;<?php 
<?php color:<?php #22c55e;<?php 
<?php cursor:<?php pointer;<?php 
<?php padding:<?php 0.5rem<?php 0;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 500;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php transition:<?php color<?php 0.3s<?php ease;<?php 
<?php margin:<?php 1rem<?php 0;<?php 
<?php }<?php 
<?php 
<?php .password-toggle:hover<?php {<?php 
<?php color:<?php #16a34a;<?php 
<?php }<?php 
<?php 
<?php .password-fields<?php {<?php 
<?php display:<?php none;<?php 
<?php flex-direction:<?php column;<?php 
<?php gap:<?php 1.5rem;<?php 
<?php margin:<?php 1.5rem<?php 0;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.05);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border-radius:<?php 16px;<?php 
<?php }<?php 
<?php 
<?php .password-fields.active<?php {<?php 
<?php display:<?php flex;<?php 
<?php }<?php 
<?php 
<?php .password-fields-title<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.05em;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Submit<?php Button<?php */<?php 
<?php .submit-btn<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border:<?php none;<?php 
<?php padding:<?php 1rem<?php 2rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php box-shadow:<?php 0<?php 4px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php margin-top:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .submit-btn:hover<?php {<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 30px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php .submit-btn:disabled<?php {<?php 
<?php opacity:<?php 0.6;<?php 
<?php cursor:<?php not-allowed;<?php 
<?php transform:<?php none;<?php 
<?php }<?php 
<?php 
<?php /*<?php Success<?php Message<?php */<?php 
<?php .success-message<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php 
<?php .success-message.active<?php {<?php 
<?php display:<?php block;<?php 
<?php animation:<?php slideIn<?php 0.3s<?php ease-out;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php slideIn<?php {<?php 
<?php from<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php transform:<?php translateY(-10px);<?php 
<?php }<?php 
<?php to<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php transform:<?php translateY(0);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Security<?php Tips<?php */<?php 
<?php .security-tips<?php {<?php 
<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.05);<?php 
<?php border:<?php 1px<?php solid<?php rgba(59,<?php 130,<?php 246,<?php 0.1);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-top:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .security-title<?php {<?php 
<?php color:<?php #3b82f6;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .security-list<?php {<?php 
<?php list-style:<?php none;<?php 
<?php padding:<?php 0;<?php 
<?php }<?php 
<?php 
<?php .security-list<?php li<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.85rem;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .security-list<?php i<?php {<?php 
<?php color:<?php #3b82f6;<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Responsive<?php */<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .perfil-container<?php {<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .page-title<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .form-card<?php {<?php 
<?php padding:<?php 2rem<?php 1.5rem;<?php 
<?php border-radius:<?php 20px;<?php 
<?php }<?php 
<?php 
<?php .stats-grid<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .stat-card<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .stat-header<?php {<?php 
<?php align-items:<?php flex-start;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .stat-value<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php line-height:<?php 1.3;<?php 
<?php }<?php 
<?php 
<?php .stat-icon<?php {<?php 
<?php width:<?php 45px;<?php 
<?php height:<?php 45px;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
<?php 
<?php .user-avatar<?php {<?php 
<?php width:<?php 80px;<?php 
<?php height:<?php 80px;<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 480px)<?php {<?php 
<?php .form-card<?php {<?php 
<?php padding:<?php 1.5rem<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .form-input<?php {<?php 
<?php padding:<?php 0.8rem<?php 0.8rem<?php 0.8rem<?php 2.5rem;<?php 
<?php }<?php 
<?php 
<?php .input-icon<?php {<?php 
<?php left:<?php 0.8rem;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Loading<?php States<?php */<?php 
<?php .loading<?php {<?php 
<?php opacity:<?php 0.7;<?php 
<?php pointer-events:<?php none;<?php 
<?php }<?php 
<?php 
<?php .loading<?php .submit-btn<?php {<?php 
<?php background:<?php #6b7280;<?php 
<?php }<?php 
<?php 
<?php /*<?php Animations<?php */<?php 
<?php .fade-in<?php {<?php 
<?php animation:<?php fadeIn<?php 0.6s<?php ease-out<?php forwards;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php fadeIn<?php {<?php 
<?php from<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php transform:<?php translateY(20px);<?php 
<?php }<?php 
<?php to<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php transform:<?php translateY(0);<?php 
<?php }<?php 
<?php }<?php 
<?php </style><?php 
</head><?php 
<body><?php 
<?php <?php<?php include('../inc/header.php');<?php ?><?php 
<?php <?php<?php include('../components/modals.php');<?php ?><?php 
<?php 
<?php <section<?php class="perfil-section"><?php 
<?php <div<?php class="perfil-container"><?php 
<?php <!--<?php Page<?php Header<?php --><?php 
<?php <div<?php class="page-header<?php fade-in"><?php 
<?php <div<?php class="user-avatar"><?php 
<?php <?php=<?php strtoupper(substr($usuario['nome'],<?php 0,<?php 2))<?php ?><?php 
<?php </div><?php 
<?php <h1<?php class="page-title">Meu<?php Perfil</h1><?php 
<?php <p<?php class="page-subtitle"><?php 
<?php Gerencie<?php suas<?php informações<?php pessoais<?php e<?php configurações<?php da<?php conta<?php 
<?php </p><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Stats<?php Grid<?php --><?php 
<?php <div<?php class="stats-grid"><?php 
<?php <div<?php class="stat-card<?php saldo"><?php 
<?php <div<?php class="stat-header"><?php 
<?php <div<?php class="stat-info"><?php 
<?php <h3>Saldo<?php Atual</h3><?php 
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($usuario['saldo']<?php ??<?php 0,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php </div><?php 
<?php <div<?php class="stat-icon<?php saldo"><?php 
<?php <i<?php class="bi<?php bi-wallet2"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="stat-card<?php depositos"><?php 
<?php <div<?php class="stat-header"><?php 
<?php <div<?php class="stat-info"><?php 
<?php <h3>Total<?php Depositado</h3><?php 
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($total_depositado,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php </div><?php 
<?php <div<?php class="stat-icon<?php depositos"><?php 
<?php <i<?php class="bi<?php bi-arrow-down-circle"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="stat-card<?php saques"><?php 
<?php <div<?php class="stat-header"><?php 
<?php <div<?php class="stat-info"><?php 
<?php <h3>Total<?php Sacado</h3><?php 
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($total_sacado,<?php 2,<?php ',',<?php '.')<?php ?></div><?php 
<?php </div><?php 
<?php <div<?php class="stat-icon<?php saques"><?php 
<?php <i<?php class="bi<?php bi-arrow-up-circle"></i><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Form<?php Card<?php --><?php 
<?php <div<?php class="form-card"><?php 
<?php <div<?php class="form-header"><?php 
<?php <div<?php class="form-icon"><?php 
<?php <i<?php class="bi<?php bi-person-gear"></i><?php 
<?php </div><?php 
<?php <h2<?php class="form-title">Editar<?php Perfil</h2><?php 
<?php <p<?php class="form-description"><?php 
<?php Atualize<?php suas<?php informações<?php pessoais<?php com<?php segurança<?php 
<?php </p><?php 
<?php </div><?php 
<?php 
<?php <form<?php method="POST"<?php class="form-grid"<?php id="perfilForm"><?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-person"></i><?php 
<?php </div><?php 
<?php <input<?php type="text"<?php 
<?php name="nome"<?php 
<?php class="form-input"<?php 
<?php value="<?php=<?php htmlspecialchars($usuario['nome']<?php ??<?php '')<?php ?>"<?php 
<?php placeholder="Nome<?php completo"<?php 
<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-telephone"></i><?php 
<?php </div><?php 
<?php <input<?php type="text"<?php 
<?php id="telefone"<?php 
<?php name="telefone"<?php 
<?php class="form-input"<?php 
<?php value="<?php=<?php htmlspecialchars($usuario['telefone']<?php ??<?php '')<?php ?>"<?php 
<?php placeholder="(11)<?php 99999-9999"<?php 
<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-envelope"></i><?php 
<?php </div><?php 
<?php <input<?php type="email"<?php 
<?php name="email"<?php 
<?php class="form-input"<?php 
<?php value="<?php=<?php htmlspecialchars($usuario['email']<?php ??<?php '')<?php ?>"<?php 
<?php placeholder="seu@email.com"<?php 
<?php required><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Password<?php Toggle<?php --><?php 
<?php <button<?php type="button"<?php class="password-toggle"<?php id="toggleSenha"><?php 
<?php <i<?php class="bi<?php bi-key"></i><?php 
<?php Alterar<?php senha<?php 
<?php </button><?php 
<?php 
<?php <!--<?php Password<?php Fields<?php --><?php 
<?php <div<?php class="password-fields"<?php id="camposSenha"><?php 
<?php <div<?php class="password-fields-title"><?php 
<?php <i<?php class="bi<?php bi-shield-lock"></i><?php 
<?php Nova<?php Senha<?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-lock"></i><?php 
<?php </div><?php 
<?php <input<?php type="password"<?php 
<?php name="nova_senha"<?php 
<?php class="form-input"<?php 
<?php placeholder="Digite<?php a<?php nova<?php senha"><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-lock-fill"></i><?php 
<?php </div><?php 
<?php <input<?php type="password"<?php 
<?php name="confirmar_senha"<?php 
<?php class="form-input"<?php 
<?php placeholder="Confirme<?php a<?php nova<?php senha"><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Current<?php Password<?php --><?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-shield-check"></i><?php 
<?php </div><?php 
<?php <input<?php type="password"<?php 
<?php name="senha_atual"<?php 
<?php class="form-input"<?php 
<?php placeholder="Senha<?php atual<?php (para<?php confirmar<?php alterações)"<?php 
<?php required><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Submit<?php Button<?php --><?php 
<?php <button<?php type="submit"<?php class="submit-btn"<?php id="submitBtn"><?php 
<?php <i<?php class="bi<?php bi-check-circle"></i><?php 
<?php Atualizar<?php Perfil<?php 
<?php </button><?php 
<?php </form><?php 
<?php 
<?php <!--<?php Security<?php Tips<?php --><?php 
<?php <div<?php class="security-tips"><?php 
<?php <div<?php class="security-title"><?php 
<?php <i<?php class="bi<?php bi-info-circle"></i><?php 
<?php Dicas<?php de<?php Segurança<?php 
<?php </div><?php 
<?php <ul<?php class="security-list"><?php 
<?php <li><?php 
<?php <i<?php class="bi<?php bi-check"></i><?php 
<?php Use<?php uma<?php senha<?php forte<?php com<?php pelo<?php menos<?php 8<?php caracteres<?php 
<?php </li><?php 
<?php <li><?php 
<?php <i<?php class="bi<?php bi-check"></i><?php 
<?php Nunca<?php compartilhe<?php sua<?php senha<?php com<?php terceiros<?php 
<?php </li><?php 
<?php <li><?php 
<?php <i<?php class="bi<?php bi-check"></i><?php 
<?php Mantenha<?php seus<?php dados<?php sempre<?php atualizados<?php 
<?php </li><?php 
<?php <li><?php 
<?php <i<?php class="bi<?php bi-check"></i><?php 
<?php Use<?php um<?php e-mail<?php válido<?php para<?php recuperação<?php da<?php conta<?php 
<?php </li><?php 
<?php </ul><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <?php<?php include('../inc/footer.php');<?php ?><?php 
<?php 
<?php <script><?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php //<?php Phone<?php mask<?php 
<?php const<?php telefoneInput<?php =<?php document.getElementById('telefone');<?php 
<?php telefoneInput.addEventListener('input',<?php function(e)<?php {<?php 
<?php let<?php value<?php =<?php e.target.value.replace(/\D/g,<?php '');<?php 
<?php if<?php (value.length<?php ><?php 11)<?php value<?php =<?php value.slice(0,<?php 11);<?php 
<?php 
<?php let<?php formatted<?php =<?php '';<?php 
<?php if<?php (value.length<?php ><?php 0)<?php {<?php 
<?php formatted<?php +=<?php '('<?php +<?php value.substring(0,<?php 2);<?php 
<?php }<?php 
<?php if<?php (value.length<?php >=<?php 3)<?php {<?php 
<?php formatted<?php +=<?php ')<?php '<?php +<?php value.substring(2,<?php 7);<?php 
<?php }<?php 
<?php if<?php (value.length<?php >=<?php 8)<?php {<?php 
<?php formatted<?php +=<?php '-'<?php +<?php value.substring(7);<?php 
<?php }<?php 
<?php e.target.value<?php =<?php formatted;<?php 
<?php });<?php 
<?php 
<?php //<?php Password<?php toggle<?php 
<?php const<?php toggleSenha<?php =<?php document.getElementById('toggleSenha');<?php 
<?php const<?php camposSenha<?php =<?php document.getElementById('camposSenha');<?php 
<?php 
<?php toggleSenha.addEventListener('click',<?php function()<?php {<?php 
<?php camposSenha.classList.toggle('active');<?php 
<?php 
<?php const<?php icon<?php =<?php this.querySelector('i');<?php 
<?php const<?php text<?php =<?php camposSenha.classList.contains('active')<?php ?<?php 'Cancelar'<?php :<?php 'Alterar<?php senha';<?php 
<?php const<?php iconClass<?php =<?php camposSenha.classList.contains('active')<?php ?<?php 'bi-x-circle'<?php :<?php 'bi-key';<?php 
<?php 
<?php icon.className<?php =<?php `bi<?php ${iconClass}`;<?php 
<?php this.innerHTML<?php =<?php `<i<?php class="bi<?php ${iconClass}"></i><?php ${text}`;<?php 
<?php });<?php 
<?php 
<?php //<?php Form<?php submission<?php 
<?php const<?php perfilForm<?php =<?php document.getElementById('perfilForm');<?php 
<?php const<?php submitBtn<?php =<?php document.getElementById('submitBtn');<?php 
<?php 
<?php perfilForm.addEventListener('submit',<?php function(e)<?php {<?php 
<?php submitBtn.disabled<?php =<?php true;<?php 
<?php submitBtn.innerHTML<?php =<?php '<i<?php class="bi<?php bi-arrow-repeat"<?php style="animation:<?php spin<?php 1s<?php linear<?php infinite;"></i><?php Atualizando...';<?php 
<?php perfilForm.classList.add('loading');<?php 
<?php });<?php 
<?php 
<?php //<?php Add<?php spin<?php animation<?php 
<?php const<?php style<?php =<?php document.createElement('style');<?php 
<?php style.textContent<?php =<?php `<?php 
<?php @keyframes<?php spin<?php {<?php 
<?php 0%<?php {<?php transform:<?php rotate(0deg);<?php }<?php 
<?php 100%<?php {<?php transform:<?php rotate(360deg);<?php }<?php 
<?php }<?php 
<?php `;<?php 
<?php document.head.appendChild(style);<?php 
<?php });<?php 
<?php 
<?php //<?php Notiflix<?php configuration<?php 
<?php Notiflix.Notify.init({<?php 
<?php width:<?php '300px',<?php 
<?php position:<?php 'right-top',<?php 
<?php distance:<?php '20px',<?php 
<?php opacity:<?php 1,<?php 
<?php borderRadius:<?php '12px',<?php 
<?php timeout:<?php 4000,<?php 
<?php success:<?php {<?php 
<?php background:<?php '#22c55e',<?php 
<?php textColor:<?php '#fff',<?php 
<?php },<?php 
<?php failure:<?php {<?php 
<?php background:<?php '#ef4444',<?php 
<?php textColor:<?php '#fff',<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Show<?php messages<?php if<?php any<?php 
<?php <?php<?php if<?php (isset($_SESSION['message'])):<?php ?><?php 
<?php Notiflix.Notify.<?php<?php echo<?php $_SESSION['message']['type'];<?php ?>('<?php<?php echo<?php $_SESSION['message']['text'];<?php ?>');<?php 
<?php <?php<?php unset($_SESSION['message']);<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php 
<?php console.log('%c👤<?php Perfil<?php do<?php usuário<?php carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php </script><?php 
</body><?php 
</html>