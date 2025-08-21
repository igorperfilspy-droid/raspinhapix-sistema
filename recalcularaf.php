function<?php recalcularafiliados(PDO<?php $pdo)<?php 
{<?php 
<?php $padrao<?php =<?php (float)$pdo->query("SELECT<?php revshare_padrao<?php FROM<?php config<?php LIMIT<?php 1")->fetchColumn();<?php 
<?php 
<?php $stmt<?php =<?php $pdo->query("<?php 
<?php SELECT<?php 
<?php o.user_id,<?php 
<?php o.resultado,<?php 
<?php r.valor<?php AS<?php custo,<?php 
<?php u.indicacao,<?php 
<?php a.id<?php AS<?php afiliado_id,<?php 
<?php a.comissao_revshare<?php 
<?php FROM<?php orders<?php o<?php 
<?php JOIN<?php raspadinhas<?php r<?php ON<?php r.id<?php =<?php o.raspadinha_id<?php 
<?php JOIN<?php usuarios<?php u<?php ON<?php u.id<?php =<?php o.user_id<?php 
<?php JOIN<?php usuarios<?php a<?php ON<?php a.id<?php =<?php u.indicacao<?php 
<?php WHERE<?php o.status<?php =<?php 1<?php AND<?php a.influencer<?php =<?php 1<?php 
<?php ");<?php 
<?php 
<?php $dados<?php =<?php [];<?php 
<?php 
<?php while<?php ($row<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC))<?php {<?php 
<?php $id<?php =<?php (int)$row['afiliado_id'];<?php 
<?php $pct<?php =<?php (float)$row['comissao_revshare']<?php ?:<?php $padrao;<?php 
<?php $custo<?php =<?php (float)$row['custo'];<?php 
<?php $perda<?php =<?php $row['resultado']<?php ===<?php 'gain'<?php ?<?php -$custo<?php :<?php $custo;<?php 
<?php $comissao<?php =<?php ($perda<?php *<?php $pct)<?php /<?php 100;<?php 
<?php 
<?php if<?php (!isset($dados[$id]))<?php $dados[$id]<?php =<?php 0;<?php 
<?php $dados[$id]<?php +=<?php $comissao;<?php 
<?php }<?php 
<?php 
<?php if<?php (!empty($dados))<?php {<?php 
<?php $ids<?php =<?php implode(',',<?php array_keys($dados));<?php 
<?php $pdo->exec("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php 0<?php WHERE<?php influencer<?php =<?php 1<?php AND<?php id<?php IN<?php ($ids)");<?php 
<?php }<?php 
<?php 
<?php $up<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php ?<?php WHERE<?php id<?php =<?php ?");<?php 
<?php foreach<?php ($dados<?php as<?php $id<?php =><?php $valor)<?php {<?php 
<?php $up->execute([round($valor,<?php 2),<?php $id]);<?php 
<?php }<?php 
<?php 
<?php echo<?php "finalizado";<?php 
}<?php 
