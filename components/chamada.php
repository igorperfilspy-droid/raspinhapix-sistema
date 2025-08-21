<?php
//<?php Buscar<?php raspadinhas<?php do<?php banco<?php de<?php dados<?php ordenadas<?php por<?php valor<?php (maior<?php para<?php menor)
try<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php raspadinhas<?php ORDER<?php BY<?php valor<?php DESC,<?php created_at<?php DESC<?php LIMIT<?php 8");
<?php $stmt->execute();
<?php $raspadinhas<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
}<?php catch<?php (PDOException<?php $e)<?php {
<?php $raspadinhas<?php =<?php [];
}
?>

<section<?php class="raspadinhas-showcase">
<?php <div<?php class="showcase-container">
<?php <div<?php class="showcase-header">
<?php <h2<?php class="showcase-title">Raspadinhas</h2>
<?php <div<?php class="showcase-filters">
<?php <button<?php class="filter-btn<?php active"<?php data-filter="todos">Todos</button>
<?php <button<?php class="filter-btn"<?php data-filter="dinheiro">Dinheiro</button>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="raspadinhas-grid">
<?php <?php<?php if<?php (!empty($raspadinhas)):<?php ?>
<?php <?php<?php foreach<?php ($raspadinhas<?php as<?php $raspinha):<?php ?>
<?php <div<?php class="raspinha-card"<?php data-category="dinheiro">
<?php <div<?php class="card-banner">
<?php <?php<?php if<?php ($raspinha['banner']<?php &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $raspinha['banner'])):<?php ?>
<?php <img<?php src="<?php=<?php htmlspecialchars($raspinha['banner'])<?php ?>"<?php alt="<?php=<?php htmlspecialchars($raspinha['nome'])<?php ?>"<?php class="banner-image">
<?php <?php<?php else:<?php ?>
<?php <div<?php class="banner-placeholder">
<?php <i<?php class="bi<?php bi-grid-3x3-gap-fill"></i>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php 
<?php <!--<?php Category<?php Badge<?php -->
<?php <div<?php class="category-badge<?php <?php=<?php rand(0,<?php 1)<?php ?<?php 'dinheiro'<?php :<?php 'produtos'<?php ?>">
<?php <?php=<?php rand(0,<?php 1)<?php ?<?php 'Dinheiro'<?php :<?php 'Produtos'<?php ?>
<?php </div>
<?php 
<?php <!--<?php Play<?php Button<?php Overlay<?php -->
<?php <div<?php class="play-overlay">
<?php <div<?php class="play-button">
<?php <i<?php class="bi<?php bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="card-content">
<?php <h3<?php class="card-title"><?php=<?php htmlspecialchars($raspinha['nome'])<?php ?></h3>
<?php <p<?php class="card-description"><?php=<?php htmlspecialchars($raspinha['descricao']<?php ?:<?php 'Raspe<?php e<?php ganhe<?php prêmios<?php incríveis!')<?php ?></p>
<?php 
<?php <div<?php class="card-footer">
<?php <div<?php class="card-price">
<?php <span<?php class="price-label">R$</span>
<?php <span<?php class="price-value"><?php=<?php number_format($raspinha['valor'],<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php 
<?php <a<?php href="/cartelas/"<?php class="play-btn">
<?php Jogar
<?php </a>
<?php </div>
<?php </div>
<?php </div>
<?php <?php<?php endforeach;<?php ?>
<?php <?php<?php else:<?php ?>
<?php <!--<?php Fallback<?php cards<?php se<?php não<?php houver<?php raspadinhas<?php -<?php também<?php ordenados<?php por<?php valor<?php decrescente<?php -->
<?php <div<?php class="raspinha-card"<?php data-category="produtos">
<?php <div<?php class="card-banner">
<?php <div<?php class="banner-placeholder<?php vehicle-theme">
<?php <i<?php class="bi<?php bi-car-front"></i>
<?php </div>
<?php <div<?php class="category-badge<?php produtos">Produtos</div>
<?php <div<?php class="play-overlay">
<?php <div<?php class="play-button">
<?php <i<?php class="bi<?php bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div<?php class="card-content">
<?php <h3<?php class="card-title">Saga<?php Motorizada</h3>
<?php <p<?php class="card-description">Ganhe<?php até<?php R$<?php 1.000,00</p>
<?php <div<?php class="card-footer">
<?php <div<?php class="card-price">
<?php <span<?php class="price-label">R$</span>
<?php <span<?php class="price-value">15,00</span>
<?php </div>
<?php <a<?php href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="raspinha-card"<?php data-category="produtos">
<?php <div<?php class="card-banner">
<?php <div<?php class="banner-placeholder<?php fashion-theme">
<?php <i<?php class="bi<?php bi-bag-heart"></i>
<?php </div>
<?php <div<?php class="category-badge<?php produtos">Produtos</div>
<?php <div<?php class="play-overlay">
<?php <div<?php class="play-button">
<?php <i<?php class="bi<?php bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div<?php class="card-content">
<?php <h3<?php class="card-title">Mimo<?php caro!</h3>
<?php <p<?php class="card-description">Ganhe<?php até<?php R$<?php 20,00</p>
<?php <div<?php class="card-footer">
<?php <div<?php class="card-price">
<?php <span<?php class="price-label">R$</span>
<?php <span<?php class="price-value">15,00</span>
<?php </div>
<?php <a<?php href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="raspinha-card"<?php data-category="produtos">
<?php <div<?php class="card-banner">
<?php <div<?php class="banner-placeholder<?php tech-theme">
<?php <i<?php class="bi<?php bi-laptop"></i>
<?php </div>
<?php <div<?php class="category-badge<?php produtos">Produtos</div>
<?php <div<?php class="play-overlay">
<?php <div<?php class="play-button">
<?php <i<?php class="bi<?php bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div<?php class="card-content">
<?php <h3<?php class="card-title">Sonho<?php de<?php Consumo</h3>
<?php <p<?php class="card-description">Eletro,<?php eletrônicos<?php e<?php comp...</p>
<?php <div<?php class="card-footer">
<?php <div<?php class="card-price">
<?php <span<?php class="price-label">R$</span>
<?php <span<?php class="price-value">10,00</span>
<?php </div>
<?php <a<?php href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php 
<?php <div<?php class="raspinha-card"<?php data-category="dinheiro">
<?php <div<?php class="card-banner">
<?php <div<?php class="banner-placeholder<?php money-theme">
<?php <i<?php class="bi<?php bi-cash-stack"></i>
<?php </div>
<?php <div<?php class="category-badge<?php dinheiro">Dinheiro</div>
<?php <div<?php class="play-overlay">
<?php <div<?php class="play-button">
<?php <i<?php class="bi<?php bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div<?php class="card-content">
<?php <h3<?php class="card-title">PIX<?php na<?php Conta</h3>
<?php <p<?php class="card-description">Ganhe<?php até<?php R$<?php 2.000,00</p>
<?php <div<?php class="card-footer">
<?php <div<?php class="card-price">
<?php <span<?php class="price-label">R$</span>
<?php <span<?php class="price-value">5,00</span>
<?php </div>
<?php <a<?php href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </div>
<?php 
<?php <?php<?php if<?php (count($raspadinhas)<?php ><?php 4):<?php ?>
<?php <div<?php class="showcase-footer">
<?php <a<?php href="/cartelas"<?php class="view-all-btn">
<?php Ver<?php todas<?php as<?php raspadinhas
<?php <i<?php class="bi<?php bi-arrow-right"></i>
<?php </a>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </div>
</section>

<style>
.raspadinhas-showcase<?php {
<?php padding:<?php 4rem<?php 0;
<?php position:<?php relative;
<?php overflow:<?php hidden;
}

.raspadinhas-showcase::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php bottom:<?php 0;
<?php background:<?php url('data:image/svg+xml,<svg<?php width="60"<?php height="60"<?php viewBox="0<?php 0<?php 60<?php 60"<?php xmlns="http://www.w3.org/2000/svg"><g<?php fill="none"<?php fill-rule="evenodd"><g<?php fill="%2322c55e"<?php fill-opacity="0.03"><circle<?php cx="30"<?php cy="30"<?php r="2"/></g></svg>')<?php repeat;
<?php pointer-events:<?php none;
}

