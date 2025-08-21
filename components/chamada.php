<?php
//<?php Buscar raspadinhas do banco de dados ordenadas por valor (maior para menor)
try {
<?php $stmt =<?php $pdo->prepare("SELECT *<?php FROM raspadinhas ORDER BY valor DESC,<?php created_at DESC LIMIT 8");
<?php $stmt->execute();
<?php $raspadinhas =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);
}<?php catch (PDOException $e)<?php {
<?php $raspadinhas =<?php [];
}
?>

<section class="raspadinhas-showcase">
<?php <div class="showcase-container">
<?php <div class="showcase-header">
<?php <h2 class="showcase-title">Raspadinhas</h2>
<?php <div class="showcase-filters">
<?php <button class="filter-btn active"<?php data-filter="todos">Todos</button>
<?php <button class="filter-btn"<?php data-filter="dinheiro">Dinheiro</button>
<?php </div>
<?php </div>
<?php <div class="raspadinhas-grid">
<?php <?php if (!empty($raspadinhas)):<?php ?>
<?php <?php foreach ($raspadinhas as $raspinha):<?php ?>
<?php <div class="raspinha-card"<?php data-category="dinheiro">
<?php <div class="card-banner">
<?php <?php if ($raspinha['banner']<?php &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $raspinha['banner'])):<?php ?>
<?php <img src="<?php=<?php htmlspecialchars($raspinha['banner'])<?php ?>"<?php alt="<?php=<?php htmlspecialchars($raspinha['nome'])<?php ?>"<?php class="banner-image">
<?php <?php else:<?php ?>
<?php <div class="banner-placeholder">
<?php <i class="bi bi-grid-3x3-gap-fill"></i>
<?php </div>
<?php <?php endif;<?php ?>
<?php <!--<?php Category Badge -->
<?php <div class="category-badge <?php=<?php rand(0,<?php 1)<?php ?<?php 'dinheiro'<?php :<?php 'produtos'<?php ?>">
<?php <?php=<?php rand(0,<?php 1)<?php ?<?php 'Dinheiro'<?php :<?php 'Produtos'<?php ?>
<?php </div>
<?php <!--<?php Play Button Overlay -->
<?php <div class="play-overlay">
<?php <div class="play-button">
<?php <i class="bi bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="card-content">
<?php <h3 class="card-title"><?php=<?php htmlspecialchars($raspinha['nome'])<?php ?></h3>
<?php <p class="card-description"><?php=<?php htmlspecialchars($raspinha['descricao']<?php ?:<?php 'Raspe e ganhe prêmios incríveis!')<?php ?></p>
<?php <div class="card-footer">
<?php <div class="card-price">
<?php <span class="price-label">R$</span>
<?php <span class="price-value"><?php=<?php number_format($raspinha['valor'],<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <a href="/cartelas/"<?php class="play-btn">
<?php Jogar </a>
<?php </div>
<?php </div>
<?php </div>
<?php <?php endforeach;<?php ?>
<?php <?php else:<?php ?>
<?php <!--<?php Fallback cards se não houver raspadinhas -<?php também ordenados por valor decrescente -->
<?php <div class="raspinha-card"<?php data-category="produtos">
<?php <div class="card-banner">
<?php <div class="banner-placeholder vehicle-theme">
<?php <i class="bi bi-car-front"></i>
<?php </div>
<?php <div class="category-badge produtos">Produtos</div>
<?php <div class="play-overlay">
<?php <div class="play-button">
<?php <i class="bi bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="card-content">
<?php <h3 class="card-title">Saga Motorizada</h3>
<?php <p class="card-description">Ganhe até<?php R$<?php 1.000,00</p>
<?php <div class="card-footer">
<?php <div class="card-price">
<?php <span class="price-label">R$</span>
<?php <span class="price-value">15,00</span>
<?php </div>
<?php <a href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="raspinha-card"<?php data-category="produtos">
<?php <div class="card-banner">
<?php <div class="banner-placeholder fashion-theme">
<?php <i class="bi bi-bag-heart"></i>
<?php </div>
<?php <div class="category-badge produtos">Produtos</div>
<?php <div class="play-overlay">
<?php <div class="play-button">
<?php <i class="bi bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="card-content">
<?php <h3 class="card-title">Mimo caro!</h3>
<?php <p class="card-description">Ganhe até<?php R$<?php 20,00</p>
<?php <div class="card-footer">
<?php <div class="card-price">
<?php <span class="price-label">R$</span>
<?php <span class="price-value">15,00</span>
<?php </div>
<?php <a href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="raspinha-card"<?php data-category="produtos">
<?php <div class="card-banner">
<?php <div class="banner-placeholder tech-theme">
<?php <i class="bi bi-laptop"></i>
<?php </div>
<?php <div class="category-badge produtos">Produtos</div>
<?php <div class="play-overlay">
<?php <div class="play-button">
<?php <i class="bi bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="card-content">
<?php <h3 class="card-title">Sonho de Consumo</h3>
<?php <p class="card-description">Eletro,<?php eletrônicos e comp...</p>
<?php <div class="card-footer">
<?php <div class="card-price">
<?php <span class="price-label">R$</span>
<?php <span class="price-value">10,00</span>
<?php </div>
<?php <a href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="raspinha-card"<?php data-category="dinheiro">
<?php <div class="card-banner">
<?php <div class="banner-placeholder money-theme">
<?php <i class="bi bi-cash-stack"></i>
<?php </div>
<?php <div class="category-badge dinheiro">Dinheiro</div>
<?php <div class="play-overlay">
<?php <div class="play-button">
<?php <i class="bi bi-play-fill"></i>
<?php </div>
<?php </div>
<?php </div>
<?php <div class="card-content">
<?php <h3 class="card-title">PIX na Conta</h3>
<?php <p class="card-description">Ganhe até<?php R$<?php 2.000,00</p>
<?php <div class="card-footer">
<?php <div class="card-price">
<?php <span class="price-label">R$</span>
<?php <span class="price-value">5,00</span>
<?php </div>
<?php <a href="/cartelas"<?php class="play-btn">Jogar</a>
<?php </div>
<?php </div>
<?php </div>
<?php <?php endif;<?php ?>
<?php </div>
<?php <?php if (count($raspadinhas)<?php ><?php 4):<?php ?>
<?php <div class="showcase-footer">
<?php <a href="/cartelas"<?php class="view-all-btn">
<?php Ver todas as raspadinhas <i class="bi bi-arrow-right"></i>
<?php </a>
<?php </div>
<?php <?php endif;<?php ?>
<?php </div>
</section>

<style>
.raspadinhas-showcase {
<?php padding:<?php 4rem 0;
<?php position:<?php relative;
<?php overflow:<?php hidden;
}

.raspadinhas-showcase::before {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php right:<?php 0;
<?php bottom:<?php 0;
<?php background:<?php url('data:image/svg+xml,<svg width="60"<?php height="60"<?php viewBox="0 0 60 60"<?php xmlns="http://www.w3.org/2000/svg"><g fill="none"<?php fill-rule="evenodd"><g fill="%2322c55e"<?php fill-opacity="0.03"><circle cx="30"<?php cy="30"<?php r="2"/></g></svg>')<?php repeat;
<?php pointer-events:<?php none;
}

.showcase-container {
<?php max-width:<?php 1400px;
<?php margin:<?php 0 auto;
<?php padding:<?php 0 2rem;
<?php position:<?php relative;
<?php z-index:<?php 1;
}

.showcase-header {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php center;
<?php margin-bottom:<?php 3rem;
<?php flex-wrap:<?php wrap;
<?php gap:<?php 1rem;
}

.showcase-title {
<?php font-size:<?php 2.5rem;
<?php font-weight:<?php 800;
<?php color:<?php #ffffff;
<?php margin:<?php 0;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php background-clip:<?php text;
}

.showcase-filters {
<?php display:<?php flex;
<?php gap:<?php 0.5rem;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 50px;
<?php padding:<?php 0.25rem;
<?php backdrop-filter:<?php blur(20px);
}

.filter-btn {
<?php background:<?php none;
<?php border:<?php none;
<?php color:<?php #9ca3af;
<?php padding:<?php 0.75rem 1.5rem;
<?php border-radius:<?php 25px;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php cursor:<?php pointer;
<?php transition:<?php all 0.3s ease;
<?php white-space:<?php nowrap;
}

.filter-btn:hover {
<?php color:<?php #ffffff;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
}

.filter-btn.active {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0 4px 15px rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.raspadinhas-grid {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(280px,<?php 1fr));
<?php gap:<?php 1.5rem;
<?php margin-bottom:<?php 3rem;
}

.raspinha-card {
<?php background:<?php linear-gradient(145deg,<?php #1e1e1e 0%,<?php #2a2a2a 100%);
<?php border:<?php 1px solid rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php overflow:<?php hidden;
<?php transition:<?php all 0.4s ease;
<?php position:<?php relative;
<?php transform-style:<?php preserve-3d;
}

.raspinha-card:hover {
<?php transform:<?php translateY(-8px)<?php rotateX(5deg);
<?php box-shadow:<?php 0 20px 40px rgba(0,<?php 0,<?php 0,<?php 0.3),
<?php 0 8px 20px rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.card-banner {
<?php position:<?php relative;
<?php height:<?php 150px;
<?php overflow:<?php hidden;
<?php background:<?php linear-gradient(135deg,<?php #2a2a2a,<?php #1a1a1a);
}

.banner-image {
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php object-fit:<?php cover;
<?php transition:<?php transform 0.4s ease;
}

.raspinha-card:hover .banner-image {
<?php transform:<?php scale(1.1);
}

.banner-placeholder {
<?php width:<?php 100%;
<?php height:<?php 100%;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 3rem;
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.3);
<?php background:<?php linear-gradient(135deg,<?php #2a2a2a,<?php #1a1a1a);
}

.banner-placeholder.money-theme {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.banner-placeholder.tech-theme {
<?php background:<?php linear-gradient(135deg,<?php #3b82f6,<?php #1d4ed8);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.banner-placeholder.vehicle-theme {
<?php background:<?php linear-gradient(135deg,<?php #10b981,<?php #047857);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.banner-placeholder.fashion-theme {
<?php background:<?php linear-gradient(135deg,<?php #8b5cf6,<?php #7c3aed);
<?php color:<?php rgba(255,<?php 255,<?php 255,<?php 0.8);
}

.category-badge {
<?php position:<?php absolute;
<?php top:<?php 0.75rem;
<?php left:<?php 0.75rem;
<?php padding:<?php 0.25rem 0.75rem;
<?php border-radius:<?php 15px;
<?php font-size:<?php 0.75rem;
<?php font-weight:<?php 600;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.5px;
<?php backdrop-filter:<?php blur(20px);
<?php z-index:<?php 2;
}

.category-badge.dinheiro {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0 4px 15px rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.category-badge.produtos {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php box-shadow:<?php 0 4px 15px rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.play-overlay {
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
<?php transition:<?php all 0.3s ease;
<?php backdrop-filter:<?php blur(4px);
}

.raspinha-card:hover .play-overlay {
<?php opacity:<?php 1;
}

.play-button {
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
<?php transition:<?php all 0.3s ease;
<?php box-shadow:<?php 0 8px 25px rgba(34,<?php 197,<?php 94,<?php 0.4);
}

.raspinha-card:hover .play-button {
<?php transform:<?php scale(1);
}

.card-content {
<?php padding:<?php 1.5rem;
}

.card-title {
<?php color:<?php #ffffff;
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 700;
<?php margin:<?php 0 0 0.5rem 0;
<?php line-height:<?php 1.3;
}

.card-description {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php margin:<?php 0 0 1.5rem 0;
<?php line-height:<?php 1.4;
}

.card-footer {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php center;
<?php gap:<?php 1rem;
}

.card-price {
<?php display:<?php flex;
<?php align-items:<?php baseline;
<?php gap:<?php 0.25rem;
}

.price-label {
<?php color:<?php #22c55e;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 600;
}

.price-value {
<?php color:<?php #22c55e;
<?php font-size:<?php 1.25rem;
<?php font-weight:<?php 800;
}

.play-btn {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php text-decoration:<?php none;
<?php padding:<?php 0.75rem 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php font-size:<?php 0.9rem;
<?php transition:<?php all 0.3s ease;
<?php box-shadow:<?php 0 4px 15px rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php white-space:<?php nowrap;
}

.play-btn:hover {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #22c55e);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 4px 15px rgba(34,<?php 197,<?php 94,<?php 0.3);<?php color:<?php #ffffff;
}

.showcase-footer {
<?php text-align:<?php center;
}

.view-all-btn {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php #ffffff;
<?php text-decoration:<?php none;
<?php padding:<?php 1rem 2rem;
<?php border-radius:<?php 50px;
<?php font-weight:<?php 600;
<?php display:<?php inline-flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php transition:<?php all 0.3s ease;
<?php box-shadow:<?php 0 8px 25px rgba(34,<?php 197,<?php 94,<?php 0.3);
}

.view-all-btn:hover {
<?php background:<?php linear-gradient(135deg,<?php #16a34a,<?php #15803d);
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0 12px 35px rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php color:<?php #ffffff;
}

/*<?php Filter Animation */
.raspinha-card {
<?php transition:<?php all 0.4s ease,<?php opacity 0.3s ease,<?php transform 0.3s ease;
}

.raspinha-card.hidden {
<?php opacity:<?php 0;
<?php transform:<?php scale(0.8);
<?php pointer-events:<?php none;
}

/*<?php Responsive Design */
@media (max-width:<?php 768px)<?php {
<?php .raspadinhas-showcase {
<?php padding:<?php 2rem 0;
<?php }
<?php .showcase-container {
<?php padding:<?php 0 1rem;
<?php }
<?php .showcase-title {
<?php font-size:<?php 2rem;
<?php }
<?php .showcase-header {
<?php flex-direction:<?php column;
<?php align-items:<?php flex-start;
<?php margin-bottom:<?php 2rem;
<?php }
<?php .raspadinhas-grid {
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(250px,<?php 1fr));
<?php gap:<?php 1rem;
<?php }
<?php .filter-btn {
<?php padding:<?php 0.5rem 1rem;
<?php font-size:<?php 0.8rem;
<?php }
<?php .card-content {
<?php padding:<?php 1rem;
<?php }
<?php .card-title {
<?php font-size:<?php 1.1rem;
<?php }
}

@media (max-width:<?php 480px)<?php {
<?php .showcase-filters {
<?php width:<?php 100%;
<?php justify-content:<?php center;
<?php }
<?php .raspadinhas-grid {
<?php grid-template-columns:<?php 1fr;
<?php }
<?php .card-footer {
<?php flex-direction:<?php column;
<?php gap:<?php 0.75rem;
<?php align-items:<?php stretch;
<?php }
<?php .play-btn {
<?php text-align:<?php center;
<?php width:<?php 100%;
<?php }
}
</style>

<script>
document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php //<?php Filter functionality const filterBtns =<?php document.querySelectorAll('.filter-btn');
<?php const cards =<?php document.querySelectorAll('.raspinha-card');
<?php filterBtns.forEach(btn =><?php {
<?php btn.addEventListener('click',<?php ()<?php =><?php {
<?php const filter =<?php btn.dataset.filter;
<?php //<?php Update active button filterBtns.forEach(b =><?php b.classList.remove('active'));
<?php btn.classList.add('active');
<?php //<?php Filter cards cards.forEach(card =><?php {
<?php const category =<?php card.dataset.category;
<?php if (filter ===<?php 'todos'<?php ||<?php category ===<?php filter)<?php {
<?php card.classList.remove('hidden');
<?php }<?php else {
<?php card.classList.add('hidden');
<?php }
<?php });
<?php });
<?php });
});
</script>