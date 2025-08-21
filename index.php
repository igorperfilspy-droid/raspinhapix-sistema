<?php
ini_set('display_errors','1'); ini_set('display_startup_errors','1'); error_reporting(E_ALL);

require __DIR__ . '/includes/session.php';
require __DIR__ . '/conexao.php';
require __DIR__ . '/includes/notiflix.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
$admin = null;
if ($usuarioId) {
    $stmt = $pdo->prepare('SELECT admin FROM usuarios WHERE id = ?');
    $stmt->execute([$usuarioId]);
    $admin = $stmt->fetchColumn();
}

if ($admin != 1) {
    $_SESSION['message'] = ['type' => 'warning', 'text' => 'Você não é um administrador!'];
    // header('Location: /login'); exit; // habilite se quiser redirecionar não-admin
}

// TODO: renderize seu HTML/view aqui abaixo
echo "<!doctype html><html lang='pt-br'><meta charset='utf-8'><title>RaspinhaPix</title><body>App carregado</body></html>";
