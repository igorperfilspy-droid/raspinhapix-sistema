<?php require_once '../conexao.php';<?php 
header('Content-Type:<?php application/json');<?php $ids =<?php array_map('intval',<?php explode(',',<?php $_GET['ids']<?php ??<?php ''));<?php 
if (!$ids)<?php {<?php echo '[]';<?php exit;<?php 
}<?php $placeholders =<?php implode(',',<?php array_fill(0,<?php count($ids),<?php '?'));<?php 
$stmt =<?php $pdo->prepare("SELECT id,<?php nome,<?php icone,<?php valor FROM raspadinha_premios WHERE id IN ($placeholders)");<?php 
$stmt->execute($ids);<?php 
$premios =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php $mapa =<?php [];<?php 
foreach ($premios as $premio)<?php {<?php $mapa[$premio['id']]<?php =<?php $premio;<?php 
}<?php $resultado =<?php [];<?php 
foreach ($ids as $id)<?php {<?php if (isset($mapa[$id]))<?php {<?php $resultado[]<?php =<?php $mapa[$id];<?php }<?php 
}<?php echo json_encode($resultado);