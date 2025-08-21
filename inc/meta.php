<link rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php time();?>"/><?php 
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script><?php 
<link rel="stylesheet"<?php href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"<?php integrity="sha512-vpKzT5cUqQlRuSPiOFLsTv6HgWmN4qkMOnREgIfw49N2oXah0iA6P9ybpIzR5I0DjXKU+7Y9KtDFuBuqD8zgVg=="<?php crossorigin="anonymous"<?php referrerpolicy="no-referrer"<?php /><?php 
<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
<script src="https://kit.fontawesome.com/a076d05399.js"<?php crossorigin="anonymous"></script><?php 
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script><?php 
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script><?php 
<title><?php echo $nomeSite;?><?php -<?php Raspadinha Online</title><?php 
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script><?php 
<script src="https://cdn.tailwindcss.com"></script><?php 
<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script><?php 
<link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet"><?php 
<?php if ($logoSite &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $logoSite)):<?php ?><?php <link rel="icon"<?php href="<?php=<?php htmlspecialchars($logoSite)<?php ?>"/><?php 
<?php else:<?php ?><?php <link rel="icon"<?php href="data:image/svg+xml,<?php=<?php urlencode('<svg xmlns="http://www.w3.org/2000/svg"<?php viewBox="0 0 100 100"><rect width="100"<?php height="100"<?php fill="#22c55e"/><text x="50"<?php y="50"<?php text-anchor="middle"<?php dominant-baseline="middle"<?php fill="white"<?php font-family="Arial"<?php font-size="40"<?php font-weight="bold">'<?php .<?php strtoupper(substr($nomeSite,<?php 0,<?php 1))<?php .<?php '</text></svg>')<?php ?>"/><?php 
<?php endif;<?php ?>