.showcase-container<?php {
<?php max-width:<?php 1400px;
<?php margin:<?php 0<?php auto;
<?php padding:<?php 0<?php 2rem;
<?php position:<?php relative;
<?php z-index:<?php 1;
}

.showcase-header<?php {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php center;
<?php margin-bottom:<?php 3rem;
<?php flex-wrap:<?php wrap;
<?php gap:<?php 1rem;
}

.showcase-title<?php {
<?php font-size:<?php 2.5rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php margin:<?php 0;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
}

.showcase-filters<?php {
<?php display:<?php flex;
<?php gap:<?php 0.5rem;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 50px;
<?php padding:<?php 0.25rem;
<?php backdrop-filter:<?php blur(20px);
}

.filter-btn<?php {
<?php background:<?php none;
<?php border:<?php none;
<?php color:<?php #9ca3af;
<?php padding:<?php 0.75rem<?php 1.5rem;
<?php border-radius:<?php 25px;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php white-space:<?php nowrap;
}

.filter-btn:hover<?php {
<?php color:<?php #ffffff;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
}

.filter-btn.active<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0<?php 4px<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.raspadinhas-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php margin-bottom:<?php 3rem;
}

.raspinha-card<?php {
<?php background:<?php linear-gradient(145deg,<?php #1e1e1e<?php 0%,<?php #2a2a2a<?php 100%);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php overflow:<?php hidden;
<?php transition:<?php all<?php 0.4s<?php ease;
<?php position:<?php relative;
<?php transform-style:<?php preserve-3d;
}

.raspinha-card:hover<?php {
<?php transform:<?php translateY(-8px)<?php rotateX(5deg);
<?php box-shadow:<?php 
<?php 0<?php 20px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3),
<?php 0<?php 8px<?php 20px<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.card-banner<?php {
<?php position:<?php relative;
<?php height:<?php 150px;
<?php overflow:<?php hidden;
<?php background:<?php linear-gradient(135deg,<?php #2a2a2a,<?php #1a1a1a);
}

.banner-image<?php {
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php object-fit:<?php cover;
<?php transition:<?php transform<?php 0.4s<?php ease;
}

.raspinha-card:hover<?php .banner-image<?php {
<?php transform:<?php scale(1.1);
}

.banner-placeholder<?php {
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 3rem;
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.3);
<?php background:<?php linear-gradient(135deg,<?php #2a2a2a,<?php #1a1a1a);
}

.banner-placeholder.money-theme<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.banner-placeholder.tech-theme<?php {
<?php background:<?php linear-gradient(135deg,<?php #3b82f6,<?php #1d4ed8);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.banner-placeholder.vehicle-theme<?php {
<?php background:<?php linear-gradient(135deg,<?php #10b981,<?php #047857);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.banner-placeholder.fashion-theme<?php {
<?php background:<?php linear-gradient(135deg,<?php #8b5cf6,<?php #7c3aed);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.category-badge<?php {
<?php position:<?php absolute;
<?php top:<?php 0.75rem;
<?php left:<?php 0.75rem;
<?php padding:<?php 0.25rem<?php 0.75rem;
<?php border-radius:<?php 15px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.5px;
<?php backdrop-filter:<?php blur(20px);
<?php z-index:<?php 2;
}

.category-badge.dinheiro<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0<?php 4px<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.category-badge.produtos<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0<?php 4px<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.play-overlay<?php {
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php bottom:<?php 0;
<?php background:<?php rgba(0,<?php 0,<?php 0,<?php 0.7);
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php opacity:<?php 0;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php backdrop-filter:<?php blur(4px);
}

.raspinha-card:hover<?php .play-overlay<?php {
<?php opacity:<?php 1;
}

.play-button<?php {
<?php width:<?php 60px;
<?php height:<?php 60px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-radius:<?php 50%;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php #ffffff;
<?php font-size:<?php 1.5rem;
<?php transform:<?php scale(0.8);
<?php transition:<?php all<?php 0.3s<?php ease;
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
}

.raspinha-card:hover<?php .play-button<?php {
<?php transform:<?php scale(1);
}

.card-content<?php {
<?php padding:<?php 1.5rem;
}

.card-title<?php {
<?php color:<?php #ffffff;
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 700;
<?php margin:<?php 0<?php 0<?php 0.5rem<?php 0;
<?php line-height:<?php 1.3;
}

.card-description<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php margin:<?php 0<?php 0<?php 1.5rem<?php 0;
<?php line-height:<?php 1.4;
}

.card-footer<?php {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
}

.card-price<?php {
<?php display:<?php flex;
<?php align-items:<?php baseline;
<?php gap:<?php 0.25rem;
}

.price-label<?php {
<?php color:<?php #22c55e;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 600;
}

.price-value<?php {
<?php color:<?php #22c55e;
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 800;
}

.play-btn<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php text-decoration:<?php none;
<?php padding:<?php 0.75rem<?php 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php box-shadow:<?php 0<?php 4px<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php white-space:<?php nowrap;
}

.play-btn:hover<?php {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #22c55e);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 4px<?php 15px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php color:<?php #ffffff;
}

.showcase-footer<?php {
<?php text-align:<?php center;
}

.view-all-btn<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php text-decoration:<?php none;
<?php padding:<?php 1rem<?php 2rem;
<?php border-radius:<?php 50px;
<?php font-weight:<?php 600;
<?php display:<?php inline-flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.view-all-btn:hover<?php {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 12px<?php 35px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php color:<?php #ffffff;
}

/*<?php Filter<?php Animation<?php */
.raspinha-card<?php {
<?php transition:<?php all<?php 0.4s<?php ease,<?php opacity<?php 0.3s<?php ease,<?php transform<?php 0.3s<?php ease;
}

.raspinha-card.hidden<?php {
<?php opacity:<?php 0;
<?php transform:<?php scale(0.8);
<?php pointer-events:<?php none;
}

/*<?php Responsive<?php Design<?php */
@media<?php (max-width:<?php 768px)<?php {
<?php .raspadinhas-showcase<?php {
<?php padding:<?php 2rem<?php 0;
<?php }
<?php 
<?php .showcase-container<?php {
<?php padding:<?php 0<?php 1rem;
<?php }
<?php 
<?php .showcase-title<?php {
<?php font-size:<?php 2rem;
<?php }
<?php 
<?php .showcase-header<?php {
<?php flex-direction:<?php column;
<?php align-items:<?php flex-start;
<?php margin-bottom:<?php 2rem;
<?php }
<?php 
<?php .raspadinhas-grid<?php {
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
<?php gap:<?php 1rem;
<?php }
<?php 
<?php .filter-btn<?php {
<?php padding:<?php 0.5rem<?php 1rem;
<?php font-size:<?php 0.8rem;
<?php }
<?php 
<?php .card-content<?php {
<?php padding:<?php 1rem;
<?php }
<?php 
<?php .card-title<?php {
<?php font-size:<?php 1.1rem;
<?php }
}

@media<?php (max-width:<?php 480px)<?php {
<?php .showcase-filters<?php {
<?php width:<?php 100%;
<?php justify-content:<?php center;
<?php }
<?php 
<?php .raspadinhas-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php 
<?php .card-footer<?php {
<?php flex-direction:<?php column;
<?php gap:<?php 0.75rem;
<?php align-items:<?php stretch;
<?php }
<?php 
<?php .play-btn<?php {
<?php text-align:<?php center;
<?php width:<?php 100%;
<?php }
}
</style>

<script>
document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php //<?php Filter<?php functionality
<?php const<?php filterBtns<?php =<?php document.querySelectorAll('.filter-btn');
<?php const<?php cards<?php =<?php document.querySelectorAll('.raspinha-card');
<?php 
<?php filterBtns.forEach(btn<?php =><?php {
<?php btn.addEventListener('click',<?php ()<?php =><?php {
<?php const<?php filter<?php =<?php btn.dataset.filter;
<?php 
<?php //<?php Update<?php active<?php button
<?php filterBtns.forEach(b<?php =><?php b.classList.remove('active'));
<?php btn.classList.add('active');
<?php 
<?php //<?php Filter<?php cards
<?php cards.forEach(card<?php =><?php {
<?php const<?php category<?php =<?php card.dataset.category;
<?php 
<?php if<?php (filter<?php ===<?php 'todos'<?php ||<?php category<?php ===<?php filter)<?php {
<?php card.classList.remove('hidden');
<?php }<?php else<?php {
<?php card.classList.add('hidden');
<?php }
<?php });
<?php });
<?php });
});
</script>