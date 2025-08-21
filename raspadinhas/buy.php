<?php
@session_start();
require_once '../conexao.php';
header('Content-Type:<?php application/json');

$userId =<?php $_SESSION['usuario_id']<?php ??<?php 0;
$raspadinhaId =<?php (int)($_POST['raspadinha_id']<?php ??<?php 0);

if (!$userId ||<?php !$raspadinhaId)<?php {
<?php http_response_code(400);
<?php exit(json_encode(['error'<?php =><?php 'Requisição inválida']));
}

$stmt =<?php $pdo->prepare("SELECT valor FROM raspadinhas WHERE id =<?php ?");
$stmt->execute([$raspadinhaId]);
$raspadinha =<?php $stmt->fetch(PDO::FETCH_ASSOC);

if (!$raspadinha)<?php {
<?php http_response_code(404);
<?php exit(json_encode(['error'<?php =><?php 'Raspadinha não encontrada']));
}

$stmt =<?php $pdo->prepare("SELECT saldo,<?php influencer FROM usuarios WHERE id =<?php ?");
$stmt->execute([$userId]);
$usuario =<?php $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario)<?php {
<?php http_response_code(404);
<?php exit(json_encode(['error'<?php =><?php 'Usuário não encontrado']));
}

if ($usuario['saldo']<?php $raspadinha['valor'])<?php {
<?php http_response_code(403);
<?php exit(json_encode(['error'<?php =><?php 'Saldo insuficiente']));
}

$isInfluencer =<?php (int)$usuario['influencer']<?php ===<?php 1;

$pdo->prepare("UPDATE usuarios SET saldo =<?php saldo -<?php ?<?php WHERE id =<?php ?")
<?php ->execute([$raspadinha['valor'],<?php $userId]);

$stmt =<?php $pdo->prepare("SELECT id,<?php probabilidade,<?php valor FROM raspadinha_premios WHERE raspadinha_id =<?php ?");
$stmt->execute([$raspadinhaId]);
$premiosBrutos =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($premiosBrutos)<?php ===<?php 0)<?php {
<?php http_response_code(500);
<?php exit(json_encode(['error'<?php =><?php 'Nenhum prêmio configurado']));
}

//<?php Sistema de probabilidade MUITO melhorado para influencers
if ($isInfluencer)<?php {
<?php $premiosBrutos =<?php aplicarBonusInfluencer($premiosBrutos,<?php $raspadinha['valor'],<?php $userId);
}

