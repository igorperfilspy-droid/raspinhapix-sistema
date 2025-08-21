<?php
session_start();

// Capturar UTMs da URL
if (isset($_GET['utm_source'])) $_SESSION['utm_source'] = $_GET['utm_source'];
if (isset($_GET['utm_medium'])) $_SESSION['utm_medium'] = $_GET['utm_medium'];
if (isset($_GET['utm_campaign'])) $_SESSION['utm_campaign'] = $_GET['utm_campaign'];
if (isset($_GET['utm_term'])) $_SESSION['utm_term'] = $_GET['utm_term'];
if (isset($_GET['utm_content'])) $_SESSION['utm_content'] = $_GET['utm_content'];
if (isset($_GET['click_id'])) $_SESSION['click_id'] = $_GET['click_id'];

echo "<h1>Teste de UTMs</h1>";
echo "<h2>UTMs da URL atual:</h2>";
echo "utm_source: " . ($_GET['utm_source'] ?? 'N/A') . "<br>";
echo "utm_medium: " . ($_GET['utm_medium'] ?? 'N/A') . "<br>";
echo "utm_campaign: " . ($_GET['utm_campaign'] ?? 'N/A') . "<br>";
echo "utm_term: " . ($_GET['utm_term'] ?? 'N/A') . "<br>";
echo "utm_content: " . ($_GET['utm_content'] ?? 'N/A') . "<br>";
echo "click_id: " . ($_GET['click_id'] ?? 'N/A') . "<br>";

echo "<h2>UTMs salvos na sessão:</h2>";
echo "utm_source: " . ($_SESSION['utm_source'] ?? 'N/A') . "<br>";
echo "utm_medium: " . ($_SESSION['utm_medium'] ?? 'N/A') . "<br>";
echo "utm_campaign: " . ($_SESSION['utm_campaign'] ?? 'N/A') . "<br>";
echo "utm_term: " . ($_SESSION['utm_term'] ?? 'N/A') . "<br>";
echo "utm_content: " . ($_SESSION['utm_content'] ?? 'N/A') . "<br>";
echo "click_id: " . ($_SESSION['click_id'] ?? 'N/A') . "<br>";
?>

