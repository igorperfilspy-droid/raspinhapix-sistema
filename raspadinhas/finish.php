<?php
@session_start();
require_once<?php '../conexao.php';
header('Content-Type:<?php application/json');

$userId<?php =<?php $_SESSION['usuario_id']<?php ??<?php 0;
$orderId<?php =<?php (int)($_POST['order_id']<?php ??<?php 0);

if<?php (!$userId<?php ||<?php !$orderId)<?php {
<?php http_response_code(400);
<?php exit(json_encode(['error'<?php =><?php 'Dados<?php inválidos']));
}

$stmt<?php =<?php $pdo->prepare("
<?php SELECT<?php o.*,<?php r.valor<?php AS<?php custo_raspadinha
<?php FROM<?php orders<?php o
<?php JOIN<?php raspadinhas<?php r<?php ON<?php r.id<?php =<?php o.raspadinha_id
<?php WHERE<?php o.id<?php =<?php ?<?php AND<?php o.user_id<?php =<?php ?
<?php LIMIT<?php 1
");

$stmt->execute([$orderId,<?php $userId]);
$order<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);

if<?php (!$order<?php ||<?php $order['status']<?php ==<?php 1)<?php {
<?php http_response_code(400);
<?php exit(json_encode(['error'<?php =><?php 'Ordem<?php inválida']));
}

$gridIds<?php =<?php json_decode($order['premios_json'],<?php true);
$contagem<?php =<?php array_count_values($gridIds);

$premioId<?php =<?php null;
$valorPremio<?php =<?php 0.00;
$resultado<?php =<?php 'loss';

//<?php ✅<?php CORREÇÃO:<?php Buscar<?php o<?php MAIOR<?php prêmio<?php ao<?php invés<?php do<?php primeiro
$maiorPremio<?php =<?php 0.00;
$melhorPremioId<?php =<?php null;

foreach<?php ($contagem<?php as<?php $id<?php =><?php $qtd)<?php {
<?php //<?php ✅<?php CORREÇÃO:<?php Aceita<?php 3<?php OU<?php MAIS<?php imagens<?php iguais<?php (>=<?php 3)
<?php if<?php ($qtd<?php >=<?php 3)<?php {
<?php $p<?php =<?php $pdo->prepare("SELECT<?php valor<?php FROM<?php raspadinha_premios<?php WHERE<?php id<?php =<?php ?");
<?php $p->execute([$id]);
<?php $valorEncontrado<?php =<?php (float)$p->fetchColumn();

<?php if<?php ($valorEncontrado<?php ><?php 0)<?php {
<?php //<?php ✅<?php Verifica<?php se<?php este<?php prêmio<?php é<?php maior<?php que<?php o<?php anterior
<?php if<?php ($valorEncontrado<?php ><?php $maiorPremio)<?php {
<?php $maiorPremio<?php =<?php $valorEncontrado;
<?php $melhorPremioId<?php =<?php $id;
<?php }
<?php }
<?php }
}

//<?php ✅<?php Define<?php o<?php resultado<?php baseado<?php no<?php maior<?php prêmio<?php encontrado
if<?php ($maiorPremio<?php ><?php 0)<?php {
<?php $premioId<?php =<?php $melhorPremioId;
<?php $valorPremio<?php =<?php $maiorPremio;
<?php $resultado<?php =<?php 'gain';
}

if<?php ($resultado<?php ===<?php 'gain')<?php {
<?php $valorTotalACreditar<?php =<?php $valorPremio<?php +<?php (float)$order['custo_raspadinha'];

<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php +<?php ?<?php WHERE<?php id<?php =<?php ?")
<?php ->execute([$valorTotalACreditar,<?php $userId]);
<?php 
<?php //<?php ✅<?php Revshare<?php baseado<?php no<?php valor<?php ganho
<?php processarRevshareGanho($pdo,<?php $userId,<?php $valorPremio);
}<?php else<?php {
<?php //<?php ✅<?php Revshare<?php baseado<?php no<?php valor<?php apostado
<?php processarRevsharePerdas($pdo,<?php $userId,<?php (float)$order['custo_raspadinha']);
}

