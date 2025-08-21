<?php
@session_start();
require_once<?php '../conexao.php';

//<?php Ordenar<?php por<?php valor<?php decrescente<?php (maior<?php para<?php menor)<?php primeiro
$sql<?php =<?php "
<?php SELECT<?php r.*,<?php 
<?php MAX(p.valor)<?php AS<?php maior_premio
<?php FROM<?php raspadinhas<?php r
<?php LEFT<?php JOIN<?php raspadinha_premios<?php p<?php ON<?php p.raspadinha_id<?php =<?php r.id
<?php GROUP<?php BY<?php r.id
<?php ORDER<?php BY<?php r.valor<?php DESC,<?php r.created_at<?php DESC
";
$cartelas<?php =<?php $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE<?php html>
<html<?php lang="pt-BR">
<head>
<?php <meta<?php charset="UTF-8">
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Raspadinhas</title>
<?php 
<?php <!--<?php Fonts<?php -->
<?php <link<?php rel="preconnect"<?php href="https://fonts.googleapis.com">
<?php <link<?php rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin>
<?php <link<?php href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"<?php rel="stylesheet">
<?php 
<?php <!--<?php Icons<?php -->
<?php <link<?php rel="stylesheet"<?php href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php 
<?php <!--<?php Styles<?php -->
<?php <link<?php rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php=<?php time();<?php ?>">
<?php 
<?php <!--<?php Scripts<?php -->
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet">

<?php <style>
<?php /*<?php Page<?php Specific<?php Styles<?php */
<?php .cartelas-section<?php {
<?php margin-top:<?php 100px;
<?php padding:<?php 4rem<?php 0;
<?php background:<?php #0a0a0a;
<?php min-height:<?php calc(100vh<?php -<?php 200px);
<?php }

<?php .cartelas-container<?php {
<?php max-width:<?php 1400px;
<?php margin:<?php 0<?php auto;
<?php padding:<?php 0<?php 2rem;
<?php }

<?php .page-header<?php {
<?php text-align:<?php center;
<?php margin-bottom:<?php 4rem;
<?php }

<?php .page-title<?php {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 900;
<?php color:<?php white;
<?php margin-bottom:<?php 1rem;
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #9ca3af);
<?php background-clip:<?php text;
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php }

<?php .page-subtitle<?php {
<?php font-size:<?php 1.2rem;
<?php color:<?php #6b7280;
<?php max-width:<?php 600px;
<?php margin:<?php 0<?php auto;
<?php line-height:<?php 1.6;
<?php }

<?php .stats-bar<?php {
<?php display:<?php flex;
<?php justify-content:<?php center;
<?php gap:<?php 3rem;
<?php margin-top:<?php 2rem;
<?php }

<?php .stat-item<?php {
<?php text-align:<?php center;
<?php }

<?php .stat-number<?php {
<?php font-size:<?php 2rem;
<?php font-weight:<?php 800;
<?php color:<?php #22c55e;
<?php display:<?php block;
<?php }

<?php .stat-label<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php margin-top:<?php 0.25rem;
<?php }

<?php .cartelas-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(350px,<?php 1fr));
<?php gap:<?php 2rem;
<?php margin-bottom:<?php 4rem;
<?php }

<?php .cartela-card<?php {
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 24px;
<?php overflow:<?php hidden;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php position:<?php relative;
<?php text-decoration:<?php none;
<?php color:<?php inherit;
<?php backdrop-filter:<?php blur(20px);
<?php }

