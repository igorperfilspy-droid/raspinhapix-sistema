<?php<?php 
//<?php Buscar<?php prêmios<?php do<?php banco<?php de<?php dados<?php 
try<?php {<?php 
<?php $stmt<?php =<?php $pdo->query("<?php 
<?php SELECT<?php rp.nome,<?php rp.icone,<?php rp.valor<?php 
<?php FROM<?php raspadinha_premios<?php rp<?php 
<?php JOIN<?php raspadinhas<?php r<?php ON<?php rp.raspadinha_id<?php =<?php r.id<?php 
<?php WHERE<?php rp.valor<?php ><?php 0<?php 
<?php ORDER<?php BY<?php RAND()<?php 
<?php LIMIT<?php 20<?php 
<?php ");<?php 
<?php $premios<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
}<?php catch<?php (PDOException<?php $e)<?php {<?php 
<?php $premios<?php =<?php [];<?php 
}<?php 
<?php 
//<?php Lista<?php de<?php nomes<?php brasileiros<?php aleatórios<?php 
$nomes<?php =<?php [<?php 
<?php 'Ana',<?php 'João',<?php 'Maria',<?php 'Pedro',<?php 'Carla',<?php 'Lucas',<?php 'Fernanda',<?php 'Rafael',<?php 
<?php 'Juliana',<?php 'Bruno',<?php 'Camila',<?php 'Diego',<?php 'Beatriz',<?php 'Thiago',<?php 'Larissa',<?php 'André',<?php 
<?php 'Patrícia',<?php 'Gustavo',<?php 'Isabela',<?php 'Felipe',<?php 'Amanda',<?php 'Rodrigo',<?php 'Natália',<?php 'Gabriel',<?php 
<?php 'Letícia',<?php 'Mateus',<?php 'Carolina',<?php 'Leonardo',<?php 'Vanessa',<?php 'Marcelo',<?php 'Priscila',<?php 'Daniel',<?php 
<?php 'Roberta',<?php 'Vinícius',<?php 'Mônica',<?php 'Ricardo',<?php 'Adriana',<?php 'Fábio',<?php 'Cristina',<?php 'Alexandre',<?php 
<?php 'Silvia',<?php 'Eduardo',<?php 'Renata',<?php 'Carlos',<?php 'Tatiane',<?php 'Paulo',<?php 'Débora',<?php 'Antônio',<?php 
<?php 'Sandra',<?php 'José',<?php 'Márcia',<?php 'Roberto',<?php 'Luciana',<?php 'Marcos',<?php 'Eliane',<?php 'Francisco',<?php 
<?php 'Regina',<?php 'Fernando',<?php 'Marta',<?php 'Luiz',<?php 'Denise',<?php 'Sérgio',<?php 'Cláudia',<?php 'Jorge',<?php 
<?php 'Vera',<?php 'Raimundo',<?php 'Solange',<?php 'Manoel',<?php 'Rosana',<?php 'Edson',<?php 'Lúcia',<?php 'Wilson',<?php 
<?php 'Simone',<?php 'Sebastião',<?php 'Teresa',<?php 'Antônio',<?php 'Aparecida',<?php 'Valdir',<?php 'Fátima',<?php 'João',<?php 
<?php 'Cleusa',<?php 'Benedito',<?php 'Rita',<?php 'Nelson',<?php 'Marlene',<?php 'Davi',<?php 'Célia',<?php 'Geraldo',<?php 
<?php 'Neusa',<?php 'Ademir',<?php 'Ivone',<?php 'Miguel',<?php 'Irene',<?php 'Waldir',<?php 'Sônia',<?php 'Benedita',<?php 
<?php 'Valter',<?php 'Lourdes',<?php 'Reinaldo',<?php 'Terezinha',<?php 'Alcides'<?php 
];<?php 
<?php 
//<?php Gerar<?php ganhadores<?php com<?php dados<?php aleatórios<?php 
$ganhadores<?php =<?php [];<?php 
$valor_total_distribuido<?php =<?php 0;<?php 
<?php 
foreach<?php ($premios<?php as<?php $premio)<?php {<?php 
<?php $nome_aleatorio<?php =<?php $nomes[array_rand($nomes)];<?php 
<?php $tempo_aleatorio<?php =<?php rand(1,<?php 60);<?php //<?php Entre<?php 1<?php e<?php 60<?php minutos<?php 
<?php 
<?php $ganhadores[]<?php =<?php [<?php 
<?php 'nome'<?php =><?php $nome_aleatorio,<?php 
<?php 'premio'<?php =><?php $premio['nome'],<?php 
<?php 'icone'<?php =><?php $premio['icone'],<?php 
<?php 'valor'<?php =><?php $premio['valor'],<?php 
<?php 'tempo'<?php =><?php $tempo_aleatorio<?php 
<?php ];<?php 
<?php 
<?php $valor_total_distribuido<?php +=<?php $premio['valor'];<?php 
}<?php 
<?php 
//<?php Duplicar<?php para<?php efeito<?php infinito<?php 
$ganhadores_duplicados<?php =<?php array_merge($ganhadores,<?php $ganhadores);<?php 
?><?php 
<?php 
<section<?php class="winners-section"><?php 
<?php <div<?php class="winners-container"><?php 
<?php <div<?php class="winners-header"><?php 
<?php <h2<?php class="winners-title">Últimos<?php Ganhadores</h2><?php 
<?php <div<?php class="total-distributed"><?php 
<?php <span<?php class="distributed-label">Prêmios<?php Distribuídos</span><?php 
<?php <span<?php class="distributed-value">R$<?php <?php=<?php number_format($valor_total_distribuido,<?php 2,<?php ',',<?php '.')<?php ?></span><?php 
<?php </div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="winners-carousel"><?php 
<?php <div<?php class="winners-track"><?php 
<?php <?php<?php foreach<?php ($ganhadores_duplicados<?php as<?php $ganhador):<?php ?><?php 
<?php <div<?php class="winner-item"><?php 
<?php <div<?php class="winner-avatar"><?php 
<?php <?php<?php if<?php (!empty($ganhador['icone'])<?php &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $ganhador['icone'])):<?php ?><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($ganhador['icone'])<?php ?>"<?php alt="<?php=<?php htmlspecialchars($ganhador['premio'])<?php ?>"<?php class="winner-image"><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="winner-placeholder"><?php 
<?php <i<?php class="bi<?php bi-gift"></i><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="winner-info"><?php 
<?php <div<?php class="winner-name">***<?php=<?php strtolower(substr($ganhador['nome'],<?php 0,<?php 1))<?php .<?php str_repeat('*',<?php strlen($ganhador['nome'])<?php -<?php 1)<?php ?></div><?php 
<?php <div<?php class="winner-time">há<?php <?php=<?php $ganhador['tempo']<?php ?><?php min</div><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="winner-prize"><?php 
<?php <div<?php class="prize-value">R$<?php <?php=<?php number_format($ganhador['valor'],<?php 0,<?php ',',<?php '.')<?php ?></div><?php 
<?php <div<?php class="prize-type"><?php 
<?php <?php<?php if<?php ($ganhador['valor']<?php >=<?php 1000):<?php ?><?php 
<?php <span<?php class="prize-badge<?php premium">PRÊMIO</span><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <span<?php class="prize-badge<?php standard">PIX</span><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
<?php <?php<?php endforeach;<?php ?><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
</section><?php 
<?php 
<style><?php 
/*<?php Winners<?php Section<?php */<?php 
.winners-section<?php {<?php 
<?php padding:<?php 3rem<?php 2rem;<?php 
<?php overflow:<?php hidden;<?php 
}<?php 
<?php 
.winners-container<?php {<?php 
<?php max-width:<?php 1400px;<?php 
<?php margin:<?php 0<?php auto;<?php 
}<?php 
<?php 
.winners-header<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php space-between;<?php 
<?php align-items:<?php center;<?php 
<?php margin-bottom:<?php 2rem;<?php 
<?php flex-wrap:<?php wrap;<?php 
<?php gap:<?php 1rem;<?php 
}<?php 
<?php 
.winners-title<?php {<?php 
<?php font-size:<?php 2rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin:<?php 0;<?php 
}<?php 
<?php 
.total-distributed<?php {<?php 
<?php text-align:<?php right;<?php 
}<?php 
<?php 
.distributed-label<?php {<?php 
<?php display:<?php block;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php color:<?php #9ca3af;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
}<?php 
<?php 
.distributed-value<?php {<?php 
<?php font-size:<?php 1.5rem;<?php 
<?php font-weight:<?php 800;<?php 
<?php color:<?php #22c55e;<?php 
<?php display:<?php block;<?php 
}<?php 
<?php 
.winners-carousel<?php {<?php 
<?php position:<?php relative;<?php 
<?php overflow:<?php hidden;<?php 
<?php mask:<?php linear-gradient(90deg,<?php transparent,<?php black<?php 5%,<?php black<?php 95%,<?php transparent);<?php 
<?php -webkit-mask:<?php linear-gradient(90deg,<?php transparent,<?php black<?php 5%,<?php black<?php 95%,<?php transparent);<?php 
}<?php 
<?php 
.winners-track<?php {<?php 
<?php display:<?php flex;<?php 
<?php gap:<?php 1rem;<?php 
<?php animation:<?php scroll-winners<?php 60s<?php linear<?php infinite;<?php 
<?php width:<?php fit-content;<?php 
}<?php 
<?php 
@keyframes<?php scroll-winners<?php {<?php 
<?php 0%<?php {<?php 
<?php transform:<?php translateX(0);<?php 
<?php }<?php 
<?php 100%<?php {<?php 
<?php transform:<?php translateX(-50%);<?php 
<?php }<?php 
}<?php 
<?php 
.winner-item<?php {<?php 
<?php flex-shrink:<?php 0;<?php 
<?php width:<?php 280px;<?php 
<?php background:<?php linear-gradient(145deg,<?php rgba(20,<?php 20,<?php 20,<?php 0.8)<?php 0%,<?php rgba(10,<?php 10,<?php 10,<?php 0.9)<?php 100%);<?php 
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php 
<?php border-radius:<?php 16px;<?php 
<?php padding:<?php 1.5rem;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 1rem;<?php 
<?php backdrop-filter:<?php blur(20px);<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
}<?php 
<?php 
.winner-item:hover<?php {<?php 
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php transform:<?php translateY(-2px);<?php 
<?php box-shadow:<?php 0<?php 8px<?php 25px<?php rgba(0,<?php 0,<?php 0,<?php 0.2);<?php 
}<?php 
<?php 
.winner-avatar<?php {<?php 
<?php width:<?php 50px;<?php 
<?php height:<?php 50px;<?php 
<?php border-radius:<?php 50%;<?php 
<?php overflow:<?php hidden;<?php 
<?php flex-shrink:<?php 0;<?php 
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.2),<?php rgba(16,<?php 185,<?php 129,<?php 0.1));<?php 
<?php border:<?php 2px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
}<?php 
<?php 
.winner-image<?php {<?php 
<?php width:<?php 100%;<?php 
<?php height:<?php 100%;<?php 
<?php object-fit:<?php cover;<?php 
}<?php 
<?php 
.winner-placeholder<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php font-size:<?php 1.5rem;<?php 
}<?php 
<?php 
.winner-info<?php {<?php 
<?php flex:<?php 1;<?php 
<?php min-width:<?php 0;<?php 
}<?php 
<?php 
.winner-name<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php color:<?php #ffffff;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
<?php text-overflow:<?php ellipsis;<?php 
<?php overflow:<?php hidden;<?php 
<?php white-space:<?php nowrap;<?php 
}<?php 
<?php 
.winner-time<?php {<?php 
<?php font-size:<?php 0.8rem;<?php 
<?php color:<?php #9ca3af;<?php 
}<?php 
<?php 
.winner-prize<?php {<?php 
<?php text-align:<?php right;<?php 
<?php flex-shrink:<?php 0;<?php 
}<?php 
<?php 
.prize-value<?php {<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php #22c55e;<?php 
<?php margin-bottom:<?php 0.25rem;<?php 
}<?php 
<?php 
.prize-type<?php {<?php 
<?php display:<?php flex;<?php 
<?php justify-content:<?php flex-end;<?php 
}<?php 
<?php 
.prize-badge<?php {<?php 
<?php font-size:<?php 0.7rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php padding:<?php 0.2rem<?php 0.5rem;<?php 
<?php border-radius:<?php 12px;<?php 
<?php text-transform:<?php uppercase;<?php 
<?php letter-spacing:<?php 0.5px;<?php 
}<?php 
<?php 
.prize-badge.premium<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #f59e0b,<?php #d97706);<?php 
<?php color:<?php white;<?php 
}<?php 
<?php 
.prize-badge.standard<?php {<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);<?php 
<?php color:<?php white;<?php 
}<?php 
<?php 
/*<?php Pause<?php animation<?php on<?php hover<?php */<?php 
.winners-carousel:hover<?php .winners-track<?php {<?php 
<?php animation-play-state:<?php paused;<?php 
}<?php 
<?php 
/*<?php Mobile<?php Responsive<?php */<?php 
@media<?php (max-width:<?php 768px)<?php {<?php 
<?php .winners-section<?php {<?php 
<?php padding:<?php 2rem<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .winners-header<?php {<?php 
<?php flex-direction:<?php column;<?php 
<?php align-items:<?php flex-start;<?php 
<?php text-align:<?php left;<?php 
<?php }<?php 
<?php 
<?php .winners-title<?php {<?php 
<?php font-size:<?php 1.6rem;<?php 
<?php }<?php 
<?php 
<?php .total-distributed<?php {<?php 
<?php text-align:<?php left;<?php 
<?php }<?php 
<?php 
<?php .distributed-value<?php {<?php 
<?php font-size:<?php 1.3rem;<?php 
<?php }<?php 
<?php 
<?php .winner-item<?php {<?php 
<?php width:<?php 250px;<?php 
<?php padding:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .winner-avatar<?php {<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php }<?php 
<?php 
<?php .winner-placeholder<?php {<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php }<?php 
<?php 
<?php .winner-name<?php {<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .winner-time<?php {<?php 
<?php font-size:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .prize-value<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .winners-track<?php {<?php 
<?php animation-duration:<?php 45s;<?php 
<?php }<?php 
}<?php 
<?php 
@media<?php (max-width:<?php 480px)<?php {<?php 
<?php .winner-item<?php {<?php 
<?php width:<?php 220px;<?php 
<?php padding:<?php 0.875rem;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php }<?php 
<?php 
<?php .winner-avatar<?php {<?php 
<?php width:<?php 35px;<?php 
<?php height:<?php 35px;<?php 
<?php }<?php 
<?php 
<?php .winner-name<?php {<?php 
<?php font-size:<?php 0.85rem;<?php 
<?php }<?php 
<?php 
<?php .prize-value<?php {<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .prize-badge<?php {<?php 
<?php font-size:<?php 0.65rem;<?php 
<?php padding:<?php 0.15rem<?php 0.4rem;<?php 
<?php }<?php 
}<?php 
<?php 
/*<?php Loading<?php state<?php for<?php images<?php */<?php 
.winner-image<?php {<?php 
<?php opacity:<?php 0;<?php 
<?php transition:<?php opacity<?php 0.3s<?php ease;<?php 
}<?php 
<?php 
.winner-image.loaded<?php {<?php 
<?php opacity:<?php 1;<?php 
}<?php 
</style><?php 
<?php 
<script><?php 
document.addEventListener('DOMContentLoaded',<?php function()<?php {<?php 
<?php //<?php Lazy<?php load<?php images<?php 
<?php const<?php images<?php =<?php document.querySelectorAll('.winner-image');<?php 
<?php images.forEach(img<?php =><?php {<?php 
<?php img.onload<?php =<?php function()<?php {<?php 
<?php this.classList.add('loaded');<?php 
<?php };<?php 
<?php 
<?php //<?php If<?php image<?php is<?php already<?php loaded<?php 
<?php if<?php (img.complete)<?php {<?php 
<?php img.classList.add('loaded');<?php 
<?php }<?php 
<?php });<?php 
<?php 
<?php console.log('%c🏆<?php Últimos<?php Ganhadores<?php carregados!',<?php 'color:<?php #22c55e;<?php font-size:<?php 14px;<?php font-weight:<?php bold;');<?php 
});<?php 
</script>