<?php<?php 
session_start();<?php 
<?php 
if(isset($_SESSION['usuario_id'])){<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php já<?php está<?php logado!'];<?php 
<?php header("Location:<?php /");<?php 
<?php exit;<?php 
}<?php 
<?php 
require_once<?php '../conexao.php';<?php 
<?php 
if<?php ($_SERVER['REQUEST_METHOD']<?php ===<?php 'POST')<?php {<?php 
<?php $nome<?php =<?php trim($_POST['nome']);<?php 
<?php $telefone<?php =<?php trim($_POST['telefone']);<?php 
<?php $email<?php =<?php trim($_POST['email']);<?php 
<?php $senha<?php =<?php password_hash($_POST['senha'],<?php PASSWORD_DEFAULT);<?php 
<?php $ref<?php =<?php $_POST['ref']<?php ??<?php null;<?php 
<?php 
<?php try<?php {<?php 
<?php $stmt_config<?php =<?php $pdo->query("SELECT<?php cpa_padrao,<?php revshare_padrao<?php FROM<?php config<?php LIMIT<?php 1");<?php 
<?php $config<?php =<?php $stmt_config->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
<?php $cpa_padrao<?php =<?php $config['cpa_padrao']<?php ??<?php 0.00;<?php 
<?php $revshare_padrao<?php =<?php $config['revshare_padrao']<?php ??<?php 0.00;<?php 
<?php }<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $cpa_padrao<?php =<?php 0.00;<?php 
<?php $revshare_padrao<?php =<?php 0.00;<?php 
<?php }<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php usuarios<?php 
<?php (nome,<?php telefone,<?php email,<?php senha,<?php saldo,<?php indicacao,<?php banido,<?php comissao_cpa,<?php comissao_revshare,<?php created_at,<?php updated_at)<?php 
<?php VALUES<?php (?,<?php ?,<?php ?,<?php ?,<?php 0,<?php ?,<?php 0,<?php ?,<?php ?,<?php NOW(),<?php NOW())");<?php 
<?php 
<?php try<?php {<?php 
<?php $stmt->execute([$nome,<?php $telefone,<?php $email,<?php $senha,<?php $ref,<?php $cpa_padrao,<?php $revshare_padrao]);<?php 
<?php 
<?php $usuarioId<?php =<?php $pdo->lastInsertId();<?php 
<?php $_SESSION['usuario_id']<?php =<?php $usuarioId;<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'success',<?php 'text'<?php =><?php 'Cadastro<?php realizado<?php com<?php sucesso!'];<?php 
<?php 
<?php header("Location:<?php /");<?php 
<?php exit;<?php 
<?php }<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php error_log("Erro<?php ao<?php cadastrar:<?php "<?php .<?php $e->getMessage());<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php realizar<?php cadastro!'];<?php 
<?php header("Location:<?php /cadastro");<?php 
<?php exit;<?php 
<?php }<?php 
}<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 

