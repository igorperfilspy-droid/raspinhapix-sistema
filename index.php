<?php
@session_start();
require __DIR__ . '/conexao.php'; // garante $pdo, $nomeSite, $urlSite (como você já tinha)

/* Captura de UTM/ClickId na sessão */
foreach ([
    'utm_source','utm_medium','utm_campaign','utm_term','utm_content','click_id'
] as $k) {
    if (isset($_GET[$k])) $_SESSION[$k] = $_GET[$k];
}

/* Fallbacks seguros */
$nomeSite = isset($nomeSite) && $nomeSite ? $nomeSite : 'RaspinhaPix';
$urlSite  = isset($urlSite)  && $urlSite  ? $urlSite  : '/';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- xTracky Integration -->
    <script 
        src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
        data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
        data-click-id-param="click_id">
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nomeSite); ?> - Raspadinhas Online</title>
    <meta name="description" content="Raspe e ganhe prêmios incríveis! PIX na conta instantâneo.">

    <!-- Preload / Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="assets/style/globalStyles.css?v=<?php echo time(); ?>"/>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts utilitários -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($nomeSite); ?> - Raspadinhas Online">
    <meta property="og:description" content="Raspe e ganhe prêmios incríveis! PIX na conta instantâneo.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($urlSite); ?>">

    <style>
        /* -------- Loading -------- */
        .loading-screen {
            position: fixed; inset: 0; width: 100vw; height: 100vh;
            background:#0a0a0a; z-index: 9999; transition: opacity .5s ease;
            display: grid; place-items: center;
        }
        .loading-spinner { width: 50px; height: 50px; position: relative; }
        .loading-spinner::before {
            content: ''; position: absolute; inset: 0;
            border: 3px solid rgba(34, 197, 94, 0.3);
            border-top-color: #22c55e; border-radius: 50%;
            transform-origin: 50% 50%; animation: spinFixed 1s linear infinite;
            margin:0; padding:0; box-sizing:border-box;
        }
        @keyframes spinFixed { from {transform:rotate(0)} to {transform:rotate(360deg)} }
        .hidden { opacity:0; pointer-events:none; }

        /* Efeitos extras */
        html { scroll-behavior: smooth; }
        .parallax-element { transform: translateZ(0); will-change: transform; }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        .animate-fade-in-up { animation: fadeInUp .6s ease-out forwards; }
        @keyframes floating { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .floating { animation: floating 3s ease-in-out infinite; }
        .glow { box-shadow: 0 0 20px rgba(34, 197, 94, 0.3); }
        .glow:hover { box-shadow: 0 0 30px rgba(34, 197, 94, 0.5); }
        .loading-screen *, body, html { box-sizing: border-box; margin: 0; padding: 0; }
    </style>
</head>
<body>
    <!-- Loading -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-spinner"></div>
    </div>

    <?php
    // Atenção: garanta que esses arquivos existem nesses caminhos:
    // ./inc/header.php, ./components/*.php, ./inc/footer.php
    // Se estiverem em outra pasta, ajuste os paths.
    if (is_file(__DIR__.'/inc/header.php')) include __DIR__.'/inc/header.php';
    ?>

    <main>
        <?php
        foreach (['carrossel','ganhos','chamada','modals','testimonials'] as $comp) {
            $p = __DIR__."/components/{$comp}.php";
            if (is_file($p)) include $p;
        }
        ?>
    </main>

    <?php if (is_file(__DIR__.'/inc/footer.php')) include __DIR__.'/inc/footer.php'; ?>

    <script>
        // Loading screen
        window.addEventListener('load', function () {
            const loadingScreen = document.getElementById('loadingScreen');
            setTimeout(() => loadingScreen.classList.add('hidden'), 1000);
        });

        // Aparecer com animação ao entrar no viewport
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add('animate-fade-in-up'); });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.step-item, .game-category, .prize-item').forEach((el) => observer.observe(el));
        });

        // Parallax
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            document.querySelectorAll('.parallax-element').forEach((el) => {
                const speed = parseFloat(el.dataset.speed || 0.5);
                el.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Elementos flutuantes
        document.addEventListener('DOMContentLoaded', function () {
            const els = document.querySelectorAll('.hero-visuals .gaming-item');
            els.forEach((el, i) => {
                el.style.animationDelay = `${i * 0.5}s`;
                el.classList.add('floating');
            });
        });

        // Notiflix
        Notiflix.Notify.init({
            width: '300px', position: 'right-top', distance: '20px', opacity: 1, borderRadius: '12px',
            timeout: 4000, messageMaxLength: 110, backOverlay: false, plainText: true,
            showOnlyTheLastOne: false, clickToClose: true, pauseOnHover: true, zindex: 4001,
            fontFamily: 'Inter', fontSize: '14px', cssAnimation: true, cssAnimationDuration: 400,
            cssAnimationStyle: 'zoom', success: { background: '#22c55e', textColor: '#fff' }
        });

        // Footer ano dinâmico
        document.addEventListener('DOMContentLoaded', function () {
            const y = new Date().getFullYear();
            const els = document.querySelectorAll('.footer-description');
            if (els.length) els[0].innerHTML = els[0].innerHTML.replace(/\b20\d{2}\b/, y);
        });

        // Glow
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-register, .hero-cta, .game-btn').forEach((el) => el.classList.add('glow'));
        });

        // Menu móvel (se existir)
        function toggleMobileMenu() {
            const mobileMenu = document.querySelector('.mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('active');
        }

        // Logs
        console.log('%c🎯 RaspaGreen - Bem-vindo!', 'color:#22c55e;font-size:16px;font-weight:bold');
        console.log('%cSistema carregado com sucesso!', 'color:#16a34a;font-size:12px');

        // Performance
        window.addEventListener('load', function () {
            if ('performance' in window && performance.timing) {
                const t = performance.timing.loadEventEnd - performance.timing.navigationStart;
                console.log(`Página carregada em ${t}ms`);
            }
        });

        // Lazy Loading (quando tiver imagens com data-src)
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting) {
                        const img = e.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        io.unobserve(img);
                    }
                });
            });
            document.querySelectorAll('img[data-src]').forEach((img) => io.observe(img));
        }
    </script>
</body>
</html>