<?php .cartela-card:hover<?php {
<?php transform:<?php translateY(-8px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php }

<?php .cartela-image<?php {
<?php width:<?php 100%;
<?php height:<?php 200px;
<?php object-fit:<?php cover;
<?php transition:<?php transform<?php 0.3s<?php ease;
<?php }

<?php .cartela-card:hover<?php .cartela-image<?php {
<?php transform:<?php scale(1.05);
<?php }

<?php .cartela-content<?php {
<?php padding:<?php 1.5rem;
<?php position:<?php relative;
<?php }

<?php .price-badge<?php {
<?php position:<?php absolute;
<?php top:<?php 1rem;
<?php right:<?php 1rem;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php padding:<?php 0.5rem<?php 1rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 700;
<?php font-size:<?php 0.9rem;
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .cartela-title<?php {
<?php font-size:<?php 1.3rem;
<?php font-weight:<?php 700;
<?php color:<?php white;
<?php margin-bottom:<?php 0.5rem;
<?php line-height:<?php 1.3;
<?php }

<?php .cartela-description<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php margin-bottom:<?php 1rem;
<?php line-height:<?php 1.5;
<?php }

<?php .prize-info<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-radius:<?php 12px;
<?php padding:<?php 1rem;
<?php margin-bottom:<?php 1rem;
<?php }

<?php .prize-label<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.8rem;
<?php margin-bottom:<?php 0.25rem;
<?php }

<?php .prize-value<?php {
<?php color:<?php #22c55e;
<?php font-weight:<?php 800;
<?php font-size:<?php 1.1rem;
<?php }

<?php .play-button<?php {
<?php width:<?php 100%;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php border:<?php none;
<?php padding:<?php 0.75rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php text-decoration:<?php none;
<?php }

<?php .play-button:hover<?php {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 8px<?php 24px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }

<?php .cartela-features<?php {
<?php display:<?php flex;
<?php gap:<?php 0.5rem;
<?php margin-bottom:<?php 1rem;
<?php }

<?php .feature-tag<?php {
<?php background:<?php rgba(99,<?php 102,<?php 241,<?php 0.1);
<?php color:<?php #a5b4fc;
<?php padding:<?php 0.25rem<?php 0.5rem;
<?php border-radius:<?php 6px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 500;
<?php }

<?php .empty-state<?php {
<?php text-align:<?php center;
<?php padding:<?php 4rem<?php 2rem;
<?php color:<?php #6b7280;
<?php }

<?php .empty-icon<?php {
<?php font-size:<?php 4rem;
<?php margin-bottom:<?php 1rem;
<?php opacity:<?php 0.5;
<?php }

<?php .loading-skeleton<?php {
<?php background:<?php linear-gradient(90deg,<?php rgba(255,255,255,0.1)<?php 25%,<?php rgba(255,255,255,0.2)<?php 50%,<?php rgba(255,255,255,0.1)<?php 75%);
<?php background-size:<?php 200%<?php 100%;
<?php animation:<?php loading<?php 1.5s<?php infinite;
<?php }

<?php @keyframes<?php loading<?php {
<?php 0%<?php {<?php background-position:<?php 200%<?php 0;<?php }
<?php 100%<?php {<?php background-position:<?php -200%<?php 0;<?php }
<?php }

<?php /*<?php Improved<?php Filter<?php Bar<?php */
<?php .filter-bar<?php {
<?php display:<?php flex;
<?php justify-content:<?php center;
<?php gap:<?php 0.75rem;
<?php margin-bottom:<?php 3rem;
<?php flex-wrap:<?php wrap;
<?php }

<?php .filter-btn<?php {
<?php background:<?php linear-gradient(145deg,<?php #1e1e1e,<?php #2a2a2a);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php color:<?php #9ca3af;
<?php padding:<?php 0.75rem<?php 1.25rem;
<?php border-radius:<?php 16px;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(0,<?php 0,<?php 0,<?php 0.2);
<?php }

<?php .filter-btn::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php -100%;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php transition:<?php left<?php 0.3s<?php ease;
<?php z-index:<?php -1;
<?php }

<?php .filter-btn:hover<?php {
<?php color:<?php #ffffff;
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php }

<?php .filter-btn:hover::before<?php {
<?php left:<?php 0;
<?php }

<?php .filter-btn.active<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-color:<?php #22c55e;
<?php color:<?php #ffffff;
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }

<?php .filter-btn.active::before<?php {
<?php left:<?php 0;
<?php }