$pdo->prepare("
<?php UPDATE<?php orders
<?php SET<?php status<?php =<?php 1,
<?php resultado<?php =<?php ?,
<?php valor_ganho<?php =<?php ?,
<?php updated_at<?php =<?php NOW()
<?php WHERE<?php id<?php =<?php ?
")->execute([$resultado,<?php $valorPremio,<?php $orderId]);

echo<?php json_encode([
<?php 'success'<?php =><?php true,
<?php 'resultado'<?php =><?php $resultado,
<?php 'valor'<?php =><?php $valorPremio<?php 
]);

/**
<?php *<?php ✅<?php Quando<?php o<?php usuário<?php GANHA,<?php afiliado<?php PERDE<?php (baseado<?php no<?php valor<?php ganho)
<?php */
function<?php processarRevshareGanho($pdo,<?php $userId,<?php $valorGanho)<?php {
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php indicacao<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$userId]);
<?php $usuario<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php 
<?php if<?php (!$usuario<?php ||<?php !$usuario['indicacao'])<?php return<?php false;
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php comissao_revshare,<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$usuario['indicacao']]);
<?php $afiliado<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php 
<?php if<?php (!$afiliado)<?php return<?php false;
<?php 
<?php $percentualRevshare<?php =<?php $afiliado['comissao_revshare'];

<?php if<?php ($percentualRevshare<?php ==<?php 0)<?php {
<?php $stmt<?php =<?php $pdo->query("SELECT<?php revshare_padrao<?php FROM<?php config<?php LIMIT<?php 1");
<?php $percentualRevshare<?php =<?php $stmt->fetchColumn()<?php ?:<?php 0;
<?php }
<?php 
<?php if<?php ($percentualRevshare<?php <=<?php 0)<?php return<?php false;

<?php //<?php ✅<?php Dedução<?php baseada<?php no<?php valor<?php ganho
<?php $valorDeduzir<?php =<?php ($valorGanho<?php *<?php $percentualRevshare)<?php /<?php 100;
<?php if<?php ($valorDeduzir<?php <=<?php 0)<?php return<?php false;

<?php $novoSaldo<?php =<?php $afiliado['saldo']<?php -<?php $valorDeduzir;
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php ?,<?php updated_at<?php =<?php NOW()<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$novoSaldo,<?php $afiliado['id']]);
<?php 
<?php registrarTransacaoRevshare($pdo,<?php $afiliado['id'],<?php $userId,<?php $valorGanho,<?php -$valorDeduzir,<?php $percentualRevshare,<?php 'ganho_usuario');
<?php return<?php true;
<?php 
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php error_log("Erro<?php ao<?php processar<?php revshare<?php ganho:<?php "<?php .<?php $e->getMessage());
<?php return<?php false;
<?php }
}

/**
<?php *<?php ✅<?php Quando<?php o<?php usuário<?php PERDE,<?php afiliado<?php GANHA<?php (baseado<?php no<?php valor<?php apostado)
<?php */
function<?php processarRevsharePerdas($pdo,<?php $userId,<?php $valorPerdido)<?php {
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php indicacao<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$userId]);
<?php $usuario<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php 
<?php if<?php (!$usuario<?php ||<?php !$usuario['indicacao'])<?php return<?php false;

<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php comissao_revshare,<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$usuario['indicacao']]);
<?php $afiliado<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php 
<?php if<?php (!$afiliado)<?php return<?php false;

<?php $percentualRevshare<?php =<?php $afiliado['comissao_revshare'];
<?php if<?php ($percentualRevshare<?php ==<?php 0)<?php {
<?php $stmt<?php =<?php $pdo->query("SELECT<?php revshare_padrao<?php FROM<?php config<?php LIMIT<?php 1");
<?php $percentualRevshare<?php =<?php $stmt->fetchColumn()<?php ?:<?php 0;
<?php }
<?php 
<?php if<?php ($percentualRevshare<?php <=<?php 0)<?php return<?php false;

