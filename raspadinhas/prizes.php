<?php<?php 
require_once<?php '../conexao.php';<?php 
header('Content-Type:<?php application/json');<?php 
<?php 
$ids<?php =<?php array_map('intval',<?php explode(',',<?php $_GET['ids']<?php ??<?php ''));<?php 
if<?php (!$ids)<?php {<?php 
<?php echo<?php '[]';<?php 
<?php exit;<?php 
}<?php 
<?php 
$placeholders<?php =<?php implode(',',<?php array_fill(0,<?php count($ids),<?php '?'));<?php 
$stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php nome,<?php icone,<?php valor<?php FROM<?php raspadinha_premios<?php WHERE<?php id<?php IN<?php ($placeholders)");<?php 
$stmt->execute($ids);<?php 
$premios<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);<?php 
<?php 
$mapa<?php =<?php [];<?php 
foreach<?php ($premios<?php as<?php $premio)<?php {<?php 
<?php $mapa[$premio['id']]<?php =<?php $premio;<?php 
}<?php 
<?php 
$resultado<?php =<?php [];<?php 
foreach<?php ($ids<?php as<?php $id)<?php {<?php 
<?php if<?php (isset($mapa[$id]))<?php {<?php 
<?php $resultado[]<?php =<?php $mapa[$id];<?php 
<?php }<?php 
}<?php 
<?php 
echo<?php json_encode($resultado);