<?php .filter-btn<?php i<?php {
<?php font-size:<?php 1rem;
<?php transition:<?php transform<?php 0.3s<?php ease;
<?php }

<?php .filter-btn:hover<?php i,
<?php .filter-btn.active<?php i<?php {
<?php transform:<?php scale(1.1);
<?php }

<?php /*<?php Filter<?php button<?php animations<?php */
<?php .filter-btn.low<?php {
<?php --hover-color:<?php #22c55e;
<?php }

<?php .filter-btn.medium<?php {
<?php --hover-color:<?php #f59e0b;
<?php }

<?php .filter-btn.high<?php {
<?php --hover-color:<?php #ef4444;
<?php }

<?php .filter-btn.medium::before<?php {
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);
<?php }

<?php .filter-btn.high::before<?php {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php }

<?php .filter-btn.medium.active<?php {
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);
<?php border-color:<?php #f59e0b;
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(245,<?php 158,<?php 11,<?php 0.4);
<?php }

<?php .filter-btn.high.active<?php {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php border-color:<?php #ef4444;
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(239,<?php 68,<?php 68,<?php 0.4);
<?php }

<?php /*<?php Responsive<?php */
<?php @media<?php (max-width:<?php 768px)<?php {
<?php .page-title<?php {
<?php font-size:<?php 2rem;
<?php }
<?php 
<?php .stats-bar<?php {
<?php gap:<?php 1.5rem;
<?php flex-wrap:<?php wrap;
<?php }
<?php 
<?php .stat-number<?php {
<?php font-size:<?php 1.5rem;
<?php }
<?php 
<?php .cartelas-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php gap:<?php 1.5rem;
<?php }
<?php 
<?php .cartela-content<?php {
<?php padding:<?php 1rem;
<?php }
<?php 
<?php .filter-bar<?php {
<?php gap:<?php 0.5rem;
<?php }
<?php 
<?php .filter-btn<?php {
<?php padding:<?php 0.6rem<?php 1rem;
<?php font-size:<?php 0.85rem;
<?php }
<?php }

<?php @media<?php (max-width:<?php 480px)<?php {
<?php .cartelas-container<?php {
<?php padding:<?php 0<?php 1rem;
<?php }
<?php 
<?php .cartelas-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php }

<?php .filter-bar<?php {
<?php flex-direction:<?php column;
<?php align-items:<?php center;
<?php }

<?php .filter-btn<?php {
<?php width:<?php 200px;
<?php justify-content:<?php center;
<?php }
<?php }

<?php /*<?php Animations<?php */
<?php .fade-in<?php {
<?php animation:<?php fadeIn<?php 0.6s<?php ease-out<?php forwards;
<?php }

<?php @keyframes<?php fadeIn<?php {
<?php from<?php {
<?php opacity:<?php 0;
<?php transform:<?php translateY(20px);
<?php }
<?php to<?php {
<?php opacity:<?php 1;
<?php transform:<?php translateY(0);
<?php }
<?php }

<?php .stagger-animation<?php .cartela-card<?php {
<?php opacity:<?php 0;
<?php animation:<?php fadeIn<?php 0.6s<?php ease-out<?php forwards;
<?php }

<?php .stagger-animation<?php .cartela-card:nth-child(1)<?php {<?php animation-delay:<?php 0.1s;<?php }
<?php .stagger-animation<?php .cartela-card:nth-child(2)<?php {<?php animation-delay:<?php 0.2s;<?php }
<?php .stagger-animation<?php .cartela-card:nth-child(3)<?php {<?php animation-delay:<?php 0.3s;<?php }
<?php .stagger-animation<?php .cartela-card:nth-child(4)<?php {<?php animation-delay:<?php 0.4s;<?php }
<?php .stagger-animation<?php .cartela-card:nth-child(5)<?php {<?php animation-delay:<?php 0.5s;<?php }
<?php .stagger-animation<?php .cartela-card:nth-child(6)<?php {<?php animation-delay:<?php 0.6s;<?php }

