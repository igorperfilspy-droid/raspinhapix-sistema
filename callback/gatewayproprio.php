<?php
session_start();
header('Content-Type:<?php application/json');
header("Access-Control-Allow-Origin:<?php *");
header("Access-Control-Allow-Methods:<?php POST,<?php OPTIONS");
header("Access-Control-Allow-Headers:<?php Content-Type");

//<?php CONFIGURAÇÃO<?php DE<?php LOGS<?php -<?php ALTERE<?php AQUI<?php PARA<?php ATIVAR/DESATIVAR
define('DEBUG_MODE',<?php false);<?php //<?php true<?php =<?php logs<?php ativos<?php |<?php false<?php =<?php logs<?php desativados
define('LOG_FILE',<?php 'logs.txt');

//<?php Função<?php para<?php gravar<?php logs<?php apenas<?php se<?php DEBUG_MODE<?php estiver<?php ativo
function<?php writeLog($message)<?php {
<?php if<?php (DEBUG_MODE)<?php {
<?php file_put_contents(LOG_FILE,<?php date('d/m/Y<?php H:i:s')<?php .<?php "<?php -<?php "<?php .<?php $message<?php .<?php PHP_EOL,<?php FILE_APPEND);
<?php }
}

if<?php ($_SERVER['REQUEST_METHOD']<?php ===<?php 'OPTIONS')<?php {
<?php http_response_code(200);
<?php exit;
}

if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo<?php json_encode(['error'<?php =><?php 'Método<?php não<?php permitido']);
<?php exit;
}

$rawInput<?php =<?php file_get_contents('php://input');
$data<?php =<?php json_decode($rawInput,<?php true);

writeLog("PAYLOAD<?php GATEWAY<?php PRÓPRIO:<?php "<?php .<?php print_r($data,<?php true));
writeLog("----------------------------------------------------------");

if<?php (!$data)<?php {
<?php http_response_code(400);
<?php echo<?php json_encode(['error'<?php =><?php 'Payload<?php inválido']);
<?php exit;
}

$transactionId<?php =<?php $data['id']<?php ??<?php '';
$status<?php =<?php $data['status']<?php ??<?php '';

if<?php ($status<?php !==<?php 'PAID'<?php ||<?php empty($transactionId))<?php {
<?php http_response_code(400);
<?php echo<?php json_encode(['error'<?php =><?php 'Dados<?php insuficientes<?php ou<?php transação<?php não<?php paga']);
<?php exit;
}

require_once<?php __DIR__<?php .<?php '/../conexao.php';

