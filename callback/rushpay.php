<?php
header('Content-Type:<?php application/json');

require_once<?php __DIR__<?php .<?php '/../conexao.php';
require_once<?php __DIR__<?php .<?php '/../classes/RushPay.php';

try<?php {
<?php //<?php Receber<?php dados<?php do<?php webhook
<?php $input<?php =<?php file_get_contents('php://input');
<?php $webhookData<?php =<?php json_decode($input,<?php true);

<?php if<?php (!$webhookData)<?php {
<?php throw<?php new<?php Exception('Dados<?php do<?php webhook<?php inválidos');
<?php }

<?php //<?php Log<?php do<?php webhook<?php para<?php debug
<?php error_log('RushPay<?php Webhook:<?php '<?php .<?php $input);

<?php $rushPay<?php =<?php new<?php RushPay($pdo);
<?php $processedData<?php =<?php $rushPay->processWebhook($webhookData);

<?php $transactionId<?php =<?php $processedData['transactionId'];
<?php $status<?php =<?php $processedData['status'];

<?php //<?php Buscar<?php depósito<?php no<?php banco
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php user_id,<?php valor,<?php status<?php FROM<?php depositos<?php WHERE<?php transactionId<?php =<?php :transactionId<?php AND<?php gateway<?php =<?php 'rushpay'<?php LIMIT<?php 1");
<?php $stmt->bindParam(':transactionId',<?php $transactionId);
<?php $stmt->execute();
<?php $deposito<?php =<?php $stmt->fetch();

<?php if<?php (!$deposito)<?php {
<?php throw<?php new<?php Exception('Depósito<?php não<?php encontrado');
<?php }

<?php //<?php Se<?php já<?php foi<?php processado,<?php retornar<?php sucesso
<?php if<?php ($deposito['status']<?php ===<?php 'PAID')<?php {
<?php echo<?php json_encode(['status'<?php =><?php 'already_processed']);
<?php exit;
<?php }

<?php //<?php Se<?php o<?php pagamento<?php foi<?php aprovado
<?php if<?php ($status<?php ===<?php 'PAID')<?php {
<?php $pdo->beginTransaction();

<?php try<?php {
<?php //<?php Atualizar<?php status<?php do<?php depósito
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php depositos<?php SET<?php status<?php =<?php 'PAID',<?php updated_at<?php =<?php NOW()<?php WHERE<?php id<?php =<?php :id");
<?php $stmt->bindParam(':id',<?php $deposito['id'],<?php PDO::PARAM_INT);
<?php $stmt->execute();

<?php //<?php Creditar<?php saldo<?php ao<?php usuário
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php +<?php :valor<?php WHERE<?php id<?php =<?php :id");
<?php $stmt->bindParam(':valor',<?php $deposito['valor']);
<?php $stmt->bindParam(':id',<?php $deposito['user_id'],<?php PDO::PARAM_INT);
<?php $stmt->execute();

<?php //<?php Registrar<?php transação
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php transacoes<?php (user_id,<?php tipo,<?php valor,<?php descricao)<?php VALUES<?php (:user_id,<?php 'DEPOSIT',<?php :valor,<?php :descricao)");
<?php $stmt->execute([
<?php ':user_id'<?php =><?php $deposito['user_id'],
<?php ':valor'<?php =><?php $deposito['valor'],
<?php ':descricao'<?php =><?php 'Depósito<?php PIX<?php RushPay<?php -<?php '<?php .<?php $transactionId
<?php ]);

<?php $pdo->commit();

<?php echo<?php json_encode(['status'<?php =><?php 'processed',<?php 'message'<?php =><?php 'Pagamento<?php processado<?php com<?php sucesso']);

<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php $pdo->rollBack();
<?php throw<?php $e;
<?php }

<?php }<?php else<?php {
<?php //<?php Atualizar<?php status<?php para<?php outros<?php casos<?php (cancelado,<?php etc)
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php depositos<?php SET<?php status<?php =<?php :status,<?php updated_at<?php =<?php NOW()<?php WHERE<?php id<?php =<?php :id");
<?php $stmt->bindParam(':status',<?php $status);
<?php $stmt->bindParam(':id',<?php $deposito['id'],<?php PDO::PARAM_INT);
<?php $stmt->execute();

<?php echo<?php json_encode(['status'<?php =><?php 'updated',<?php 'new_status'<?php =><?php $status]);
<?php }

}<?php catch<?php (Exception<?php $e)<?php {
<?php error_log('RushPay<?php Webhook<?php Error:<?php '<?php .<?php $e->getMessage());
<?php http_response_code(500);
<?php echo<?php json_encode(['error'<?php =><?php $e->getMessage()]);
}
?>

