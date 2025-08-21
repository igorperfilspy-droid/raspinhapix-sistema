<?php
session_start();
header('Content-Type:<?php application/json');

require_once<?php __DIR__<?php .<?php '/../conexao.php';
require_once<?php __DIR__<?php .<?php '/../classes/RushPay.php';

if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo<?php json_encode(['error'<?php =><?php 'Método<?php não<?php permitido']);
<?php exit;
}

$transactionId<?php =<?php $_POST['transactionId']<?php ??<?php '';

if<?php (empty($transactionId))<?php {
<?php http_response_code(400);
<?php echo<?php json_encode(['error'<?php =><?php 'Transaction<?php ID<?php obrigatório']);
<?php exit;
}

try<?php {
<?php //<?php Buscar<?php depósito
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php *<?php FROM<?php depositos<?php WHERE<?php transactionId<?php =<?php :transactionId<?php AND<?php status<?php =<?php 'PENDING'");
<?php $stmt->bindParam(':transactionId',<?php $transactionId);
<?php $stmt->execute();
<?php $deposit<?php =<?php $stmt->fetch();

<?php if<?php (!$deposit)<?php {
<?php echo<?php json_encode(['isPaid'<?php =><?php false,<?php 'message'<?php =><?php 'Depósito<?php não<?php encontrado<?php ou<?php já<?php processado']);
<?php exit;
<?php }

<?php //<?php Verificar<?php status<?php na<?php RushPay
<?php $rushPay<?php =<?php new<?php RushPay($pdo);
<?php $status<?php =<?php $rushPay->checkPaymentStatus($transactionId);

<?php if<?php ($status<?php &&<?php $status['status']<?php ===<?php 'APPROVED')<?php {
<?php $pdo->beginTransaction();

<?php try<?php {
<?php //<?php Atualizar<?php status<?php do<?php depósito
<?php $updateStmt<?php =<?php $pdo->prepare("
<?php UPDATE<?php depositos<?php 
<?php SET<?php status<?php =<?php 'PAID',<?php webhook_data<?php =<?php :webhook_data,<?php updated_at<?php =<?php NOW()
<?php WHERE<?php transactionId<?php =<?php :transactionId
<?php ");
<?php $updateStmt->execute([
<?php ':webhook_data'<?php =><?php json_encode(['auto_processed'<?php =><?php true,<?php 'processed_at'<?php =><?php date('Y-m-d<?php H:i:s'),<?php 'rushpay_data'<?php =><?php $status]),
<?php ':transactionId'<?php =><?php $transactionId
<?php ]);

<?php //<?php Creditar<?php saldo<?php do<?php usuário
<?php $creditStmt<?php =<?php $pdo->prepare("
<?php UPDATE<?php usuarios<?php 
<?php SET<?php saldo<?php =<?php saldo<?php +<?php :valor<?php 
<?php WHERE<?php id<?php =<?php :user_id
<?php ");
<?php $creditStmt->execute([
<?php ':valor'<?php =><?php $deposit['valor'],
<?php ':user_id'<?php =><?php $deposit['user_id']
<?php ]);

<?php $pdo->commit();

<?php //<?php XTracky<?php será<?php notificado<?php via<?php webhook<?php RushPay<?php -><?php xTracky<?php (não<?php duplicar<?php aqui)


<?php echo<?php json_encode([
<?php 'isPaid'<?php =><?php true,<?php 
<?php 'message'<?php =><?php 'Pagamento<?php aprovado!',
<?php 'amount'<?php =><?php $deposit['valor']
<?php ]);

<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php $pdo->rollBack();
<?php throw<?php $e;
<?php }

<?php }<?php else<?php {
<?php echo<?php json_encode([
<?php 'isPaid'<?php =><?php false,<?php 
<?php 'message'<?php =><?php 'Aguardando<?php confirmação<?php do<?php pagamento...',
<?php 'status'<?php =><?php $status['status']<?php ??<?php 'PENDING'
<?php ]);
<?php }

}<?php catch<?php (Exception<?php $e)<?php {
<?php http_response_code(500);
<?php echo<?php json_encode([
<?php 'isPaid'<?php =><?php false,
<?php 'error'<?php =><?php $e->getMessage()
<?php ]);
}
?>

