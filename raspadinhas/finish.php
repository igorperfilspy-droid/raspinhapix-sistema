<?php
@session_start();
require_once '../conexao.php';
header('Content-Type:<?php application/json');

$userId =<?php $_SESSION['usuario_id']<?php ??<?php 0;
$orderId =<?php (int)($_POST['order_id']<?php ??<?php 0);

if (!$userId ||<?php !$orderId)<?php {
<?php http_response_code(400);
<?php exit(json_encode(['error'<?php =><?php 'Dados inválidos']));
}

$stmt =<?php $pdo->prepare("
<?php SELECT o.*,<?php r.valor AS custo_raspadinha FROM orders o JOIN raspadinhas r ON r.id =<?php o.raspadinha_id WHERE o.id =<?php ?<?php AND o.user_id =<?php ?
<?php LIMIT 1
");

$stmt->execute([$orderId,<?php $userId]);
$order =<?php $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order ||<?php $order['status']<?php ==<?php 1)<?php {
<?php http_response_code(400);
<?php exit(json_encode(['error'<?php =><?php 'Ordem inválida']));
}

$gridIds =<?php json_decode($order['premios_json'],<?php true);
$contagem =<?php array_count_values($gridIds);

$premioId =<?php null;
$valorPremio =<?php 0.00;
$resultado =<?php 'loss';

//<?php ✅<?php CORREÇÃO:<?php Buscar o MAIOR prêmio ao invés do primeiro
$maiorPremio =<?php 0.00;
$melhorPremioId =<?php null;

foreach ($contagem as $id =><?php $qtd)<?php {
<?php //<?php ✅<?php CORREÇÃO:<?php Aceita 3 OU MAIS imagens iguais (>=<?php 3)
<?php if ($qtd >=<?php 3)<?php {
<?php $p =<?php $pdo->prepare("SELECT valor FROM raspadinha_premios WHERE id =<?php ?");
<?php $p->execute([$id]);
<?php $valorEncontrado =<?php (float)$p->fetchColumn();

<?php if ($valorEncontrado ><?php 0)<?php {
<?php //<?php ✅<?php Verifica se este prêmio é<?php maior que o anterior if ($valorEncontrado ><?php $maiorPremio)<?php {
<?php $maiorPremio =<?php $valorEncontrado;
<?php $melhorPremioId =<?php $id;
<?php }
<?php }
<?php }
}

//<?php ✅<?php Define o resultado baseado no maior prêmio encontrado
if ($maiorPremio ><?php 0)<?php {
<?php $premioId =<?php $melhorPremioId;
<?php $valorPremio =<?php $maiorPremio;
<?php $resultado =<?php 'gain';
}

if ($resultado ===<?php 'gain')<?php {
<?php $valorTotalACreditar =<?php $valorPremio +<?php (float)$order['custo_raspadinha'];

<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php saldo +<?php ?<?php WHERE id =<?php ?")
<?php ->execute([$valorTotalACreditar,<?php $userId]);
<?php //<?php ✅<?php Revshare baseado no valor ganho processarRevshareGanho($pdo,<?php $userId,<?php $valorPremio);
}<?php else {
<?php //<?php ✅<?php Revshare baseado no valor apostado processarRevsharePerdas($pdo,<?php $userId,<?php (float)$order['custo_raspadinha']);
}

