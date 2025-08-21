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
$porPagina<?php =<?php 10;<?php //<?php apostas<?php por<?php página<?php 
$paginaAtual<?php =<?php isset($_GET['pagina'])<?php ?<?php max(1,<?php intval($_GET['pagina']))<?php :<?php 1;<?php 
$offset<?php =<?php ($paginaAtual<?php -<?php 1)<?php *<?php $porPagina;<?php 
<?php 
//<?php Obter<?php total<?php de<?php apostas<?php 
try<?php {<?php 
<?php $stmtTotal<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php FROM<?php orders<?php WHERE<?php user_id<?php =<?php :user_id");<?php 
<?php $stmtTotal->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmtTotal->execute();<?php 
<?php $totalApostas<?php =<?php $stmtTotal->fetchColumn();<?php 
<?php $totalPaginas<?php =<?php ceil($totalApostas<?php /<?php $porPagina);<?php 
}<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $totalApostas<?php =<?php 0;<?php 
<?php $totalPaginas<?php =<?php 1;<?php 
}<?php 
<?php 
//<?php Buscar<?php apostas<?php paginadas<?php 
try<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php o.created_at,<?php o.resultado,<?php o.valor_ganho,<?php r.nome,<?php r.valor<?php AS<?php valor_apostado<?php 
<?php FROM<?php orders<?php o<?php 
<?php JOIN<?php raspadinhas<?php r<?php ON<?php o.raspadinha_id<?php =<?php r.id<?php 
<?php WHERE<?php o.user_id<?php =<?php :user_id<?php 
<?php ORDER<?php BY<?php o.created_at<?php DESC<?php 
<?php LIMIT<?php :limit<?php OFFSET<?php :offset<?php 
<?php ");<?php 
<?php $stmt->bindParam(':user_id',<?php $usuario_id,<?php PDO::PARAM_INT);<?php 
<?php $stmt->bindParam(':limit',<?php $porPagina,<?php PDO::PARAM_INT);<?php 
<?php $stmt->bindParam(':offset',<?php $offset,<?php PDO::PARAM_INT);<?php 
<?php $stmt->execute();<?php 
<?php $apostas<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
}<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $apostas<?php =<?php [];<?php 
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php carregar<?php apostas'];<?php 
}<?php 
?><?php 
<!DOCTYPE<?php html><?php 
<html<?php lang="pt-br"><?php 
<head><?php 
<?php <meta<?php charset="UTF-8"><?php 
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0"><?php 
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Minhas<?php Apostas</title><?php 
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
<?php .apostas-section<?php {<?php 
<?php margin-top:<?php 100px;<?php 
<?php padding:<?php 4rem<?php 0;<?php 
<?php background:<?php #0a0a0a;<?php 
<?php min-height:<?php calc(100vh<?php -<?php 200px);<?php 
<?php }<?php 
<?php 
<?php .apostas-container<?php {<?php 
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
<?php /*<?php Stats<?php Cards<?php */<?php 
<?php .stats-grid<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));<?php 
<?php gap:<?php 1rem;<?php 
<?php margin-bottom:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .stat-card<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php text-align:<?php center;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .stat-card:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php }<?php 
<?php 
<?php .stat-icon<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php color:<?php #22c55e;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .stat-value<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php white;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php }<?php 
<?php 
<?php .stat-label<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Main<?php Container<?php */<?php 
<?php .main-container<?php {<?php 
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php border-radius:<?php 24px;<?php 
<?php padding:<?php 2rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);<?php 
<?php }<?php 
<?php 
<?php .main-title<?php {<?php 
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
<?php /*<?php Empty<?php State<?php */<?php 
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
<?php /*<?php Bet<?php Items<?php */<?php 
<?php .bet-item<?php {<?php 
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
<?php .bet-item::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 4px;<?php 
<?php height:<?php 100%;<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
<?php }<?php 
<?php 
<?php .bet-item.win::before<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .bet-item.lose::before<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);<?php 
<?php opacity:<?php 1;<?php 
<?php }<?php 
<?php 
<?php .bet-item:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 10px<?php 30px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .bet-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .bet-date<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .bet-date<?php i<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .bet-status<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php padding:<?php 0.5rem<?php 1rem;<?php 
<?php border-radius:<?php 25px;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.5px;<?php 
<?php }<?php 
<?php 
<?php .bet-status.win<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(16,<?php 163,<?php 74,<?php 0.1));<?php 
<?php color:<?php #22c55e;<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .bet-status.lose<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(239,<?php 68,<?php 68,<?php 0.2),<?php rgba(220,<?php 38,<?php 38,<?php 0.1));<?php 
<?php color:<?php #ef4444;<?php 
<?php border:<?php 1px<?php solid<?php rgba(239,<?php 68,<?php 68,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .bet-content<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php 1fr<?php auto;<?php 
<?php gap:<?php 1rem;<?php 
<?php align-items:<?php center;<?php 
<?php }<?php 
<?php 
<?php .bet-details<?php {<?php 
<?php color:<?php #e5e7eb;<?php 
<?php }<?php 
<?php 
<?php .bet-game<?php {<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php margin-bottom:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .bet-values<?php {<?php 
<?php display:<?php flex;<?php 
<?php flex-direction:<?php column;<?php 
<?php gap:<?php 0.25rem;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .bet-value<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php }<?php 
<?php 
<?php .bet-value-label<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php }<?php 
<?php 
<?php .bet-value-amount<?php {<?php 
<?php font-weight:<?php 600;<?php 
<?php }<?php 
<?php 
<?php .bet-value-amount.win<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php }<?php 
<?php 
<?php .bet-value-amount.lose<?php {<?php 
<?php color:<?php #ef4444;<?php 
<?php }<?php 
<?php 
<?php .bet-summary<?php {<?php 
<?php text-align:<?php right;<?php 
<?php padding:<?php 1rem;<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.2);<?php 
<?php border-radius:<?php 12px;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .bet-summary-value<?php {<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php }<?php 
<?php 
<?php .bet-summary-label<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php }<?php 
<?php 
<?php /*<?php Pagination<?php */<?php 
<?php .pagination<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php center;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php margin-top:<?php 2rem;<?php 
<?php flex-wrap:<?php wrap;<?php 
<?php }<?php 
<?php 
<?php .pagination-item<?php {<?php 
<?php padding:<?php 0.75rem<?php 1rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php font-weight:<?php 600;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php text-decoration:<?php none;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);<?php 
<?php }<?php 
<?php 
<?php .pagination-item:hover<?php {<?php 
<?php transform:<?php translateY(-1px);<?php 
<?php }<?php 
<?php 
<?php .pagination-item.active<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php .pagination-item:not(.active)<?php {<?php 
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.3);<?php 
<?php color:<?php #9ca3af;<?php 
<?php }<?php 
<?php 
<?php .pagination-item:not(.active):hover<?php {<?php 
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);<?php 
<?php color:<?php #22c55e;<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php }<?php 
<?php 
<?php /*<?php Responsive<?php */<?php 
<?php @media<?php (max-width:<?php 768px)<?php {<?php 
<?php .apostas-container<?php {<?php 
<?php padding:<?php 0<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .header-title<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .main-container<?php {<?php 
<?php padding:<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .bet-content<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .bet-summary<?php {<?php 
<?php text-align:<?php center;<?php 
<?php }<?php 
<?php 
<?php .bet-values<?php {<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php @media<?php (max-width:<?php 480px)<?php {<?php 
<?php .header-title<?php {<?php 
<?php font-size:<?php 1.8rem;<?php 
<?php }<?php 
<?php 
<?php .bet-header<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php gap:<?php 0.5rem;<?php 
<?php align-items:<?php stretch;<?php 
<?php }<?php 
<?php 
<?php .bet-status<?php {<?php 
<?php align-self:<?php flex-end;<?php 
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
<?php <section<?php class="apostas-section"><?php 
<?php <div<?php class="apostas-container"><?php 
<?php <!--<?php Header<?php Card<?php --><?php 
<?php <div<?php class="header-card"><?php 
<?php <div<?php class="header-icon"><?php 
<?php <i<?php class="bi<?php bi-dice-5"></i><?php 
<?php </div><?php 
<?php <h1<?php class="header-title">Minhas<?php Apostas</h1><?php 
<?php <p<?php class="header-subtitle">Acompanhe<?php seu<?php histórico<?php de<?php jogos<?php e<?php resultados</p><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Main<?php Container<?php --><?php 
<?php <div<?php class="main-container"><?php 
<?php <!--<?php Stats<?php Grid<?php --><?php 
<?php <div<?php class="stats-grid"><?php 
<?php <div<?php class="stat-card"><?php 
<?php <div<?php class="stat-icon"><?php 
<?php <i<?php class="bi<?php bi-collection"></i><?php 
<?php </div><?php 
<?php <div<?php class="stat-value"><?php=<?php $totalApostas<?php ?></div><?php 
<?php <div<?php class="stat-label">Total<?php de<?php Apostas</div><?php 
<?php </div><?php 
<?php <div<?php class="stat-card"><?php 
<?php <div<?php class="stat-icon"><?php 
<?php <i<?php class="bi<?php bi-file-earmark-text"></i><?php 
<?php </div><?php 
<?php <div<?php class="stat-value"><?php=<?php $paginaAtual<?php ?>/<?php=<?php $totalPaginas<?php ?></div><?php 
<?php <div<?php class="stat-label">Página<?php Atual</div><?php 
<?php </div><?php 
<?php <div<?php class="stat-card"><?php 
<?php <div<?php class="stat-icon"><?php 
<?php <i<?php class="bi<?php bi-grid-3x3"></i><?php 
<?php </div><?php 
<?php <div<?php class="stat-value"><?php=<?php count($apostas)<?php ?></div><?php 
<?php <div<?php class="stat-label">Nesta<?php Página</div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <h2<?php class="main-title"><?php 
<?php <i<?php class="bi<?php bi-trophy"></i><?php 
<?php Histórico<?php de<?php Jogos<?php 
<?php </h2><?php 
<?php 
<?php <?php<?php if<?php (empty($apostas)):<?php ?><?php 
<?php <div<?php class="empty-state"><?php 
<?php <i<?php class="bi<?php bi-dice-1"></i><?php 
<?php <h3>Nenhuma<?php aposta<?php encontrada</h3><?php 
<?php <p>Quando<?php você<?php jogar<?php uma<?php raspadinha,<?php ela<?php aparecerá<?php aqui</p><?php 
<?php </div><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="bets-list"><?php 
<?php <?php<?php foreach<?php ($apostas<?php as<?php $aposta):<?php ?><?php 
<?php <?php<?php 
<?php $data<?php =<?php date('d/m/Y<?php H:i',<?php strtotime($aposta['created_at']));<?php 
<?php $isWin<?php =<?php ($aposta['resultado']<?php ===<?php 'gain');<?php 
<?php $status<?php =<?php $isWin<?php ?<?php 'GANHOU'<?php :<?php 'PERDEU';<?php 
<?php $statusClass<?php =<?php $isWin<?php ?<?php 'win'<?php :<?php 'lose';<?php 
<?php $valorGanho<?php =<?php number_format($aposta['valor_ganho'],<?php 2,<?php ',',<?php '.');<?php 
<?php $valorApostado<?php =<?php number_format($aposta['valor_apostado'],<?php 2,<?php ',',<?php '.');<?php 
<?php ?><?php 
<?php <div<?php class="bet-item<?php <?php=<?php $statusClass<?php ?>"><?php 
<?php <div<?php class="bet-header"><?php 
<?php <div<?php class="bet-date"><?php 
<?php <i<?php class="bi<?php bi-calendar-event"></i><?php 
<?php <span><?php=<?php $data<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="bet-status<?php <?php=<?php $statusClass<?php ?>"><?php 
<?php <i<?php class="bi<?php bi-<?php=<?php $isWin<?php ?<?php 'trophy-fill'<?php :<?php 'x-circle-fill'<?php ?>"></i><?php 
<?php <?php=<?php $status<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="bet-content"><?php 
<?php <div<?php class="bet-details"><?php 
<?php <div<?php class="bet-game"><?php 
<?php <i<?php class="bi<?php bi-gem"></i><?php 
<?php <?php=<?php htmlspecialchars($aposta['nome'])<?php ?><?php 
<?php </div><?php 
<?php <div<?php class="bet-values"><?php 
<?php <div<?php class="bet-value"><?php 
<?php <span<?php class="bet-value-label">Valor<?php Apostado:</span><?php 
<?php <span<?php class="bet-value-amount">R$<?php <?php=<?php $valorApostado<?php ?></span><?php 
<?php </div><?php 
<?php <div<?php class="bet-value"><?php 
<?php <span<?php class="bet-value-label">Valor<?php Ganho:</span><?php 
<?php <span<?php class="bet-value-amount<?php <?php=<?php $statusClass<?php ?>">R$<?php <?php=<?php $valorGanho<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="bet-summary"><?php 
<?php <div<?php class="bet-summary-value<?php <?php=<?php $statusClass<?php ?>"><?php 
<?php <?php=<?php $isWin<?php ?<?php '+'<?php :<?php ''<?php ?>R$<?php <?php=<?php number_format($aposta['valor_ganho']<?php -<?php $aposta['valor_apostado'],<?php 2,<?php ',',<?php '.')<?php ?><?php 
<?php </div><?php 
<?php <div<?php class="bet-summary-label"><?php 
<?php <?php=<?php $isWin<?php ?<?php 'Lucro'<?php :<?php 'Perda'<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <!--<?php Pagination<?php --><?php 
<?php <?php<?php if<?php ($totalPaginas<?php ><?php 1):<?php ?><?php 
<?php <div<?php class="pagination"><?php 
<?php <?php<?php for<?php ($i<?php =<?php 1;<?php $i<?php <=<?php $totalPaginas;<?php $i++):<?php ?><?php 
<?php <a<?php href="?pagina=<?php=<?php $i<?php ?>"<?php 
<?php class="pagination-item<?php <?php=<?php $i<?php ==<?php $paginaAtual<?php ?<?php 'active'<?php :<?php ''<?php ?>"><?php 
<?php <?php=<?php $i<?php ?><?php 
<?php </a><?php 
<?php <?php<?php endfor;<?php ?><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </section><?php 
<?php 
<?php <?php<?php include('../inc/footer.php');<?php ?><?php 
<?php <?php<?php include('../components/modals.php');<?php ?><?php 
<?php 
<?php <script><?php 
<?php //<?php Initialize<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php console.log('%c🎲<?php Apostas<?php carregadas!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php 
<?php //<?php Add<?php hover<?php effects<?php to<?php bet<?php items<?php 
<?php document.querySelectorAll('.bet-item').forEach(item<?php =><?php {<?php 
<?php item.addEventListener('mouseenter',<?php function()<?php {<?php 
<?php this.style.transform<?php =<?php 'translateY(-2px)';<?php 
<?php });<?php 
<?php 
<?php item.addEventListener('mouseleave',<?php function()<?php {<?php 
<?php this.style.transform<?php =<?php 'translateY(0)';<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Add<?php click<?php effect<?php to<?php pagination<?php 
<?php document.querySelectorAll('.pagination-item').forEach(item<?php =><?php {<?php 
<?php item.addEventListener('click',<?php function()<?php {<?php 
<?php if<?php (!this.classList.contains('active'))<?php {<?php 
<?php this.style.transform<?php =<?php 'scale(0.95)';<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php this.style.transform<?php =<?php '';<?php 
<?php },<?php 150);<?php 
<?php }<?php 
<?php });<?php 
<?php });<?php 
<?php });<?php 
<?php </script><?php 
</body><?php 
</html>