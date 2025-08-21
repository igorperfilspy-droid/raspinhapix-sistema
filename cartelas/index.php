<?php
@session_start();
require_once '../conexao.php';

//<?php Ordenar por valor decrescente (maior para menor)<?php primeiro
$sql =<?php "
<?php SELECT r.*,<?php MAX(p.valor)<?php AS maior_premio FROM raspadinhas r LEFT JOIN raspadinha_premios p ON p.raspadinha_id =<?php r.id GROUP BY r.id ORDER BY r.valor DESC,<?php r.created_at DESC
";
$cartelas =<?php $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?php <meta charset="UTF-8">
<?php <meta name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php echo $nomeSite;?><?php -<?php Raspadinhas</title>
<?php <!--<?php Fonts -->
<?php <link rel="preconnect"<?php href="https://fonts.googleapis.com">
<?php <link rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin>
<?php <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"<?php rel="stylesheet">
<?php <!--<?php Icons -->
<?php <link rel="stylesheet"<?php href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php <!--<?php Styles -->
<?php <link rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php=<?php time();<?php ?>">
<?php <!--<?php Scripts -->
<?php <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<?php <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet">

<?php <style>
<?php /*<?php Page Specific Styles */
<?php .cartelas-section {
<?php margin-top:<?php 100px;
<?php padding:<?php 4rem 0;
<?php background:<?php #0a0a0a;
<?php min-height:<?php calc(100vh -<?php 200px);
<?php }

<?php .cartelas-container {
<?php max-width:<?php 1400px;
<?php margin:<?php 0 auto;
<?php padding:<?php 0 2rem;
<?php }

<?php .page-header {
<?php text-align:<?php center;
<?php margin-bottom:<?php 4rem;
<?php }

<?php .page-title {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 900;
<?php color:<?php white;
<?php margin-bottom:<?php 1rem;
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #9ca3af);
<?php background-clip:<?php text;
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php }

<?php .page-subtitle {
<?php font-size:<?php 1.2rem;
<?php color:<?php #6b7280;
<?php max-width:<?php 600px;
<?php margin:<?php 0 auto;
<?php line-height:<?php 1.6;
<?php }

<?php .stats-bar {
<?php display:<?php flex;
<?php justify-content:<?php center;
<?php gap:<?php 3rem;
<?php margin-top:<?php 2rem;
<?php }

<?php .stat-item {
<?php text-align:<?php center;
<?php }

<?php .stat-number {
<?php font-size:<?php 2rem;
<?php font-weight:<?php 800;
<?php color:<?php #22c55e;
<?php display:<?php block;
<?php }

<?php .stat-label {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php margin-top:<?php 0.25rem;
<?php }

<?php .cartelas-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fill,<?php minmax(350px,<?php 1fr));
<?php gap:<?php 2rem;
<?php margin-bottom:<?php 4rem;
<?php }

<?php .cartela-card {
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 24px;
<?php overflow:<?php hidden;
<?php transition:<?php all 0.3s ease;
<?php position:<?php relative;
<?php text-decoration:<?php none;
<?php color:<?php inherit;
<?php backdrop-filter:<?php blur(20px);
<?php }

