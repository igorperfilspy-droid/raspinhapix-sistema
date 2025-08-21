<?php
session_start();
header('Content-Type: application/json');

// Log de debug
error_log('Payment Debug - Início da requisição');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

sleep(2);

$amount = isset($_POST['amount']) ? floatval(str_replace(',', '.', $_POST['amount'])) : 0;
$cpf = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : '';

error_log('Payment Debug - Amount: ' . $amount . ', CPF: ' . $cpf);

if ($amount <= 0 || strlen($cpf) !== 11) {
    error_log('Payment Debug - Dados inválidos');
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../classes/RushPay.php';

try {
    // Verificar autenticação do usuário
    if (!isset($_SESSION['usuario_id'])) {
        error_log('Payment Debug - Usuário não autenticado');
        throw new Exception('Usuário não autenticado.');
    }

    $usuario_id = $_SESSION['usuario_id'];
    error_log('Payment Debug - Usuario ID: ' . $usuario_id);

    // Buscar dados do usuário
    $stmt = $pdo->prepare("SELECT nome, email, telefone FROM usuarios WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if (!$usuario) {
        error_log('Payment Debug - Usuário não encontrado');
        throw new Exception('Usuário não encontrado.');
    }

    error_log('Payment Debug - Usuario encontrado: ' . $usuario['nome']);

    // Configurar URLs base
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $base = $protocol . $host;

    $external_id = uniqid();
    $idempotencyKey = uniqid() . '-' . time();

    // Capturar utm_source da sessão
    $utmSource = $_SESSION['utm_source'] ?? null;
    error_log("Payment Debug - UTM Source capturado: " . ($utmSource ?? "NULL"));

    error_log('Payment Debug - Iniciando RushPay');

    // Processar com RushPay
    $rushPay = new RushPay($pdo);
    
$callbackUrl = 'https://api.xtracky.com/api/integrations/rushpay';
    
    // Preparar telefone no formato correto
    $phone = $usuario['telefone'] ?? '11999999999';
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) === 11) {
        $phone = substr($phone, 2); // Remove código do país se presente
    } elseif (strlen($phone) === 10) {
        // Telefone já no formato correto
    } else {
        $phone = '11999999999'; // Telefone padrão se inválido
    }

    error_log('Payment Debug - Telefone formatado: ' . $phone);

    $depositData = $rushPay->createDeposit(
        $amount,
        $cpf,
        $usuario['nome'],
        $usuario['email'],
        $phone,
        $callbackUrl,
        $idempotencyKey,
        $utmSource
    );

    error_log('Payment Debug - RushPay retornou: ' . json_encode($depositData));

    // Salvar no banco
    $stmt = $pdo->prepare("
        INSERT INTO depositos (transactionId, user_id, nome, cpf, valor, status, qrcode, gateway, idempotency_key)
        VALUES (:transactionId, :user_id, :nome, :cpf, :valor, 'PENDING', :qrcode, 'rushpay', :idempotency_key)
    ");

    $stmt->execute([
        ':transactionId' => $depositData['transactionId'],
        ':user_id' => $usuario_id,
        ':nome' => $usuario['nome'],
        ':cpf' => $cpf,
        ':valor' => $amount,
        ':qrcode' => $depositData['qrcode'],
        ':idempotency_key' => $depositData['idempotencyKey']
    ]);

    error_log('Payment Debug - Salvo no banco com sucesso');

    // XTracky será notificado via webhook RushPay -> xTracky (não duplicar aqui)


    $_SESSION['transactionId'] = $depositData['transactionId'];

    echo json_encode([
        'qrcode' => $depositData['pixCode'], // Usar pixCode como qrcode para compatibilidade
        'pixCode' => $depositData['pixCode'],
        'gateway' => 'rushpay',
        'transactionId' => $depositData['transactionId'],
        'expiresAt' => $depositData['expiresAt'],
        'debug' => 'Sucesso!'
    ]);

} catch (Exception $e) {
    error_log('Payment Debug - Erro: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage(), 'debug' => 'Erro capturado']);
    exit;
}
?>

