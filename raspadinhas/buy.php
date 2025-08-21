<?php
@session_start();
require_once<?php '../conexao.php';
header('Content-Type:<?php application/json');

$userId<?php =<?php $_SESSION['usuario_id']<?php ??<?php 0;
$raspadinhaId<?php =<?php (int)($_POST['raspadinha_id']<?php ??<?php 0);

if<?php (!$userId<?php ||<?php !$raspadinhaId)<?php {
<?php http_response_code(400);
<?php exit(json_encode(['error'<?php =><?php 'Requisição<?php inválida']));
}

$stmt<?php =<?php $pdo->prepare("SELECT<?php valor<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");
$stmt->execute([$raspadinhaId]);
$raspadinha<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);

if<?php (!$raspadinha)<?php {
<?php http_response_code(404);
<?php exit(json_encode(['error'<?php =><?php 'Raspadinha<?php não<?php encontrada']));
}

$stmt<?php =<?php $pdo->prepare("SELECT<?php saldo,<?php influencer<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php ?");
$stmt->execute([$userId]);
$usuario<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);

if<?php (!$usuario)<?php {
<?php http_response_code(404);
<?php exit(json_encode(['error'<?php =><?php 'Usuário<?php não<?php encontrado']));
}

if<?php ($usuario['saldo']<?php <?php $raspadinha['valor'])<?php {
<?php http_response_code(403);
<?php exit(json_encode(['error'<?php =><?php 'Saldo<?php insuficiente']));
}

$isInfluencer<?php =<?php (int)$usuario['influencer']<?php ===<?php 1;

$pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php -<?php ?<?php WHERE<?php id<?php =<?php ?")
<?php ->execute([$raspadinha['valor'],<?php $userId]);

$stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php probabilidade,<?php valor<?php FROM<?php raspadinha_premios<?php WHERE<?php raspadinha_id<?php =<?php ?");
$stmt->execute([$raspadinhaId]);
$premiosBrutos<?php =<?php $stmt->fetchAll(PDO::FETCH_ASSOC);

if<?php (count($premiosBrutos)<?php ===<?php 0)<?php {
<?php http_response_code(500);
<?php exit(json_encode(['error'<?php =><?php 'Nenhum<?php prêmio<?php configurado']));
}

//<?php Sistema<?php de<?php probabilidade<?php MUITO<?php melhorado<?php para<?php influencers
if<?php ($isInfluencer)<?php {
<?php $premiosBrutos<?php =<?php aplicarBonusInfluencer($premiosBrutos,<?php $raspadinha['valor'],<?php $userId);
}

