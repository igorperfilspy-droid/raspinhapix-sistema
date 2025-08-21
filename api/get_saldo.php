<?php<?php 
@session_start();<?php 
require_once<?php '../conexao.php';<?php 
<?php 
header('Content-Type:<?php application/json');<?php 
<?php 
$userId<?php =<?php $_SESSION['usuario_id']<?php ??<?php 0;<?php 
<?php 
if<?php (!$userId)<?php {<?php 
<?php http_response_code(401);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Usuário<?php não<?php autenticado']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$stmt<?php =<?php $pdo->prepare("SELECT<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");<?php 
$stmt->execute([$userId]);<?php 
<?php 
$saldo<?php =<?php $stmt->fetchColumn();<?php 
<?php 
if<?php ($saldo<?php ===<?php false)<?php {<?php 
<?php http_response_code(404);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Usuário<?php não<?php encontrado']);<?php 
<?php exit;<?php 
}<?php 
<?php 
echo<?php json_encode([<?php 
<?php 'success'<?php =><?php true,<?php 
<?php 'saldo'<?php =><?php (float)$saldo,<?php 
]);<?php 
