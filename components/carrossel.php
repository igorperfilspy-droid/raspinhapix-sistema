<?php
//<?php Buscar banners ativos do banco de dados
$query =<?php "SELECT *<?php FROM banners WHERE ativo =<?php 1 ORDER BY ordem ASC";
$banners =<?php $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="hero-section">
<?php <div class="hero-container">
<?php <div class="hero-banner carousel-container">
<?php <?php if (!empty($banners)):<?php ?>
<?php <!--<?php Container dos slides -->
<?php <div class="carousel-wrapper">
<?php <?php foreach ($banners as $index =><?php $banner):<?php ?>
<?php <div class="hero-slide banner-slide <?php=<?php $index ===<?php 0 ?<?php 'active'<?php :<?php ''<?php ?>"<?php data-slide="<?php=<?php $index ?>"<?php style="background-image:<?php url('<?php=<?php htmlspecialchars($banner['banner_img'])<?php ?>');">
<?php <div class="banner-overlay"></div>
<?php </div>
<?php <?php endforeach;<?php ?>
<?php </div>
<?php <?php if (count($banners)<?php ><?php 1):<?php ?>
<?php <!--<?php NAVEGAÇÃO DO CARROSSEL -<?php ÍCONES CORRIGIDOS -->
<?php <button class="carousel-nav prev"<?php onclick="changeSlide(-1)"<?php aria-label="Banner anterior">
<?php <span class="nav-arrow">‹</span>
<?php </button>
<?php <button class="carousel-nav next"<?php onclick="changeSlide(1)"<?php aria-label="Próximo banner">
<?php <span class="nav-arrow">›</span>
<?php </button>
<?php <!--<?php Indicadores -->
<?php <div class="carousel-indicators">
<?php <?php foreach ($banners as $index =><?php $banner):<?php ?>
<?php <button class="indicator <?php=<?php $index ===<?php 0 ?<?php 'active'<?php :<?php ''<?php ?>"<?php onclick="goToSlide(<?php=<?php $index ?>)"
<?php aria-label="Ir para banner <?php=<?php $index +<?php 1 ?>"></button>
<?php <?php endforeach;<?php ?>
<?php </div>
<?php <?php endif;<?php ?>
<?php <?php else:<?php ?>
<?php <!--<?php Fallback caso não tenha banners -->
<?php <div class="carousel-wrapper">
<?php <div class="hero-slide fallback-slide active">
<?php <div class="hero-content">
<?php <h1 class="hero-title">PRÊMIOS DE ATÉ</h1>
<?php <h2 class="hero-subtitle">15 MIL REAIS</h2>
<?php <a href="#games"<?php class="hero-cta">RASPE AGORA!</a>
<?php </div>
<?php <div class="hero-visuals">
<?php <div class="money-stack"></div>
<?php </div>
<?php </div>
<?php </div>
<?php <?php endif;<?php ?>
<?php </div>
<?php </div>
</section>

<style>
/*<?php ==========================================
<?php BANNER CAROUSEL STYLES -<?php NAVEGAÇÃO CORRIGIDA ==========================================<?php */

/*<?php Container principal do carrossel */
.carousel-container {
<?php position:<?php relative;
<?php width:<?php 100%;
<?php max-width:<?php 100%;
<?php height:<?php 500px;
<?php border-radius:<?php 24px;
<?php overflow:<?php hidden;
<?php margin:<?php 0 auto;
}

/*<?php Wrapper dos slides */
.carousel-wrapper {
<?php position:<?php relative;
<?php width:<?php 100%;
<?php height:<?php 100%;
}

/*<?php Slides dos banners */
.hero-slide {
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php background-size:<?php cover;
<?php background-position:<?php center;
<?php background-repeat:<?php no-repeat;
<?php opacity:<?php 0;
<?php visibility:<?php hidden;
<?php transition:<?php all 0.6s cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php z-index:<?php 1;
}

/*<?php Slide ativo */
.hero-slide.active {
<?php opacity:<?php 1;
<?php visibility:<?php visible;
<?php z-index:<?php 2;
}

/*<?php Slide de fallback */
.hero-slide.fallback-slide {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php padding:<?php 3rem 4rem;
}