$pdo->prepare("
<?php UPDATE orders SET status =<?php 1,
<?php resultado =<?php ?,
<?php valor_ganho =<?php ?,
<?php updated_at =<?php NOW()
<?php WHERE id =<?php ?
")->execute([$resultado,<?php $valorPremio,<?php $orderId]);

echo json_encode([
<?php 'success'<?php =><?php true,
<?php 'resultado'<?php =><?php $resultado,
<?php 'valor'<?php =><?php $valorPremio ]);

/**
<?php *<?php ✅<?php Quando o usuário GANHA,<?php afiliado PERDE (baseado no valor ganho)
<?php */
function processarRevshareGanho($pdo,<?php $userId,<?php $valorGanho)<?php {
<?php try {
<?php $stmt =<?php $pdo->prepare("SELECT indicacao FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$userId]);
<?php $usuario =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php if (!$usuario ||<?php !$usuario['indicacao'])<?php return false;
<?php $stmt =<?php $pdo->prepare("SELECT id,<?php comissao_revshare,<?php saldo FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$usuario['indicacao']]);
<?php $afiliado =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php if (!$afiliado)<?php return false;
<?php $percentualRevshare =<?php $afiliado['comissao_revshare'];

<?php if ($percentualRevshare ==<?php 0)<?php {
<?php $stmt =<?php $pdo->query("SELECT revshare_padrao FROM config LIMIT 1");
<?php $percentualRevshare =<?php $stmt->fetchColumn()<?php ?:<?php 0;
<?php }
<?php if ($percentualRevshare <=<?php 0)<?php return false;

<?php //<?php ✅<?php Dedução baseada no valor ganho $valorDeduzir =<?php ($valorGanho *<?php $percentualRevshare)<?php /<?php 100;
<?php if ($valorDeduzir <=<?php 0)<?php return false;

<?php $novoSaldo =<?php $afiliado['saldo']<?php -<?php $valorDeduzir;
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php ?,<?php updated_at =<?php NOW()<?php WHERE id =<?php ?");
<?php $stmt->execute([$novoSaldo,<?php $afiliado['id']]);
<?php registrarTransacaoRevshare($pdo,<?php $afiliado['id'],<?php $userId,<?php $valorGanho,<?php -$valorDeduzir,<?php $percentualRevshare,<?php 'ganho_usuario');
<?php return true;
<?php }<?php catch (PDOException $e)<?php {
<?php error_log("Erro ao processar revshare ganho:<?php "<?php .<?php $e->getMessage());
<?php return false;
<?php }
}

/**
<?php *<?php ✅<?php Quando o usuário PERDE,<?php afiliado GANHA (baseado no valor apostado)
<?php */
function processarRevsharePerdas($pdo,<?php $userId,<?php $valorPerdido)<?php {
<?php try {
<?php $stmt =<?php $pdo->prepare("SELECT indicacao FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$userId]);
<?php $usuario =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php if (!$usuario ||<?php !$usuario['indicacao'])<?php return false;

<?php $stmt =<?php $pdo->prepare("SELECT id,<?php comissao_revshare,<?php saldo FROM usuarios WHERE id =<?php ?");
<?php $stmt->execute([$usuario['indicacao']]);
<?php $afiliado =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php if (!$afiliado)<?php return false;

<?php $percentualRevshare =<?php $afiliado['comissao_revshare'];
<?php if ($percentualRevshare ==<?php 0)<?php {
<?php $stmt =<?php $pdo->query("SELECT revshare_padrao FROM config LIMIT 1");
<?php $percentualRevshare =<?php $stmt->fetchColumn()<?php ?:<?php 0;
<?php }
<?php if ($percentualRevshare <=<?php 0)<?php return false;

<?php $comissao =<?php ($valorPerdido *<?php $percentualRevshare)<?php /<?php 100;
<?php if ($comissao <=<?php 0)<?php return false;

<?php $novoSaldo =<?php $afiliado['saldo']<?php +<?php $comissao;
<?php $stmt =<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php ?,<?php updated_at =<?php NOW()<?php WHERE id =<?php ?");
<?php $stmt->execute([$novoSaldo,<?php $afiliado['id']]);
<?php registrarTransacaoRevshare($pdo,<?php $afiliado['id'],<?php $userId,<?php $valorPerdido,<?php $comissao,<?php $percentualRevshare,<?php 'perda_usuario');
<?php return true;
<?php }<?php catch (PDOException $e)<?php {
<?php error_log("Erro ao processar revshare perda:<?php "<?php .<?php $e->getMessage());
<?php return false;
<?php }
}

/**
<?php *<?php Histórico de transações de revshare */
function registrarTransacaoRevshare($pdo,<?php $afiliadoId,<?php $usuarioId,<?php $valorBase,<?php $valorRevshare,<?php $percentual,<?php $tipo)<?php {
<?php try {
<?php criarTabelaHistoricoRevshare($pdo);
<?php $stmt =<?php $pdo->prepare("
<?php INSERT INTO historico_revshare <?php (afiliado_id,<?php usuario_id,<?php valor_apostado,<?php valor_revshare,<?php percentual,<?php tipo,<?php created_at)<?php VALUES (?,<?php ?,<?php ?,<?php ?,<?php ?,<?php ?,<?php NOW())
<?php ");
<?php $stmt->execute([$afiliadoId,<?php $usuarioId,<?php $valorBase,<?php $valorRevshare,<?php $percentual,<?php $tipo]);
<?php }<?php catch (PDOException $e)<?php {
<?php error_log("Erro ao registrar histórico revshare:<?php "<?php .<?php $e->getMessage());
<?php }
}

/**
<?php *<?php Criação da tabela de histórico se necessário */
function criarTabelaHistoricoRevshare($pdo)<?php {
<?php try {
<?php $sql =<?php "
<?php CREATE TABLE IF NOT EXISTS `historico_revshare`<?php (
<?php `id`<?php bigint(20)<?php UNSIGNED NOT NULL AUTO_INCREMENT,
<?php `afiliado_id`<?php int(11)<?php NOT NULL,
<?php `usuario_id`<?php int(11)<?php NOT NULL,
<?php `valor_apostado`<?php decimal(10,2)<?php NOT NULL,
<?php `valor_revshare`<?php decimal(10,2)<?php NOT NULL,
<?php `percentual`<?php float NOT NULL,
<?php `tipo`<?php enum('perda_usuario','ganho_usuario')<?php NOT NULL,
<?php `created_at`<?php datetime DEFAULT current_timestamp(),
<?php PRIMARY KEY (`id`),
<?php KEY `idx_afiliado_id`<?php (`afiliado_id`),
<?php KEY `idx_usuario_id`<?php (`usuario_id`),
<?php KEY `idx_tipo`<?php (`tipo`),
<?php KEY `idx_created_at`<?php (`created_at`)
<?php )<?php ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ";
<?php $pdo->exec($sql);
<?php }<?php catch (PDOException $e)<?php {
<?php error_log("Erro ao criar tabela historico_revshare:<?php "<?php .<?php $e->getMessage());
<?php }
}