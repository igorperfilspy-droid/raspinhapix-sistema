<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../classes/RushPay.php';

try {
    // Receber dados do webhook
    $input = file_get_contents('php://input');
    $webhookData = json_decode($input, true);

    if (!$webhookData) {
        throw new Exception('Dados do webhook inválidos');
    }

    // Log do webhook para debug
    error_log('RushPay Webhook: ' . $input);

    $rushPay = new RushPay($pdo);
    $processedData = $rushPay->processWebhook($webhookData);

    $transactionId = $processedData['transactionId'];
    $status = $processedData['status'];

    // Buscar depósito no banco
    $stmt = $pdo->prepare("SELECT id, user_id, valor, status FROM depositos WHERE transactionId = :transactionId AND gateway = 'rushpay' LIMIT 1");
    $stmt->bindParam(':transactionId', $transactionId);
    $stmt->execute();
    $deposito = $stmt->fetch();

    if (!$deposito) {
        throw new Exception('Depósito não encontrado');
    }

    // Se já foi processado, retornar sucesso
    if ($deposito['status'] === 'PAID') {
        echo json_encode(['status' => 'already_processed']);
        exit;
    }

    // Se o pagamento foi aprovado
    if ($status === 'PAID') {
        $pdo->beginTransaction();

        try {
            // Atualizar status do depósito
            $stmt = $pdo->prepare("UPDATE depositos SET status = 'PAID', updated_at = NOW() WHERE id = :id");
            $stmt->bindParam(':id', $deposito['id'], PDO::PARAM_INT);
            $stmt->execute();

            // Creditar saldo ao usuário
            $stmt = $pdo->prepare("UPDATE usuarios SET saldo = saldo + :valor WHERE id = :id");
            $stmt->bindParam(':valor', $deposito['valor']);
            $stmt->bindParam(':id', $deposito['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            // Registrar transação
            $stmt = $pdo->prepare("INSERT INTO transacoes (user_id, tipo, valor, descricao) VALUES (:user_id, 'DEPOSIT', :valor, :descricao)");
            $stmt->execute([
                ':user_id' => $deposito['user_id'],
                ':valor' => $deposito['valor'],
                ':descricao' => 'Depósito PIX RushPay - ' . $transactionId
            ]);

            $pdo->commit();

            echo json_encode(['status' => 'processed', 'message' => 'Pagamento processado com sucesso']);

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }

    } else {
        // Atualizar status para outros casos (cancelado, etc)
        $stmt = $pdo->prepare("UPDATE depositos SET status = :status, updated_at = NOW() WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $deposito['id'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'updated', 'new_status' => $status]);
    }

} catch (Exception $e) {
    error_log('RushPay Webhook Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

