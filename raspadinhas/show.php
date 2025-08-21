<?php<?php 
@session_start();<?php 
require_once<?php '../conexao.php';<?php 
<?php 
if<?php (!isset($_SESSION['usuario_id']))<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php precisa<?php estar<?php logado<?php para<?php acessar<?php esta<?php página!'];<?php 
<?php header("Location:<?php /login");<?php 
<?php exit;<?php 
}<?php 
<?php 
$id<?php =<?php (int)($_GET['id']<?php ??<?php 0);<?php 
$stmt<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");<?php 
$stmt->execute([$id]);<?php 
$cartela<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);<?php 
<?php 
if<?php (!$cartela)<?php {<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Cartela<?php não<?php encontrada.'];<?php 
<?php header("Location:<?php /raspadinhas");<?php 
<?php exit;<?php 
}<?php 
<?php 
$premios<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinha_premios<?php WHERE<?php raspadinha_id<?php =<?php ?<?php ORDER<?php BY<?php valor<?php DESC");<?php 
$premios->execute([$id]);<?php 
$premios<?php =<?php $premios->fetchAll(PDO::FETCH_ASSOC);<?php 
?><?php 
<?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-BR"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php <?php=<?php htmlspecialchars($cartela['nome']);<?php ?></title><?php 
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
<?php <script<?php src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script><?php 
<?php 
<?php <style><?php 
<?php /*<?php Page<?php Styles<?php */<?php 
<?php .raspadinha-section<?php {<?php 
<?php margin-top:<?php 100px;<?php 
<?php padding:<?php 4rem<?php 0;<?php 
<?php background:<?php #0a0a0a;<?php 
<?php min-height:<?php calc(100vh<?php -<?php 200px);<?php 
<?php }<?php 
<?php 
<?php .raspadinha-container<?php {<?php 
<?php max-width:<?php 800px;<?php 
<?php margin:<?php 0<?php auto;<?php 
<?php padding:<?php 0<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Header<?php Card<?php */<?php 
<?php .header-card<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php rgba(16,<?php 163,<?php 74,<?php 0.05));<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 2rem;<?php 
<?php margin-bottom:<?php 3rem;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .header-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php -50%;<?php 
<?php right:<?php -50%;<?php 
<?php width:<?php 200px;<?php 
<?php height:<?php 200px;<?php 
<?php background:<?php linear-gradient(45deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php transparent);<?php 
<?php border-radius:<?php 50%;<?php 
<?php animation:<?php float<?php 6s<?php ease-in-out<?php infinite;<?php 
<?php }<?php 
<?php 
<?php .header-card::after<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php bottom:<?php -50%;<?php 
<?php left:<?php -50%;<?php 
<?php width:<?php 150px;<?php 
<?php height:<?php 150px;<?php 
<?php background:<?php linear-gradient(45deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.05),<?php transparent);<?php 
<?php border-radius:<?php 50%;<?php 
<?php animation:<?php float<?php 8s<?php ease-in-out<?php infinite<?php reverse;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php float<?php {<?php 
<?php 0%,<?php 100%<?php {<?php transform:<?php translateY(0)<?php rotate(0deg);<?php }<?php 
<?php 50%<?php {<?php transform:<?php translateY(-20px)<?php rotate(180deg);<?php }<?php 
<?php }<?php 
<?php 
<?php .cartela-banner<?php {<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 200px;<?php 
<?php border-radius:<?php 20px;<?php 
<?php overflow:<?php hidden;<?php 
<?php position:<?php relative;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php box-shadow:<?php 0<?php 10px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .cartela-image<?php {<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php object-fit:<?php cover;<?php 
<?php }<?php 
<?php 
<?php .cartela-overlay<?php {<?php 
<?php position:<?php absolute;<?php 
<?php inset:<?php 0;<?php 
<?php background:<?php linear-gradient(45deg,<?php rgba(0,<?php 0,<?php 0,<?php 0.3),<?php rgba(34,<?php 197,<?php 94,<?php 0.1));<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php }<?php 
<?php 
<?php .cartela-title<?php {<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 2.5rem;<?php 
<?php font-weight:<?php 900;<?php 
<?php text-align:<?php center;<?php 
<?php text-shadow:<?php 2px<?php 2px<?php 4px<?php rgba(0,<?php 0,<?php 0,<?php 0.7);<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .price-badge<?php {<?php 
<?php position:<?php absolute;<?php 
<?php bottom:<?php 1rem;<?php 
<?php left:<?php 50%;<?php 
<?php transform:<?php translateX(-50%);<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php padding:<?php 0.5rem<?php 1.5rem;<?php 
<?php border-radius:<?php 25px;<?php 
<?php font-weight:<?php 700;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php }<?php 
<?php 
<?php /*<?php Instructions<?php */<?php 
<?php .instructions<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php .instructions<?php h3<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-weight:<?php 700;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .instructions-list<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));<?php 
<?php gap:<?php 1rem;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php }<?php 
<?php 
<?php .instruction-item<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .instruction-icon<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Prizes<?php Section<?php */<?php 
<?php .prizes-section<?php {<?php 
<?php background:<?php rgba(10,<?php 10,<?php 10,<?php 0.6);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 2rem;<?php 
<?php margin-top:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .prizes-title<?php {<?php 
<?php color:<?php #ffffff;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.5px;<?php 
<?php }<?php 
<?php 
<?php .prizes-title<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php }<?php 
<?php 
<?php .prizes-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(140px,<?php 1fr));<?php 
<?php gap:<?php 1rem;<?php 
<?php max-height:<?php 320px;<?php 
<?php overflow-y:<?php auto;<?php 
<?php padding:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Custom<?php scrollbar<?php for<?php prizes<?php grid<?php */<?php 
<?php .prizes-grid::-webkit-scrollbar<?php {<?php 
<?php width:<?php 6px;<?php 
<?php }<?php 
<?php 
<?php .prizes-grid::-webkit-scrollbar-track<?php {<?php 
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php border-radius:<?php 3px;<?php 
<?php }<?php 
<?php 
<?php .prizes-grid::-webkit-scrollbar-thumb<?php {<?php 
<?php background:<?php #22c55e;<?php 
<?php border-radius:<?php 3px;<?php 
<?php }<?php 
<?php 
<?php .prizes-grid::-webkit-scrollbar-thumb:hover<?php {<?php 
<?php background:<?php #16a34a;<?php 
<?php }<?php 
<?php 
<?php .prize-card<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.6);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 12px;<?php 
<?php padding:<?php 1rem;<?php 
<?php text-align:<?php center;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .prize-card::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php right:<?php 0;<?php 
<?php bottom:<?php 0;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1)<?php 0%,<?php transparent<?php 100%);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .prize-card:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .prize-card:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.15);<?php 
<?php }<?php 
<?php 
<?php .prize-image<?php {<?php 
<?php width:<?php 64px;<?php 
<?php height:<?php 64px;<?php 
<?php margin:<?php 0<?php auto<?php 0.75rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php border-radius:<?php 8px;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .prize-image<?php img<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php object-fit:<?php contain;<?php 
<?php filter:<?php drop-shadow(0<?php 2px<?php 4px<?php rgba(0,<?php 0,<?php 0,<?php 0.3));<?php 
<?php }<?php 
<?php 
<?php .prize-info<?php {<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .prize-name<?php {<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php line-height:<?php 1.2;<?php 
<?php }<?php 
<?php 
<?php .prize-value<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php }<?php 
<?php 
<?php /*<?php Game<?php Container<?php */<?php 
<?php .game-container<?php {<?php 
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 2rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php position:<?php relative;<?php 
<?php }<?php 
<?php 
<?php .game-title<?php {<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Scratch<?php Container<?php */<?php 
<?php #scratch-container<?php {<?php 
<?php position:<?php relative;<?php 
<?php width:<?php 100%;<?php 
<?php max-width:<?php 500px;<?php 
<?php aspect-ratio:<?php 1<?php /<?php 1;<?php 
<?php margin:<?php 0<?php auto<?php 2rem;<?php 
<?php border-radius:<?php 20px;<?php 
<?php user-select:<?php none;<?php 
<?php box-shadow:<?php 0<?php 10px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php #prizes-grid<?php {<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(3,<?php 1fr);<?php 
<?php grid-template-rows:<?php repeat(3,<?php 1fr);<?php 
<?php gap:<?php 8px;<?php 
<?php padding:<?php 12px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #1f2937,<?php #374151);<?php 
<?php color:<?php white;<?php 
<?php border-radius:<?php 20px;<?php 
<?php z-index:<?php 1;<?php 
<?php }<?php 
<?php 
<?php #prizes-grid<?php ><?php div<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.8);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 12px;<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php justify-content:<?php center;<?php 
<?php align-items:<?php center;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 0.85rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php #prizes-grid<?php ><?php div::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php inset:<?php 0;<?php 
<?php background:<?php linear-gradient(45deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php transparent);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php #prizes-grid<?php ><?php div:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php #prizes-grid<?php img<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php object-fit:<?php contain;<?php 
<?php margin-bottom:<?php 6px;<?php 
<?php filter:<?php drop-shadow(0<?php 2px<?php 4px<?php rgba(0,<?php 0,<?php 0,<?php 0.3));<?php 
<?php }<?php 
<?php 
<?php #scratch-canvas<?php {<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php border-radius:<?php 20px;<?php 
<?php z-index:<?php 10;<?php 
<?php touch-action:<?php none;<?php 
<?php cursor:<?php pointer;<?php 
<?php user-select:<?php none;<?php 
<?php }<?php 
<?php 
<?php #btn-overlay<?php {<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.8);<?php 
<?php backdrop-filter:<?php blur(4px);<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php justify-content:<?php center;<?php 
<?php align-items:<?php center;<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #fff;<?php 
<?php z-index:<?php 30;<?php 
<?php border-radius:<?php 20px;<?php 
<?php text-align:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .overlay-icon<?php {<?php 
<?php font-size:<?php 3rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Buy<?php Button<?php */<?php 
<?php .buy-button<?php {<?php 
<?php width:<?php 100%;<?php 
<?php max-width:<?php 500px;<?php 
<?php margin:<?php 0<?php auto;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border:<?php none;<?php 
<?php padding:<?php 1rem<?php 2rem;<?php 
<?php border-radius:<?php 16px;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php box-shadow:<?php 0<?php 8px<?php 30px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .buy-button:hover<?php {<?php 
<?php transform:<?php translateY(-3px);<?php 
<?php box-shadow:<?php 0<?php 12px<?php 40px<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php }<?php 
<?php 
<?php .buy-button:disabled<?php {<?php 
<?php opacity:<?php 0.6;<?php 
<?php cursor:<?php not-allowed;<?php 
<?php transform:<?php none;<?php 
<?php }<?php 
<?php 
<?php .buy-button::before<?php {<?php 
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
<?php .buy-button:hover::before<?php {<?php 
<?php left:<?php 100%;<?php 
<?php }<?php 
<?php 
<?php /*<?php Result<?php Message<?php */<?php 
<?php #result-msg<?php {<?php 
<?php margin-top:<?php 2rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php text-align:<?php center;<?php 
<?php min-height:<?php 2rem;<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Loading<?php States<?php */<?php 
<?php .loading-pulse<?php {<?php 
<?php animation:<?php pulse<?php 2s<?php ease-in-out<?php infinite;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php pulse<?php {<?php 
<?php 0%,<?php 100%<?php {<?php opacity:<?php 1;<?php }<?php 
<?php 50%<?php {<?php opacity:<?php 0.5;<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Prize<?php animations<?php */<?php 
<?php .prize-reveal<?php {<?php 
<?php animation:<?php prizeReveal<?php 0.5s<?php ease-out<?php forwards;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php prizeReveal<?php {<?php 
<?php 0%<?php {<?php 
<?php transform:<?php scale(0.8);<?php 
<?php opacity:<?php 0;<?php 
<?php }<?php 
<?php 50%<?php {<?php 
<?php transform:<?php scale(1.1);<?php 
<?php }<?php 
<?php 100%<?php {<?php 
<?php transform:<?php scale(1);<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Success<?php animations<?php */<?php 
<?php .win-animation<?php {<?php 
<?php animation:<?php winPulse<?php 1s<?php ease-in-out<?php infinite;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php winPulse<?php {<?php 
<?php 0%,<?php 100%<?php {<?php transform:<?php scale(1);<?php }<?php 
<?php 50%<?php {<?php transform:<?php scale(1.05);<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Responsive<?php */<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .raspadinha-container<?php {<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .cartela-title<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .game-container<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .instructions-list<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php }<?php 
<?php 
<?php .prizes-section<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-top:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .prizes-grid<?php {<?php 
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(120px,<?php 1fr));<?php 
<?php gap:<?php 0.75rem;<?php 
<?php max-height:<?php 280px;<?php 
<?php }<?php 
<?php 
<?php .prize-card<?php {<?php 
<?php padding:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .prize-image<?php {<?php 
<?php width:<?php 56px;<?php 
<?php height:<?php 56px;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .prize-image<?php img<?php {<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php }<?php 
<?php 
<?php .prize-name<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .prize-value<?php {<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 480px)<?php {<?php 
<?php .cartela-title<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php #scratch-container<?php {<?php 
<?php max-width:<?php 300px;<?php 
<?php }<?php 
<?php 
<?php .prizes-section<?php {<?php 
<?php padding:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .prizes-grid<?php {<?php 
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(100px,<?php 1fr));<?php 
<?php gap:<?php 0.5rem;<?php 
<?php max-height:<?php 240px;<?php 
<?php }<?php 
<?php 
<?php .prize-card<?php {<?php 
<?php padding:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .prize-image<?php {<?php 
<?php width:<?php 48px;<?php 
<?php height:<?php 48px;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .prize-image<?php img<?php {<?php 
<?php width:<?php 32px;<?php 
<?php height:<?php 32px;<?php 
<?php }<?php 
<?php 
<?php .prize-name<?php {<?php 
<?php font-size:<?php 0.7rem;<?php 
<?php }<?php 
<?php 
<?php .prize-value<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .prizes-title<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php }<?php 
<?php </style><?php 
</head><?php 
<body><?php 
<?php <?php<?php include('../inc/header.php');<?php ?><?php 
<?php <?php<?php include('../components/modals.php');<?php ?><?php 
<?php 
<?php <section<?php class="raspadinha-section"><?php 
<?php <div<?php class="raspadinha-container"><?php 
<?php <!--<?php Header<?php Card<?php --><?php 
<?php <div<?php class="header-card"><?php 
<?php <div<?php class="cartela-banner"><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($cartela['banner']);<?php ?>"<?php 
<?php class="cartela-image"<?php 
<?php alt="Banner<?php <?php=<?php htmlspecialchars($cartela['nome']);<?php ?>"><?php 
<?php 
<?php <div<?php class="cartela-overlay"><?php 
<?php <h1<?php class="cartela-title"><?php=<?php htmlspecialchars($cartela['nome']);<?php ?></h1><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="price-badge"><?php 
<?php <i<?php class="bi<?php bi-tag-fill"></i><?php 
<?php R$<?php <?php=<?php number_format($cartela['valor'],<?php 2,<?php ',',<?php '.');<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Instructions<?php --><?php 
<?php <div<?php class="instructions"><?php 
<?php <h3><i<?php class="bi<?php bi-info-circle"></i><?php Como<?php Jogar</h3><?php 
<?php <div<?php class="instructions-list"><?php 
<?php <div<?php class="instruction-item"><?php 
<?php <i<?php class="bi<?php bi-1-circle<?php instruction-icon"></i><?php 
<?php <span>Clique<?php em<?php "Comprar<?php e<?php Raspar"</span><?php 
<?php </div><?php 
<?php <div<?php class="instruction-item"><?php 
<?php <i<?php class="bi<?php bi-2-circle<?php instruction-icon"></i><?php 
<?php <span>Raspe<?php a<?php cartela<?php com<?php o<?php mouse/dedo</span><?php 
<?php </div><?php 
<?php <div<?php class="instruction-item"><?php 
<?php <i<?php class="bi<?php bi-3-circle<?php instruction-icon"></i><?php 
<?php <span>Descubra<?php se<?php você<?php ganhou!</span><?php 
<?php </div><?php 
<?php <div<?php class="instruction-item"><?php 
<?php <i<?php class="bi<?php bi-4-circle<?php instruction-icon"></i><?php 
<?php <span>Prêmios<?php são<?php creditados<?php na<?php hora</span><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Prizes<?php Section<?php --><?php 
<?php <?php<?php if<?php (!empty($premios)):<?php ?><?php 
<?php <div<?php class="prizes-section"><?php 
<?php <h3<?php class="prizes-title"><?php 
<?php <i<?php class="bi<?php bi-gift-fill"></i><?php 
<?php CONTEÚDO<?php DESSA<?php RASPADINHA:<?php 
<?php </h3><?php 
<?php 
<?php <div<?php class="prizes-grid"><?php 
<?php <?php<?php foreach<?php ($premios<?php as<?php $premio):<?php ?><?php 
<?php <div<?php class="prize-card"><?php 
<?php <div<?php class="prize-image"><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($premio['icone']);<?php ?>"<?php 
<?php alt="<?php=<?php htmlspecialchars($premio['nome']);<?php ?>"<?php 
<?php onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjY0IiBoZWlnaHQ9IjY0IiByeD0iMTIiIGZpbGw9IiMyMmM1NWUiLz4KPHN2ZyB4PSIxNiIgeT0iMTYiIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgZmlsbD0id2hpdGUiPgo8cGF0aCBkPSJNMTYgOGMwLTQuNDExIDMuNTg5LTggOC04czggMy41ODkgOCA4djJjMCAxLjEwNS0uODk1IDItMiAySDJjLTEuMTA1IDAtMi0uODk1LTItMlY4eiIvPgo8L3N2Zz4KPC9zdmc+'"><?php 
<?php </div><?php 
<?php <div<?php class="prize-info"><?php 
<?php <div<?php class="prize-name"><?php=<?php htmlspecialchars($premio['nome']);<?php ?></div><?php 
<?php <div<?php class="prize-value">R$<?php <?php=<?php number_format($premio['valor'],<?php 2,<?php ',',<?php '.');<?php ?></div><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Game<?php Container<?php --><?php 
<?php <div<?php class="game-container"><?php 
<?php <h2<?php class="game-title"><?php 
<?php <i<?php class="bi<?php bi-diamond-fill"></i><?php 
<?php Sua<?php Raspadinha<?php 
<?php </h2><?php 
<?php 
<?php <div<?php id="scratch-container"><?php 
<?php <div<?php id="prizes-grid"></div><?php 
<?php <canvas<?php id="scratch-canvas"></canvas><?php 
<?php <div<?php id="btn-overlay"><?php 
<?php <i<?php class="bi<?php bi-play-circle<?php overlay-icon"></i><?php 
<?php <div>Clique<?php em<?php "Comprar"<?php para<?php jogar</div><?php 
<?php <div<?php style="font-size:<?php 0.9rem;<?php opacity:<?php 0.8;">Boa<?php sorte!<?php 🍀</div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <button<?php id="btn-buy"<?php class="buy-button"><?php 
<?php <i<?php class="bi<?php bi-credit-card"></i><?php 
<?php Comprar<?php e<?php Raspar<?php (R$<?php <?php=<?php number_format($cartela['valor'],<?php 2,<?php ',',<?php '.');<?php ?>)<?php 
<?php </button><?php 
<?php 
<?php <div<?php id="result-msg"></div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <?php<?php include('../inc/footer.php');<?php ?><?php 
<?php 
<?php <script><?php 
<?php let<?php container<?php =<?php document.getElementById('scratch-container');<?php 
<?php let<?php canvas<?php =<?php document.getElementById('scratch-canvas');<?php 
<?php let<?php ctx<?php =<?php canvas.getContext('2d');<?php 
<?php let<?php prizesGrid<?php =<?php document.getElementById('prizes-grid');<?php 
<?php let<?php btnBuy<?php =<?php document.getElementById('btn-buy');<?php 
<?php let<?php resultMsg<?php =<?php document.getElementById('result-msg');<?php 
<?php let<?php overlay<?php =<?php document.getElementById('btn-overlay');<?php 
<?php let<?php scratchImage<?php =<?php new<?php Image();<?php 
<?php scratchImage.src<?php =<?php '/assets/img/raspe.png?id=122';<?php 
<?php 
<?php let<?php orderId<?php =<?php null;<?php 
<?php let<?php brushRadius<?php =<?php 55;<?php 
<?php let<?php isDrawing<?php =<?php false;<?php 
<?php let<?php scratchedPercentage<?php =<?php 0;<?php 
<?php let<?php isScratchEnabled<?php =<?php false;<?php 
<?php 
<?php function<?php ajustarCanvas()<?php {<?php 
<?php const<?php size<?php =<?php container.clientWidth;<?php 
<?php canvas.width<?php =<?php size;<?php 
<?php canvas.height<?php =<?php size;<?php 
<?php drawScratchImage();<?php 
<?php }<?php 
<?php 
<?php function<?php resetCanvas()<?php {<?php 
<?php if<?php (canvas<?php &&<?php canvas.parentNode)<?php canvas.parentNode.removeChild(canvas);<?php 
<?php 
<?php const<?php newCanvas<?php =<?php document.createElement('canvas');<?php 
<?php newCanvas.id<?php =<?php 'scratch-canvas';<?php 
<?php newCanvas.className<?php =<?php canvas.className;<?php 
<?php container.appendChild(newCanvas);<?php 
<?php 
<?php canvas<?php =<?php newCanvas;<?php 
<?php ctx<?php =<?php newCanvas.getContext('2d');<?php 
<?php 
<?php ajustarCanvas();<?php 
<?php addCanvasListeners();<?php 
<?php }<?php 
<?php 
<?php function<?php addCanvasListeners()<?php {<?php 
<?php canvas.replaceWith(canvas.cloneNode(true));<?php 
<?php canvas<?php =<?php document.getElementById('scratch-canvas');<?php 
<?php ctx<?php =<?php canvas.getContext('2d');<?php 
<?php 
<?php canvas.addEventListener('mousedown',<?php handleStart);<?php 
<?php canvas.addEventListener('mousemove',<?php handleMove);<?php 
<?php canvas.addEventListener('mouseup',<?php handleEnd);<?php 
<?php canvas.addEventListener('mouseleave',<?php handleEnd);<?php 
<?php canvas.addEventListener('touchstart',<?php handleStart,<?php {passive:false});<?php 
<?php canvas.addEventListener('touchmove',<?php handleMove,<?php {passive:false});<?php 
<?php canvas.addEventListener('touchend',<?php handleEnd);<?php 
<?php canvas.addEventListener('touchcancel',<?php handleEnd);<?php 
<?php }<?php 
<?php 
<?php window.addEventListener('resize',<?php ajustarCanvas);<?php 
<?php scratchImage.onload<?php =<?php ()<?php =><?php {<?php 
<?php ajustarCanvas();<?php 
<?php };<?php 
<?php 
<?php function<?php drawScratchImage()<?php {<?php 
<?php ctx.clearRect(0,<?php 0,<?php canvas.width,<?php canvas.height);<?php 
<?php ctx.globalCompositeOperation<?php =<?php 'source-over';<?php 
<?php ctx.drawImage(scratchImage,<?php 0,<?php 0,<?php canvas.width,<?php canvas.height);<?php 
<?php }<?php 
<?php 
<?php function<?php scratch(x,<?php y)<?php {<?php 
<?php if<?php (!isScratchEnabled)<?php return;<?php 
<?php ctx.globalCompositeOperation<?php =<?php 'destination-out';<?php 
<?php ctx.beginPath();<?php 
<?php ctx.arc(x,<?php y,<?php brushRadius,<?php 0,<?php Math.PI<?php *<?php 2);<?php 
<?php ctx.fill();<?php 
<?php }<?php 
<?php 
<?php function<?php getScratchedPercentage()<?php {<?php 
<?php const<?php imageData<?php =<?php ctx.getImageData(0,<?php 0,<?php canvas.width,<?php canvas.height);<?php 
<?php const<?php pixels<?php =<?php imageData.data;<?php 
<?php let<?php transparentPixels<?php =<?php 0;<?php 
<?php 
<?php for<?php (let<?php i<?php =<?php 3;<?php i<?php <?php pixels.length;<?php i<?php +=<?php 4)<?php {<?php 
<?php if<?php (pixels[i]<?php ===<?php 0)<?php transparentPixels++;<?php 
<?php }<?php 
<?php return<?php (transparentPixels<?php /<?php (canvas.width<?php *<?php canvas.height))<?php *<?php 100;<?php 
<?php }<?php 
<?php 
<?php function<?php getMousePos(e)<?php {<?php 
<?php const<?php rect<?php =<?php canvas.getBoundingClientRect();<?php 
<?php if<?php (e.touches)<?php {<?php 
<?php return<?php {<?php 
<?php x:<?php e.touches[0].clientX<?php -<?php rect.left,<?php 
<?php y:<?php e.touches[0].clientY<?php -<?php rect.top<?php 
<?php };<?php 
<?php }<?php else<?php {<?php 
<?php return<?php {<?php 
<?php x:<?php e.clientX<?php -<?php rect.left,<?php 
<?php y:<?php e.clientY<?php -<?php rect.top<?php 
<?php };<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php function<?php handleStart(e)<?php {<?php 
<?php if<?php (!isScratchEnabled)<?php return;<?php 
<?php isDrawing<?php =<?php true;<?php 
<?php const<?php pos<?php =<?php getMousePos(e);<?php 
<?php scratch(pos.x,<?php pos.y);<?php 
<?php }<?php 
<?php 
<?php function<?php handleMove(e)<?php {<?php 
<?php if<?php (!isDrawing<?php ||<?php !isScratchEnabled)<?php return;<?php 
<?php const<?php pos<?php =<?php getMousePos(e);<?php 
<?php scratch(pos.x,<?php pos.y);<?php 
<?php scratchedPercentage<?php =<?php getScratchedPercentage();<?php 
<?php if<?php (scratchedPercentage<?php ><?php 75)<?php {<?php 
<?php autoFinishScratch();<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php function<?php handleEnd()<?php {<?php 
<?php isDrawing<?php =<?php false;<?php 
<?php }<?php 
<?php 
<?php function<?php buildCell(prize)<?php {<?php 
<?php return<?php `<?php 
<?php <div<?php class="prize-reveal"><?php 
<?php <img<?php src="${prize.icone}"<?php alt="${prize.nome}"<?php /><?php 
<?php <span>${prize.valor<?php ><?php 0<?php ?<?php 'R$<?php '<?php +<?php prize.valor.toLocaleString('pt-BR',<?php {<?php minimumFractionDigits:<?php 2<?php })<?php :<?php prize.nome}</span><?php 
<?php </div><?php 
<?php `;<?php 
<?php }<?php 
<?php 
<?php let<?php fadeInterval<?php =<?php null;<?php 
<?php 
<?php async<?php function<?php autoFinishScratch()<?php {<?php 
<?php isScratchEnabled<?php =<?php false;<?php 
<?php fadeInterval<?php =<?php setInterval(()<?php =><?php {<?php 
<?php ctx.globalCompositeOperation<?php =<?php 'destination-out';<?php 
<?php ctx.fillStyle<?php =<?php 'rgba(0,0,0,0.1)';<?php 
<?php ctx.fillRect(0,<?php 0,<?php canvas.width,<?php canvas.height);<?php 
<?php },<?php 50);<?php 
<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php clearInterval(fadeInterval);<?php 
<?php fadeInterval<?php =<?php null;<?php 
<?php ctx.clearRect(0,<?php 0,<?php canvas.width,<?php canvas.height);<?php 
<?php },<?php 500);<?php 
<?php 
<?php finishScratch();<?php 
<?php }<?php 
<?php 
<?php async<?php function<?php finishScratch()<?php {<?php 
<?php resultMsg.innerHTML<?php =<?php '<i<?php class="bi<?php bi-hourglass-split<?php loading-pulse"></i><?php Verificando<?php resultado...';<?php 
<?php 
<?php const<?php fd<?php =<?php new<?php FormData();<?php 
<?php fd.append('order_id',<?php orderId);<?php 
<?php const<?php response<?php =<?php await<?php fetch('/raspadinhas/finish.php',<?php {<?php method:<?php 'POST',<?php body:<?php fd<?php });<?php 
<?php const<?php json<?php =<?php await<?php response.json();<?php 
<?php 
<?php if<?php (!json.success)<?php {<?php 
<?php Notiflix.Notify.failure('Erro<?php ao<?php finalizar.');<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php const<?php jsConfetti<?php =<?php new<?php JSConfetti();<?php 
<?php 
<?php if<?php (json.valor<?php ===<?php 0<?php ||<?php json.resultado<?php ===<?php 'lose')<?php {<?php 
<?php resultMsg.innerHTML<?php =<?php `<?php 
<?php <div<?php style="color:<?php #ef4444;"><?php 
<?php <i<?php class="bi<?php bi-emoji-frown"></i><?php 
<?php Não<?php foi<?php dessa<?php vez.<?php Tente<?php novamente!<?php 
<?php </div><?php 
<?php `;<?php 
<?php Notiflix.Notify.info('Não<?php foi<?php dessa<?php vez.<?php 😢');<?php 
<?php clearInterval(fadeInterval);<?php 
<?php fadeInterval<?php =<?php 0;<?php 
<?php await<?php atualizarSaldoUsuario();<?php 
<?php }<?php else<?php {<?php 
<?php container.classList.add('win-animation');<?php 
<?php resultMsg.innerHTML<?php =<?php `<?php 
<?php <div<?php style="color:<?php #22c55e;"><?php 
<?php <i<?php class="bi<?php bi-trophy-fill"></i><?php 
<?php 🎉<?php Parabéns!<?php Você<?php ganhou<?php R$<?php ${json.valor.toLocaleString('pt-BR',<?php {<?php minimumFractionDigits:<?php 2<?php })}!<?php 
<?php </div><?php 
<?php `;<?php 
<?php Notiflix.Notify.success(`🎉<?php Você<?php ganhou<?php R$<?php ${json.valor.toLocaleString('pt-BR',<?php {<?php minimumFractionDigits:<?php 2<?php })}!`);<?php 
<?php clearInterval(fadeInterval);<?php 
<?php fadeInterval<?php =<?php 0;<?php 
<?php 
<?php jsConfetti.addConfetti({<?php 
<?php emojis:<?php ['🎉',<?php '✨',<?php '🎊',<?php '🥳',<?php '💰',<?php '🍀'],<?php 
<?php emojiSize:<?php 20,<?php 
<?php confettiNumber:<?php 300,<?php 
<?php confettiRadius:<?php 6,<?php 
<?php confettiColors:<?php ['#22c55e',<?php '#16a34a',<?php '#15803d',<?php '#166534',<?php '#14532d']<?php 
<?php });<?php 
<?php 
<?php await<?php atualizarSaldoUsuario();<?php 
<?php }<?php 
<?php 
<?php btnBuy.style.opacity<?php =<?php '1';<?php 
<?php btnBuy.disabled<?php =<?php false;<?php 
<?php btnBuy.innerHTML<?php =<?php '<i<?php class="bi<?php bi-arrow-clockwise"></i><?php Jogar<?php Novamente';<?php 
<?php }<?php 
<?php 
<?php function<?php reiniciarJogo()<?php {<?php 
<?php if<?php (fadeInterval)<?php {<?php 
<?php clearInterval(fadeInterval);<?php 
<?php fadeInterval<?php =<?php null;<?php 
<?php }<?php 
<?php 
<?php container.classList.remove('win-animation');<?php 
<?php prizesGrid.innerHTML<?php =<?php '';<?php 
<?php resultMsg.innerHTML<?php =<?php '';<?php 
<?php overlay.style.display<?php =<?php 'flex';<?php 
<?php orderId<?php =<?php null;<?php 
<?php scratchedPercentage<?php =<?php 0;<?php 
<?php isScratchEnabled<?php =<?php false;<?php 
<?php isDrawing<?php =<?php false;<?php 
<?php ctx.globalCompositeOperation<?php =<?php 'source-over';<?php 
<?php ajustarCanvas();<?php 
<?php resetCanvas();<?php 
<?php btnBuy.disabled<?php =<?php false;<?php 
<?php btnBuy.innerHTML<?php =<?php '<i<?php class="bi<?php bi-credit-card"></i><?php Comprar<?php e<?php Raspar<?php (R$<?php <?php=<?php number_format($cartela['valor'],<?php 2,<?php ',',<?php '.');<?php ?>)';<?php 
<?php btnBuy.style.opacity<?php =<?php '1';<?php 
<?php }<?php 
<?php 
<?php btnBuy.addEventListener('click',<?php async<?php ()<?php =><?php {<?php 
<?php if<?php (btnBuy.innerHTML.includes('Jogar<?php Novamente'))<?php {<?php 
<?php reiniciarJogo();<?php 
<?php setTimeout(()<?php =><?php btnBuy.click(),<?php 100);<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php btnBuy.disabled<?php =<?php true;<?php 
<?php btnBuy.innerHTML<?php =<?php '<i<?php class="bi<?php bi-hourglass-split<?php loading-pulse"></i><?php Gerando...';<?php 
<?php resultMsg.innerHTML<?php =<?php '';<?php 
<?php prizesGrid.innerHTML<?php =<?php '';<?php 
<?php overlay.style.display<?php =<?php 'none';<?php 
<?php 
<?php const<?php fd<?php =<?php new<?php FormData();<?php 
<?php fd.append('raspadinha_id',<?php <?php=<?php $cartela['id'];<?php ?>);<?php 
<?php const<?php res<?php =<?php await<?php fetch('/raspadinhas/buy.php',<?php {<?php method:<?php 'POST',<?php body:<?php fd<?php });<?php 
<?php const<?php json<?php =<?php await<?php res.json();<?php 
<?php 
<?php if<?php (!json.success)<?php {<?php 
<?php Notiflix.Notify.failure(json.error);<?php 
<?php btnBuy.disabled<?php =<?php false;<?php 
<?php btnBuy.innerHTML<?php =<?php '<i<?php class="bi<?php bi-credit-card"></i><?php Comprar<?php e<?php Raspar';<?php 
<?php overlay.style.display<?php =<?php 'flex';<?php 
<?php return;<?php 
<?php }<?php 
<?php 
<?php orderId<?php =<?php json.order_id;<?php 
<?php const<?php premiosRes<?php =<?php await<?php fetch('/raspadinhas/prizes.php?ids='<?php +<?php json.grid.join(','));<?php 
<?php const<?php premios<?php =<?php await<?php premiosRes.json();<?php 
<?php 
<?php prizesGrid.innerHTML<?php =<?php premios.map(buildCell).join('');<?php 
<?php drawScratchImage();<?php 
<?php isScratchEnabled<?php =<?php true;<?php 
<?php btnBuy.style.opacity<?php =<?php '0.6';<?php 
<?php btnBuy.innerHTML<?php =<?php '<i<?php class="bi<?php bi-hand-index"></i><?php Raspe<?php a<?php cartela!';<?php 
<?php });<?php 
<?php 
<?php //<?php Canvas<?php event<?php listeners<?php 
<?php canvas.addEventListener('mousedown',<?php handleStart);<?php 
<?php canvas.addEventListener('mousemove',<?php handleMove);<?php 
<?php canvas.addEventListener('mouseup',<?php handleEnd);<?php 
<?php canvas.addEventListener('mouseleave',<?php handleEnd);<?php 
<?php canvas.addEventListener('touchstart',<?php handleStart);<?php 
<?php canvas.addEventListener('touchmove',<?php handleMove);<?php 
<?php canvas.addEventListener('touchend',<?php handleEnd);<?php 
<?php canvas.addEventListener('touchcancel',<?php handleEnd);<?php 
<?php 
<?php async<?php function<?php atualizarSaldoUsuario()<?php {<?php 
<?php try<?php {<?php 
<?php const<?php res<?php =<?php await<?php fetch('/api/get_saldo.php');<?php 
<?php const<?php json<?php =<?php await<?php res.json();<?php 
<?php 
<?php if<?php (json.success)<?php {<?php 
<?php const<?php saldoFormatado<?php =<?php 'R$<?php '<?php +<?php json.saldo.toFixed(2).replace('.',<?php ',');<?php 
<?php const<?php el<?php =<?php document.getElementById('headerSaldo');<?php 
<?php if<?php (el)<?php {<?php 
<?php el.textContent<?php =<?php saldoFormatado;<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php console.warn('Erro<?php ao<?php buscar<?php saldo:',<?php json.error);<?php 
<?php }<?php 
<?php }<?php catch<?php (e)<?php {<?php 
<?php console.error('Erro<?php na<?php requisição<?php de<?php saldo:',<?php e);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php console.log('%c🎮<?php Raspadinha<?php carregada!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php console.log(`Cartela:<?php ${<?php=<?php json_encode($cartela['nome']);<?php ?>}`);<?php 
<?php });<?php 
<?php </script><?php 
</body><?php 
</html>