<?php $comissao<?php =<?php ($valorPerdido<?php *<?php $percentualRevshare)<?php /<?php 100;
<?php if<?php ($comissao<?php <=<?php 0)<?php return<?php false;

<?php $novoSaldo<?php =<?php $afiliado['saldo']<?php +<?php $comissao;
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php ?,<?php updated_at<?php =<?php NOW()<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$novoSaldo,<?php $afiliado['id']]);
<?php 
<?php registrarTransacaoRevshare($pdo,<?php $afiliado['id'],<?php $userId,<?php $valorPerdido,<?php $comissao,<?php $percentualRevshare,<?php 'perda_usuario');
<?php return<?php true;
<?php 
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php error_log("Erro<?php ao<?php processar<?php revshare<?php perda:<?php "<?php .<?php $e->getMessage());
<?php return<?php false;
<?php }
}

/**
<?php *<?php Histórico<?php de<?php transações<?php de<?php revshare
<?php */
function<?php registrarTransacaoRevshare($pdo,<?php $afiliadoId,<?php $usuarioId,<?php $valorBase,<?php $valorRevshare,<?php $percentual,<?php $tipo)<?php {
<?php try<?php {
<?php criarTabelaHistoricoRevshare($pdo);
<?php $stmt<?php =<?php $pdo->prepare("
<?php INSERT<?php INTO<?php historico_revshare<?php 
<?php (afiliado_id,<?php usuario_id,<?php valor_apostado,<?php valor_revshare,<?php percentual,<?php tipo,<?php created_at)<?php 
<?php VALUES<?php (?,<?php ?,<?php ?,<?php ?,<?php ?,<?php ?,<?php NOW())
<?php ");
<?php $stmt->execute([$afiliadoId,<?php $usuarioId,<?php $valorBase,<?php $valorRevshare,<?php $percentual,<?php $tipo]);
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php error_log("Erro<?php ao<?php registrar<?php histórico<?php revshare:<?php "<?php .<?php $e->getMessage());
<?php }
}

/**
<?php *<?php Criação<?php da<?php tabela<?php de<?php histórico<?php se<?php necessário
<?php */
function<?php criarTabelaHistoricoRevshare($pdo)<?php {
<?php try<?php {
<?php $sql<?php =<?php "
<?php CREATE<?php TABLE<?php IF<?php NOT<?php EXISTS<?php `historico_revshare`<?php (
<?php `id`<?php bigint(20)<?php UNSIGNED<?php NOT<?php NULL<?php AUTO_INCREMENT,
<?php `afiliado_id`<?php int(11)<?php NOT<?php NULL,
<?php `usuario_id`<?php int(11)<?php NOT<?php NULL,
<?php `valor_apostado`<?php decimal(10,2)<?php NOT<?php NULL,
<?php `valor_revshare`<?php decimal(10,2)<?php NOT<?php NULL,
<?php `percentual`<?php float<?php NOT<?php NULL,
<?php `tipo`<?php enum('perda_usuario','ganho_usuario')<?php NOT<?php NULL,
<?php `created_at`<?php datetime<?php DEFAULT<?php current_timestamp(),
<?php PRIMARY<?php KEY<?php (`id`),
<?php KEY<?php `idx_afiliado_id`<?php (`afiliado_id`),
<?php KEY<?php `idx_usuario_id`<?php (`usuario_id`),
<?php KEY<?php `idx_tipo`<?php (`tipo`),
<?php KEY<?php `idx_created_at`<?php (`created_at`)
<?php )<?php ENGINE=InnoDB<?php DEFAULT<?php CHARSET=utf8mb4<?php COLLATE=utf8mb4_general_ci
<?php ";
<?php $pdo->exec($sql);
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php error_log("Erro<?php ao<?php criar<?php tabela<?php historico_revshare:<?php "<?php .<?php $e->getMessage());
<?php }
}