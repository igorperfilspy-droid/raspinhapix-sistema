<?php
// Inicia sessão com defaults seguros
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    // SameSite Lax é um bom default para apps comuns
    ini_set('session.cookie_samesite', 'Lax');
    // Cookie Secure só quando HTTPS estiver ativo
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// Mensagem flash opcional
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = null;
}