<?php .cartela-card:hover {
<?php transform:<?php translateY(-8px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php box-shadow:<?php 0 20px 60px rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php }

<?php .cartela-image {
<?php width:<?php 100%;
<?php height:<?php 200px;
<?php object-fit:<?php cover;
<?php transition:<?php transform 0.3s ease;
<?php }

<?php .cartela-card:hover .cartela-image {
<?php transform:<?php scale(1.05);
<?php }

<?php .cartela-content {
<?php padding:<?php 1.5rem;
<?php position:<?php relative;
<?php }

<?php .price-badge {
<?php position:<?php absolute;
<?php top:<?php 1rem;
<?php right:<?php 1rem;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php padding:<?php 0.5rem 1rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 700;
<?php font-size:<?php 0.9rem;
<?php box-shadow:<?php 0 4px 16px rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .cartela-title {
<?php font-size:<?php 1.3rem;
<?php font-weight:<?php 700;
<?php color:<?php white;
<?php margin-bottom:<?php 0.5rem;
<?php line-height:<?php 1.3;
<?php }

<?php .cartela-description {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php margin-bottom:<?php 1rem;
<?php line-height:<?php 1.5;
<?php }

<?php .prize-info {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php border:<?php 1px solid rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-radius:<?php 12px;
<?php padding:<?php 1rem;
<?php margin-bottom:<?php 1rem;
<?php }

<?php .prize-label {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.8rem;
<?php margin-bottom:<?php 0.25rem;
<?php }

<?php .prize-value {
<?php color:<?php #22c55e;
<?php font-weight:<?php 800;
<?php font-size:<?php 1.1rem;
<?php }

<?php .play-button {
<?php width:<?php 100%;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php border:<?php none;
<?php padding:<?php 0.75rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php gap:<?php 0.5rem;
<?php text-decoration:<?php none;
<?php }

<?php .play-button:hover {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 8px 24px rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }

<?php .cartela-features {
<?php display:<?php flex;
<?php gap:<?php 0.5rem;
<?php margin-bottom:<?php 1rem;
<?php }

<?php .feature-tag {
<?php background:<?php rgba(99,<?php 102,<?php 241,<?php 0.1);
<?php color:<?php #a5b4fc;
<?php padding:<?php 0.25rem 0.5rem;
<?php border-radius:<?php 6px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 500;
<?php }

<?php .empty-state {
<?php text-align:<?php center;
<?php padding:<?php 4rem 2rem;
<?php color:<?php #6b7280;
<?php }

<?php .empty-icon {
<?php font-size:<?php 4rem;
<?php margin-bottom:<?php 1rem;
<?php opacity:<?php 0.5;
<?php }

<?php .loading-skeleton {
<?php background:<?php linear-gradient(90deg,<?php rgba(255,255,255,0.1)<?php 25%,<?php rgba(255,255,255,0.2)<?php 50%,<?php rgba(255,255,255,0.1)<?php 75%);
<?php background-size:<?php 200%<?php 100%;
<?php animation:<?php loading 1.5s infinite;
<?php }

<?php @keyframes loading {
<?php 0%<?php {<?php background-position:<?php 200%<?php 0;<?php }
<?php 100%<?php {<?php background-position:<?php -200%<?php 0;<?php }
<?php }

<?php /*<?php Improved Filter Bar */
<?php .filter-bar {
<?php display:<?php flex;
<?php justify-content:<?php center;
<?php gap:<?php 0.75rem;
<?php margin-bottom:<?php 3rem;
<?php flex-wrap:<?php wrap;
<?php }

<?php .filter-btn {
<?php background:<?php linear-gradient(145deg,<?php #1e1e1e,<?php #2a2a2a);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php color:<?php #9ca3af;
<?php padding:<?php 0.75rem 1.25rem;
<?php border-radius:<?php 16px;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0 4px 12px rgba(0,<?php 0,<?php 0,<?php 0.2);
<?php }

<?php .filter-btn::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php -100%;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php transition:<?php left 0.3s ease;
<?php z-index:<?php -1;
<?php }

<?php .filter-btn:hover {
<?php color:<?php #ffffff;
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 8px 25px rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php }

<?php .filter-btn:hover::before {
<?php left:<?php 0;
<?php }

<?php .filter-btn.active {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-color:<?php #22c55e;
<?php color:<?php #ffffff;
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 8px 25px rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }

<?php .filter-btn.active::before {
<?php left:<?php 0;
<?php }

<?php .filter-btn i {
<?php font-size:<?php 1rem;
<?php transition:<?php transform 0.3s ease;
<?php }

<?php .filter-btn:hover i,
<?php .filter-btn.active i {
<?php transform:<?php scale(1.1);
<?php }

<?php /*<?php Filter button animations */
<?php .filter-btn.low {
<?php --hover-color:<?php #22c55e;
<?php }

<?php .filter-btn.medium {
<?php --hover-color:<?php #f59e0b;
<?php }

<?php .filter-btn.high {
<?php --hover-color:<?php #ef4444;
<?php }

<?php .filter-btn.medium::before {
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);
<?php }

<?php .filter-btn.high::before {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php }

<?php .filter-btn.medium.active {
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);
<?php border-color:<?php #f59e0b;
<?php box-shadow:<?php 0 8px 25px rgba(245,<?php 158,<?php 11,<?php 0.4);
<?php }

