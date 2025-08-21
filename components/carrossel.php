<?php
//<?php Buscar<?php banners<?php ativos<?php do<?php banco<?php de<?php dados
$query<?php =<?php "SELECT<?php *<?php FROM<?php banners<?php WHERE<?php ativo<?php =<?php 1<?php ORDER<?php BY<?php ordem<?php ASC";
$banners<?php =<?php $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<section<?php class="hero-section">
<?php <div<?php class="hero-container">
<?php <div<?php class="hero-banner<?php carousel-container">
<?php <?php<?php if<?php (!empty($banners)):<?php ?>
<?php <!--<?php Container<?php dos<?php slides<?php -->
<?php <div<?php class="carousel-wrapper">
<?php <?php<?php foreach<?php ($banners<?php as<?php $index<?php =><?php $banner):<?php ?>
<?php <div<?php class="hero-slide<?php banner-slide<?php <?php=<?php $index<?php ===<?php 0<?php ?<?php 'active'<?php :<?php ''<?php ?>"<?php 
<?php data-slide="<?php=<?php $index<?php ?>"<?php 
<?php style="background-image:<?php url('<?php=<?php htmlspecialchars($banner['banner_img'])<?php ?>');">
<?php <div<?php class="banner-overlay"></div>
<?php </div>
<?php <?php<?php endforeach;<?php ?>
<?php </div>
<?php 
<?php <?php<?php if<?php (count($banners)<?php ><?php 1):<?php ?>
<?php <!--<?php NAVEGAÇÃO<?php DO<?php CARROSSEL<?php -<?php ÍCONES<?php CORRIGIDOS<?php -->
<?php <button<?php class="carousel-nav<?php prev"<?php onclick="changeSlide(-1)"<?php aria-label="Banner<?php anterior">
<?php <span<?php class="nav-arrow">‹</span>
<?php </button>
<?php <button<?php class="carousel-nav<?php next"<?php onclick="changeSlide(1)"<?php aria-label="Próximo<?php banner">
<?php <span<?php class="nav-arrow">›</span>
<?php </button>
<?php 
<?php <!--<?php Indicadores<?php -->
<?php <div<?php class="carousel-indicators">
<?php <?php<?php foreach<?php ($banners<?php as<?php $index<?php =><?php $banner):<?php ?>
<?php <button<?php class="indicator<?php <?php=<?php $index<?php ===<?php 0<?php ?<?php 'active'<?php :<?php ''<?php ?>"<?php 
<?php onclick="goToSlide(<?php=<?php $index<?php ?>)"
<?php aria-label="Ir<?php para<?php banner<?php <?php=<?php $index<?php +<?php 1<?php ?>"></button>
<?php <?php<?php endforeach;<?php ?>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php <?php<?php else:<?php ?>
<?php <!--<?php Fallback<?php caso<?php não<?php tenha<?php banners<?php -->
<?php <div<?php class="carousel-wrapper">
<?php <div<?php class="hero-slide<?php fallback-slide<?php active">
<?php <div<?php class="hero-content">
<?php <h1<?php class="hero-title">PRÊMIOS<?php DE<?php ATÉ</h1>
<?php <h2<?php class="hero-subtitle">15<?php MIL<?php REAIS</h2>
<?php <a<?php href="#games"<?php class="hero-cta">RASPE<?php AGORA!</a>
<?php </div>
<?php <div<?php class="hero-visuals">
<?php <div<?php class="money-stack"></div>
<?php </div>
<?php </div>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </div>
<?php </div>
</section>

<style>
/*<?php ==========================================
<?php BANNER<?php CAROUSEL<?php STYLES<?php -<?php NAVEGAÇÃO<?php CORRIGIDA
<?php ==========================================<?php */

/*<?php Container<?php principal<?php do<?php carrossel<?php */
.carousel-container<?php {
<?php position:<?php relative;
<?php width:<?php 100%;
<?php max-width:<?php 100%;
<?php height:<?php 500px;
<?php border-radius:<?php 24px;
<?php overflow:<?php hidden;
<?php margin:<?php 0<?php auto;
}

