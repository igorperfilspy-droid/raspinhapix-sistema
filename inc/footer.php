<footer<?php class="footer"><?php 
<?php <div<?php class="footer-container"><?php 
<?php <div<?php class="footer-content"><?php 
<?php <div><?php 
<?php <div<?php class="footer-brand"><?php 
<?php <?php<?php if<?php ($logoSite<?php &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $logoSite)):<?php ?><?php 
<?php <img<?php src="<?php=<?php htmlspecialchars($logoSite)<?php ?>"<?php alt="<?php=<?php htmlspecialchars($nomeSite)<?php ?>"<?php class="footer-logo-image"><?php 
<?php <?php<?php else:<?php ?><?php 
<?php <div<?php class="footer-logo-icon"><?php 
<?php <?php=<?php strtoupper(substr($nomeSite,<?php 0,<?php 1))<?php ?><?php 
<?php </div><?php 
<?php <?php<?php endif;<?php ?><?php 
<?php </div><?php 
<?php <p<?php class="footer-description"><?php 
<?php ©<?php 2025<?php <?php<?php echo<?php $nomeSite;<?php ?>.<?php Todos<?php os<?php direitos<?php reservados.<?php 
<?php </p><?php 
<?php <p<?php class="footer-description"><?php 
<?php Raspadinhas<?php e<?php outros<?php jogos<?php de<?php azar<?php são<?php regulamentados<?php e<?php cobertos<?php pela<?php nossa<?php licença<?php de<?php jogos.<?php Jogue<?php com<?php responsabilidade.<?php 
<?php </p><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="footer-section"><?php 
<?php <h3>Regulamentos</h3><?php 
<?php <ul<?php class="footer-links"><?php 
<?php <li><a<?php href="#">Jogo<?php responsável</a></li><?php 
<?php <li><a<?php href="#">Política<?php de<?php Privacidade</a></li><?php 
<?php <li><a<?php href="#">Termos<?php de<?php Uso</a></li><?php 
<?php </ul><?php 
<?php </div><?php 
<?php 
<?php <div<?php class="footer-section"><?php 
<?php <h3>Ajuda</h3><?php 
<?php <ul<?php class="footer-links"><?php 
<?php <li><a<?php href="#">Perguntas<?php Frequentes</a></li><?php 
<?php <li><a<?php href="#">Como<?php Jogar</a></li><?php 
<?php <li><a<?php href="#">Suporte<?php Técnico</a></li><?php 
<?php </ul><?php 
<?php </div><?php 
<?php </div><?php 
<?php </div><?php 
</footer><?php 
<?php 
<style><?php 
/*<?php Footer<?php Logo<?php Styles<?php */<?php 
.footer-brand<?php {<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php gap:<?php 0.75rem;<?php 
<?php margin-bottom:<?php 1rem;<?php 
}<?php 
<?php 
.footer-logo-image<?php {<?php 
<?php height:<?php 40px;<?php 
<?php width:<?php auto;<?php 
<?php max-width:<?php 150px;<?php 
<?php object-fit:<?php contain;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
}<?php 
<?php 
.footer-logo-image:hover<?php {<?php 
<?php transform:<?php scale(1.02);<?php 
}<?php 
<?php 
.footer-logo-icon<?php {<?php 
<?php width:<?php 40px;<?php 
<?php height:<?php 40px;<?php 
<?php background:<?php linear-gradient(135deg,<?php #22c55e<?php 0%,<?php #16a34a<?php 100%);<?php 
<?php border-radius:<?php 10px;<?php 
<?php display:<?php flex;<?php 
<?php align-items:<?php center;<?php 
<?php justify-content:<?php center;<?php 
<?php font-size:<?php 1.2rem;<?php 
<?php color:<?php #ffffff;<?php 
<?php font-weight:<?php 800;<?php 
<?php box-shadow:<?php 0<?php 4px<?php 12px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
}<?php 
<?php 
.footer-logo-icon:hover<?php {<?php 
<?php box-shadow:<?php 0<?php 6px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);<?php 
<?php transform:<?php translateY(-1px);<?php 
}<?php 
<?php 
.footer-brand<?php span<?php {<?php 
<?php font-size:<?php 1.25rem;<?php 
<?php font-weight:<?php 700;<?php 
<?php color:<?php white;<?php 
}<?php 
<?php 
/*<?php Footer<?php Base<?php Styles<?php */<?php 
.footer<?php {<?php 
<?php background:<?php linear-gradient(145deg,<?php #0a0a0a<?php 0%,<?php #1a1a1a<?php 100%);<?php 
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.05);<?php 
<?php margin-top:<?php 4rem;<?php 
}<?php 
<?php 
.footer-container<?php {<?php 
<?php max-width:<?php 1400px;<?php 
<?php margin:<?php 0<?php auto;<?php 
<?php padding:<?php 3rem<?php 2rem<?php 2rem;<?php 
}<?php 
<?php 
.footer-content<?php {<?php 
<?php display:<?php grid;<?php 
<?php grid-template-columns:<?php 2fr<?php 1fr<?php 1fr;<?php 
<?php gap:<?php 3rem;<?php 
<?php margin-bottom:<?php 2rem;<?php 
}<?php 
<?php 
.footer-section<?php h3<?php {<?php 
<?php color:<?php white;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php font-weight:<?php 600;<?php 
<?php margin-bottom:<?php 1rem;<?php 
<?php border-bottom:<?php 2px<?php solid<?php #22c55e;<?php 
<?php padding-bottom:<?php 0.5rem;<?php 
<?php display:<?php inline-block;<?php 
}<?php 
<?php 
.footer-links<?php {<?php 
<?php list-style:<?php none;<?php 
<?php padding:<?php 0;<?php 
}<?php 
<?php 
.footer-links<?php li<?php {<?php 
<?php margin-bottom:<?php 0.75rem;<?php 
}<?php 
<?php 
.footer-links<?php a<?php {<?php 
<?php color:<?php #9ca3af;<?php 
<?php text-decoration:<?php none;<?php 
<?php transition:<?php all<?php 0.3s<?php ease;<?php 
<?php font-size:<?php 0.95rem;<?php 
}<?php 
<?php 
.footer-links<?php a:hover<?php {<?php 
<?php color:<?php #22c55e;<?php 
<?php padding-left:<?php 0.5rem;<?php 
}<?php 
<?php 
.footer-description<?php {<?php 
<?php color:<?php #6b7280;<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php line-height:<?php 1.5;<?php 
<?php margin-bottom:<?php 1rem;<?php 
}<?php 
<?php 
/*<?php Responsive<?php */<?php 
@media<?php (max-width:<?php 768px)<?php {<?php 
<?php .footer-content<?php {<?php 
<?php grid-template-columns:<?php 1fr;<?php 
<?php gap:<?php 2rem;<?php 
<?php }<?php 
<?php 
<?php .footer-container<?php {<?php 
<?php padding:<?php 2rem<?php 1rem<?php 1.5rem;<?php 
<?php }<?php 
<?php 
<?php .footer-brand<?php {<?php 
<?php gap:<?php 0.5rem;<?php 
<?php }<?php 
<?php 
<?php .footer-logo-image<?php {<?php 
<?php height:<?php 35px;<?php 
<?php }<?php 
<?php 
<?php .footer-logo-icon<?php {<?php 
<?php width:<?php 35px;<?php 
<?php height:<?php 35px;<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php border-radius:<?php 8px;<?php 
<?php }<?php 
<?php 
<?php .footer-brand<?php span<?php {<?php 
<?php font-size:<?php 1.1rem;<?php 
<?php }<?php 
}<?php 
<?php 
@media<?php (max-width:<?php 480px)<?php {<?php 
<?php .footer-brand<?php {<?php 
<?php gap:<?php 0.4rem;<?php 
<?php }<?php 
<?php 
<?php .footer-logo-image<?php {<?php 
<?php height:<?php 32px;<?php 
<?php }<?php 
<?php 
<?php .footer-logo-icon<?php {<?php 
<?php width:<?php 32px;<?php 
<?php height:<?php 32px;<?php 
<?php font-size:<?php 1rem;<?php 
<?php border-radius:<?php 6px;<?php 
<?php }<?php 
<?php 
<?php .footer-brand<?php span<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .footer-section<?php h3<?php {<?php 
<?php font-size:<?php 1rem;<?php 
<?php }<?php 
<?php 
<?php .footer-links<?php a<?php {<?php 
<?php font-size:<?php 0.9rem;<?php 
<?php }<?php 
<?php 
<?php .footer-description<?php {<?php 
<?php font-size:<?php 0.85rem;<?php 
<?php }<?php 
}<?php 
</style>