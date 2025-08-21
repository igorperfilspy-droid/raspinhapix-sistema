<?php
require __DIR__ . '/includes/session.php';
require __DIR__ . '/conexao.php';
require __DIR__ . '/includes/notiflix.php';

// pega o usuário logado (se houver)
$usuarioId = $_SESSION['usuario_id'] ?? null;

// consulta flag de admin com segurança
$admin = null;
if ($usuarioId) {
    $stmt = $pdo->prepare('SELECT admin FROM usuarios WHERE id = ?');
    $stmt->execute([$usuarioId]);
    $admin = $stmt->fetchColumn();
}

// regra de acesso (ajuste o destino conforme seu fluxo)
if ($admin != 1) {
    $_SESSION['message'] = ['type' => 'warning', 'text' => 'Você não é um administrador!'];
    // header('Location: /login'); exit; // se quiser redirecionar
}

// …aqui segue o restante do seu HTML/PHP da página inicial…
