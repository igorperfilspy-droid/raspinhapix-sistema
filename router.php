<?php
// DEBUG TEMPORÁRIO (remova depois que tudo estiver OK)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/**
 * Roteador para `php -S`: se for arquivo real (css/js/img etc.), deixa o
 * servidor embutido servir direto; caso contrário inclui `index.php`.
 */
if (php_sapi_name() === 'cli-server') {
    $path     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;

    if ($path !== '/' && file_exists($fullPath) && !is_dir($fullPath)) {
        return false; // serve arquivo estático
    }
}

// cai no aplicativo
require __DIR__ . '/index.php';