<?php <!--<?php xTracky<?php Integration<?php -->
<?php <script<?php 
<?php src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
<?php data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
<?php data-click-id-param="click_id">
<?php </script>
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Cadastro</title><?php 
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
<?php <link<?php rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php<?php echo<?php time();?>"/><?php 
<?php 
<?php <!--<?php Scripts<?php --><?php 
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
<?php 
<?php <style><?php 
<?php /*<?php Page<?php Styles<?php */<?php 
<?php .cadastro-section<?php {<?php 
<?php margin-top:<?php 100px;<?php 
<?php padding:<?php 4rem<?php 0;<?php 
<?php background:<?php #0a0a0a;<?php 
<?php min-height:<?php calc(100vh<?php -<?php 100px);<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .cadastro-container<?php {<?php 
<?php max-width:<?php 1200px;<?php 
<?php width:<?php 100%;<?php 
<?php margin:<?php 0<?php auto;<?php 
<?php padding:<?php 0<?php 2rem;<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php 1fr<?php 1fr;<?php 
<?php gap:<?php 4rem;<?php 
<?php align-items:<?php center;<?php 
<?php min-height:<?php calc(100vh<?php -<?php 200px);<?php 
<?php }<?php 
<?php 
<?php /*<?php Left<?php Section<?php */<?php 
<?php .left-section<?php {<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php justify-content:<?php center;<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .brand-content<?php {<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .brand-logo<?php {<?php 
<?php width:<?php 120px;<?php 
<?php height:<?php 120px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 24px;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 3rem;<?php 
<?php color:<?php white;<?php 
<?php font-weight:<?php 900;<?php 
<?php box-shadow:<?php 0<?php 20px<?php 40px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .brand-logo::after<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php inset:<?php -4px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php border-radius:<?php 28px;<?php 
<?php z-index:<?php -1;<?php 
<?php opacity:<?php 0.3;<?php 
<?php animation:<?php pulse<?php 3s<?php infinite;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php pulse<?php {<?php 
<?php 0%,<?php 100%<?php {<?php transform:<?php scale(1);<?php opacity:<?php 0.3;<?php }<?php 
<?php 50%<?php {<?php transform:<?php scale(1.05);<?php opacity:<?php 0.1;<?php }<?php 
<?php }<?php 
<?php 
<?php .brand-title<?php {<?php 
<?php font-size:<?php 3rem;<?php 
<?php font-weight:<?php 900;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #9ca3af);<?php 
<?php background-clip:<?php text;<?php 
<?php -webkit-background-clip:<?php text;<?php 
<?php -webkit-text-fill-color:<?php transparent;<?php 
<?php line-height:<?php 1.1;<?php 
<?php }<?php 
<?php 
<?php .brand-subtitle<?php {<?php 
<?php font-size:<?php 1.3rem;<?php 
<?php color:<?php #6b7280;<?php 
<?php line-height:<?php 1.6;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php }<?php 
<?php 
<?php .highlight-text<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-weight:<?php 700;<?php 
<?php }<?php 
<?php 
<?php .benefits-list<?php {<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php gap:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .benefit-item<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
<?php 
<?php .benefit-icon<?php {<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 50%;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php flex-shrink:<?php 0;<?php 
<?php }<?php 
<?php 
<?php /*<?php Right<?php Section<?php */<?php 
<?php .right-section<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php }<?php 
<?php 
<?php .cadastro-card<?php {<?php 
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 3rem;<?php 
<?php width:<?php 100%;<?php 
<?php max-width:<?php 450px;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .cadastro-card::before<?php {<?php 
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
<?php .cadastro-header<?php {<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 2.5rem;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .cadastro-icon<?php {<?php 
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
<?php .cadastro-title<?php {<?php 
<?php font-size:<?php 1.8rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php white;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .cadastro-subtitle<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Form<?php Styles<?php */<?php 
<?php .cadastro-form<?php {<?php 
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
<?php /*<?php Checkbox<?php */<?php 
<?php .checkbox-group<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php flex-start;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php margin:<?php 1rem<?php 0;<?php 
<?php }<?php 
<?php 
<?php .custom-checkbox<?php {<?php 
<?php width:<?php 18px;<?php 
<?php height:<?php 18px;<?php 
<?php background:<?php #22c55e;<?php 
<?php border:<?php 1px<?php solid<?php #22c55e;<?php 
<?php border-radius:<?php 4px;<?php 
<?php cursor:<?php pointer;<?php 
<?php position:<?php relative;<?php 
<?php flex-shrink:<?php 0;<?php 
<?php margin-top:<?php 3px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php }<?php 
<?php 
<?php .custom-checkbox<?php input<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php position:<?php absolute;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php cursor:<?php pointer;<?php 
<?php margin:<?php 0;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .custom-checkbox::after<?php {<?php 
<?php content:<?php '✓';<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 11px;<?php 
<?php font-weight:<?php bold;<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 50%;<?php 
<?php left:<?php 50%;<?php 
<?php transform:<?php translate(-50%,<?php -50%);<?php 
<?php }<?php 
<?php 
<?php .custom-checkbox<?php input:not(:checked)<?php +<?php .checkmark<?php {<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php border-color:<?php rgba(255,<?php 255,<?php 255,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .custom-checkbox<?php input:not(:checked)<?php ~<?php .checkmark::after<?php {<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php 
<?php .checkmark<?php {<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php border-radius:<?php 4px;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php pointer-events:<?php none;<?php 
<?php }<?php 
<?php 
<?php .checkbox-label<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php line-height:<?php 1.5;<?php 
<?php }<?php 
<?php 
<?php .checkbox-label<?php a<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php text-decoration:<?php none;<?php 
<?php }<?php 
<?php 
<?php .checkbox-label<?php a:hover<?php {<?php 
<?php text-decoration:<?php underline;<?php 
<?php }<?php 
<?php 
<?php /*<?php Submit<?php Button<?php */<?php 
<?php .submit-btn<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border:<?php none;<?php 
<?php padding:<?php 1rem;<?php 
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
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
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
<?php .submit-btn::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php -100%;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php linear-gradient(90deg,<?php transparent,<?php rgba(255,<?php 255,<?php 255,<?php 0.2),<?php transparent);<?php 
<?php transition:<?php left<?php 0.5s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .submit-btn:hover::before<?php {<?php 
<?php left:<?php 100%;<?php 
<?php }<?php 
<?php 
<?php /*<?php Footer<?php Links<?php */<?php 
<?php .form-footer<?php {<?php 
<?php text-align:<?php center;<?php 
<?php margin-top:<?php 2rem;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .footer-text<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .footer-link<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php text-decoration:<?php none;<?php 
<?php font-weight:<?php 600;<?php 
<?php transition:<?php color<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .footer-link:hover<?php {<?php 
<?php color:<?php #16a34a;<?php 
<?php text-decoration:<?php underline;<?php 
<?php }<?php 
<?php 
<?php .divider<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php margin:<?php 1.5rem<?php 0;<?php 
<?php color:<?php #6b7280;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .divider::before,<?php 
<?php .divider::after<?php {<?php 
<?php content:<?php '';<?php 
<?php flex:<?php 1;<?php 
<?php height:<?php 1px;<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .divider<?php span<?php {<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Floating<?php Elements<?php */<?php 
<?php .floating-elements<?php {<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php pointer-events:<?php none;<?php 
<?php z-index:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .floating-element<?php {<?php 
<?php position:<?php absolute;<?php 
<?php width:<?php 8px;<?php 
<?php height:<?php 8px;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-radius:<?php 50%;<?php 
<?php animation:<?php float<?php 6s<?php ease-in-out<?php infinite;<?php 
<?php }<?php 
<?php 
<?php .floating-element:nth-child(1)<?php {<?php 
<?php top:<?php 20%;<?php 
<?php left:<?php 10%;<?php 
<?php animation-delay:<?php 0s;<?php 
<?php }<?php 
<?php 
<?php .floating-element:nth-child(2)<?php {<?php 
<?php top:<?php 40%;<?php 
<?php right:<?php 15%;<?php 
<?php animation-delay:<?php 1s;<?php 
<?php }<?php 
<?php 
<?php .floating-element:nth-child(3)<?php {<?php 
<?php bottom:<?php 30%;<?php 
<?php left:<?php 20%;<?php 
<?php animation-delay:<?php 2s;<?php 
<?php }<?php 
<?php 
<?php .floating-element:nth-child(4)<?php {<?php 
<?php bottom:<?php 20%;<?php 
<?php right:<?php 25%;<?php 
<?php animation-delay:<?php 3s;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php float<?php {<?php 
<?php 0%,<?php 100%<?php {<?php 
<?php transform:<?php translateY(0)<?php rotate(0deg);<?php 
<?php opacity:<?php 0.3;<?php 
<?php }<?php 
<?php 50%<?php {<?php 
<?php transform:<?php translateY(-20px)<?php rotate(180deg);<?php 
<?php opacity:<?php 0.8;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Responsive<?php */<?php 
<?php @media<?php (max-width:<?php 1024px)<?php {<?php 
<?php .cadastro-container<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 2rem;<?php 
<?php max-width:<?php 600px;<?php 
<?php }<?php 
<?php 
<?php .brand-content<?php {<?php 
<?php display:<?php none<?php !important;<?php 
<?php }<?php 
<?php 
<?php .left-section<?php {<?php 
<?php order:<?php 2;<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php .right-section<?php {<?php 
<?php order:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .brand-title<?php {<?php 
<?php font-size:<?php 2.5rem;<?php 
<?php }<?php 
<?php 
<?php .benefits-list<?php {<?php 
<?php flex-direction:<?php row;<?php 
<?php flex-wrap:<?php wrap;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .benefit-item<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .cadastro-section<?php {<?php 
<?php padding:<?php 2rem<?php 0;<?php 
<?php }<?php 
<?php 
<?php .brand-content<?php {<?php 
<?php display:<?php none<?php !important;<?php 
<?php }<?php 
<?php 
<?php .cadastro-container<?php {<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .cadastro-card<?php {<?php 
<?php padding:<?php 2rem;<?php 
<?php border-radius:<?php 20px;<?php 
<?php }<?php 
<?php 
<?php .brand-logo<?php {<?php 
<?php width:<?php 80px;<?php 
<?php height:<?php 80px;<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .brand-title<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .brand-subtitle<?php {<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
<?php 
<?php .benefits-list<?php {<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 480px)<?php {<?php 
<?php .cadastro-card<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .form-input<?php {<?php 
<?php padding:<?php 0.8rem<?php 0.8rem<?php 0.8rem<?php 2.5rem;<?php 
<?php }<?php 
<?php 
<?php .brand-content<?php {<?php 
<?php display:<?php none<?php !important;<?php 
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
<?php 
<?php <section<?php class="cadastro-section"><?php 
<?php <!--<?php Floating<?php Elements<?php --><?php 
<?php <div<?php class="floating-elements"><?php 
<?php <div<?php class="floating-element"></div><?php 
<?php <div<?php class="floating-element"></div><?php 
<?php <div<?php class="floating-element"></div><?php 
<?php <div<?php class="floating-element"></div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="cadastro-container<?php fade-in"><?php 
<?php <!--<?php Left<?php Section<?php --><?php 
<?php <div<?php class="left-section"><?php 
<?php <div<?php class="brand-content"><?php 
<?php <h1<?php class="brand-title">Comece<?php a<?php ganhar<?php hoje!</h1><?php 
<?php <p<?php class="brand-subtitle"><?php 
<?php Junte-se<?php a<?php milhares<?php de<?php usuários<?php que<?php já<?php estão<?php ganhando<?php 
<?php <span<?php class="highlight-text">prêmios<?php incríveis</span><?php 
<?php com<?php a<?php RaspaGreen!<?php 
<?php </p><?php 
<?php 
<?php <div<?php class="benefits-list"><?php 
<?php <div<?php class="benefit-item"><?php 
<?php <div<?php class="benefit-icon"><?php 
<?php <i<?php class="bi<?php bi-gift"></i><?php 
<?php </div><?php 
<?php <span>Prêmios<?php de<?php até<?php R$<?php 15.000</span><?php 
<?php </div><?php 
<?php <div<?php class="benefit-item"><?php 
<?php <div<?php class="benefit-icon"><?php 
<?php <i<?php class="bi<?php bi-lightning-charge"></i><?php 
<?php </div><?php 
<?php <span>PIX<?php instantâneo</span><?php 
<?php </div><?php 
<?php <div<?php class="benefit-item"><?php 
<?php <div<?php class="benefit-icon"><?php 
<?php <i<?php class="bi<?php bi-shield-check"></i><?php 
<?php </div><?php 
<?php <span>Plataforma<?php 100%<?php segura</span><?php 
<?php </div><?php 
<?php <div<?php class="benefit-item"><?php 
<?php <div<?php class="benefit-icon"><?php 
<?php <i<?php class="bi<?php bi-clock"></i><?php 
<?php </div><?php 
<?php <span>Disponível<?php 24/7</span><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Right<?php Section<?php --><?php 
<?php <div<?php class="right-section"><?php 
<?php <div<?php class="cadastro-card"><?php 
<?php <div<?php class="cadastro-header"><?php 
<?php <div<?php class="cadastro-icon"><?php 
<?php <i<?php class="bi<?php bi-person-plus"></i><?php 
<?php </div><?php 
<?php <h2<?php class="cadastro-title">Cadastre-se</h2><?php 
<?php <p<?php class="cadastro-subtitle"><?php 
<?php Crie<?php sua<?php conta<?php grátis<?php em<?php menos<?php de<?php 1<?php minuto<?php 
<?php </p><?php 
<?php </div><?php 
<?php 
<?php <form<?php id="cadastroForm"<?php method="POST"<?php class="cadastro-form"><?php 
<?php <input<?php id="ref"<?php name="ref"<?php type="hidden"<?php value=""><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-person"></i><?php 
<?php </div><?php 
<?php <input<?php type="text"<?php name="nome"<?php class="form-input"<?php placeholder="Nome<?php completo"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-telephone"></i><?php 
<?php </div><?php 
<?php <input<?php type="text"<?php id="telefone"<?php name="telefone"<?php class="form-input"<?php placeholder="(11)<?php 99999-9999"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-envelope"></i><?php 
<?php </div><?php 
<?php <input<?php type="email"<?php name="email"<?php class="form-input"<?php placeholder="seu@email.com"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="form-group"><?php 
<?php <div<?php class="input-icon"><?php 
<?php <i<?php class="bi<?php bi-lock"></i><?php 
<?php </div><?php 
<?php <input<?php type="password"<?php name="senha"<?php class="form-input"<?php placeholder="Senha<?php segura"<?php required><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="checkbox-group"><?php 
<?php <div<?php class="custom-checkbox"><?php 
<?php <input<?php id="aceiteTermos"<?php name="aceiteTermos"<?php type="checkbox"<?php checked<?php required><?php 
<?php <div<?php class="checkmark"></div><?php 
<?php </div><?php 
<?php <label<?php for="aceiteTermos"<?php class="checkbox-label"><?php 
<?php Li<?php e<?php aceito<?php os<?php <a<?php href="/politica"<?php target="_blank">termos<?php e<?php políticas<?php de<?php privacidade</a><?php 
<?php </label><?php 
<?php </div><?php 
<?php 
<?php <button<?php type="submit"<?php class="submit-btn"<?php id="submitBtn"><?php 
<?php <i<?php class="bi<?php bi-check-circle"></i><?php 
<?php Criar<?php Conta<?php Grátis<?php 
<?php </button><?php 
<?php </form><?php 
<?php 
<?php <div<?php class="form-footer"><?php 
<?php <div<?php class="divider"><?php 
<?php <span>ou</span><?php 
<?php </div><?php 
<?php 
<?php <p<?php class="footer-text"><?php 
<?php Já<?php tem<?php uma<?php conta?<?php 
<?php </p><?php 
<?php <a<?php href="/login"<?php class="footer-link"><?php 
<?php Faça<?php login<?php 
<?php </a><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <?php<?php include('../inc/footer.php');<?php ?><?php 
<?php 
<?php <script><?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php //<?php Ref<?php parameter<?php handling<?php 
<?php function<?php getRefParam()<?php {<?php 
<?php const<?php urlParams<?php =<?php new<?php URLSearchParams(window.location.search);<?php 
<?php return<?php urlParams.get('ref');<?php 
<?php }<?php 
<?php 
<?php const<?php refParam<?php =<?php getRefParam();<?php 
<?php if<?php (refParam)<?php {<?php 
<?php localStorage.setItem('ref',<?php refParam);<?php 
<?php }<?php 
<?php 
<?php const<?php params<?php =<?php new<?php URLSearchParams(window.location.search);<?php 
<?php let<?php refValue<?php =<?php params.get('ref');<?php 
<?php 
<?php if<?php (!refValue)<?php {<?php 
<?php refValue<?php =<?php localStorage.getItem('ref')<?php ||<?php '';<?php 
<?php }<?php else<?php {<?php 
<?php localStorage.setItem('ref',<?php refValue);<?php 
<?php }<?php 
<?php 
<?php const<?php refInput<?php =<?php document.getElementById('ref');<?php 
<?php if<?php (refInput)<?php refInput.value<?php =<?php refValue;<?php 
<?php 
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
<?php //<?php Form<?php submission<?php 
<?php const<?php cadastroForm<?php =<?php document.getElementById('cadastroForm');<?php 
<?php const<?php submitBtn<?php =<?php document.getElementById('submitBtn');<?php 
<?php 
<?php cadastroForm.addEventListener('submit',<?php function(e)<?php {<?php 
<?php submitBtn.disabled<?php =<?php true;<?php 
<?php submitBtn.innerHTML<?php =<?php '<i<?php class="bi<?php bi-arrow-repeat"<?php style="animation:<?php spin<?php 1s<?php linear<?php infinite;"></i><?php Criando<?php conta...';<?php 
<?php cadastroForm.classList.add('loading');<?php 
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
<?php 
<?php //<?php Focus<?php enhancements<?php 
<?php const<?php inputs<?php =<?php document.querySelectorAll('.form-input');<?php 
<?php inputs.forEach(input<?php =><?php {<?php 
<?php input.addEventListener('focus',<?php function()<?php {<?php 
<?php this.parentElement.style.transform<?php =<?php 'translateY(-2px)';<?php 
<?php });<?php 
<?php 
<?php input.addEventListener('blur',<?php function()<?php {<?php 
<?php this.parentElement.style.transform<?php =<?php 'translateY(0)';<?php 
<?php });<?php 
<?php });<?php 
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
<?php },<?php 
<?php warning:<?php {<?php 
<?php background:<?php '#f59e0b',<?php 
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
<?php console.log('%c🎯<?php RaspaGreen<?php -<?php Cadastro<?php Split<?php Screen',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php </script><?php 
</body><?php 
</html>