/**
<?php *<?php Aplica bonus GENEROSO de probabilidade para influencers *<?php Sistema inteligente que aumenta drasticamente as chances de ganho *<?php @param array $premios Array de prêmios *<?php @param float $custoRaspadinha Valor da raspadinha *<?php @param int $userId ID do usuário para verificar histórico *<?php @return array Prêmios com probabilidades MUITO melhoradas para influencers */
function aplicarBonusInfluencer(array $premios,<?php float $custoRaspadinha,<?php int $userId):<?php array {
<?php global $pdo;
<?php //<?php Verificar últimas 5 jogadas do influencer para análise mais ampla $stmt =<?php $pdo->prepare("
<?php SELECT o.resultado <?php FROM orders o <?php WHERE o.user_id =<?php ?<?php ORDER BY o.created_at DESC <?php LIMIT 5 ");
<?php $stmt->execute([$userId]);
<?php $ultimasJogadas =<?php $stmt->fetchAll(PDO::FETCH_COLUMN);
<?php //<?php Analisar padrão de vitórias/derrotas $derrotasConsecutivas =<?php 0;
<?php $vitoriasUltimas5 =<?php 0;
<?php foreach ($ultimasJogadas as $index =><?php $resultado)<?php {
<?php if ($resultado ===<?php 'gain')<?php {
<?php $vitoriasUltimas5++;
<?php if ($index ===<?php 0)<?php break;<?php //<?php Se a última foi vitória,<?php para a contagem de derrotas }<?php else {
<?php if ($index <?php 3)<?php $derrotasConsecutivas++;<?php //<?php Só<?php conta derrotas recentes }
<?php }
<?php //<?php Configurações GENEROSAS para influencers $configuracao =<?php [
<?php //<?php Bonus base para influencers (sempre aplicado)
<?php 'bonus_base_influencer'<?php =><?php 8,
<?php //<?php Bonus por categoria de prêmio 'bonus_premios_pequenos'<?php =><?php 15,<?php //<?php 1x a 3x o valor da raspadinha 'bonus_premios_medios'<?php =><?php 12,<?php //<?php 3x a 8x o valor da raspadinha 'bonus_premios_grandes'<?php =><?php 8,<?php //<?php 8x a 15x o valor da raspadinha 'bonus_premios_mega'<?php =><?php 4,<?php //<?php Acima de 15x (ainda tem bonus,<?php mas menor)
<?php //<?php Bonus por situação 'bonus_derrotas_consecutivas'<?php =><?php $derrotasConsecutivas *<?php 5,<?php //<?php +5 para cada derrota seguida 'bonus_poucas_vitorias'<?php =><?php ($vitoriasUltimas5 <=<?php 1)<?php ?<?php 10 :<?php 0,<?php //<?php Se ganhou pouco recentemente <?php //<?php Redução na chance de não ganhar nada 'reducao_sem_premio'<?php =><?php 25,<?php //<?php Reduz drasticamente a chance de sair sem nada <?php //<?php Multiplicador geral de sorte 'multiplicador_sorte'<?php =><?php 1.5 //<?php 50%<?php de bonus geral ];
<?php foreach ($premios as &$premio)<?php {
<?php $valorPremio =<?php (float)$premio['valor'];
<?php $multiplicador =<?php $valorPremio /<?php $custoRaspadinha;
<?php $probabilidadeOriginal =<?php $premio['probabilidade'];
<?php if ($valorPremio ==<?php 0)<?php {
<?php //<?php REDUZ DRASTICAMENTE a chance de não ganhar nada $novaProb =<?php max(1,<?php $probabilidadeOriginal -<?php $configuracao['reducao_sem_premio']);
<?php $premio['probabilidade']<?php =<?php $novaProb;
<?php }<?php else {
<?php //<?php Determina categoria do prêmio e aplica bonus correspondente $bonusCategoria =<?php 0;
<?php if ($multiplicador <=<?php 3)<?php {
<?php $bonusCategoria =<?php $configuracao['bonus_premios_pequenos'];
<?php }<?php elseif ($multiplicador <=<?php 8)<?php {
<?php $bonusCategoria =<?php $configuracao['bonus_premios_medios'];
<?php }<?php elseif ($multiplicador <=<?php 15)<?php {
<?php $bonusCategoria =<?php $configuracao['bonus_premios_grandes'];
<?php }<?php else {
<?php $bonusCategoria =<?php $configuracao['bonus_premios_mega'];
<?php }
<?php //<?php Calcula bonus total $bonusTotal =<?php $configuracao['bonus_base_influencer']<?php +
<?php $bonusCategoria +
<?php $configuracao['bonus_derrotas_consecutivas']<?php +
<?php $configuracao['bonus_poucas_vitorias'];
<?php //<?php Aplica bonus e multiplicador de sorte $novaProb =<?php ($probabilidadeOriginal +<?php $bonusTotal)<?php *<?php $configuracao['multiplicador_sorte'];
<?php $premio['probabilidade']<?php =<?php max(0.5,<?php $novaProb);
<?php }
<?php }
<?php //<?php Log para acompanhar os ajustes (remover em produção se necessário)
<?php error_log("Influencer $userId -<?php Derrotas consecutivas:<?php $derrotasConsecutivas,<?php Vitórias últimas 5:<?php $vitoriasUltimas5");
<?php return $premios;
}

function sortearPremio(array $premios):<?php int {
<?php $total =<?php 0;
<?php foreach ($premios as $p)<?php {
<?php $total +=<?php $p['probabilidade'];
<?php }

<?php $rand =<?php mt_rand(0,<?php (int)($total *<?php 100))<?php /<?php 100;
<?php $acumulado =<?php 0;

<?php foreach ($premios as $p)<?php {
<?php $acumulado +=<?php $p['probabilidade'];
<?php if ($rand <=<?php $acumulado)<?php {
<?php return (int)$p['id'];
<?php }
<?php }

<?php return (int)$premios[array_key_last($premios)]['id'];<?php //<?php fallback
}

/**
<?php *<?php Função melhorada para controlar repetições no grid *<?php Para influencers:<?php permite mais facilmente combinações vencedoras */
function gerarGridEquilibrado(array $premios,<?php bool $isInfluencer):<?php array {
<?php $grid =<?php [];
<?php $contagem =<?php [];
<?php $maxTentativasItem =<?php $isInfluencer ?<?php 100 :<?php 50;<?php //<?php Influencers têm mais tentativas <?php //<?php Buscar custo da raspadinha para calcular multiplicadores global $pdo,<?php $raspadinhaId;
<?php $stmt =<?php $pdo->prepare("SELECT valor FROM raspadinhas WHERE id =<?php ?");
<?php $stmt->execute([$raspadinhaId]);
<?php $raspadinha =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $custoRaspadinha =<?php (float)$raspadinha['valor'];
<?php //<?php Para influencers,<?php só<?php prêmios MUITO altos (acima de 20x)<?php têm restrição $premiosRestritivos =<?php [];
<?php if (!$isInfluencer)<?php {
<?php //<?php Usuários normais:<?php prêmios acima de 10x são restritivos foreach ($premios as $premio)<?php {
<?php $multiplicador =<?php (float)$premio['valor']<?php /<?php $custoRaspadinha;
<?php if ($multiplicador ><?php 10)<?php {
<?php $premiosRestritivos[]<?php =<?php (int)$premio['id'];
<?php }
<?php }
<?php }<?php else {
<?php //<?php Influencers:<?php apenas prêmios EXTREMAMENTE altos são restritivos foreach ($premios as $premio)<?php {
<?php $multiplicador =<?php (float)$premio['valor']<?php /<?php $custoRaspadinha;
<?php if ($multiplicador ><?php 20)<?php {<?php //<?php Muito mais permissivo para influencers $premiosRestritivos[]<?php =<?php (int)$premio['id'];
<?php }
<?php }
<?php }
<?php //<?php Configurações muito mais permissivas para influencers $config =<?php [
<?php 'max_grupos_tres'<?php =><?php $isInfluencer ?<?php 3 :<?php 1,<?php //<?php Influencers podem ter até<?php 3 grupos de 3 'tentativas_extras'<?php =><?php $isInfluencer ?<?php 50 :<?php 0,
<?php 'premios_restritivos'<?php =><?php $premiosRestritivos,
<?php 'max_repeticoes_especiais'<?php =><?php $isInfluencer ?<?php 3 :<?php 2 //<?php Influencers podem repetir mais ];
<?php for ($i =<?php 0;<?php $i <?php 9;<?php $i++)<?php {
<?php $tentativas =<?php 0;
<?php $maxTentativas =<?php $maxTentativasItem +<?php $config['tentativas_extras'];

<?php do {
<?php $itemId =<?php sortearPremio($premios);
<?php $tentativas++;

<?php $countItem =<?php $contagem[$itemId]<?php ??<?php 0;
<?php $gruposTres =<?php 0;
<?php foreach ($contagem as $count)<?php {
<?php if ($count >=<?php 3)<?php {
<?php $gruposTres++;
<?php }
<?php }

<?php //<?php Regras mais flexíveis para influencers $isPremioRestritivo =<?php in_array($itemId,<?php $config['premios_restritivos']);
<?php $maxRepeticoesItem =<?php $isPremioRestritivo ?<?php $config['max_repeticoes_especiais']<?php :<?php 3;
<?php //<?php Para influencers,<?php permite mais grupos de 3 $limiteRepeticoes =<?php ($gruposTres >=<?php $config['max_grupos_tres'])<?php ?<?php 2 :<?php $maxRepeticoesItem;
<?php $ok =<?php ($countItem <?php $limiteRepeticoes);

<?php if ($tentativas ><?php $maxTentativas)<?php {
<?php $itemId =<?php encontrarItemSeguro($premios,<?php $contagem,<?php $limiteRepeticoes,<?php $config['premios_restritivos']);
<?php break;
<?php }

<?php }<?php while (!$ok);

<?php $grid[]<?php =<?php $itemId;
<?php $contagem[$itemId]<?php =<?php ($contagem[$itemId]<?php ??<?php 0)<?php +<?php 1;
<?php }
<?php return $grid;
}