/*<?php Conteúdo do fallback */
.hero-content {
<?php color:<?php white;
<?php z-index:<?php 3;
}

.hero-title {
<?php font-size:<?php 2.5rem;
<?php font-weight:<?php bold;
<?php margin-bottom:<?php 0.5rem;
}

.hero-subtitle {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 900;
<?php color:<?php #22c55e;
<?php margin-bottom:<?php 1.5rem;
}

.hero-cta {
<?php display:<?php inline-block;
<?php background:<?php #22c55e;
<?php color:<?php white;
<?php padding:<?php 1rem 2rem;
<?php border-radius:<?php 8px;
<?php text-decoration:<?php none;
<?php font-weight:<?php bold;
<?php transition:<?php transform 0.3s ease;
}

.hero-cta:hover {
<?php transform:<?php translateY(-2px);
}

/*<?php Slides com banners */
.hero-slide.banner-slide {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
}

/*<?php Overlay para banners */
.banner-overlay {
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php bottom:<?php 0;
<?php pointer-events:<?php none;
<?php z-index:<?php 1;
}

/*<?php NAVEGAÇÃO DO CARROSSEL -<?php ÍCONES CORRIGIDOS */
.carousel-nav {
<?php position:<?php absolute;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php backdrop-filter:<?php blur(10px);
<?php color:<?php white;
<?php border:<?php 2px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php width:<?php 50px;
<?php height:<?php 50px;
<?php border-radius:<?php 50%;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php z-index:<?php 10;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.2rem;
}

.carousel-nav .nav-arrow {
<?php font-size:<?php 24px;
<?php font-weight:<?php bold;
<?php line-height:<?php 1;
<?php user-select:<?php none;
<?php pointer-events:<?php none;
}

.carousel-nav:hover {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.8);
<?php border-color:<?php #22c55e;
<?php transform:<?php translateY(-50%)<?php scale(1.1);
<?php box-shadow:<?php 0 8px 25px rgba(34,<?php 197,<?php 94,<?php 0.4);
}

.carousel-nav.prev {
<?php left:<?php 2rem;
}

.carousel-nav.next {
<?php right:<?php 2rem;
}

/*<?php Indicadores */
.carousel-indicators {
<?php position:<?php absolute;
<?php bottom:<?php 2rem;
<?php left:<?php 50%;
<?php transform:<?php translateX(-50%);
<?php display:<?php flex;
<?php gap:<?php 0.75rem;
<?php z-index:<?php 10;
}

.indicator {
<?php width:<?php 12px;
<?php height:<?php 12px;
<?php border-radius:<?php 50%;
<?php border:<?php 2px solid rgba(255,<?php 255,<?php 255,<?php 0.7);
<?php background:<?php transparent;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php backdrop-filter:<?php blur(5px);
}

.indicator:hover {
<?php border-color:<?php white;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.3);
<?php transform:<?php scale(1.2);
}

.indicator.active {
<?php background:<?php #22c55e;
<?php border-color:<?php #22c55e;
<?php box-shadow:<?php 0 0 15px rgba(34,<?php 197,<?php 94,<?php 0.6);
}

/*<?php Loading state */
.banner-slide::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 50%;
<?php left:<?php 50%;
<?php transform:<?php translate(-50%,<?php -50%);
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php border:<?php 3px solid rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-top-color:<?php #22c55e;
<?php border-radius:<?php 50%;
<?php animation:<?php spin 1s linear infinite;
<?php opacity:<?php 0;
<?php transition:<?php opacity 0.3s ease;
<?php z-index:<?php 5;
}

.banner-slide.loading::before {
<?php opacity:<?php 1;
}

@keyframes spin {
<?php to {<?php transform:<?php translate(-50%,<?php -50%)<?php rotate(360deg);<?php }
}

/*<?php Animações de transição */
@keyframes slideInRight {
<?php from {
<?php opacity:<?php 0;
<?php transform:<?php translateX(50px);
<?php }
<?php to {
<?php opacity:<?php 1;
<?php transform:<?php translateX(0);
<?php }
}

@keyframes slideInLeft {
<?php from {
<?php opacity:<?php 0;
<?php transform:<?php translateX(-50px);
<?php }
<?php to {
<?php opacity:<?php 1;
<?php transform:<?php translateX(0);
<?php }
}

