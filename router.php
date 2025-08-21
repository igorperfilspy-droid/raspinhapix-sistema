<?php
// Exibir erros durante o debug
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Descobre o path da requisição
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$fullPath = __DIR__ . $path;

// Se o path existir como arquivo físico (ex: CSS, JS, imagens), entrega direto
if ($path !== "/" && file_exists($fullPath)) {
    return false; // deixa o servidor embutido do PHP servir o arquivo
}

// Caso contrário, sempre carrega o index.php
require __DIR__ . "/index.php";
