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
<?php $stmt_depositos<?php =<?php $pdo->prepare("SELECT<?php 
<?php created_at,<?php 
<?php updated_at,<?php 
<?php cpf,<?php 
<?php valor,<?php 
<?php status<?php 
<?php FROM<?php depositos<?php 
<?php WHERE<?php user_id<?php =<?php :user_id<?php 
<?php ORDER<?php BY<?php created_at<?php DESC");<?php 
<?php $stmt_depositos->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt_depositos->execute();<?php 
<?php $depositos<?php =<?php $stmt_depositos->fetchAll(PDO::FETCH_ASSOC);<?php 
}<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $depositos<?php =<?php [];<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php carregar<?php depósitos'];<?php 
}<?php 
<?php 
try<?php {<?php 
<?php $stmt_saques<?php =<?php $pdo->prepare("SELECT<?php 
<?php created_at,<?php 
<?php updated_at,<?php 
<?php cpf,<?php 
<?php valor,<?php 
<?php status<?php 
<?php FROM<?php saques<?php 
<?php WHERE<?php user_id<?php =<?php :user_id<?php 
<?php ORDER<?php BY<?php created_at<?php DESC");<?php 
<?php $stmt_saques->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt_saques->execute();<?php 
<?php $saques<?php =<?php $stmt_saques->fetchAll(PDO::FETCH_ASSOC);<?php 
}<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $saques<?php =<?php [];<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php carregar<?php saques'];<?php 
}<?php 
?><?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-br"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Minhas<?php Transações</title><?php 
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
<?php .transactions-section<?php {<?php 
<?php margin-top:<?php 100px;<?php 
<?php padding:<?php 4rem<?php 0;<?php 
<?php background:<?php #0a0a0a;<?php 
<?php min-height:<?php calc(100vh<?php -<?php 200px);<?php 
<?php }<?php 
<?php 
<?php .transactions-container<?php {<?php 
<?php max-width:<?php 850px;<?php 
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
<?php .header-icon<?php {<?php 
<?php width:<?php 80px;<?php 
<?php height:<?php 80px;<?php 
<?php margin:<?php 0<?php auto<?php 1.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(16,<?php 163,<?php 74,<?php 0.1));<?php 
<?php border-radius:<?php 50%;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .header-icon<?php i<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .header-title<?php {<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 2.5rem;<?php 
<?php font-weight:<?php 900;<?php 
<?php text-align:<?php center;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php .header-subtitle<?php {<?php 
<?php color:<?php #e5e7eb;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php text-align:<?php center;<?php 
<?php opacity:<?php 0.8;<?php 
<?php position:<?php relative;<?php 
<?php z-index:<?php 2;<?php 
<?php }<?php 
<?php 
<?php /*<?php Tabs<?php */<?php 
<?php .tabs-container<?php {<?php 
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 2rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php }<?php 
<?php 
<?php .tabs-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 0.5rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .tab-button<?php {<?php 
<?php flex:<?php 1;<?php 
<?php background:<?php transparent;<?php 
<?php border:<?php none;<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php padding:<?php 1rem<?php 1.5rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .tab-button::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php -100%;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php linear-gradient(90deg,<?php transparent,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php transparent);<?php 
<?php transition:<?php left<?php 0.5s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .tab-button:hover::before<?php {<?php 
<?php left:<?php 100%;<?php 
<?php }<?php 
<?php 
<?php .tab-button.active<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .tab-button.active::before<?php {<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php 
<?php /*<?php Content<?php Area<?php */<?php 
<?php .transactions-content<?php {<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php 
<?php .transactions-content.active<?php {<?php 
<?php display:<?php block;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php {<?php 
<?php text-align:<?php center;<?php 
<?php padding:<?php 4rem<?php 2rem;<?php 
<?php color:<?php #9ca3af;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php i<?php {<?php 
<?php font-size:<?php 4rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php opacity:<?php 0.7;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php h3<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php }<?php 
<?php 
<?php .empty-state<?php p<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php opacity:<?php 0.8;<?php 
<?php }<?php 
<?php 
<?php /*<?php Transaction<?php Items<?php */<?php 
<?php .transaction-item<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php }<?php 
<?php 
<?php .transaction-item::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 4px;<?php 
<?php height:<?php 100%;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .transaction-item:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 10px<?php 30px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .transaction-item:hover::before<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php /*<?php Desktop<?php Layout<?php */<?php 
<?php .transaction-header<?php {<?php 
<?php display:<?php none;<?php 
<?php grid-template-columns:<?php 3fr<?php 2fr<?php 2fr<?php 1.5fr;<?php 
<?php gap:<?php 1rem;<?php 
<?php padding:<?php 1rem<?php 1.5rem;<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php border-radius:<?php 12px;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .transaction-row<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php 3fr<?php 2fr<?php 2fr<?php 1.5fr;<?php 
<?php gap:<?php 1rem;<?php 
<?php align-items:<?php center;<?php 
<?php }<?php 
<?php 
<?php .transaction-date<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php color:<?php #e5e7eb;<?php 
<?php }<?php 
<?php 
<?php .transaction-date<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .transaction-cpf<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-family:<?php 'Courier<?php New',<?php monospace;<?php 
<?php cursor:<?php pointer;<?php 
<?php transition:<?php color<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .transaction-cpf:hover<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .transaction-amount<?php {<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
<?php 
<?php .status-badge<?php {<?php 
<?php display:<?php inline-flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php padding:<?php 0.5rem<?php 1rem;<?php 
<?php border-radius:<?php 25px;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.5px;<?php 
<?php justify-self:<?php end;<?php 
<?php }<?php 
<?php 
<?php .status-badge.approved<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(16,<?php 163,<?php 74,<?php 0.1));<?php 
<?php color:<?php #22c55e;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .status-badge.pending<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(251,<?php 191,<?php 36,<?php 0.2),<?php rgba(245,<?php 158,<?php 11,<?php 0.1));<?php 
<?php color:<?php #fbbf24;<?php 
<?php border:<?php 1px<?php solid<?php rgba(251,<?php 191,<?php 36,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php /*<?php Mobile<?php Layout<?php */<?php 
<?php .transaction-mobile<?php {<?php 
<?php display:<?php block;<?php 
<?php }<?php 
<?php 
<?php .transaction-mobile-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .transaction-mobile-footer<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php margin-top:<?php 1rem;<?php 
<?php padding-top:<?php 1rem;<?php 
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php /*<?php Responsive<?php */<?php 
<?php @media<?php (min-width:<?php 768px)<?php {<?php 
<?php .transaction-header<?php {<?php 
<?php display:<?php grid;<?php 
<?php }<?php 
<?php 
<?php .transaction-mobile<?php {<?php 
<?php display:<?php none;<?php 
<?php }<?php 
<?php 
<?php .transaction-row<?php {<?php 
<?php display:<?php grid;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .transactions-container<?php {<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .header-title<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .tabs-container<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .tab-button<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php padding:<?php 0.8rem<?php 1rem;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Loading<?php Animation<?php */<?php 
<?php .loading-pulse<?php {<?php 
<?php animation:<?php pulse<?php 2s<?php ease-in-out<?php infinite;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php pulse<?php {<?php 
<?php 0%,<?php 100%<?php {<?php opacity:<?php 1;<?php }<?php 
<?php 50%<?php {<?php opacity:<?php 0.5;<?php }<?php 
<?php }<?php 
<?php </style><?php 
</head><?php 
<body><?php 
<?php <?php<?php include('../inc/header.php');<?php ?><?php 
<?php 
<?php <section<?php class="transactions-section"><?php 
<?php <div<?php class="transactions-container"><?php 
<?php <!--<?php Header<?php Card<?php --><?php 
<?php <div<?php class="header-card"><?php 
<?php <div<?php class="header-icon"><?php 
<?php <i<?php class="bi<?php bi-receipt"></i><?php 
<?php </div><?php 
<?php <h1<?php class="header-title">Minhas<?php Transações</h1><?php 
<?php <p<?php class="header-subtitle">Acompanhe<?php seu<?php histórico<?php de<?php depósitos<?php e<?php saques</p><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Tabs<?php Container<?php --><?php 
<?php <div<?php class="tabs-container"><?php 
<?php <div<?php class="tabs-header"><?php 
<?php <button<?php id="tabDepositos"<?php class="tab-button<?php active"><?php 
<?php <i<?php class="bi<?php bi-wallet2"></i><?php 
<?php Depósitos<?php 
<?php </button><?php 
<?php <button<?php id="tabSaques"<?php class="tab-button"><?php 
<?php <i<?php class="bi<?php bi-cash-coin"></i><?php 
<?php Saques<?php 
<?php </button><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Depósitos<?php Content<?php --><?php 
<?php <div<?php id="depositosContent"<?php class="transactions-content<?php active"><?php 
<?php <?php<?php if<?php (empty($depositos)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="bi<?php bi-wallet2"></i><?php 
<?php <h3>Nenhum<?php depósito<?php encontrado</h3><?php 
<?php <p>Quando<?php você<?php fizer<?php um<?php depósito,<?php ele<?php aparecerá<?php aqui</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="transaction-header"><?php 
<?php <div><i<?php class="bi<?php bi-calendar3"></i><?php Data/Hora</div><?php 
<?php <div><i<?php class="bi<?php bi-person-badge"></i><?php CPF</div><?php 
<?php <div><i<?php class="bi<?php bi-currency-dollar"></i><?php Valor</div><?php 
<?php <div><i<?php class="bi<?php bi-check-circle"></i><?php Status</div><?php 
<?php </div><?php 
<?php 
<?php <?php<?php foreach<?php ($depositos<?php as<?php $deposito):<?php ?><?php 
<?php <div<?php class="transaction-item"><?php 
<?php <div<?php class="transaction-row"><?php 
<?php <div<?php class="transaction-date"><?php 
<?php <i<?php class="bi<?php bi-calendar-event"></i><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($deposito['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="transaction-cpf"<?php onclick="toggleCPF(this)"<?php data-full="<?php=<?php htmlspecialchars($deposito['cpf'])<?php ?>"><?php 
<?php <?php=<?php substr($deposito['cpf'],<?php 0,<?php 3)<?php ?>.***.***-**<?php 
<?php </div><?php 
<?php <div<?php class="transaction-amount"><?php 
<?php R$<?php <?php=<?php number_format($deposito['valor'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php <div<?php class="status-badge<?php <?php=<?php $deposito['status']<?php ===<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>"><?php 
<?php <i<?php class="bi<?php bi-<?php=<?php $deposito['status']<?php ===<?php 'PAID'<?php ?<?php 'check-circle-fill'<?php :<?php 'clock'<?php ?>"></i><?php 
<?php <?php=<?php $deposito['status']<?php ===<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Mobile<?php Layout<?php --><?php 
<?php <div<?php class="transaction-mobile"><?php 
<?php <div<?php class="transaction-mobile-header"><?php 
<?php <div<?php class="transaction-date"><?php 
<?php <i<?php class="bi<?php bi-calendar-event"></i><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($deposito['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="transaction-amount"><?php 
<?php R$<?php <?php=<?php number_format($deposito['valor'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="transaction-mobile-footer"><?php 
<?php <div<?php class="transaction-cpf"<?php onclick="toggleCPF(this)"<?php data-full="<?php=<?php htmlspecialchars($deposito['cpf'])<?php ?>"><?php 
<?php <?php=<?php substr($deposito['cpf'],<?php 0,<?php 3)<?php ?>.***.***-**<?php 
<?php </div><?php 
<?php <div<?php class="status-badge<?php <?php=<?php $deposito['status']<?php ===<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>"><?php 
<?php <i<?php class="bi<?php bi-<?php=<?php $deposito['status']<?php ===<?php 'PAID'<?php ?<?php 'check-circle-fill'<?php :<?php 'clock'<?php ?>"></i><?php 
<?php <?php=<?php $deposito['status']<?php ===<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Saques<?php Content<?php --><?php 
<?php <div<?php id="saquesContent"<?php class="transactions-content"><?php 
<?php <?php<?php if<?php (empty($saques)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="bi<?php bi-cash-coin"></i><?php 
<?php <h3>Nenhum<?php saque<?php encontrado</h3><?php 
<?php <p>Quando<?php você<?php fizer<?php um<?php saque,<?php ele<?php aparecerá<?php aqui</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="transaction-header"><?php 
<?php <div><i<?php class="bi<?php bi-calendar3"></i><?php Data/Hora</div><?php 
<?php <div><i<?php class="bi<?php bi-person-badge"></i><?php CPF</div><?php 
<?php <div><i<?php class="bi<?php bi-currency-dollar"></i><?php Valor</div><?php 
<?php <div><i<?php class="bi<?php bi-check-circle"></i><?php Status</div><?php 
<?php </div><?php 
<?php 
<?php <?php<?php foreach<?php ($saques<?php as<?php $saque):<?php ?><?php 
<?php <div<?php class="transaction-item"><?php 
<?php <div<?php class="transaction-row"><?php 
<?php <div<?php class="transaction-date"><?php 
<?php <i<?php class="bi<?php bi-calendar-event"></i><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($saque['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="transaction-cpf"<?php onclick="toggleCPF(this)"<?php data-full="<?php=<?php htmlspecialchars($saque['cpf'])<?php ?>"><?php 
<?php <?php=<?php substr($saque['cpf'],<?php 0,<?php 3)<?php ?>.***.***-**<?php 
<?php </div><?php 
<?php <div<?php class="transaction-amount"><?php 
<?php R$<?php <?php=<?php number_format($saque['valor'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php <div<?php class="status-badge<?php <?php=<?php $saque['status']<?php ===<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>"><?php 
<?php <i<?php class="bi<?php bi-<?php=<?php $saque['status']<?php ===<?php 'PAID'<?php ?<?php 'check-circle-fill'<?php :<?php 'clock'<?php ?>"></i><?php 
<?php <?php=<?php $saque['status']<?php ===<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Mobile<?php Layout<?php --><?php 
<?php <div<?php class="transaction-mobile"><?php 
<?php <div<?php class="transaction-mobile-header"><?php 
<?php <div<?php class="transaction-date"><?php 
<?php <i<?php class="bi<?php bi-calendar-event"></i><?php 
<?php <span><?php=<?php date('d/m/Y<?php H:i',<?php strtotime($saque['updated_at']))<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="transaction-amount"><?php 
<?php R$<?php <?php=<?php number_format($saque['valor'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php <div<?php class="transaction-mobile-footer"><?php 
<?php <div<?php class="transaction-cpf"<?php onclick="toggleCPF(this)"<?php data-full="<?php=<?php htmlspecialchars($saque['cpf'])<?php ?>"><?php 
<?php <?php=<?php substr($saque['cpf'],<?php 0,<?php 3)<?php ?>.***.***-**<?php 
<?php </div><?php 
<?php <div<?php class="status-badge<?php <?php=<?php $saque['status']<?php ===<?php 'PAID'<?php ?<?php 'approved'<?php :<?php 'pending'<?php ?>"><?php 
<?php <i<?php class="bi<?php bi-<?php=<?php $saque['status']<?php ===<?php 'PAID'<?php ?<?php 'check-circle-fill'<?php :<?php 'clock'<?php ?>"></i><?php 
<?php <?php=<?php $saque['status']<?php ===<?php 'PAID'<?php ?<?php 'Aprovado'<?php :<?php 'Pendente'<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <?php<?php include('../inc/footer.php');<?php ?><?php 
<?php <?php<?php include('../components/modals.php');<?php ?><?php 
<?php 
<?php <script><?php 
<?php //<?php Tab<?php functionality<?php 
<?php document.getElementById('tabDepositos').addEventListener('click',<?php function()<?php {<?php 
<?php switchTab('depositos');<?php 
<?php });<?php 
<?php 
<?php document.getElementById('tabSaques').addEventListener('click',<?php function()<?php {<?php 
<?php switchTab('saques');<?php 
<?php });<?php 
<?php 
<?php function<?php switchTab(tabName)<?php {<?php 
<?php //<?php Remove<?php active<?php class<?php from<?php all<?php tabs<?php 
<?php document.querySelectorAll('.tab-button').forEach(btn<?php =><?php btn.classList.remove('active'));<?php 
<?php document.querySelectorAll('.transactions-content').forEach(content<?php =><?php content.classList.remove('active'));<?php 
<?php 
<?php //<?php Add<?php active<?php class<?php to<?php selected<?php tab<?php 
<?php document.getElementById(`tab${tabName.charAt(0).toUpperCase()<?php +<?php tabName.slice(1)}`).classList.add('active');<?php 
<?php document.getElementById(`${tabName}Content`).classList.add('active');<?php 
<?php }<?php 
<?php 
<?php //<?php CPF<?php reveal<?php functionality<?php 
<?php function<?php toggleCPF(element)<?php {<?php 
<?php const<?php fullCPF<?php =<?php element.getAttribute('data-full');<?php 
<?php const<?php maskedCPF<?php =<?php fullCPF.substring(0,<?php 3)<?php +<?php '.***.***-**';<?php 
<?php 
<?php if<?php (element.textContent.includes('*'))<?php {<?php 
<?php element.textContent<?php =<?php fullCPF;<?php 
<?php element.style.color<?php =<?php '#22c55e';<?php 
<?php }<?php else<?php {<?php 
<?php element.textContent<?php =<?php maskedCPF;<?php 
<?php element.style.color<?php =<?php '#9ca3af';<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php console.log('%c💳<?php Transações<?php carregadas!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Add<?php hover<?php effects<?php to<?php transaction<?php items<?php 
<?php document.querySelectorAll('.transaction-item').forEach(item<?php =><?php {<?php 
<?php item.addEventListener('mouseenter',<?php function()<?php {<?php 
<?php this.style.transform<?php =<?php 'translateY(-2px)';<?php 
<?php });<?php 
<?php 
<?php item.addEventListener('mouseleave',<?php function()<?php {<?php 
<?php this.style.transform<?php =<?php 'translateY(0)';<?php 
<?php });<?php 
<?php });<?php 
<?php });<?php 
<?php </script><?php 
</body><?php 
</html>