/**
<?php *<?php Encontra um item que pode ser usado sem quebrar as regras *<?php Mais permissivo para influencers */
function encontrarItemSeguro(array $premios,<?php array $contagem,<?php int $limiteRepeticoes,<?php array $premiosRestritivos =<?php []):<?php int {
<?php //<?php Para influencers,<?php tenta primeiro os prêmios de valor foreach ($premios as $premio)<?php {
<?php $id =<?php (int)$premio['id'];
<?php $count =<?php $contagem[$id]<?php ??<?php 0;
<?php $isPremioRestritivo =<?php in_array($id,<?php $premiosRestritivos);
<?php //<?php Se é<?php um prêmio bom e pode ser usado if (!$isPremioRestritivo &&<?php $count <?php $limiteRepeticoes &&<?php $premio['valor']<?php ><?php 0)<?php {
<?php return $id;
<?php }
<?php }
<?php //<?php Depois tenta qualquer prêmio que não seja restritivo foreach ($premios as $premio)<?php {
<?php $id =<?php (int)$premio['id'];
<?php $count =<?php $contagem[$id]<?php ??<?php 0;
<?php $isPremioRestritivo =<?php in_array($id,<?php $premiosRestritivos);
<?php if (!$isPremioRestritivo &&<?php $count <?php $limiteRepeticoes)<?php {
<?php return $id;
<?php }
<?php }
<?php //<?php Se só<?php restam restritivos,<?php usa um com menos repetições foreach ($premios as $premio)<?php {
<?php $id =<?php (int)$premio['id'];
<?php $count =<?php $contagem[$id]<?php ??<?php 0;
<?php if ($count <?php 2)<?php {
<?php return $id;
<?php }
<?php }
<?php //<?php Última opção return (int)$premios[0]['id'];
}

//<?php Gera o grid usando a função melhorada
$grid =<?php gerarGridEquilibrado($premiosBrutos,<?php $isInfluencer);

$stmt =<?php $pdo->prepare("INSERT INTO orders (user_id,<?php raspadinha_id,<?php premios_json)<?php VALUES (?,<?php ?,<?php ?)");
$stmt->execute([$userId,<?php $raspadinhaId,<?php json_encode($grid)]);
$orderId =<?php $pdo->lastInsertId();

//<?php Log detalhado para influencers
if ($isInfluencer)<?php {
<?php $totalProbabilidade =<?php array_sum(array_column($premiosBrutos,<?php 'probabilidade'));
<?php error_log("Influencer ID $userId -<?php Total probabilidade:<?php $totalProbabilidade -<?php Grid:<?php "<?php .<?php json_encode($grid));
}

echo json_encode([
<?php 'success'<?php =><?php true,
<?php 'order_id'<?php =><?php $orderId,
<?php 'grid'<?php =><?php $grid,
<?php 'saldo_novo'<?php =><?php $usuario['saldo']<?php -<?php $raspadinha['valor'],
<?php 'influencer'<?php =><?php $isInfluencer
]);
?>