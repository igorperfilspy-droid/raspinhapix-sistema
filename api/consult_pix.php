<?php<?php 
session_start();<?php 
header('Content-Type:<?php application/json');<?php 
<?php 
if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {<?php 
<?php http_response_code(405);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Método<?php não<?php permitido']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$qrcode<?php =<?php $_POST['qrcode']<?php ??<?php '';<?php 
<?php 
if<?php (empty($qrcode))<?php {<?php 
<?php http_response_code(400);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Parâmetro<?php qrcode<?php ausente']);<?php 
<?php exit;<?php 
}<?php 
<?php 
require_once<?php __DIR__<?php .<?php '/../conexao.php';<?php 
<?php 
try<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php status<?php FROM<?php depositos<?php WHERE<?php qrcode<?php =<?php :qrcode<?php LIMIT<?php 1");<?php 
<?php $stmt->bindParam(':qrcode',<?php $qrcode,<?php PDO::PARAM_STR);<?php 
<?php $stmt->execute();<?php 
<?php 
<?php $row<?php =<?php $stmt->fetch();<?php 
<?php 
<?php if<?php (!$row)<?php {<?php 
<?php echo<?php json_encode(['paid'<?php =><?php false]);<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php $paid<?php =<?php ($row['status']<?php ===<?php 'PAID');<?php 
<?php echo<?php json_encode(['paid'<?php =><?php $paid]);<?php 
}<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php http_response_code(500);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Erro<?php na<?php consulta']);<?php 
<?php exit;<?php 
}