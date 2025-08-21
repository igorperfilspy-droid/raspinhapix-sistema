<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../classes/RushPay.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$transactionId = $_POST['transactionId'] ?? '';

if (empty($transactionId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Transaction ID obrigatório']);
    exit;
}

try {
    // Buscar depósito
    $stmt = $pdo->prepare("SELECT * FROM depositos WHERE transactionId = :transactionId AND status = 'PENDING'");
    $stmt->bindParam(':transactionId', $transactionId);
    $stmt->execute();
    $deposit = $stmt->fetch();

    if (!$deposit) {
        echo json_encode(['isPaid' => false, 'message' => 'Depósito não encontrado ou já processado']);
        exit;
    }

    // Verificar status na RushPay
    $rushPay = new RushPay($pdo);
    $status = $rushPay->checkPaymentStatus($transactionId);

    if ($status && $status['status'] === 'APPROVED') {
        $pdo->beginTransaction();

        try {
            // Atualizar status do depósito
            $updateStmt = $pdo->prepare("
                UPDATE depositos 
                SET status = 'PAID', webhook_data = :webhook_data, updated_at = NOW()
                WHERE transactionId = :transactionId
            ");
            $updateStmt->execute([
                ':webhook_data' => json_encode(['auto_processed' => true, 'processed_at' => date('Y-m-d H:i:s'), 'rushpay_data' => $status]),
                ':transactionId' => $transactionId
            ]);

            // Creditar saldo do usuário
            $creditStmt = $pdo->prepare("
                UPDATE usuarios 
                SET saldo = saldo + :valor 
                WHERE id = :user_id
            ");
            $creditStmt->execute([
                ':valor' => $deposit['valor'],
                ':user_id' => $deposit['user_id']
            ]);

            $pdo->commit();

            // XTracky será notificado via webhook RushPay -> xTracky (não duplicar aqui)


            echo json_encode([
                'isPaid' => true, 
                'message' => 'Pagamento aprovado!',
                'amount' => $deposit['valor']
            ]);

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }

    } else {
        echo json_encode([
            'isPaid' => false, 
            'message' => 'Aguardando confirmação do pagamento...',
            'status' => $status['status'] ?? 'PENDING'
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'isPaid' => false,
        'error' => $e->getMessage()
    ]);
}
?>

