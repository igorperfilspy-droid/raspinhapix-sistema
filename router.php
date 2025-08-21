<?php
// Router para o servidor embutido do PHP: serve estáticos e manda o resto pro index.php
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;
    if ($path !== '/' && file_exists($fullPath) && is_file($fullPath)) {
        return false; // deixa o server embutido servir o arquivo estático real
    }
}
require __DIR__ . '/index.php';
