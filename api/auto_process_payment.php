<?php
session_start();
header('Content-Type:<?php application/json');

require_once __DIR__ .<?php '/../conexao.php';
require_once __DIR__ .<?php '/../classes/RushPay.php';

if ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo json_encode(['error'<?php =><?php 'Método não permitido']);
<?php exit;
}

$transactionId =<?php $_POST['transactionId']<?php ??<?php '';

if (empty($transactionId))<?php {
<?php http_response_code(400);
<?php echo json_encode(['error'<?php =><?php 'Transaction ID obrigatório']);
<?php exit;
}

try {
<?php //<?php Buscar depósito $stmt =<?php $pdo->prepare("SELECT *<?php FROM depositos WHERE transactionId =<?php :transactionId AND status =<?php 'PENDING'");
<?php $stmt->bindParam(':transactionId',<?php $transactionId);
<?php $stmt->execute();
<?php $deposit =<?php $stmt->fetch();

<?php if (!$deposit)<?php {
<?php echo json_encode(['isPaid'<?php =><?php false,<?php 'message'<?php =><?php 'Depósito não encontrado ou já<?php processado']);
<?php exit;
<?php }

<?php //<?php Verificar status na RushPay $rushPay =<?php new RushPay($pdo);
<?php $status =<?php $rushPay->checkPaymentStatus($transactionId);

<?php if ($status &&<?php $status['status']<?php ===<?php 'APPROVED')<?php {
<?php $pdo->beginTransaction();

<?php try {
<?php //<?php Atualizar status do depósito $updateStmt =<?php $pdo->prepare("
<?php UPDATE depositos <?php SET status =<?php 'PAID',<?php webhook_data =<?php :webhook_data,<?php updated_at =<?php NOW()
<?php WHERE transactionId =<?php :transactionId ");
<?php $updateStmt->execute([
<?php ':webhook_data'<?php =><?php json_encode(['auto_processed'<?php =><?php true,<?php 'processed_at'<?php =><?php date('Y-m-d H:i:s'),<?php 'rushpay_data'<?php =><?php $status]),
<?php ':transactionId'<?php =><?php $transactionId ]);

<?php //<?php Creditar saldo do usuário $creditStmt =<?php $pdo->prepare("
<?php UPDATE usuarios <?php SET saldo =<?php saldo +<?php :valor <?php WHERE id =<?php :user_id ");
<?php $creditStmt->execute([
<?php ':valor'<?php =><?php $deposit['valor'],
<?php ':user_id'<?php =><?php $deposit['user_id']
<?php ]);

<?php $pdo->commit();

<?php //<?php XTracky será<?php notificado via webhook RushPay -><?php xTracky (não duplicar aqui)


<?php echo json_encode([
<?php 'isPaid'<?php =><?php true,<?php 'message'<?php =><?php 'Pagamento aprovado!',
<?php 'amount'<?php =><?php $deposit['valor']
<?php ]);

<?php }<?php catch (Exception $e)<?php {
<?php $pdo->rollBack();
<?php throw $e;
<?php }

<?php }<?php else {
<?php echo json_encode([
<?php 'isPaid'<?php =><?php false,<?php 'message'<?php =><?php 'Aguardando confirmação do pagamento...',
<?php 'status'<?php =><?php $status['status']<?php ??<?php 'PENDING'
<?php ]);
<?php }

}<?php catch (Exception $e)<?php {
<?php http_response_code(500);
<?php echo json_encode([
<?php 'isPaid'<?php =><?php false,
<?php 'error'<?php =><?php $e->getMessage()
<?php ]);
}
?>

