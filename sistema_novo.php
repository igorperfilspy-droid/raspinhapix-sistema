<?php<?php 
@session_start();<?php 
include('./conexao.php');<?php 
//<?php Capturar<?php UTMs<?php da<?php URL<?php e<?php salvar<?php na<?php sessão
if<?php (isset($_GET["utm_source"]))<?php $_SESSION["utm_source"]<?php =<?php $_GET["utm_source"];
if<?php (isset($_GET["utm_medium"]))<?php $_SESSION["utm_medium"]<?php =<?php $_GET["utm_medium"];
if<?php (isset($_GET["utm_campaign"]))<?php $_SESSION["utm_campaign"]<?php =<?php $_GET["utm_campaign"];
if<?php (isset($_GET["utm_term"]))<?php $_SESSION["utm_term"]<?php =<?php $_GET["utm_term"];
if<?php (isset($_GET["utm_content"]))<?php $_SESSION["utm_content"]<?php =<?php $_GET["utm_content"];
if<?php (isset($_GET["click_id"]))<?php $_SESSION["click_id"]<?php =<?php $_GET["click_id"];
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
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Raspadinhas<?php Online</title><?php 
<?php <meta<?php name="description"<?php content="Raspe<?php e<?php ganhe<?php prêmios<?php incríveis!<?php PIX<?php na<?php conta<?php instantâneo."><?php 
<?php 
<?php <!--<?php Preload<?php Critical<?php Resources<?php --><?php 
<?php <link<?php rel="preconnect"<?php href="https://fonts.googleapis.com"><?php 
<?php <link<?php rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin><?php 
<?php <link<?php href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"<?php rel="stylesheet"><?php 
<?php 
<?php <!--<?php Styles<?php --><?php 
<?php <link<?php rel="stylesheet"<?php href="assets/style/globalStyles.css?v=<?php<?php echo<?php time();?>"/><?php 
<?php 
<?php <!--<?php Bootstrap<?php Icons<?php --><?php 
<?php <link<?php rel="stylesheet"<?php href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"><?php 
<?php 
<?php <!--<?php Scripts<?php --><?php 
<?php <script<?php src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script><?php 
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
<?php 
<?php <!--<?php Favicon<?php --><?php 
<?php <link<?php rel="icon"<?php type="image/x-icon"<?php href="assets/images/favicon.ico"><?php 
<?php 
<?php <!--<?php Open<?php Graph<?php --><?php 
<?php <meta<?php property="og:title"<?php content="<?php<?php echo<?php $nomeSite;?><?php -<?php Raspadinhas<?php Online"><?php 
<?php <meta<?php property="og:description"<?php content="Raspe<?php e<?php ganhe<?php prêmios<?php incríveis!<?php PIX<?php na<?php conta<?php instantâneo."><?php 
<?php <meta<?php property="og:type"<?php content="website"><?php 
<?php <meta<?php property="og:url"<?php content="<?php<?php echo<?php $urlSite;?>"><?php 
<?php 
<?php <style><?php 
<?php /*<?php Loading<?php Animation<?php */<?php 
<?php /*<?php Solução<?php definitiva<?php para<?php loading<?php spinner<?php fixo<?php */<?php 
<?php .loading-screen<?php {<?php 
<?php position:<?php fixed;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100vw;<?php 
<?php height:<?php 100vh;<?php 
<?php background:<?php #0a0a0a;<?php 
<?php z-index:<?php 9999;<?php 
<?php transition:<?php opacity<?php 0.5s<?php ease;<?php 
<?php 
<?php /*<?php Centralização<?php perfeita<?php */<?php 
<?php display:<?php grid;<?php 
<?php place-items:<?php center;<?php 
<?php }<?php 
<?php 
<?php .loading-spinner<?php {<?php 
<?php width:<?php 50px;<?php 
<?php height:<?php 50px;<?php 
<?php position:<?php relative;<?php 
<?php /*<?php Remove<?php todas<?php as<?php propriedades<?php de<?php borda<?php do<?php elemento<?php principal<?php */<?php 
<?php }<?php 
<?php 
<?php .loading-spinner::before<?php {<?php 
<?php content:<?php '';<?php 
<?php position:<?php absolute;<?php 
<?php top:<?php 0;<?php 
<?php left:<?php 0;<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php border:<?php 3px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php border-top-color:<?php #22c55e;<?php 
<?php border-radius:<?php 50%;<?php 
<?php 
<?php /*<?php Chaves<?php para<?php rotação<?php sem<?php movimento<?php */<?php 
<?php transform-origin:<?php 50%<?php 50%;<?php /*<?php Centro<?php exato<?php */<?php 
<?php animation:<?php spinFixed<?php 1s<?php linear<?php infinite;<?php 
<?php 
<?php /*<?php Força<?php o<?php elemento<?php a<?php manter<?php posição<?php */<?php 
<?php margin:<?php 0;<?php 
<?php padding:<?php 0;<?php 
<?php box-sizing:<?php border-box;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php spinFixed<?php {<?php 
<?php from<?php {<?php 
<?php transform:<?php rotate(0deg);<?php 
<?php }<?php 
<?php to<?php {<?php 
<?php transform:<?php rotate(360deg);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Alternativa<?php ainda<?php mais<?php simples<?php usando<?php apenas<?php border-image<?php */<?php 
<?php .loading-spinner-simple<?php {<?php 
<?php width:<?php 50px;<?php 
<?php height:<?php 50px;<?php 
<?php border-radius:<?php 50%;<?php 
<?php background:<?php conic-gradient(#22c55e,<?php rgba(34,<?php 197,<?php 94,<?php 0.3));<?php 
<?php animation:<?php rotateSimple<?php 1s<?php linear<?php infinite;<?php 
<?php position:<?php relative;<?php 
<?php 
<?php /*<?php Máscara<?php para<?php criar<?php o<?php efeito<?php de<?php spinner<?php */<?php 
<?php mask:<?php radial-gradient(circle<?php at<?php center,<?php transparent<?php 18px,<?php black<?php 21px);<?php 
<?php -webkit-mask:<?php radial-gradient(circle<?php at<?php center,<?php transparent<?php 18px,<?php black<?php 21px);<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php rotateSimple<?php {<?php 
<?php to<?php {<?php 
<?php transform:<?php rotate(360deg);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Versão<?php com<?php CSS<?php puro<?php -<?php mais<?php moderna<?php */<?php 
<?php .loading-spinner-modern<?php {<?php 
<?php width:<?php 50px;<?php 
<?php height:<?php 50px;<?php 
<?php background:<?php 
<?php conic-gradient(from<?php 0deg,<?php transparent,<?php #22c55e,<?php transparent),<?php 
<?php conic-gradient(from<?php 180deg,<?php transparent,<?php rgba(34,<?php 197,<?php 94,<?php 0.3),<?php transparent);<?php 
<?php border-radius:<?php 50%;<?php 
<?php animation:<?php rotateModern<?php 1s<?php linear<?php infinite;<?php 
<?php position:<?php relative;<?php 
<?php 
<?php /*<?php Efeito<?php de<?php máscara<?php para<?php criar<?php o<?php anel<?php */<?php 
<?php mask:<?php radial-gradient(circle,<?php transparent<?php 17px,<?php black<?php 20px);<?php 
<?php -webkit-mask:<?php radial-gradient(circle,<?php transparent<?php 17px,<?php black<?php 20px);<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php rotateModern<?php {<?php 
<?php 100%<?php {<?php 
<?php transform:<?php rotate(360deg);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php .hidden<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php pointer-events:<?php none;<?php 
<?php }<?php 
<?php 
<?php /*<?php Reset<?php completo<?php para<?php garantir<?php que<?php não<?php há<?php interferências<?php */<?php 
<?php .loading-screen<?php *<?php {<?php 
<?php box-sizing:<?php border-box;<?php 
<?php margin:<?php 0;<?php 
<?php padding:<?php 0;<?php 
<?php }<?php 
<?php 
<?php /*<?php Smooth<?php scroll<?php */<?php 
<?php html<?php {<?php 
<?php scroll-behavior:<?php smooth;<?php 
<?php }<?php 
<?php 
<?php /*<?php Parallax<?php effect<?php */<?php 
<?php .parallax-element<?php {<?php 
<?php transform:<?php translateZ(0);<?php 
<?php will-change:<?php transform;<?php 
<?php }<?php 
<?php 
<?php /*<?php Animations<?php */<?php 
<?php @keyframes<?php fadeInUp<?php {<?php 
<?php from<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php transform:<?php translateY(30px);<?php 
<?php }<?php 
<?php to<?php {<?php 
<?php opacity:<?php 1;<?php 
<?php transform:<?php translateY(0);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php .animate-fade-in-up<?php {<?php 
<?php animation:<?php fadeInUp<?php 0.6s<?php ease-out<?php forwards;<?php 
<?php }<?php 
<?php 
<?php /*<?php Floating<?php elements<?php animation<?php */<?php 
<?php .floating<?php {<?php 
<?php animation:<?php floating<?php 3s<?php ease-in-out<?php infinite;<?php 
<?php }<?php 
<?php 
<?php @keyframes<?php floating<?php {<?php 
<?php 0%,<?php 100%<?php {<?php transform:<?php translateY(0);<?php }<?php 
<?php 50%<?php {<?php transform:<?php translateY(-10px);<?php }<?php 
<?php }<?php 
<?php 
<?php /*<?php Glowing<?php effect<?php */<?php 
<?php .glow<?php {<?php 
<?php box-shadow:<?php 0<?php 0<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php }<?php 
<?php 
<?php .glow:hover<?php {<?php 
<?php box-shadow:<?php 0<?php 0<?php 30px<?php rgba(34,<?php 197,<?php 94,<?php 0.5);<?php 
<?php }<?php 
<?php </style><?php 
</head><?php 
<body><?php 
<?php <!--<?php Loading<?php Screen<?php --><?php 
<?php <div<?php class="loading-screen"<?php id="loadingScreen"><?php 
<?php <div<?php class="loading-spinner"></div><?php 
<?php </div><?php 
<?php 
<?php <?php<?php include('./inc/header.php');<?php ?><?php 
<?php 
<?php <main><?php 
<?php <?php<?php include('./components/carrossel.php');<?php ?><?php 
<?php 
<?php <?php<?php include('./components/ganhos.php');<?php ?><?php 
<?php 
<?php <?php<?php include('./components/chamada.php');<?php ?><?php 
<?php 
<?php <?php<?php include('./components/modals.php');<?php ?><?php 
<?php 
<?php <?php<?php include('./components/testimonials.php');<?php ?><?php 
<?php </main><?php 
<?php 
<?php <?php<?php include('./inc/footer.php');<?php ?><?php 
<?php 
<?php <script><?php 
<?php //<?php Loading<?php screen<?php 
<?php window.addEventListener('load',<?php function()<?php {<?php 
<?php const<?php loadingScreen<?php =<?php document.getElementById('loadingScreen');<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php loadingScreen.classList.add('hidden');<?php 
<?php },<?php 1000);<?php 
<?php });<?php 
<?php 
<?php //<?php Smooth<?php animations<?php on<?php scroll<?php 
<?php const<?php observerOptions<?php =<?php {<?php 
<?php threshold:<?php 0.1,<?php 
<?php rootMargin:<?php '0px<?php 0px<?php -50px<?php 0px'<?php 
<?php };<?php 
<?php 
<?php const<?php observer<?php =<?php new<?php IntersectionObserver((entries)<?php =><?php {<?php 
<?php entries.forEach(entry<?php =><?php {<?php 
<?php if<?php (entry.isIntersecting)<?php {<?php 
<?php entry.target.classList.add('animate-fade-in-up');<?php 
<?php }<?php 
<?php });<?php 
<?php },<?php observerOptions);<?php 
<?php 
<?php //<?php Observe<?php elements<?php for<?php animation<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php const<?php elementsToAnimate<?php =<?php document.querySelectorAll('.step-item,<?php .game-category,<?php .prize-item');<?php 
<?php elementsToAnimate.forEach(el<?php =><?php {<?php 
<?php observer.observe(el);<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Parallax<?php effect<?php for<?php hero<?php section<?php 
<?php window.addEventListener('scroll',<?php ()<?php =><?php {<?php 
<?php const<?php scrolled<?php =<?php window.pageYOffset;<?php 
<?php const<?php heroElements<?php =<?php document.querySelectorAll('.parallax-element');<?php 
<?php 
<?php heroElements.forEach(element<?php =><?php {<?php 
<?php const<?php speed<?php =<?php element.dataset.speed<?php ||<?php 0.5;<?php 
<?php element.style.transform<?php =<?php `translateY(${scrolled<?php *<?php speed}px)`;<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Add<?php floating<?php animation<?php to<?php certain<?php elements<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php const<?php floatingElements<?php =<?php document.querySelectorAll('.hero-visuals<?php .gaming-item');<?php 
<?php floatingElements.forEach((el,<?php index)<?php =><?php {<?php 
<?php el.style.animationDelay<?php =<?php `${index<?php *<?php 0.5}s`;<?php 
<?php el.classList.add('floating');<?php 
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
<?php rtl:<?php false,<?php 
<?php timeout:<?php 4000,<?php 
<?php messageMaxLength:<?php 110,<?php 
<?php backOverlay:<?php false,<?php 
<?php backOverlayColor:<?php 'rgba(0,0,0,0.5)',<?php 
<?php plainText:<?php true,<?php 
<?php showOnlyTheLastOne:<?php false,<?php 
<?php clickToClose:<?php true,<?php 
<?php pauseOnHover:<?php true,<?php 
<?php ID:<?php 'NotiflixNotify',<?php 
<?php className:<?php 'notiflix-notify',<?php 
<?php zindex:<?php 4001,<?php 
<?php fontFamily:<?php 'Inter',<?php 
<?php fontSize:<?php '14px',<?php 
<?php cssAnimation:<?php true,<?php 
<?php cssAnimationDuration:<?php 400,<?php 
<?php cssAnimationStyle:<?php 'zoom',<?php 
<?php closeButton:<?php false,<?php 
<?php useIcon:<?php true,<?php 
<?php useFontAwesome:<?php false,<?php 
<?php fontAwesomeIconStyle:<?php 'basic',<?php 
<?php fontAwesomeIconSize:<?php '16px',<?php 
<?php success:<?php {<?php 
<?php background:<?php '#22c55e',<?php 
<?php textColor:<?php '#fff',<?php 
<?php childClassName:<?php 'notiflix-notify-success',<?php 
<?php notiflixIconColor:<?php 'rgba(0,0,0,0.2)',<?php 
<?php fontAwesomeClassName:<?php 'fas<?php fa-check-circle',<?php 
<?php fontAwesomeIconColor:<?php 'rgba(0,0,0,0.2)',<?php 
<?php backOverlayColor:<?php 'rgba(34,197,94,0.2)',<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Dynamic<?php copyright<?php year<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php const<?php currentYear<?php =<?php new<?php Date().getFullYear();<?php 
<?php const<?php copyrightElements<?php =<?php document.querySelectorAll('.footer-description');<?php 
<?php if<?php (copyrightElements.length<?php ><?php 0)<?php {<?php 
<?php copyrightElements[0].innerHTML<?php =<?php copyrightElements[0].innerHTML.replace('2025',<?php currentYear);<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Add<?php glow<?php effect<?php to<?php interactive<?php elements<?php 
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php const<?php glowElements<?php =<?php document.querySelectorAll('.btn-register,<?php .hero-cta,<?php .game-btn');<?php 
<?php glowElements.forEach(el<?php =><?php {<?php 
<?php el.classList.add('glow');<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php //<?php Mobile<?php menu<?php toggle<?php (if<?php needed)<?php 
<?php function<?php toggleMobileMenu()<?php {<?php 
<?php const<?php mobileMenu<?php =<?php document.querySelector('.mobile-menu');<?php 
<?php if<?php (mobileMenu)<?php {<?php 
<?php mobileMenu.classList.toggle('active');<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php //<?php Console<?php welcome<?php message<?php 
<?php console.log('%c🎯<?php RaspaGreen<?php -<?php Bem-vindo!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');<?php 
<?php console.log('%cSistema<?php carregado<?php com<?php sucesso!',<?php 'color:<?php #16a34a;<?php font-size:<?php 12px;');<?php 
<?php </script><?php 
<?php 
<?php <!--<?php Performance<?php and<?php Analytics<?php --><?php 
<?php <script><?php 
<?php //<?php Performance<?php monitoring<?php 
<?php window.addEventListener('load',<?php function()<?php {<?php 
<?php if<?php ('performance'<?php in<?php window)<?php {<?php 
<?php const<?php loadTime<?php =<?php performance.timing.loadEventEnd<?php -<?php performance.timing.navigationStart;<?php 
<?php console.log(`Página<?php carregada<?php em<?php ${loadTime}ms`);<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php //<?php Error<?php handling<?php 
<?php window.addEventListener('error',<?php function(e)<?php {<?php 
<?php console.error('Erro<?php na<?php página:',<?php e.error);<?php 
<?php });<?php 
<?php 
<?php //<?php Lazy<?php loading<?php for<?php images<?php when<?php implemented<?php 
<?php if<?php ('IntersectionObserver'<?php in<?php window)<?php {<?php 
<?php const<?php imageObserver<?php =<?php new<?php IntersectionObserver((entries,<?php observer)<?php =><?php {<?php 
<?php entries.forEach(entry<?php =><?php {<?php 
<?php if<?php (entry.isIntersecting)<?php {<?php 
<?php const<?php img<?php =<?php entry.target;<?php 
<?php img.src<?php =<?php img.dataset.src;<?php 
<?php img.classList.remove('lazy');<?php 
<?php imageObserver.unobserve(img);<?php 
<?php }<?php 
<?php });<?php 
<?php });<?php 
<?php 
<?php document.querySelectorAll('img[data-src]').forEach(img<?php =><?php {<?php 
<?php imageObserver.observe(img);<?php 
<?php });<?php 
<?php }<?php 
<?php </script><?php 
</body><?php 
</html>