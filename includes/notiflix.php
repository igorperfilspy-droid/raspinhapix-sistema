<?php /* carrega Notiflix e mostra mensagens de sessão */ ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css">
<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script>
<script>
(function () {
  <?php
  if (!empty($_SESSION['message']) && is_array($_SESSION['message'])) {
      $type = $_SESSION['message']['type'] ?? 'info';
      $text = $_SESSION['message']['text'] ?? '';
      // consome a flash-message
      $_SESSION['message'] = null;
      // saneia para JS
      $type_js = json_encode($type, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      $text_js = json_encode($text, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      echo "Notiflix.Notify." . ($type ?? 'info') . "(" . ($text_js ?? "''") . ");";
  }
  ?>
})();
</script>
