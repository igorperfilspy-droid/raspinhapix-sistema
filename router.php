<?php
// Roteador p/ php -S: serve arquivos reais e manda o resto para index.php
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = __DIR__ . $uri;

if ($uri !== '/' && file_exists($path) && !is_dir($path)) {
    return false; // deixa o servidor embutido servir o arquivo estático
}

require __DIR__ . '/index.php';