/**
<?php *<?php Aplica<?php bonus<?php GENEROSO<?php de<?php probabilidade<?php para<?php influencers
<?php *<?php Sistema<?php inteligente<?php que<?php aumenta<?php drasticamente<?php as<?php chances<?php de<?php ganho
<?php *<?php @param<?php array<?php $premios<?php Array<?php de<?php prêmios
<?php *<?php @param<?php float<?php $custoRaspadinha<?php Valor<?php da<?php raspadinha
<?php *<?php @param<?php int<?php $userId<?php ID<?php do<?php usuário<?php para<?php verificar<?php histórico
<?php *<?php @return<?php array<?php Prêmios<?php com<?php probabilidades<?php MUITO<?php melhoradas<?php para<?php influencers
<?php */
function<?php aplicarBonusInfluencer(array<?php $premios,<?php float<?php $custoRaspadinha,<?php int<?php $userId):<?php array<?php {
<?php global<?php $pdo;
<?php 
<?php //<?php Verificar<?php últimas<?php 5<?php jogadas<?php do<?php influencer<?php para<?php análise<?php mais<?php ampla
<?php $stmt<?php =<?php $pdo->prepare("
<?php SELECT<?php o.resultado<?php 
<?php FROM<?php orders<?php o<?php 
<?php WHERE<?php o.user_id<?php =<?php ?<?php 
<?php ORDER<?php BY<?php o.created_at<?php DESC<?php 
<?php LIMIT<?php 5
<?php ");
<?php $stmt->execute([$userId]);
<?php $ultimasJogadas<?php =<?php $stmt->fetchAll(PDO::FETCH_COLUMN);
<?php 
<?php //<?php Analisar<?php padrão<?php de<?php vitórias/derrotas
<?php $derrotasConsecutivas<?php =<?php 0;
<?php $vitoriasUltimas5<?php =<?php 0;
<?php 
<?php foreach<?php ($ultimasJogadas<?php as<?php $index<?php =><?php $resultado)<?php {
<?php if<?php ($resultado<?php ===<?php 'gain')<?php {
<?php $vitoriasUltimas5++;
<?php if<?php ($index<?php ===<?php 0)<?php break;<?php //<?php Se<?php a<?php última<?php foi<?php vitória,<?php para<?php a<?php contagem<?php de<?php derrotas
<?php }<?php else<?php {
<?php if<?php ($index<?php <?php 3)<?php $derrotasConsecutivas++;<?php //<?php Só<?php conta<?php derrotas<?php recentes
<?php }
<?php }
<?php 
<?php //<?php Configurações<?php GENEROSAS<?php para<?php influencers
<?php $configuracao<?php =<?php [
<?php //<?php Bonus<?php base<?php para<?php influencers<?php (sempre<?php aplicado)
<?php 'bonus_base_influencer'<?php =><?php 8,
<?php 
<?php //<?php Bonus<?php por<?php categoria<?php de<?php prêmio
<?php 'bonus_premios_pequenos'<?php =><?php 15,<?php //<?php 1x<?php a<?php 3x<?php o<?php valor<?php da<?php raspadinha
<?php 'bonus_premios_medios'<?php =><?php 12,<?php //<?php 3x<?php a<?php 8x<?php o<?php valor<?php da<?php raspadinha
<?php 'bonus_premios_grandes'<?php =><?php 8,<?php //<?php 8x<?php a<?php 15x<?php o<?php valor<?php da<?php raspadinha
<?php 'bonus_premios_mega'<?php =><?php 4,<?php //<?php Acima<?php de<?php 15x<?php (ainda<?php tem<?php bonus,<?php mas<?php menor)
<?php 
<?php //<?php Bonus<?php por<?php situação
<?php 'bonus_derrotas_consecutivas'<?php =><?php $derrotasConsecutivas<?php *<?php 5,<?php //<?php +5<?php para<?php cada<?php derrota<?php seguida
<?php 'bonus_poucas_vitorias'<?php =><?php ($vitoriasUltimas5<?php <=<?php 1)<?php ?<?php 10<?php :<?php 0,<?php //<?php Se<?php ganhou<?php pouco<?php recentemente
<?php 
<?php //<?php Redução<?php na<?php chance<?php de<?php não<?php ganhar<?php nada
<?php 'reducao_sem_premio'<?php =><?php 25,<?php //<?php Reduz<?php drasticamente<?php a<?php chance<?php de<?php sair<?php sem<?php nada
<?php 
<?php //<?php Multiplicador<?php geral<?php de<?php sorte
<?php 'multiplicador_sorte'<?php =><?php 1.5<?php //<?php 50%<?php de<?php bonus<?php geral
<?php ];
<?php 
<?php foreach<?php ($premios<?php as<?php &$premio)<?php {
<?php $valorPremio<?php =<?php (float)$premio['valor'];
<?php $multiplicador<?php =<?php $valorPremio<?php /<?php $custoRaspadinha;
<?php $probabilidadeOriginal<?php =<?php $premio['probabilidade'];
<?php 
<?php if<?php ($valorPremio<?php ==<?php 0)<?php {
<?php //<?php REDUZ<?php DRASTICAMENTE<?php a<?php chance<?php de<?php não<?php ganhar<?php nada
<?php $novaProb<?php =<?php max(1,<?php $probabilidadeOriginal<?php -<?php $configuracao['reducao_sem_premio']);
<?php $premio['probabilidade']<?php =<?php $novaProb;
<?php 
<?php }<?php else<?php {
<?php //<?php Determina<?php categoria<?php do<?php prêmio<?php e<?php aplica<?php bonus<?php correspondente
<?php $bonusCategoria<?php =<?php 0;
<?php if<?php ($multiplicador<?php <=<?php 3)<?php {
<?php $bonusCategoria<?php =<?php $configuracao['bonus_premios_pequenos'];
<?php }<?php elseif<?php ($multiplicador<?php <=<?php 8)<?php {
<?php $bonusCategoria<?php =<?php $configuracao['bonus_premios_medios'];
<?php }<?php elseif<?php ($multiplicador<?php <=<?php 15)<?php {
<?php $bonusCategoria<?php =<?php $configuracao['bonus_premios_grandes'];
<?php }<?php else<?php {
<?php $bonusCategoria<?php =<?php $configuracao['bonus_premios_mega'];
<?php }
<?php 
<?php //<?php Calcula<?php bonus<?php total
<?php $bonusTotal<?php =<?php 
<?php $configuracao['bonus_base_influencer']<?php +
<?php $bonusCategoria<?php +
<?php $configuracao['bonus_derrotas_consecutivas']<?php +
<?php $configuracao['bonus_poucas_vitorias'];
<?php 
<?php //<?php Aplica<?php bonus<?php e<?php multiplicador<?php de<?php sorte
<?php $novaProb<?php =<?php ($probabilidadeOriginal<?php +<?php $bonusTotal)<?php *<?php $configuracao['multiplicador_sorte'];
<?php $premio['probabilidade']<?php =<?php max(0.5,<?php $novaProb);
<?php }
<?php }
<?php 
<?php //<?php Log<?php para<?php acompanhar<?php os<?php ajustes<?php (remover<?php em<?php produção<?php se<?php necessário)
<?php error_log("Influencer<?php $userId<?php -<?php Derrotas<?php consecutivas:<?php $derrotasConsecutivas,<?php Vitórias<?php últimas<?php 5:<?php $vitoriasUltimas5");
<?php 
<?php return<?php $premios;
}

function<?php sortearPremio(array<?php $premios):<?php int<?php {
<?php $total<?php =<?php 0;
<?php foreach<?php ($premios<?php as<?php $p)<?php {
<?php $total<?php +=<?php $p['probabilidade'];
<?php }

<?php $rand<?php =<?php mt_rand(0,<?php (int)($total<?php *<?php 100))<?php /<?php 100;
<?php $acumulado<?php =<?php 0;

<?php foreach<?php ($premios<?php as<?php $p)<?php {
<?php $acumulado<?php +=<?php $p['probabilidade'];
<?php if<?php ($rand<?php <=<?php $acumulado)<?php {
<?php return<?php (int)$p['id'];
<?php }
<?php }

<?php return<?php (int)$premios[array_key_last($premios)]['id'];<?php //<?php fallback
}

/**
<?php *<?php Função<?php melhorada<?php para<?php controlar<?php repetições<?php no<?php grid
<?php *<?php Para<?php influencers:<?php permite<?php mais<?php facilmente<?php combinações<?php vencedoras
<?php */
function<?php gerarGridEquilibrado(array<?php $premios,<?php bool<?php $isInfluencer):<?php array<?php {
<?php $grid<?php =<?php [];
<?php $contagem<?php =<?php [];
<?php $maxTentativasItem<?php =<?php $isInfluencer<?php ?<?php 100<?php :<?php 50;<?php //<?php Influencers<?php têm<?php mais<?php tentativas
<?php 
<?php //<?php Buscar<?php custo<?php da<?php raspadinha<?php para<?php calcular<?php multiplicadores
<?php global<?php $pdo,<?php $raspadinhaId;
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php valor<?php FROM<?php raspadinhas<?php WHERE<?php id<?php =<?php ?");
<?php $stmt->execute([$raspadinhaId]);
<?php $raspadinha<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $custoRaspadinha<?php =<?php (float)$raspadinha['valor'];
<?php 
<?php //<?php Para<?php influencers,<?php só<?php prêmios<?php MUITO<?php altos<?php (acima<?php de<?php 20x)<?php têm<?php restrição
<?php $premiosRestritivos<?php =<?php [];
<?php if<?php (!$isInfluencer)<?php {
<?php //<?php Usuários<?php normais:<?php prêmios<?php acima<?php de<?php 10x<?php são<?php restritivos
<?php foreach<?php ($premios<?php as<?php $premio)<?php {
<?php $multiplicador<?php =<?php (float)$premio['valor']<?php /<?php $custoRaspadinha;
<?php if<?php ($multiplicador<?php ><?php 10)<?php {
<?php $premiosRestritivos[]<?php =<?php (int)$premio['id'];
<?php }
<?php }
<?php }<?php else<?php {
<?php //<?php Influencers:<?php apenas<?php prêmios<?php EXTREMAMENTE<?php altos<?php são<?php restritivos
<?php foreach<?php ($premios<?php as<?php $premio)<?php {
<?php $multiplicador<?php =<?php (float)$premio['valor']<?php /<?php $custoRaspadinha;
<?php if<?php ($multiplicador<?php ><?php 20)<?php {<?php //<?php Muito<?php mais<?php permissivo<?php para<?php influencers
<?php $premiosRestritivos[]<?php =<?php (int)$premio['id'];
<?php }
<?php }
<?php }
<?php 
<?php //<?php Configurações<?php muito<?php mais<?php permissivas<?php para<?php influencers
<?php $config<?php =<?php [
<?php 'max_grupos_tres'<?php =><?php $isInfluencer<?php ?<?php 3<?php :<?php 1,<?php //<?php Influencers<?php podem<?php ter<?php até<?php 3<?php grupos<?php de<?php 3
<?php 'tentativas_extras'<?php =><?php $isInfluencer<?php ?<?php 50<?php :<?php 0,
<?php 'premios_restritivos'<?php =><?php $premiosRestritivos,
<?php 'max_repeticoes_especiais'<?php =><?php $isInfluencer<?php ?<?php 3<?php :<?php 2<?php //<?php Influencers<?php podem<?php repetir<?php mais
<?php ];
<?php 
<?php for<?php ($i<?php =<?php 0;<?php $i<?php <?php 9;<?php $i++)<?php {
<?php $tentativas<?php =<?php 0;
<?php $maxTentativas<?php =<?php $maxTentativasItem<?php +<?php $config['tentativas_extras'];

<?php do<?php {
<?php $itemId<?php =<?php sortearPremio($premios);
<?php $tentativas++;

<?php $countItem<?php =<?php $contagem[$itemId]<?php ??<?php 0;
<?php $gruposTres<?php =<?php 0;
<?php foreach<?php ($contagem<?php as<?php $count)<?php {
<?php if<?php ($count<?php >=<?php 3)<?php {
<?php $gruposTres++;
<?php }
<?php }

<?php //<?php Regras<?php mais<?php flexíveis<?php para<?php influencers
<?php $isPremioRestritivo<?php =<?php in_array($itemId,<?php $config['premios_restritivos']);
<?php $maxRepeticoesItem<?php =<?php $isPremioRestritivo<?php ?<?php $config['max_repeticoes_especiais']<?php :<?php 3;
<?php 
<?php //<?php Para<?php influencers,<?php permite<?php mais<?php grupos<?php de<?php 3
<?php $limiteRepeticoes<?php =<?php ($gruposTres<?php >=<?php $config['max_grupos_tres'])<?php ?<?php 2<?php :<?php $maxRepeticoesItem;
<?php $ok<?php =<?php ($countItem<?php <?php $limiteRepeticoes);

<?php if<?php ($tentativas<?php ><?php $maxTentativas)<?php {
<?php $itemId<?php =<?php encontrarItemSeguro($premios,<?php $contagem,<?php $limiteRepeticoes,<?php $config['premios_restritivos']);
<?php break;
<?php }

<?php }<?php while<?php (!$ok);

<?php $grid[]<?php =<?php $itemId;
<?php $contagem[$itemId]<?php =<?php ($contagem[$itemId]<?php ??<?php 0)<?php +<?php 1;
<?php }
<?php 
<?php return<?php $grid;
}

/**
<?php *<?php Encontra<?php um<?php item<?php que<?php pode<?php ser<?php usado<?php sem<?php quebrar<?php as<?php regras
<?php *<?php Mais<?php permissivo<?php para<?php influencers
<?php */
function<?php encontrarItemSeguro(array<?php $premios,<?php array<?php $contagem,<?php int<?php $limiteRepeticoes,<?php array<?php $premiosRestritivos<?php =<?php []):<?php int<?php {
<?php //<?php Para<?php influencers,<?php tenta<?php primeiro<?php os<?php prêmios<?php de<?php valor
<?php foreach<?php ($premios<?php as<?php $premio)<?php {
<?php $id<?php =<?php (int)$premio['id'];
<?php $count<?php =<?php $contagem[$id]<?php ??<?php 0;
<?php $isPremioRestritivo<?php =<?php in_array($id,<?php $premiosRestritivos);
<?php 
<?php //<?php Se<?php é<?php um<?php prêmio<?php bom<?php e<?php pode<?php ser<?php usado
<?php if<?php (!$isPremioRestritivo<?php &&<?php $count<?php <?php $limiteRepeticoes<?php &&<?php $premio['valor']<?php ><?php 0)<?php {
<?php return<?php $id;
<?php }
<?php }
<?php 
<?php //<?php Depois<?php tenta<?php qualquer<?php prêmio<?php que<?php não<?php seja<?php restritivo
<?php foreach<?php ($premios<?php as<?php $premio)<?php {
<?php $id<?php =<?php (int)$premio['id'];
<?php $count<?php =<?php $contagem[$id]<?php ??<?php 0;
<?php $isPremioRestritivo<?php =<?php in_array($id,<?php $premiosRestritivos);
<?php 
<?php if<?php (!$isPremioRestritivo<?php &&<?php $count<?php <?php $limiteRepeticoes)<?php {
<?php return<?php $id;
<?php }
<?php }
<?php 
<?php //<?php Se<?php só<?php restam<?php restritivos,<?php usa<?php um<?php com<?php menos<?php repetições
<?php foreach<?php ($premios<?php as<?php $premio)<?php {
<?php $id<?php =<?php (int)$premio['id'];
<?php $count<?php =<?php $contagem[$id]<?php ??<?php 0;
<?php if<?php ($count<?php <?php 2)<?php {
<?php return<?php $id;
<?php }
<?php }
<?php 
<?php //<?php Última<?php opção
<?php return<?php (int)$premios[0]['id'];
}

//<?php Gera<?php o<?php grid<?php usando<?php a<?php função<?php melhorada
$grid<?php =<?php gerarGridEquilibrado($premiosBrutos,<?php $isInfluencer);

$stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php orders<?php (user_id,<?php raspadinha_id,<?php premios_json)<?php VALUES<?php (?,<?php ?,<?php ?)");
$stmt->execute([$userId,<?php $raspadinhaId,<?php json_encode($grid)]);
$orderId<?php =<?php $pdo->lastInsertId();

//<?php Log<?php detalhado<?php para<?php influencers
if<?php ($isInfluencer)<?php {
<?php $totalProbabilidade<?php =<?php array_sum(array_column($premiosBrutos,<?php 'probabilidade'));
<?php error_log("Influencer<?php ID<?php $userId<?php -<?php Total<?php probabilidade:<?php $totalProbabilidade<?php -<?php Grid:<?php "<?php .<?php json_encode($grid));
}

echo<?php json_encode([
<?php 'success'<?php =><?php true,
<?php 'order_id'<?php =><?php $orderId,
<?php 'grid'<?php =><?php $grid,
<?php 'saldo_novo'<?php =><?php $usuario['saldo']<?php -<?php $raspadinha['valor'],
<?php 'influencer'<?php =><?php $isInfluencer
]);
?>