/*<?php Wrapper<?php dos<?php slides<?php */
.carousel-wrapper<?php {
<?php position:<?php relative;
<?php width:<?php 100%;
<?php height:<?php 100%;
}

/*<?php Slides<?php dos<?php banners<?php */
.hero-slide<?php {
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
<?php transition:<?php all<?php 0.6s<?php cubic-bezier(0.4,<?php 0,<?php 0.2,<?php 1);
<?php z-index:<?php 1;
}

/*<?php Slide<?php ativo<?php */
.hero-slide.active<?php {
<?php opacity:<?php 1;
<?php visibility:<?php visible;
<?php z-index:<?php 2;
}

/*<?php Slide<?php de<?php fallback<?php */
.hero-slide.fallback-slide<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php padding:<?php 3rem<?php 4rem;
}

/*<?php Conteúdo<?php do<?php fallback<?php */
.hero-content<?php {
<?php color:<?php white;
<?php z-index:<?php 3;
}

.hero-title<?php {
<?php font-size:<?php 2.5rem;
<?php font-weight:<?php bold;
<?php margin-bottom:<?php 0.5rem;
}

.hero-subtitle<?php {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 900;
<?php color:<?php #22c55e;
<?php margin-bottom:<?php 1.5rem;
}

.hero-cta<?php {
<?php display:<?php inline-block;
<?php background:<?php #22c55e;
<?php color:<?php white;
<?php padding:<?php 1rem<?php 2rem;
<?php border-radius:<?php 8px;
<?php text-decoration:<?php none;
<?php font-weight:<?php bold;
<?php transition:<?php transform<?php 0.3s<?php ease;
}

.hero-cta:hover<?php {
<?php transform:<?php translateY(-2px);
}

/*<?php Slides<?php com<?php banners<?php */
.hero-slide.banner-slide<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
}

/*<?php Overlay<?php para<?php banners<?php */
.banner-overlay<?php {
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php bottom:<?php 0;
<?php pointer-events:<?php none;
<?php z-index:<?php 1;
}

/*<?php NAVEGAÇÃO<?php DO<?php CARROSSEL<?php -<?php ÍCONES<?php CORRIGIDOS<?php */
.carousel-nav<?php {
<?php position:<?php absolute;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php backdrop-filter:<?php blur(10px);
<?php color:<?php white;
<?php border:<?php 2px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php width:<?php 50px;
<?php height:<?php 50px;
<?php border-radius:<?php 50%;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php z-index:<?php 10;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.2rem;
}

.carousel-nav<?php .nav-arrow<?php {
<?php font-size:<?php 24px;
<?php font-weight:<?php bold;
<?php line-height:<?php 1;
<?php user-select:<?php none;
<?php pointer-events:<?php none;
}

.carousel-nav:hover<?php {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.8);
<?php border-color:<?php #22c55e;
<?php transform:<?php translateY(-50%)<?php scale(1.1);
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
}

.carousel-nav.prev<?php {
<?php left:<?php 2rem;
}

.carousel-nav.next<?php {
<?php right:<?php 2rem;
}

/*<?php Indicadores<?php */
.carousel-indicators<?php {
<?php position:<?php absolute;
<?php bottom:<?php 2rem;
<?php left:<?php 50%;
<?php transform:<?php translateX(-50%);
<?php display:<?php flex;
<?php gap:<?php 0.75rem;
<?php z-index:<?php 10;
}

.indicator<?php {
<?php width:<?php 12px;
<?php height:<?php 12px;
<?php border-radius:<?php 50%;
<?php border:<?php 2px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.7);
<?php background:<?php transparent;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php backdrop-filter:<?php blur(5px);
}

.indicator:hover<?php {
<?php border-color:<?php white;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.3);
<?php transform:<?php scale(1.2);
}

.indicator.active<?php {
<?php background:<?php #22c55e;
<?php border-color:<?php #22c55e;
<?php box-shadow:<?php 0<?php 0<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.6);
}

/*<?php Loading<?php state<?php */
.banner-slide::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 50%;
<?php left:<?php 50%;
<?php transform:<?php translate(-50%,<?php -50%);
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php border:<?php 3px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php border-top-color:<?php #22c55e;
<?php border-radius:<?php 50%;
<?php animation:<?php spin<?php 1s<?php linear<?php infinite;
<?php opacity:<?php 0;
<?php transition:<?php opacity<?php 0.3s<?php ease;
<?php z-index:<?php 5;
}