<?php .filter-btn.high.active {
<?php background:<?php linear-gradient(135deg,<?php #ef4444,<?php #dc2626);
<?php border-color:<?php #ef4444;
<?php box-shadow:<?php 0 8px 25px rgba(239,<?php 68,<?php 68,<?php 0.4);
<?php }

<?php /*<?php Responsive */
<?php @media (max-width:<?php 768px)<?php {
<?php .page-title {
<?php font-size:<?php 2rem;
<?php }
<?php .stats-bar {
<?php gap:<?php 1.5rem;
<?php flex-wrap:<?php wrap;
<?php }
<?php .stat-number {
<?php font-size:<?php 1.5rem;
<?php }
<?php .cartelas-grid {
<?php grid-template-columns:<?php 1fr;
<?php gap:<?php 1.5rem;
<?php }
<?php .cartela-content {
<?php padding:<?php 1rem;
<?php }
<?php .filter-bar {
<?php gap:<?php 0.5rem;
<?php }
<?php .filter-btn {
<?php padding:<?php 0.6rem 1rem;
<?php font-size:<?php 0.85rem;
<?php }
<?php }

<?php @media (max-width:<?php 480px)<?php {
<?php .cartelas-container {
<?php padding:<?php 0 1rem;
<?php }
<?php .cartelas-grid {
<?php grid-template-columns:<?php 1fr;
<?php }

<?php .filter-bar {
<?php flex-direction:<?php column;
<?php align-items:<?php center;
<?php }

<?php .filter-btn {
<?php width:<?php 200px;
<?php justify-content:<?php center;
<?php }
<?php }

<?php /*<?php Animations */
<?php .fade-in {
<?php animation:<?php fadeIn 0.6s ease-out forwards;
<?php }

<?php @keyframes fadeIn {
<?php from {
<?php opacity:<?php 0;
<?php transform:<?php translateY(20px);
<?php }
<?php to {
<?php opacity:<?php 1;
<?php transform:<?php translateY(0);
<?php }
<?php }

<?php .stagger-animation .cartela-card {
<?php opacity:<?php 0;
<?php animation:<?php fadeIn 0.6s ease-out forwards;
<?php }

<?php .stagger-animation .cartela-card:nth-child(1)<?php {<?php animation-delay:<?php 0.1s;<?php }
<?php .stagger-animation .cartela-card:nth-child(2)<?php {<?php animation-delay:<?php 0.2s;<?php }
<?php .stagger-animation .cartela-card:nth-child(3)<?php {<?php animation-delay:<?php 0.3s;<?php }
<?php .stagger-animation .cartela-card:nth-child(4)<?php {<?php animation-delay:<?php 0.4s;<?php }
<?php .stagger-animation .cartela-card:nth-child(5)<?php {<?php animation-delay:<?php 0.5s;<?php }
<?php .stagger-animation .cartela-card:nth-child(6)<?php {<?php animation-delay:<?php 0.6s;<?php }

<?php /*<?php Sparkle animation for premium buttons */
<?php @keyframes sparkle {
<?php 0%,<?php 100%<?php {<?php transform:<?php scale(1)<?php rotate(0deg);<?php opacity:<?php 1;<?php }
<?php 50%<?php {<?php transform:<?php scale(1.05)<?php rotate(2deg);<?php opacity:<?php 0.9;<?php }
<?php }

<?php .filter-btn.high.active {
<?php animation:<?php sparkle 2s ease-in-out infinite;
<?php }
<?php </style>
</head>
<body>
<?php <?php include('../inc/header.php');<?php ?>
<?php <?php include('../components/modals.php');<?php ?>

<?php <section class="cartelas-section">
<?php <div class="cartelas-container">
<?php <!--<?php Page Header -->
<?php <div class="page-header fade-in">
<?php <h1 class="page-title">Escolha sua Raspadinha</h1>
<?php <p class="page-subtitle">
<?php Centenas de prêmios esperando por você!<?php Raspe e ganhe na hora com PIX instantâneo.
<?php </p>
<?php <div class="stats-bar">
<?php <div class="stat-item">
<?php <span class="stat-number"><?php=<?php count($cartelas);<?php ?></span>
<?php <span class="stat-label">Raspadinhas</span>
<?php </div>
<?php <div class="stat-item">
<?php <span class="stat-number">R$<?php <?php=<?php number_format(array_sum(array_column($cartelas,<?php 'maior_premio')),<?php 0,<?php ',',<?php '.');<?php ?></span>
<?php <span class="stat-label">Em Prêmios</span>
<?php </div>
<?php <div class="stat-item">
<?php <span class="stat-number">24/7</span>
<?php <span class="stat-label">Disponível</span>
<?php </div>
<?php </div>
<?php </div>
<?php <!--<?php Enhanced Filter Bar -->
<?php <div class="filter-bar fade-in">
<?php <button class="filter-btn active"<?php data-filter="all">
<?php <i class="bi bi-grid-3x3-gap-fill"></i>
<?php <span>Todas as Raspadinhas</span>
<?php </button>
<?php <button class="filter-btn low"<?php data-filter="low">
<?php <i class="bi bi-coin"></i>
<?php <span>Até<?php R$<?php 10</span>
<?php </button>
<?php <button class="filter-btn medium"<?php data-filter="medium">
<?php <i class="bi bi-cash-stack"></i>
<?php <span>R$<?php 10 -<?php R$<?php 50</span>
<?php </button>
<?php <button class="filter-btn high"<?php data-filter="high">
<?php <i class="bi bi-gem"></i>
<?php <span>Acima de R$<?php 50</span>
<?php </button>
<?php </div>

<?php <!--<?php Cartelas Grid -->
<?php <?php if (empty($cartelas)):<?php ?>
<?php <div class="empty-state">
<?php <i class="bi bi-grid-3x3-gap empty-icon"></i>
<?php <h3 style="color:<?php white;<?php margin-bottom:<?php 1rem;">Nenhuma raspadinha disponível</h3>
<?php <p>Novas raspadinhas em breve!<?php Fique atento às atualizações.</p>
<?php </div>
<?php <?php else:<?php ?>
<?php <div class="cartelas-grid stagger-animation"<?php id="cartelasGrid">
<?php <?php foreach ($cartelas as $c):<?php ?>
<?php <a href="/raspadinhas/show.php?id=<?php=<?php $c['id'];<?php ?>"<?php class="cartela-card"<?php data-price="<?php=<?php $c['valor'];<?php ?>"
<?php data-aos="fade-up">
<?php <div style="position:<?php relative;<?php overflow:<?php hidden;">
<?php <img src="<?php=<?php htmlspecialchars($c['banner']);<?php ?>"
<?php alt="Banner <?php=<?php htmlspecialchars($c['nome']);<?php ?>"
<?php class="cartela-image"<?php loading="lazy"
<?php onerror="this.src='/assets/img/placeholder-raspadinha.jpg'">
<?php <div class="price-badge">
<?php <i class="bi bi-tag-fill"></i>
<?php R$<?php <?php=<?php number_format($c['valor'],<?php 2,<?php ',',<?php '.');<?php ?>
<?php </div>
<?php </div>

<?php <div class="cartela-content">
<?php <div class="cartela-features">
<?php <span class="feature-tag">
<?php <i class="bi bi-lightning-fill"></i>
<?php PIX Instantâneo </span>
<?php <?php if($c['maior_premio']<?php >=<?php 1000):<?php ?>
<?php <span class="feature-tag">
<?php <i class="bi bi-star-fill"></i>
<?php Premium </span>
<?php <?php endif;<?php ?>
<?php </div>

<?php <h2 class="cartela-title">
<?php <?php=<?php htmlspecialchars($c['nome']);<?php ?>
<?php </h2>
<?php <p class="cartela-description">
<?php <?php=<?php htmlspecialchars($c['descricao']);<?php ?>
<?php </p>

<?php <div class="prize-info">
<?php <div>
<?php <div class="prize-label">Prêmio máximo</div>
<?php <div class="prize-value">
<?php <i class="bi bi-trophy-fill"></i>
<?php R$<?php <?php=<?php number_format($c['maior_premio'],<?php 0,<?php ',',<?php '.');<?php ?>
<?php </div>
<?php </div>
<?php <div style="text-align:<?php right;">
<?php <div class="prize-label">Via PIX</div>
<?php <div style="color:<?php #22c55e;<?php font-size:<?php 0.9rem;">
<?php <i class="bi bi-check-circle-fill"></i>
<?php Instantâneo </div>
<?php </div>
<?php </div>

<?php <div class="play-button">
<?php <i class="bi bi-play-circle-fill"></i>
<?php Jogar Agora </div>
<?php </div>
<?php </a>
<?php <?php endforeach;<?php ?>
<?php </div>
<?php <?php endif;<?php ?>
<?php </div>
<?php </section>

<?php <?php include('../inc/footer.php');<?php ?>

<?php <script>
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php //<?php Filter functionality const filterBtns =<?php document.querySelectorAll('.filter-btn');
<?php const cartelasGrid =<?php document.getElementById('cartelasGrid');
<?php const cartelas =<?php document.querySelectorAll('.cartela-card');

<?php filterBtns.forEach(btn =><?php {
<?php btn.addEventListener('click',<?php ()<?php =><?php {
<?php //<?php Update active button filterBtns.forEach(b =><?php b.classList.remove('active'));
<?php btn.classList.add('active');

<?php const filter =<?php btn.dataset.filter;
<?php cartelas.forEach(cartela =><?php {
<?php const price =<?php parseFloat(cartela.dataset.price);
<?php let show =<?php false;

<?php switch(filter)<?php {
<?php case 'all':
<?php show =<?php true;
<?php break;
<?php case 'low':
<?php show =<?php price <=<?php 10;
<?php break;
<?php case 'medium':
<?php show =<?php price ><?php 10 &&<?php price <=<?php 50;
<?php break;
<?php case 'high':
<?php show =<?php price ><?php 50;
<?php break;
<?php }

<?php if (show)<?php {
<?php cartela.style.display =<?php 'block';
<?php cartela.style.animation =<?php 'fadeIn 0.5s ease-out forwards';
<?php }<?php else {
<?php cartela.style.display =<?php 'none';
<?php }
<?php });

<?php //<?php Add click feedback btn.style.transform =<?php 'scale(0.95)';
<?php setTimeout(()<?php =><?php {
<?php btn.style.transform =<?php '';
<?php },<?php 150);
<?php });
<?php });

<?php //<?php Image lazy loading fallback const images =<?php document.querySelectorAll('.cartela-image');
<?php images.forEach(img =><?php {
<?php img.addEventListener('error',<?php function()<?php {
<?php this.src =<?php '/assets/img/placeholder-raspadinha.jpg';
<?php });
<?php });

<?php //<?php Smooth scroll animations const observerOptions =<?php {
<?php threshold:<?php 0.1,
<?php rootMargin:<?php '0px 0px -50px 0px'
<?php };