.hero-slide.slide-right {
<?php animation:<?php slideInRight 0.6s ease-out;
}

.hero-slide.slide-left {
<?php animation:<?php slideInLeft 0.6s ease-out;
}

/*<?php ==========================================
<?php RESPONSIVO ==========================================<?php */

/*<?php Desktop grande */
@media (min-width:<?php 1440px)<?php {
<?php .carousel-container {
<?php height:<?php 600px;
<?php max-width:<?php 95%;
<?php }
}

/*<?php Desktop médio */
@media (min-width:<?php 1025px)<?php and (max-width:<?php 1439px)<?php {
<?php .carousel-container {
<?php height:<?php 500px;
<?php max-width:<?php 90%;
<?php }
}

/*<?php Tablet */
@media (max-width:<?php 1024px)<?php {
<?php .carousel-container {
<?php height:<?php 350px;
<?php border-radius:<?php 20px;
<?php max-width:<?php 95%;
<?php }
<?php .hero-slide.fallback-slide {
<?php padding:<?php 2rem 3rem;
<?php }
<?php .hero-title {
<?php font-size:<?php 2rem;
<?php }
<?php .hero-subtitle {
<?php font-size:<?php 2.5rem;
<?php }
}

/*<?php Mobile */
@media (max-width:<?php 768px)<?php {
<?php .hero-section {
<?php padding:<?php 1rem 0;
<?php }
<?php .hero-container {
<?php padding:<?php 0 1rem;
<?php }
<?php .carousel-container {
<?php height:<?php 220px;
<?php border-radius:<?php 16px;
<?php max-width:<?php 100%;
<?php margin:<?php 0;
<?php }
<?php .hero-slide {
<?php background-size:<?php contain;
<?php background-position:<?php center;
<?php }
<?php .hero-slide.fallback-slide {
<?php flex-direction:<?php column;
<?php text-align:<?php center;
<?php padding:<?php 1.5rem 1rem;
<?php gap:<?php 1rem;
<?php background-size:<?php cover;
<?php }
<?php .hero-title {
<?php font-size:<?php 1.5rem;
<?php margin-bottom:<?php 0.25rem;
<?php }
<?php .hero-subtitle {
<?php font-size:<?php 2rem;
<?php margin-bottom:<?php 1rem;
<?php }
<?php .hero-cta {
<?php padding:<?php 0.75rem 1.5rem;
<?php font-size:<?php 0.9rem;
<?php }
<?php .carousel-nav {
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php font-size:<?php 1rem;
<?php }
<?php .carousel-nav .nav-arrow {
<?php font-size:<?php 18px;
<?php }
<?php .carousel-nav.prev {
<?php left:<?php 1rem;
<?php }
<?php .carousel-nav.next {
<?php right:<?php 1rem;
<?php }
<?php .carousel-indicators {
<?php bottom:<?php 1rem;
<?php gap:<?php 0.5rem;
<?php }
<?php .indicator {
<?php width:<?php 8px;
<?php height:<?php 8px;
<?php }
<?php /*<?php Remover animações no mobile */
<?php .hero-slide.slide-right,
<?php .hero-slide.slide-left {
<?php animation:<?php none;
<?php }
}

/*<?php Mobile pequeno */
@media (max-width:<?php 480px)<?php {
<?php .hero-section {
<?php padding:<?php 0.5rem 0;
<?php }
<?php .hero-container {
<?php padding:<?php 0 0.5rem;
<?php }
<?php .carousel-container {
<?php height:<?php 200px;
<?php border-radius:<?php 12px;
<?php max-width:<?php 100%;
<?php margin:<?php 0;
<?php }
<?php .hero-slide {
<?php background-size:<?php contain;
<?php background-position:<?php center;
<?php }
<?php .hero-slide.fallback-slide {
<?php padding:<?php 1rem;
<?php background-size:<?php cover;
<?php }
<?php .hero-title {
<?php font-size:<?php 1.25rem;
<?php }
<?php .hero-subtitle {
<?php font-size:<?php 1.75rem;
<?php }
<?php .hero-cta {
<?php padding:<?php 0.5rem 1rem;
<?php font-size:<?php 0.8rem;
<?php }
<?php .carousel-nav {
<?php width:<?php 32px;
<?php height:<?php 32px;
<?php font-size:<?php 0.8rem;
<?php }
<?php .carousel-nav .nav-arrow {
<?php font-size:<?php 14px;
<?php }
<?php .carousel-nav.prev {
<?php left:<?php 0.5rem;
<?php }
<?php .carousel-nav.next {
<?php right:<?php 0.5rem;
<?php }
<?php .carousel-indicators {
<?php bottom:<?php 0.5rem;
<?php gap:<?php 0.4rem;
<?php }
<?php .indicator {
<?php width:<?php 6px;
<?php height:<?php 6px;
<?php }
}
</style>