.banner-slide.loading::before<?php {
<?php opacity:<?php 1;
}

@keyframes<?php spin<?php {
<?php to<?php {<?php transform:<?php translate(-50%,<?php -50%)<?php rotate(360deg);<?php }
}

/*<?php Animações<?php de<?php transição<?php */
@keyframes<?php slideInRight<?php {
<?php from<?php {
<?php opacity:<?php 0;
<?php transform:<?php translateX(50px);
<?php }
<?php to<?php {
<?php opacity:<?php 1;
<?php transform:<?php translateX(0);
<?php }
}

@keyframes<?php slideInLeft<?php {
<?php from<?php {
<?php opacity:<?php 0;
<?php transform:<?php translateX(-50px);
<?php }
<?php to<?php {
<?php opacity:<?php 1;
<?php transform:<?php translateX(0);
<?php }
}

.hero-slide.slide-right<?php {
<?php animation:<?php slideInRight<?php 0.6s<?php ease-out;
}

.hero-slide.slide-left<?php {
<?php animation:<?php slideInLeft<?php 0.6s<?php ease-out;
}

/*<?php ==========================================
<?php RESPONSIVO
<?php ==========================================<?php */

/*<?php Desktop<?php grande<?php */
@media<?php (min-width:<?php 1440px)<?php {
<?php .carousel-container<?php {
<?php height:<?php 600px;
<?php max-width:<?php 95%;
<?php }
}

/*<?php Desktop<?php médio<?php */
@media<?php (min-width:<?php 1025px)<?php and<?php (max-width:<?php 1439px)<?php {
<?php .carousel-container<?php {
<?php height:<?php 500px;
<?php max-width:<?php 90%;
<?php }
}

/*<?php Tablet<?php */
@media<?php (max-width:<?php 1024px)<?php {
<?php .carousel-container<?php {
<?php height:<?php 350px;
<?php border-radius:<?php 20px;
<?php max-width:<?php 95%;
<?php }
<?php 
<?php .hero-slide.fallback-slide<?php {
<?php padding:<?php 2rem<?php 3rem;
<?php }
<?php 
<?php .hero-title<?php {
<?php font-size:<?php 2rem;
<?php }
<?php 
<?php .hero-subtitle<?php {
<?php font-size:<?php 2.5rem;
<?php }
}

/*<?php Mobile<?php */
@media<?php (max-width:<?php 768px)<?php {
<?php .hero-section<?php {
<?php padding:<?php 1rem<?php 0;
<?php }
<?php 
<?php .hero-container<?php {
<?php padding:<?php 0<?php 1rem;
<?php }
<?php 
<?php .carousel-container<?php {
<?php height:<?php 220px;
<?php border-radius:<?php 16px;
<?php max-width:<?php 100%;
<?php margin:<?php 0;
<?php }
<?php 
<?php .hero-slide<?php {
<?php background-size:<?php contain;
<?php background-position:<?php center;
<?php }
<?php 
<?php .hero-slide.fallback-slide<?php {
<?php flex-direction:<?php column;
<?php text-align:<?php center;
<?php padding:<?php 1.5rem<?php 1rem;
<?php gap:<?php 1rem;
<?php background-size:<?php cover;
<?php }
<?php 
<?php .hero-title<?php {
<?php font-size:<?php 1.5rem;
<?php margin-bottom:<?php 0.25rem;
<?php }
<?php 
<?php .hero-subtitle<?php {
<?php font-size:<?php 2rem;
<?php margin-bottom:<?php 1rem;
<?php }
<?php 
<?php .hero-cta<?php {
<?php padding:<?php 0.75rem<?php 1.5rem;
<?php font-size:<?php 0.9rem;
<?php }
<?php 
<?php .carousel-nav<?php {
<?php width:<?php 40px;
<?php height:<?php 40px;
<?php font-size:<?php 1rem;
<?php }
<?php 
<?php .carousel-nav<?php .nav-arrow<?php {
<?php font-size:<?php 18px;
<?php }
<?php 
<?php .carousel-nav.prev<?php {
<?php left:<?php 1rem;
<?php }
<?php 
<?php .carousel-nav.next<?php {
<?php right:<?php 1rem;
<?php }
<?php 
<?php .carousel-indicators<?php {
<?php bottom:<?php 1rem;
<?php gap:<?php 0.5rem;
<?php }
<?php 
<?php .indicator<?php {
<?php width:<?php 8px;
<?php height:<?php 8px;
<?php }
<?php 
<?php /*<?php Remover<?php animações<?php no<?php mobile<?php */
<?php .hero-slide.slide-right,
<?php .hero-slide.slide-left<?php {
<?php animation:<?php none;
<?php }
}

