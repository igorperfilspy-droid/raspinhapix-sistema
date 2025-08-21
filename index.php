<?php
@session_start();
require __DIR__ . '/conexao.php'; // aqui você já define $pdo, $nomeSite, $urlSite, etc.

// --- UTMs na sessão ---
foreach (['utm_source','utm_medium','utm_campaign','utm_term','utm_content','click_id'] as $k) {
    if (isset($_GET[$k])) $_SESSION[$k] = $_GET[$k];
}

// --- Fallbacks seguros ---
$nomeSite = !empty($nomeSite) ? $nomeSite : 'RaspinhaPix';
$urlSite  = !empty($urlSite)  ? $urlSite  : '/';

// --- Flags de arquivos existentes (evita erros) ---
$haveHeader = is_file(__DIR__.'/inc/header.php');
$haveFooter = is_file(__DIR__.'/inc/footer.php');

$components = [
  'carrossel','ganhos','chamada','modals','testimonials'
];
$haveAnyComponent = false;
foreach ($components as $c) {
    if (is_file(__DIR__."/components/{$c}.php")) { $haveAnyComponent = true; break; }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($nomeSite) ?> - Raspadinhas Online</title>
  <meta name="description" content="Raspe e ganhe prêmios incríveis! PIX na conta instantâneo.">
  <meta property="og:title" content="<?= htmlspecialchars($nomeSite) ?> - Raspadinhas Online">
  <meta property="og:description" content="Raspe e ganhe prêmios incríveis! PIX na conta instantâneo.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= htmlspecialchars($urlSite) ?>">

  <!-- Fonts / Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Notiflix CSS primeiro (evita flash sem estilo) -->
  <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css" rel="stylesheet">
  <!-- Seu CSS -->
  <link rel="stylesheet" href="assets/style/globalStyles.css?v=<?= time() ?>">
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">

  <style>
    :root { color-scheme: dark; }
    body,html { margin:0; padding:0; font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background:#0b0b0b; color:#e5e5e5; }
    a { color:#22c55e; text-decoration:none; }
    .container { max-width: 1120px; margin: 0 auto; padding: 16px; }
    .card { background:#121212; border:1px solid #1f1f1f; border-radius:14px; padding:16px; }

    /* Loader ultra-resiliente */
    .loading-screen { position: fixed; inset:0; display:grid; place-items:center; background:#0a0a0a; z-index:9999; transition:opacity .3s ease; }
    .loading-spinner { width: 52px; height: 52px; position: relative; }
    .loading-spinner::before {
      content:""; position:absolute; inset:0;
      border:3px solid rgba(34, 197, 94, .25);
      border-top-color:#22c55e; border-radius:50%;
      animation:spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    /* Se JS estiver off, não travar tela */
    noscript .loading-screen { display:none !important; }

    /* Fallback básico quando faltam includes */
    .fallback-hero { padding:60px 0; text-align:center; }
    .fallback-hero h1 { font-size: 32px; margin: 0 0 12px; }
    .fallback-hero p { color:#b5b5b5; margin:0; }
  </style>
</head>
<body>

  <!-- Loader -->
  <div id="loadingScreen" class="loading-screen" aria-hidden="true">
    <div class="loading-spinner" role="status" aria-label="Carregando"></div>
  </div>
  <noscript><div class="loading-screen" style="display:none"></div></noscript>

  <?php if ($haveHeader) { include __DIR__.'/inc/header.php'; } ?>

  <main class="container" id="appRoot">
    <?php if ($haveAnyComponent): ?>
      <?php foreach ($components as $c): $p = __DIR__."/components/{$c}.php"; if (is_file($p)) include $p; endforeach; ?>
    <?php else: ?>
      <!-- Fallback amigável: NUNCA deixa tela preta -->
      <section class="card fallback-hero">
        <h1>🎯 <?= htmlspecialchars($nomeSite) ?></h1>
        <p>Seu site está online! Adicione seus arquivos em <code>/inc</code> e <code>/components</code> para ver o conteúdo completo.</p>
        <p style="margin-top:12px">Caminhos esperados: <code>inc/header.php</code>, <code>components/carrossel.php</code>, etc.</p>
      </section>
    <?php endif; ?>
  </main>

  <?php if ($haveFooter) { include __DIR__.'/inc/footer.php'; } ?>

  <!-- Scripts NÃO bloqueantes -->
  <script defer src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>

  <!-- xTracky (UTM handler) - também defer -->
  <script defer
    src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
    data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
    data-click-id-param="click_id"></script>

  <script>
    // ====== HIDE LOADER (remove de verdade do DOM) ======
    (function () {
      var scr = document.getElementById('loadingScreen');
      if (!scr) return;

      function hide() {
        // remove do DOM (não só opacity)
        if (scr && scr.parentNode) scr.parentNode.removeChild(scr);
        scr = null;
      }

      // 1) Assim que DOM montar
      document.addEventListener('DOMContentLoaded', function () {
        setTimeout(hide, 600);
      });

      // 2) Quando tudo carregar (imagens, fontes…)
      window.addEventListener('load', hide);

      // 3) Failsafe absoluto
      setTimeout(hide, 4000);

      // Log de erros JS para debug
      window.addEventListener('error', function (e) {
        console.error('[RaspinhaPix] JS error:', e?.error || e?.message || e);
      });
      window.addEventListener('unhandledrejection', function (e) {
        console.error('[RaspinhaPix] Promise rejection:', e?.reason || e);
      });
    })();

    // ====== UX extra (não impacta o loader) ======
    (function () {
      // Notiflix é opcional — só inicializa se existir
      function initNotiflix() {
        if (!window.Notiflix || !Notiflix.Notify) return;
        Notiflix.Notify.init({
          width:'300px', position:'right-top', distance:'20px',
          borderRadius:'12px', timeout:3500, clickToClose:true,
          cssAnimation:true, cssAnimationDuration:350, cssAnimationStyle:'zoom',
          success: { background:'#22c55e', textColor:'#fff' }
        });
      }

      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initNotiflix();
      } else {
        document.addEventListener('DOMContentLoaded', initNotiflix);
      }

      // Atualiza ano no footer se existir o seletor
      document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.footer-description');
        if (els.length) {
          els[0].innerHTML = els[0].innerHTML.replace(/\b20\d{2}\b/, new Date().getFullYear());
        }
      });
    })();
  </script>
</body>
</html>