<?php /*<?php Sparkle<?php animation<?php for<?php premium<?php buttons<?php */
<?php @keyframes<?php sparkle<?php {
<?php 0%,<?php 100%<?php {<?php transform:<?php scale(1)<?php rotate(0deg);<?php opacity:<?php 1;<?php }
<?php 50%<?php {<?php transform:<?php scale(1.05)<?php rotate(2deg);<?php opacity:<?php 0.9;<?php }
<?php }

<?php .filter-btn.high.active<?php {
<?php animation:<?php sparkle<?php 2s<?php ease-in-out<?php infinite;
<?php }
<?php </style>
</head>
<body>
<?php <?php<?php include('../inc/header.php');<?php ?>
<?php <?php<?php include('../components/modals.php');<?php ?>

<?php <section<?php class="cartelas-section">
<?php <div<?php class="cartelas-container">
<?php <!--<?php Page<?php Header<?php -->
<?php <div<?php class="page-header<?php fade-in">
<?php <h1<?php class="page-title">Escolha<?php sua<?php Raspadinha</h1>
<?php <p<?php class="page-subtitle">
<?php Centenas<?php de<?php prêmios<?php esperando<?php por<?php você!<?php Raspe<?php e<?php ganhe<?php na<?php hora<?php com<?php PIX<?php instantâneo.
<?php </p>
<?php 
<?php <div<?php class="stats-bar">
<?php <div<?php class="stat-item">
<?php <span<?php class="stat-number"><?php=<?php count($cartelas);<?php ?></span>
<?php <span<?php class="stat-label">Raspadinhas</span>
<?php </div>
<?php <div<?php class="stat-item">
<?php <span<?php class="stat-number">R$<?php <?php=<?php number_format(array_sum(array_column($cartelas,<?php 'maior_premio')),<?php 0,<?php ',',<?php '.');<?php ?></span>
<?php <span<?php class="stat-label">Em<?php Prêmios</span>
<?php </div>
<?php <div<?php class="stat-item">
<?php <span<?php class="stat-number">24/7</span>
<?php <span<?php class="stat-label">Disponível</span>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <!--<?php Enhanced<?php Filter<?php Bar<?php -->
<?php <div<?php class="filter-bar<?php fade-in">
<?php <button<?php class="filter-btn<?php active"<?php data-filter="all">
<?php <i<?php class="bi<?php bi-grid-3x3-gap-fill"></i>
<?php <span>Todas<?php as<?php Raspadinhas</span>
<?php </button>
<?php <button<?php class="filter-btn<?php low"<?php data-filter="low">
<?php <i<?php class="bi<?php bi-coin"></i>
<?php <span>Até<?php R$<?php 10</span>
<?php </button>
<?php <button<?php class="filter-btn<?php medium"<?php data-filter="medium">
<?php <i<?php class="bi<?php bi-cash-stack"></i>
<?php <span>R$<?php 10<?php -<?php R$<?php 50</span>
<?php </button>
<?php <button<?php class="filter-btn<?php high"<?php data-filter="high">
<?php <i<?php class="bi<?php bi-gem"></i>
<?php <span>Acima<?php de<?php R$<?php 50</span>
<?php </button>
<?php </div>

