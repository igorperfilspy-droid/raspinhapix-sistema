<?php
// Debug (pode desligar depois)
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);

// Boot básico
require __DIR__ . '/includes/session.php';
require __DIR__ . '/conexao.php';
require __DIR__ . '/includes/notiflix.php';

// Autenticação / checagem de admin (opcional, mantenho como estava)
$usuarioId = $_SESSION['usuario_id'] ?? null;
$admin = null;
if ($usuarioId) {
    $stmt = $pdo->prepare('SELECT admin FROM usuarios WHERE id = ?');
    $stmt->execute([$usuarioId]);
    $admin = $stmt->fetchColumn();
}
if ($admin != 1) {
    $_SESSION['message'] = ['type' => 'warning', 'text' => 'Você não é um administrador!'];
    // Se quiser redirecionar não-admins, descomente:
    // header('Location: /login'); exit;
}

// === Escolhe a view real da home ===
// Ajuste a ordem conforme seu projeto.
$possiveisViews = [
    __DIR__ . '/page/index.php',
    __DIR__ . '/index2.php',
    __DIR__ . '/home.php',
];

// Inclui a primeira view existente
$incluiu = false;
foreach ($possiveisViews as $view) {
    if (is_file($view)) {
        require $view;
        $incluiu = true;
        break;
    }
}

// Se nada foi encontrado, mostra mensagem clara
if (!$incluiu) {
    http_response_code(500);
    echo "<!doctype html><html lang='pt-br'><meta charset='utf-8'><title>Erro</title>
          <body style='font-family:system-ui;padding:24px'>
            <h2>View inicial não encontrada</h2>
            <p>Procurei por:</p>
            <ul>" . implode('', array_map(fn($v)=>"<li>{$v}</li>", $possiveisViews)) . "</ul>
            <p>Ajuste a lista no <code>index.php</code> para apontar para o arquivo correto da sua home.</p>
          </body></html>";
}
