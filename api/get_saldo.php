<?php @session_start();<?php 
require_once '../conexao.php';<?php header('Content-Type:<?php application/json');<?php $userId =<?php $_SESSION['usuario_id']<?php ??<?php 0;<?php if (!$userId)<?php {<?php http_response_code(401);<?php echo json_encode(['error'<?php =><?php 'Usuário não autenticado']);<?php exit;<?php 
}<?php $stmt =<?php $pdo->prepare("SELECT saldo FROM usuarios WHERE id =<?php ?");<?php 
$stmt->execute([$userId]);<?php $saldo =<?php $stmt->fetchColumn();<?php if ($saldo ===<?php false)<?php {<?php http_response_code(404);<?php echo json_encode(['error'<?php =><?php 'Usuário não encontrado']);<?php exit;<?php 
}<?php echo json_encode([<?php 'success'<?php =><?php true,<?php 'saldo'<?php =><?php (float)$saldo,<?php 
]);<?php 
