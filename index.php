include<?php '../includes/session.php';
include<?php '../conexao.php';
include<?php '../includes/notiflix.php';

$usuarioId<?php =<?php $_SESSION['usuario_id'];
$admin<?php =<?php ($stmt<?php =<?php $pdo->prepare("SELECT<?php admin<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?"))->execute([$usuarioId])<?php ?<?php $stmt->fetchColumn()<?php :<?php null;

if(<?php $admin<?php !=<?php 1){
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php não<?php é<?php um<?php administrador!'];
