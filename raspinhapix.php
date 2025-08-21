@session_start();
include('./conexao.php');
//<?php Capturar UTMs da URL e salvar na sessão
if (isset($_GET["utm_source"]))<?php $_SESSION["utm_source"]<?php =<?php $_GET["utm_source"];
if (isset($_GET["utm_medium"]))<?php $_SESSION["utm_medium"]<?php =<?php $_GET["utm_medium"];
if (isset($_GET["utm_campaign"]))<?php $_SESSION["utm_campaign"]<?php =<?php $_GET["utm_campaign"];
if (isset($_GET["utm_term"]))<?php $_SESSION["utm_term"]<?php =<?php $_GET["utm_term"];
if (isset($_GET["utm_content"]))<?php $_SESSION["utm_content"]<?php =<?php $_GET["utm_content"];
if (isset($_GET["click_id"]))<?php $_SESSION["click_id"]<?php =<?php $_GET["click_id"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

<?php <!--<?php xTracky Integration -->
<?php <script <?php src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
<?php data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
<?php data-click-id-param="click_id">
<?php </script>
<?php <meta charset="UTF-8">
<?php <meta name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php echo $nomeSite;?><?php -<?php Raspadinhas Online</title>
<?php <meta name="description"<?php content="Raspe e ganhe prêmios incríveis!<?php PIX na conta instantâneo.">
<?php <!--<?php Preload Critical Resources -->
<?php <link rel="preconnect"<?php href="https://fonts.googleapis.com">
<?php <link rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin>