<script>
document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php let currentSlide =<?php 0;
<?php const slides =<?php document.querySelectorAll('.hero-slide');
<?php const indicators =<?php document.querySelectorAll('.indicator');
<?php const totalSlides =<?php slides.length;
<?php let isTransitioning =<?php false;
<?php let autoPlayInterval;

<?php //<?php Inicializar apenas se houver slides if (totalSlides ><?php 0)<?php {
<?php initializeCarousel();
<?php }

<?php function initializeCarousel()<?php {
<?php //<?php Precarregar imagens dos banners preloadImages();
<?php //<?php Iniciar autoplay se houver mais de um banner if (totalSlides ><?php 1)<?php {
<?php startAutoPlay();
<?php //<?php Controles de mouse para pausar autoplay const carouselContainer =<?php document.querySelector('.carousel-container');
<?php if (carouselContainer)<?php {
<?php carouselContainer.addEventListener('mouseenter',<?php pauseAutoPlay);
<?php carouselContainer.addEventListener('mouseleave',<?php startAutoPlay);
<?php }
<?php }
<?php //<?php Configurar controles de teclado setupKeyboardControls();
<?php //<?php Configurar controles touch para mobile setupTouchControls();
<?php }

<?php function preloadImages()<?php {
<?php slides.forEach((slide)<?php =><?php {
<?php if (slide.classList.contains('banner-slide'))<?php {
<?php const bgImage =<?php slide.style.backgroundImage;
<?php if (bgImage)<?php {
<?php const imageUrl =<?php bgImage.slice(4,<?php -1).replace(/["']/g,<?php "");
<?php const img =<?php new Image();
<?php slide.classList.add('loading');
<?php img.onload =<?php function()<?php {
<?php slide.classList.remove('loading');
<?php //<?php Detectar melhor fit baseado na proporção da imagem const containerWidth =<?php slide.offsetWidth;
<?php const containerHeight =<?php slide.offsetHeight;
<?php const containerRatio =<?php containerWidth /<?php containerHeight;
<?php const imageRatio =<?php img.width /<?php img.height;
<?php //<?php Se a imagem for muito mais larga que o container,<?php usar contain //<?php Se for similar,<?php usar cover para preencher if (Math.abs(imageRatio -<?php containerRatio)<?php ><?php 0.5)<?php {
<?php slide.style.backgroundSize =<?php 'contain';
<?php }<?php else {
<?php slide.style.backgroundSize =<?php 'cover';
<?php }
<?php };
<?php img.onerror =<?php function()<?php {
<?php slide.classList.remove('loading');
<?php //<?php Fallback em caso de erro };
<?php img.src =<?php imageUrl;
<?php }
<?php }
<?php });
<?php }

<?php function showSlide(index,<?php direction =<?php 'right')<?php {
<?php if (isTransitioning ||<?php index ===<?php currentSlide ||<?php index >=<?php totalSlides)<?php return;
<?php isTransitioning =<?php true;
<?php //<?php Remove classe ativa do slide atual slides[currentSlide].classList.remove('active');
<?php //<?php Adiciona classe ativa ao novo slide slides[index].classList.add('active');
<?php //<?php Adiciona animação apenas em desktop if (window.innerWidth ><?php 768)<?php {
<?php slides[index].classList.add(direction ===<?php 'right'<?php ?<?php 'slide-right'<?php :<?php 'slide-left');
<?php setTimeout(()<?php =><?php {
<?php slides[index].classList.remove('slide-right',<?php 'slide-left');
<?php },<?php 600);
<?php }
<?php //<?php Atualizar indicadores updateIndicators(index);
<?php currentSlide =<?php index;
<?php setTimeout(()<?php =><?php {
<?php isTransitioning =<?php false;
<?php },<?php 300);
<?php }

<?php function updateIndicators(activeIndex)<?php {
<?php indicators.forEach((indicator,<?php i)<?php =><?php {
<?php indicator.classList.toggle('active',<?php i ===<?php activeIndex);
<?php });
<?php }

<?php //<?php FUNÇÕES GLOBAIS PARA OS BOTÕES -<?php CORRIGIDAS window.changeSlide =<?php function(direction)<?php {
<?php if (totalSlides <=<?php 1)<?php return;
<?php let newSlide =<?php currentSlide +<?php direction;
<?php if (newSlide >=<?php totalSlides)<?php {
<?php newSlide =<?php 0;
<?php }<?php else if (newSlide <?php 0)<?php {
<?php newSlide =<?php totalSlides -<?php 1;
<?php }
<?php const slideDirection =<?php direction ><?php 0 ?<?php 'right'<?php :<?php 'left';
<?php showSlide(newSlide,<?php slideDirection);
<?php };

