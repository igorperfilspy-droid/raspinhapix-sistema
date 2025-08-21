<?php
header('Content-Type:<?php application/json');

require_once __DIR__ .<?php '/../conexao.php';
require_once __DIR__ .<?php '/../classes/RushPay.php';

try {
<?php //<?php Receber dados do webhook $input =<?php file_get_contents('php://input');
<?php $webhookData =<?php json_decode($input,<?php true);

<?php if (!$webhookData)<?php {
<?php throw new Exception('Dados do webhook inválidos');
<?php }

<?php //<?php Log do webhook para debug error_log('RushPay Webhook:<?php '<?php .<?php $input);

<?php $rushPay =<?php new RushPay($pdo);
<?php $processedData =<?php $rushPay->processWebhook($webhookData);

<?php $transactionId =<?php $processedData['transactionId'];
<?php $status =<?php $processedData['status'];

<?php //<?php Buscar depósito no banco $stmt =<?php $pdo->prepare("SELECT id,<?php user_id,<?php valor,<?php status FROM depositos WHERE transactionId =<?php :transactionId AND gateway =<?php 'rushpay'<?php LIMIT 1");
<?php $stmt->bindParam(':transactionId',<?php $transactionId);
<?php $stmt->execute();
<?php $deposito =<?php $stmt->fetch();

<?php if (!$deposito)<?php {
<?php throw new Exception('Depósito não encontrado');
<?php }

<?php //<?php Se já<?php foi processado,<?php retornar sucesso if ($deposito['status']<?php ===<?php 'PAID')<?php {
<?php echo json_encode(['status'<?php =><?php 'already_processed']);
<?php exit;
<?php }

<?php //<?php Se o pagamento foi aprovado if ($status ===<?php 'PAID')<?php {
<?php $pdo->beginTransaction();

<?php try {
<?php //<?php Atualizar status do depósito $stmt =<?php $pdo->prepare("UPDATE depositos SET status =<?php 'PAID',<?php updated_at =<?php NOW()<?php WHERE id =<?php :id");
<?php $stmt->bindParam(':id',<?php $deposito['id'],<?php PDO::PARAM_INT);
<?php $stmt->execute();

<?php //<?php Creditar saldo ao usuário $stmt =<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php saldo +<?php :valor WHERE id =<?php :id");
<?php $stmt->bindParam(':valor',<?php $deposito['valor']);
<?php $stmt->bindParam(':id',<?php $deposito['user_id'],<?php PDO::PARAM_INT);
<?php $stmt->execute();

<?php //<?php Registrar transação $stmt =<?php $pdo->prepare("INSERT INTO transacoes (user_id,<?php tipo,<?php valor,<?php descricao)<?php VALUES (:user_id,<?php 'DEPOSIT',<?php :valor,<?php :descricao)");
<?php $stmt->execute([
<?php ':user_id'<?php =><?php $deposito['user_id'],
<?php ':valor'<?php =><?php $deposito['valor'],
<?php ':descricao'<?php =><?php 'Depósito PIX RushPay -<?php '<?php .<?php $transactionId ]);

<?php $pdo->commit();

<?php echo json_encode(['status'<?php =><?php 'processed',<?php 'message'<?php =><?php 'Pagamento processado com sucesso']);

<?php }<?php catch (Exception $e)<?php {
<?php $pdo->rollBack();
<?php throw $e;
<?php }

<?php }<?php else {
<?php //<?php Atualizar status para outros casos (cancelado,<?php etc)
<?php $stmt =<?php $pdo->prepare("UPDATE depositos SET status =<?php :status,<?php updated_at =<?php NOW()<?php WHERE id =<?php :id");
<?php $stmt->bindParam(':status',<?php $status);
<?php $stmt->bindParam(':id',<?php $deposito['id'],<?php PDO::PARAM_INT);
<?php $stmt->execute();

<?php echo json_encode(['status'<?php =><?php 'updated',<?php 'new_status'<?php =><?php $status]);
<?php }

}<?php catch (Exception $e)<?php {
<?php error_log('RushPay Webhook Error:<?php '<?php .<?php $e->getMessage());
<?php http_response_code(500);
<?php echo json_encode(['error'<?php =><?php $e->getMessage()]);
}
?>