<?php <!--<?php Cartelas<?php Grid<?php -->
<?php <?php<?php if<?php (empty($cartelas)):<?php ?>
<?php <div<?php class="empty-state">
<?php <i<?php class="bi<?php bi-grid-3x3-gap<?php empty-icon"></i>
<?php <h3<?php style="color:<?php white;<?php margin-bottom:<?php 1rem;">Nenhuma<?php raspadinha<?php disponível</h3>
<?php <p>Novas<?php raspadinhas<?php em<?php breve!<?php Fique<?php atento<?php às<?php atualizações.</p>
<?php </div>
<?php <?php<?php else:<?php ?>
<?php <div<?php class="cartelas-grid<?php stagger-animation"<?php id="cartelasGrid">
<?php <?php<?php foreach<?php ($cartelas<?php as<?php $c):<?php ?>
<?php <a<?php href="/raspadinhas/show.php?id=<?php=<?php $c['id'];<?php ?>"<?php 
<?php class="cartela-card"<?php 
<?php data-price="<?php=<?php $c['valor'];<?php ?>"
<?php data-aos="fade-up">
<?php 
<?php <div<?php style="position:<?php relative;<?php overflow:<?php hidden;">
<?php <img<?php src="<?php=<?php htmlspecialchars($c['banner']);<?php ?>"
<?php alt="Banner<?php <?php=<?php htmlspecialchars($c['nome']);<?php ?>"
<?php class="cartela-image"<?php 
<?php loading="lazy"
<?php onerror="this.src='/assets/img/placeholder-raspadinha.jpg'">
<?php 
<?php <div<?php class="price-badge">
<?php <i<?php class="bi<?php bi-tag-fill"></i>
<?php R$<?php <?php=<?php number_format($c['valor'],<?php 2,<?php ',',<?php '.');<?php ?>
<?php </div>
<?php </div>

<?php <div<?php class="cartela-content">
<?php <div<?php class="cartela-features">
<?php <span<?php class="feature-tag">
<?php <i<?php class="bi<?php bi-lightning-fill"></i>
<?php PIX<?php Instantâneo
<?php </span>
<?php <?php<?php if($c['maior_premio']<?php >=<?php 1000):<?php ?>
<?php <span<?php class="feature-tag">
<?php <i<?php class="bi<?php bi-star-fill"></i>
<?php Premium
<?php </span>
<?php <?php<?php endif;<?php ?>
<?php </div>

<?php <h2<?php class="cartela-title">
<?php <?php=<?php htmlspecialchars($c['nome']);<?php ?>
<?php </h2>
<?php 
<?php <p<?php class="cartela-description">
<?php <?php=<?php htmlspecialchars($c['descricao']);<?php ?>
<?php </p>

<?php <div<?php class="prize-info">
<?php <div>
<?php <div<?php class="prize-label">Prêmio<?php máximo</div>
<?php <div<?php class="prize-value">
<?php <i<?php class="bi<?php bi-trophy-fill"></i>
<?php R$<?php <?php=<?php number_format($c['maior_premio'],<?php 0,<?php ',',<?php '.');<?php ?>
<?php </div>
<?php </div>
<?php <div<?php style="text-align:<?php right;">
<?php <div<?php class="prize-label">Via<?php PIX</div>
<?php <div<?php style="color:<?php #22c55e;<?php font-size:<?php 0.9rem;">
<?php <i<?php class="bi<?php bi-check-circle-fill"></i>
<?php Instantâneo
<?php </div>
<?php </div>
<?php </div>

<?php <div<?php class="play-button">
<?php <i<?php class="bi<?php bi-play-circle-fill"></i>
<?php Jogar<?php Agora
<?php </div>
<?php </div>
<?php </a>
<?php <?php<?php endforeach;<?php ?>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </div>
<?php </section>

<?php <?php<?php include('../inc/footer.php');<?php ?>

<?php <script>
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php //<?php Filter<?php functionality
<?php const<?php filterBtns<?php =<?php document.querySelectorAll('.filter-btn');
<?php const<?php cartelasGrid<?php =<?php document.getElementById('cartelasGrid');
<?php const<?php cartelas<?php =<?php document.querySelectorAll('.cartela-card');