/*<?php Mobile<?php pequeno<?php */
@media<?php (max-width:<?php 480px)<?php {
<?php .hero-section<?php {
<?php padding:<?php 0.5rem<?php 0;
<?php }
<?php 
<?php .hero-container<?php {
<?php padding:<?php 0<?php 0.5rem;
<?php }
<?php 
<?php .carousel-container<?php {
<?php height:<?php 200px;
<?php border-radius:<?php 12px;
<?php max-width:<?php 100%;
<?php margin:<?php 0;
<?php }
<?php 
<?php .hero-slide<?php {
<?php background-size:<?php contain;
<?php background-position:<?php center;
<?php }
<?php 
<?php .hero-slide.fallback-slide<?php {
<?php padding:<?php 1rem;
<?php background-size:<?php cover;
<?php }
<?php 
<?php .hero-title<?php {
<?php font-size:<?php 1.25rem;
<?php }
<?php 
<?php .hero-subtitle<?php {
<?php font-size:<?php 1.75rem;
<?php }
<?php 
<?php .hero-cta<?php {
<?php padding:<?php 0.5rem<?php 1rem;
<?php font-size:<?php 0.8rem;
<?php }
<?php 
<?php .carousel-nav<?php {
<?php width:<?php 32px;
<?php height:<?php 32px;
<?php font-size:<?php 0.8rem;
<?php }
<?php 
<?php .carousel-nav<?php .nav-arrow<?php {
<?php font-size:<?php 14px;
<?php }
<?php 
<?php .carousel-nav.prev<?php {
<?php left:<?php 0.5rem;
<?php }
<?php 
<?php .carousel-nav.next<?php {
<?php right:<?php 0.5rem;
<?php }
<?php 
<?php .carousel-indicators<?php {
<?php bottom:<?php 0.5rem;
<?php gap:<?php 0.4rem;
<?php }
<?php 
<?php .indicator<?php {
<?php width:<?php 6px;
<?php height:<?php 6px;
<?php }
}
</style>

<script>
document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php let<?php currentSlide<?php =<?php 0;
<?php const<?php slides<?php =<?php document.querySelectorAll('.hero-slide');
<?php const<?php indicators<?php =<?php document.querySelectorAll('.indicator');
<?php const<?php totalSlides<?php =<?php slides.length;
<?php let<?php isTransitioning<?php =<?php false;
<?php let<?php autoPlayInterval;

<?php //<?php Inicializar<?php apenas<?php se<?php houver<?php slides
<?php if<?php (totalSlides<?php ><?php 0)<?php {
<?php initializeCarousel();
<?php }

<?php function<?php initializeCarousel()<?php {
<?php //<?php Precarregar<?php imagens<?php dos<?php banners
<?php preloadImages();
<?php 
<?php //<?php Iniciar<?php autoplay<?php se<?php houver<?php mais<?php de<?php um<?php banner
<?php if<?php (totalSlides<?php ><?php 1)<?php {
<?php startAutoPlay();
<?php 
<?php //<?php Controles<?php de<?php mouse<?php para<?php pausar<?php autoplay
<?php const<?php carouselContainer<?php =<?php document.querySelector('.carousel-container');
<?php if<?php (carouselContainer)<?php {
<?php carouselContainer.addEventListener('mouseenter',<?php pauseAutoPlay);
<?php carouselContainer.addEventListener('mouseleave',<?php startAutoPlay);
<?php }
<?php }
<?php 
<?php //<?php Configurar<?php controles<?php de<?php teclado
<?php setupKeyboardControls();
<?php 
<?php //<?php Configurar<?php controles<?php touch<?php para<?php mobile
<?php setupTouchControls();
<?php }

