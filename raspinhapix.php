@session_start();
include('./conexao.php');
//<?php Capturar<?php UTMs<?php da<?php URL<?php e<?php salvar<?php na<?php sessão
if<?php (isset($_GET["utm_source"]))<?php $_SESSION["utm_source"]<?php =<?php $_GET["utm_source"];
if<?php (isset($_GET["utm_medium"]))<?php $_SESSION["utm_medium"]<?php =<?php $_GET["utm_medium"];
if<?php (isset($_GET["utm_campaign"]))<?php $_SESSION["utm_campaign"]<?php =<?php $_GET["utm_campaign"];
if<?php (isset($_GET["utm_term"]))<?php $_SESSION["utm_term"]<?php =<?php $_GET["utm_term"];
if<?php (isset($_GET["utm_content"]))<?php $_SESSION["utm_content"]<?php =<?php $_GET["utm_content"];
if<?php (isset($_GET["click_id"]))<?php $_SESSION["click_id"]<?php =<?php $_GET["click_id"];
?>

<!DOCTYPE<?php html>
<html<?php lang="pt-BR">
<head>

<?php <!--<?php xTracky<?php Integration<?php -->
<?php <script<?php 
<?php src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
<?php data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
<?php data-click-id-param="click_id">
<?php </script>
<?php <meta<?php charset="UTF-8">
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php<?php echo<?php $nomeSite;?><?php -<?php Raspadinhas<?php Online</title>
<?php <meta<?php name="description"<?php content="Raspe<?php e<?php ganhe<?php prêmios<?php incríveis!<?php PIX<?php na<?php conta<?php instantâneo.">
<?php 
<?php <!--<?php Preload<?php Critical<?php Resources<?php -->
<?php <link<?php rel="preconnect"<?php href="https://fonts.googleapis.com">
<?php <link<?php rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin>