<?php window.goToSlide =<?php function(index)<?php {
<?php if (index <?php 0 ||<?php index >=<?php totalSlides ||<?php index ===<?php currentSlide)<?php return;
<?php const direction =<?php index ><?php currentSlide ?<?php 'right'<?php :<?php 'left';
<?php showSlide(index,<?php direction);
<?php };

<?php function startAutoPlay()<?php {
<?php if (totalSlides ><?php 1)<?php {
<?php clearInterval(autoPlayInterval);
<?php autoPlayInterval =<?php setInterval(()<?php =><?php {
<?php changeSlide(1);
<?php },<?php 5000);
<?php }
<?php }

<?php function pauseAutoPlay()<?php {
<?php if (autoPlayInterval)<?php {
<?php clearInterval(autoPlayInterval);
<?php }
<?php }

<?php function setupKeyboardControls()<?php {
<?php document.addEventListener('keydown',<?php function(e)<?php {
<?php if (totalSlides ><?php 1)<?php {
<?php if (e.key ===<?php 'ArrowLeft')<?php {
<?php e.preventDefault();
<?php changeSlide(-1);
<?php }<?php else if (e.key ===<?php 'ArrowRight')<?php {
<?php e.preventDefault();
<?php changeSlide(1);
<?php }
<?php }
<?php });
<?php }

<?php function setupTouchControls()<?php {
<?php let touchStartX =<?php 0;
<?php let touchEndX =<?php 0;
<?php const swipeThreshold =<?php 50;

<?php const carouselContainer =<?php document.querySelector('.carousel-container');
<?php if (!carouselContainer ||<?php totalSlides <=<?php 1)<?php return;

<?php carouselContainer.addEventListener('touchstart',<?php function(e)<?php {
<?php touchStartX =<?php e.changedTouches[0].screenX;
<?php },<?php {<?php passive:<?php true });

<?php carouselContainer.addEventListener('touchend',<?php function(e)<?php {
<?php touchEndX =<?php e.changedTouches[0].screenX;
<?php handleSwipe();
<?php },<?php {<?php passive:<?php true });

<?php function handleSwipe()<?php {
<?php const diff =<?php touchStartX -<?php touchEndX;

<?php if (Math.abs(diff)<?php ><?php swipeThreshold)<?php {
<?php if (diff ><?php 0)<?php {
<?php //<?php Swipe left -<?php próximo slide changeSlide(1);
<?php }<?php else {
<?php //<?php Swipe right -<?php slide anterior changeSlide(-1);
<?php }
<?php }
<?php }
<?php }

<?php //<?php Pausar autoplay quando a aba não está<?php visível document.addEventListener('visibilitychange',<?php function()<?php {
<?php if (document.hidden)<?php {
<?php pauseAutoPlay();
<?php }<?php else if (totalSlides ><?php 1)<?php {
<?php startAutoPlay();
<?php }
<?php });
});
</script>