<?php filterBtns.forEach(btn<?php =><?php {
<?php btn.addEventListener('click',<?php ()<?php =><?php {
<?php //<?php Update<?php active<?php button
<?php filterBtns.forEach(b<?php =><?php b.classList.remove('active'));
<?php btn.classList.add('active');

<?php const<?php filter<?php =<?php btn.dataset.filter;
<?php 
<?php cartelas.forEach(cartela<?php =><?php {
<?php const<?php price<?php =<?php parseFloat(cartela.dataset.price);
<?php let<?php show<?php =<?php false;

<?php switch(filter)<?php {
<?php case<?php 'all':
<?php show<?php =<?php true;
<?php break;
<?php case<?php 'low':
<?php show<?php =<?php price<?php <=<?php 10;
<?php break;
<?php case<?php 'medium':
<?php show<?php =<?php price<?php ><?php 10<?php &&<?php price<?php <=<?php 50;
<?php break;
<?php case<?php 'high':
<?php show<?php =<?php price<?php ><?php 50;
<?php break;
<?php }

<?php if<?php (show)<?php {
<?php cartela.style.display<?php =<?php 'block';
<?php cartela.style.animation<?php =<?php 'fadeIn<?php 0.5s<?php ease-out<?php forwards';
<?php }<?php else<?php {
<?php cartela.style.display<?php =<?php 'none';
<?php }
<?php });

<?php //<?php Add<?php click<?php feedback
<?php btn.style.transform<?php =<?php 'scale(0.95)';
<?php setTimeout(()<?php =><?php {
<?php btn.style.transform<?php =<?php '';
<?php },<?php 150);
<?php });
<?php });

<?php //<?php Image<?php lazy<?php loading<?php fallback
<?php const<?php images<?php =<?php document.querySelectorAll('.cartela-image');
<?php images.forEach(img<?php =><?php {
<?php img.addEventListener('error',<?php function()<?php {
<?php this.src<?php =<?php '/assets/img/placeholder-raspadinha.jpg';
<?php });
<?php });

<?php //<?php Smooth<?php scroll<?php animations
<?php const<?php observerOptions<?php =<?php {
<?php threshold:<?php 0.1,
<?php rootMargin:<?php '0px<?php 0px<?php -50px<?php 0px'
<?php };

<?php const<?php observer<?php =<?php new<?php IntersectionObserver((entries)<?php =><?php {
<?php entries.forEach(entry<?php =><?php {
<?php if<?php (entry.isIntersecting)<?php {
<?php entry.target.style.opacity<?php =<?php '1';
<?php entry.target.style.transform<?php =<?php 'translateY(0)';
<?php }
<?php });
<?php },<?php observerOptions);

<?php //<?php Performance<?php optimization:<?php only<?php observe<?php visible<?php cards
<?php const<?php visibleCards<?php =<?php Array.from(cartelas).slice(0,<?php 6);
<?php visibleCards.forEach(card<?php =><?php observer.observe(card));

<?php //<?php Add<?php hover<?php sound<?php effect<?php (optional)
<?php cartelas.forEach(card<?php =><?php {
<?php card.addEventListener('mouseenter',<?php ()<?php =><?php {
<?php //<?php Optional:<?php Add<?php subtle<?php hover<?php sound
<?php //<?php new<?php Audio('/assets/sounds/hover.mp3').play().catch(()<?php =><?php {});
<?php });
<?php });

<?php //<?php Enhanced<?php button<?php interactions
<?php filterBtns.forEach(btn<?php =><?php {
<?php btn.addEventListener('mouseenter',<?php ()<?php =><?php {
<?php if<?php (!btn.classList.contains('active'))<?php {
<?php btn.style.transform<?php =<?php 'translateY(-3px)<?php scale(1.02)';
<?php }
<?php });

<?php btn.addEventListener('mouseleave',<?php ()<?php =><?php {
<?php if<?php (!btn.classList.contains('active'))<?php {
<?php btn.style.transform<?php =<?php '';
<?php }
<?php });
<?php });

<?php console.log('%c🎮<?php Raspadinhas<?php carregadas!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php console.log(`Total<?php de<?php ${cartelas.length}<?php raspadinhas<?php disponíveis`);
<?php });

<?php //<?php Notiflix<?php configuration
<?php Notiflix.Notify.init({
<?php width:<?php '300px',
<?php position:<?php 'right-top',
<?php distance:<?php '20px',
<?php opacity:<?php 1,
<?php borderRadius:<?php '12px',
<?php timeout:<?php 4000,
<?php success:<?php {
<?php background:<?php '#22c55e',
<?php textColor:<?php '#fff',
<?php }
<?php });
<?php </script>
</body>
</html>