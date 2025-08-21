@session_start();
include('./conexao.php');
// Capturar UTMs da URL e salvar na sessão
if (isset($_GET["utm_source"])) $_SESSION["utm_source"] = $_GET["utm_source"];
if (isset($_GET["utm_medium"])) $_SESSION["utm_medium"] = $_GET["utm_medium"];
if (isset($_GET["utm_campaign"])) $_SESSION["utm_campaign"] = $_GET["utm_campaign"];
if (isset($_GET["utm_term"])) $_SESSION["utm_term"] = $_GET["utm_term"];
if (isset($_GET["utm_content"])) $_SESSION["utm_content"] = $_GET["utm_content"];
if (isset($_GET["click_id"])) $_SESSION["click_id"] = $_GET["click_id"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <!-- xTracky Integration -->
    <script 
        src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
        data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
        data-click-id-param="click_id">
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nomeSite;?> - Raspadinhas Online</title>
    <meta name="description" content="Raspe e ganhe prêmios incríveis! PIX na conta instantâneo.">
    
    <!-- Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