<?php function<?php preloadImages()<?php {
<?php slides.forEach((slide)<?php =><?php {
<?php if<?php (slide.classList.contains('banner-slide'))<?php {
<?php const<?php bgImage<?php =<?php slide.style.backgroundImage;
<?php if<?php (bgImage)<?php {
<?php const<?php imageUrl<?php =<?php bgImage.slice(4,<?php -1).replace(/["']/g,<?php "");
<?php const<?php img<?php =<?php new<?php Image();
<?php 
<?php slide.classList.add('loading');
<?php 
<?php img.onload<?php =<?php function()<?php {
<?php slide.classList.remove('loading');
<?php 
<?php //<?php Detectar<?php melhor<?php fit<?php baseado<?php na<?php proporção<?php da<?php imagem
<?php const<?php containerWidth<?php =<?php slide.offsetWidth;
<?php const<?php containerHeight<?php =<?php slide.offsetHeight;
<?php const<?php containerRatio<?php =<?php containerWidth<?php /<?php containerHeight;
<?php const<?php imageRatio<?php =<?php img.width<?php /<?php img.height;
<?php 
<?php //<?php Se<?php a<?php imagem<?php for<?php muito<?php mais<?php larga<?php que<?php o<?php container,<?php usar<?php contain
<?php //<?php Se<?php for<?php similar,<?php usar<?php cover<?php para<?php preencher
<?php if<?php (Math.abs(imageRatio<?php -<?php containerRatio)<?php ><?php 0.5)<?php {
<?php slide.style.backgroundSize<?php =<?php 'contain';
<?php }<?php else<?php {
<?php slide.style.backgroundSize<?php =<?php 'cover';
<?php }
<?php };
<?php 
<?php img.onerror<?php =<?php function()<?php {
<?php slide.classList.remove('loading');
<?php //<?php Fallback<?php em<?php caso<?php de<?php erro
<?php };
<?php 
<?php img.src<?php =<?php imageUrl;
<?php }
<?php }
<?php });
<?php }

<?php function<?php showSlide(index,<?php direction<?php =<?php 'right')<?php {
<?php if<?php (isTransitioning<?php ||<?php index<?php ===<?php currentSlide<?php ||<?php index<?php >=<?php totalSlides)<?php return;
<?php 
<?php isTransitioning<?php =<?php true;
<?php 
<?php //<?php Remove<?php classe<?php ativa<?php do<?php slide<?php atual
<?php slides[currentSlide].classList.remove('active');
<?php 
<?php //<?php Adiciona<?php classe<?php ativa<?php ao<?php novo<?php slide
<?php slides[index].classList.add('active');
<?php 
<?php //<?php Adiciona<?php animação<?php apenas<?php em<?php desktop
<?php if<?php (window.innerWidth<?php ><?php 768)<?php {
<?php slides[index].classList.add(direction<?php ===<?php 'right'<?php ?<?php 'slide-right'<?php :<?php 'slide-left');
<?php 
<?php setTimeout(()<?php =><?php {
<?php slides[index].classList.remove('slide-right',<?php 'slide-left');
<?php },<?php 600);
<?php }
<?php 
<?php //<?php Atualizar<?php indicadores
<?php updateIndicators(index);
<?php 
<?php currentSlide<?php =<?php index;
<?php 
<?php setTimeout(()<?php =><?php {
<?php isTransitioning<?php =<?php false;
<?php },<?php 300);
<?php }

<?php function<?php updateIndicators(activeIndex)<?php {
<?php indicators.forEach((indicator,<?php i)<?php =><?php {
<?php indicator.classList.toggle('active',<?php i<?php ===<?php activeIndex);
<?php });
<?php }

<?php //<?php FUNÇÕES<?php GLOBAIS<?php PARA<?php OS<?php BOTÕES<?php -<?php CORRIGIDAS
<?php window.changeSlide<?php =<?php function(direction)<?php {
<?php if<?php (totalSlides<?php <=<?php 1)<?php return;
<?php 
<?php let<?php newSlide<?php =<?php currentSlide<?php +<?php direction;
<?php 
<?php if<?php (newSlide<?php >=<?php totalSlides)<?php {
<?php newSlide<?php =<?php 0;
<?php }<?php else<?php if<?php (newSlide<?php <?php 0)<?php {
<?php newSlide<?php =<?php totalSlides<?php -<?php 1;
<?php }
<?php 
<?php const<?php slideDirection<?php =<?php direction<?php ><?php 0<?php ?<?php 'right'<?php :<?php 'left';
<?php showSlide(newSlide,<?php slideDirection);
<?php };

<?php window.goToSlide<?php =<?php function(index)<?php {
<?php if<?php (index<?php <?php 0<?php ||<?php index<?php >=<?php totalSlides<?php ||<?php index<?php ===<?php currentSlide)<?php return;
<?php 
<?php const<?php direction<?php =<?php index<?php ><?php currentSlide<?php ?<?php 'right'<?php :<?php 'left';
<?php showSlide(index,<?php direction);
<?php };

<?php function<?php startAutoPlay()<?php {
<?php if<?php (totalSlides<?php ><?php 1)<?php {
<?php clearInterval(autoPlayInterval);
<?php autoPlayInterval<?php =<?php setInterval(()<?php =><?php {
<?php changeSlide(1);
<?php },<?php 5000);
<?php }
<?php }

<?php function<?php pauseAutoPlay()<?php {
<?php if<?php (autoPlayInterval)<?php {
<?php clearInterval(autoPlayInterval);
<?php }
<?php }

<?php function<?php setupKeyboardControls()<?php {
<?php document.addEventListener('keydown',<?php function(e)<?php {
<?php if<?php (totalSlides<?php ><?php 1)<?php {
<?php if<?php (e.key<?php ===<?php 'ArrowLeft')<?php {
<?php e.preventDefault();
<?php changeSlide(-1);
<?php }<?php else<?php if<?php (e.key<?php ===<?php 'ArrowRight')<?php {
<?php e.preventDefault();
<?php changeSlide(1);
<?php }
<?php }
<?php });
<?php }

<?php function<?php setupTouchControls()<?php {
<?php let<?php touchStartX<?php =<?php 0;
<?php let<?php touchEndX<?php =<?php 0;
<?php const<?php swipeThreshold<?php =<?php 50;

<?php const<?php carouselContainer<?php =<?php document.querySelector('.carousel-container');
<?php if<?php (!carouselContainer<?php ||<?php totalSlides<?php <=<?php 1)<?php return;

<?php carouselContainer.addEventListener('touchstart',<?php function(e)<?php {
<?php touchStartX<?php =<?php e.changedTouches[0].screenX;
<?php },<?php {<?php passive:<?php true<?php });

<?php carouselContainer.addEventListener('touchend',<?php function(e)<?php {
<?php touchEndX<?php =<?php e.changedTouches[0].screenX;
<?php handleSwipe();
<?php },<?php {<?php passive:<?php true<?php });

<?php function<?php handleSwipe()<?php {
<?php const<?php diff<?php =<?php touchStartX<?php -<?php touchEndX;

<?php if<?php (Math.abs(diff)<?php ><?php swipeThreshold)<?php {
<?php if<?php (diff<?php ><?php 0)<?php {
<?php //<?php Swipe<?php left<?php -<?php próximo<?php slide
<?php changeSlide(1);
<?php }<?php else<?php {
<?php //<?php Swipe<?php right<?php -<?php slide<?php anterior
<?php changeSlide(-1);
<?php }
<?php }
<?php }
<?php }

<?php //<?php Pausar<?php autoplay<?php quando<?php a<?php aba<?php não<?php está<?php visível
<?php document.addEventListener('visibilitychange',<?php function()<?php {
<?php if<?php (document.hidden)<?php {
<?php pauseAutoPlay();
<?php }<?php else<?php if<?php (totalSlides<?php ><?php 1)<?php {
<?php startAutoPlay();
<?php }
<?php });
});
</script>