<?php const observer =<?php new IntersectionObserver((entries)<?php =><?php {
<?php entries.forEach(entry =><?php {
<?php if (entry.isIntersecting)<?php {
<?php entry.target.style.opacity =<?php '1';
<?php entry.target.style.transform =<?php 'translateY(0)';
<?php }
<?php });
<?php },<?php observerOptions);

<?php //<?php Performance optimization:<?php only observe visible cards const visibleCards =<?php Array.from(cartelas).slice(0,<?php 6);
<?php visibleCards.forEach(card =><?php observer.observe(card));

<?php //<?php Add hover sound effect (optional)
<?php cartelas.forEach(card =><?php {
<?php card.addEventListener('mouseenter',<?php ()<?php =><?php {
<?php //<?php Optional:<?php Add subtle hover sound //<?php new Audio('/assets/sounds/hover.mp3').play().catch(()<?php =><?php {});
<?php });
<?php });

<?php //<?php Enhanced button interactions filterBtns.forEach(btn =><?php {
<?php btn.addEventListener('mouseenter',<?php ()<?php =><?php {
<?php if (!btn.classList.contains('active'))<?php {
<?php btn.style.transform =<?php 'translateY(-3px)<?php scale(1.02)';
<?php }
<?php });

<?php btn.addEventListener('mouseleave',<?php ()<?php =><?php {
<?php if (!btn.classList.contains('active'))<?php {
<?php btn.style.transform =<?php '';
<?php }
<?php });
<?php });

<?php console.log('%c🎮<?php Raspadinhas carregadas!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php console.log(`Total de ${cartelas.length}<?php raspadinhas disponíveis`);
<?php });

<?php //<?php Notiflix configuration Notiflix.Notify.init({
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