try<?php {
<?php $pdo->beginTransaction();
<?php 
<?php writeLog("INICIANDO<?php PROCESSO<?php PARA<?php TXN:<?php "<?php .<?php $transactionId);
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php user_id,<?php valor,<?php status<?php FROM<?php depositos<?php WHERE<?php transactionId<?php =<?php :txid<?php AND<?php gateway<?php =<?php 'gatewayproprio'<?php LIMIT<?php 1<?php FOR<?php UPDATE");
<?php $stmt->execute([':txid'<?php =><?php $transactionId]);
<?php $deposito<?php =<?php $stmt->fetch();
<?php 
<?php if<?php (!$deposito)<?php {
<?php $pdo->commit();
<?php writeLog("ERRO:<?php Depósito<?php não<?php encontrado<?php para<?php TXN:<?php "<?php .<?php $transactionId);
<?php http_response_code(404);
<?php echo<?php json_encode(['error'<?php =><?php 'Depósito<?php não<?php encontrado']);
<?php exit;
<?php }
<?php 
<?php writeLog("DEPÓSITO<?php ENCONTRADO:<?php "<?php .<?php print_r($deposito,<?php true));
<?php 
<?php if<?php ($deposito['status']<?php ===<?php 'PAID')<?php {
<?php $pdo->commit();
<?php echo<?php json_encode(['message'<?php =><?php 'Este<?php pagamento<?php já<?php foi<?php aprovado']);
<?php exit;
<?php }
<?php 
<?php //<?php Atualiza<?php o<?php status<?php do<?php depósito
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php depositos<?php SET<?php status<?php =<?php 'PAID',<?php updated_at<?php =<?php NOW()<?php WHERE<?php id<?php =<?php :id");
<?php $stmt->execute([':id'<?php =><?php $deposito['id']]);
<?php writeLog("DEPÓSITO<?php ATUALIZADO<?php PARA<?php PAID");
<?php 
<?php //<?php Credita<?php o<?php saldo<?php do<?php usuário
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php +<?php :valor<?php WHERE<?php id<?php =<?php :uid");
<?php $stmt->execute([
<?php ':valor'<?php =><?php $deposito['valor'],
<?php ':uid'<?php =><?php $deposito['user_id']
<?php ]);
<?php writeLog("SALDO<?php CREDITADO:<?php R$<?php "<?php .<?php $deposito['valor']<?php .<?php "<?php para<?php usuário<?php "<?php .<?php $deposito['user_id']);
<?php 
<?php //<?php VERIFICAÇÃO<?php PARA<?php CPA<?php (APENAS<?php PRIMEIRO<?php DEPÓSITO)
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php indicacao<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :uid");
<?php $stmt->execute([':uid'<?php =><?php $deposito['user_id']]);
<?php $usuario<?php =<?php $stmt->fetch();
<?php 
<?php writeLog("USUÁRIO<?php DATA:<?php "<?php .<?php print_r($usuario,<?php true));
<?php 
<?php if<?php ($usuario<?php &&<?php !empty($usuario['indicacao']))<?php {
<?php writeLog("USUÁRIO<?php TEM<?php INDICAÇÃO:<?php "<?php .<?php $usuario['indicacao']);
<?php 
<?php //<?php Verifica<?php se<?php este<?php usuário<?php JÁ<?php teve<?php algum<?php depósito<?php aprovado<?php anteriormente
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php as<?php total_pagos<?php FROM<?php depositos<?php WHERE<?php user_id<?php =<?php :uid<?php AND<?php status<?php =<?php 'PAID'<?php AND<?php id<?php !=<?php :current_id");
<?php $stmt->execute([
<?php ':uid'<?php =><?php $deposito['user_id'],
<?php ':current_id'<?php =><?php $deposito['id']
<?php ]);
<?php $depositosAnteriores<?php =<?php $stmt->fetch();
<?php 
<?php writeLog("DEPÓSITOS<?php ANTERIORES<?php PAGOS:<?php "<?php .<?php $depositosAnteriores['total_pagos']);
<?php 
<?php //<?php CPA<?php só<?php é<?php pago<?php se<?php este<?php for<?php o<?php PRIMEIRO<?php depósito<?php aprovado<?php do<?php usuário
<?php if<?php ($depositosAnteriores['total_pagos']<?php ==<?php 0)<?php {
<?php writeLog("É<?php O<?php PRIMEIRO<?php DEPÓSITO,<?php VERIFICANDO<?php AFILIADO");
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php comissao_cpa,<?php banido<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :afiliado_id");
<?php $stmt->execute([':afiliado_id'<?php =><?php $usuario['indicacao']]);
<?php $afiliado<?php =<?php $stmt->fetch();
<?php 
<?php writeLog("AFILIADO<?php DATA:<?php "<?php .<?php print_r($afiliado,<?php true));
<?php 
<?php if<?php ($afiliado<?php &&<?php $afiliado['banido']<?php !=<?php 1<?php &&<?php !empty($afiliado['comissao_cpa']))<?php {
<?php $comissao<?php =<?php $afiliado['comissao_cpa'];
<?php 
<?php //<?php Credita<?php a<?php comissão<?php CPA<?php para<?php o<?php afiliado
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php +<?php :comissao<?php WHERE<?php id<?php =<?php :afiliado_id");
<?php $stmt->execute([
<?php ':comissao'<?php =><?php $comissao,
<?php ':afiliado_id'<?php =><?php $afiliado['id']
<?php ]);
<?php 
<?php //<?php Tenta<?php inserir<?php na<?php tabela<?php transacoes_afiliados<?php (removendo<?php o<?php campo<?php 'tipo'<?php caso<?php não<?php exista)
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php transacoes_afiliados
<?php (afiliado_id,<?php usuario_id,<?php deposito_id,<?php valor,<?php created_at)
<?php VALUES<?php (:afiliado_id,<?php :usuario_id,<?php :deposito_id,<?php :valor,<?php NOW())");
<?php $stmt->execute([
<?php ':afiliado_id'<?php =><?php $afiliado['id'],
<?php ':usuario_id'<?php =><?php $deposito['user_id'],
<?php ':deposito_id'<?php =><?php $deposito['id'],
<?php ':valor'<?php =><?php $comissao
<?php ]);
<?php }<?php catch<?php (Exception<?php $insertError)<?php {
<?php writeLog("ERRO<?php AO<?php INSERIR<?php TRANSAÇÃO<?php AFILIADO:<?php "<?php .<?php $insertError->getMessage());
<?php }
<?php 
<?php writeLog("CPA<?php PAGO:<?php Afiliado<?php {$afiliado['id']}<?php recebeu<?php R$<?php {$comissao}<?php pelo<?php primeiro<?php depósito<?php do<?php usuário<?php {$deposito['user_id']}");
<?php }<?php else<?php {
<?php writeLog("CPA<?php NÃO<?php PAGO:<?php Afiliado<?php inválido<?php ou<?php sem<?php comissão");
<?php }
<?php }<?php else<?php {
<?php writeLog("CPA<?php NÃO<?php PAGO:<?php Usuário<?php {$deposito['user_id']}<?php já<?php teve<?php {$depositosAnteriores['total_pagos']}<?php depósito(s)<?php aprovado(s)<?php anteriormente");
<?php }
<?php }<?php else<?php {
<?php writeLog("USUÁRIO<?php SEM<?php INDICAÇÃO");
<?php }
<?php 
<?php $pdo->commit();
<?php writeLog("TRANSAÇÃO<?php FINALIZADA<?php COM<?php SUCESSO");
<?php echo<?php json_encode(['message'<?php =><?php 'OK']);
<?php 
}<?php catch<?php (Exception<?php $e)<?php {
<?php $pdo->rollBack();
<?php writeLog("ERRO<?php GERAL:<?php "<?php .<?php $e->getMessage());
<?php writeLog("STACK<?php TRACE:<?php "<?php .<?php $e->getTraceAsString());
<?php http_response_code(500);
<?php echo<?php json_encode(['error'<?php =><?php 'Erro<?php interno']);
<?php exit;
}