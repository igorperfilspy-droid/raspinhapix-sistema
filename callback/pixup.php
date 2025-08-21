<?php<?php 
session_start();<?php 
header('Content-Type:<?php application/json');<?php 
header("Access-Control-Allow-Origin:<?php *");<?php 
header("Access-Control-Allow-Methods:<?php POST,<?php OPTIONS");<?php 
header("Access-Control-Allow-Headers:<?php Content-Type");<?php 
<?php 
//<?php CONFIGURAÇÃO<?php DE<?php LOGS<?php -<?php ALTERE<?php AQUI<?php PARA<?php ATIVAR/DESATIVAR<?php 
define('DEBUG_MODE',<?php false);<?php //<?php true<?php =<?php logs<?php ativos<?php |<?php false<?php =<?php logs<?php desativados<?php 
define('LOG_FILE',<?php 'logs.txt');<?php 
<?php 
//<?php Função<?php para<?php gravar<?php logs<?php apenas<?php se<?php DEBUG_MODE<?php estiver<?php ativo<?php 
function<?php writeLog($message)<?php {<?php 
<?php if<?php (DEBUG_MODE)<?php {<?php 
<?php file_put_contents(LOG_FILE,<?php date('d/m/Y<?php H:i:s')<?php .<?php "<?php -<?php "<?php .<?php $message<?php .<?php PHP_EOL,<?php FILE_APPEND);<?php 
<?php }<?php 
}<?php 
<?php 
if<?php ($_SERVER['REQUEST_METHOD']<?php ===<?php 'OPTIONS')<?php {<?php 
<?php http_response_code(200);<?php 
<?php exit;<?php 
}<?php 
<?php 
if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {<?php 
<?php http_response_code(405);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Método<?php não<?php permitido']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$rawInput<?php =<?php file_get_contents('php://input');<?php 
$data<?php =<?php json_decode($rawInput,<?php true);<?php 
<?php 
writeLog("PAYLOAD<?php PIXUP:<?php "<?php .<?php print_r($data,<?php true));<?php 
writeLog("----------------------------------------------------------");<?php 
<?php 
if<?php (!isset($data['requestBody']))<?php {<?php 
<?php http_response_code(400);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Payload<?php inválido']);<?php 
<?php exit;<?php 
}<?php 
<?php 
$body<?php =<?php $data['requestBody'];<?php 
$paymentType<?php =<?php $body['paymentType']<?php ??<?php '';<?php 
$status<?php =<?php $body['status']<?php ??<?php '';<?php 
$transactionId<?php =<?php $body['transactionId']<?php ??<?php '';<?php 
<?php 
if<?php ($paymentType<?php !==<?php 'PIX'<?php ||<?php $status<?php !==<?php 'PAID'<?php ||<?php empty($transactionId))<?php {<?php 
<?php http_response_code(400);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Dados<?php insuficientes<?php ou<?php transação<?php não<?php paga']);<?php 
<?php exit;<?php 
}<?php 
<?php 
require_once<?php __DIR__<?php .<?php '/../conexao.php';<?php 
<?php 
try<?php {<?php 
<?php $pdo->beginTransaction();<?php 
<?php 
<?php writeLog("INICIANDO<?php PROCESSO<?php PARA<?php TXN:<?php "<?php .<?php $transactionId);<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php user_id,<?php valor,<?php status<?php FROM<?php depositos<?php WHERE<?php transactionId<?php =<?php :txid<?php LIMIT<?php 1<?php FOR<?php UPDATE");<?php 
<?php $stmt->execute([':txid'<?php =><?php $transactionId]);<?php 
<?php $deposito<?php =<?php $stmt->fetch();<?php 
<?php 
<?php if<?php (!$deposito)<?php {<?php 
<?php $pdo->commit();<?php 
<?php writeLog("ERRO:<?php Depósito<?php não<?php encontrado<?php para<?php TXN:<?php "<?php .<?php $transactionId);<?php 
<?php http_response_code(404);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Depósito<?php não<?php encontrado']);<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php writeLog("DEPÓSITO<?php ENCONTRADO:<?php "<?php .<?php print_r($deposito,<?php true));<?php 
<?php 
<?php if<?php ($deposito['status']<?php ===<?php 'PAID')<?php {<?php 
<?php $pdo->commit();<?php 
<?php echo<?php json_encode(['message'<?php =><?php 'Este<?php pagamento<?php já<?php foi<?php aprovado']);<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php //<?php Atualiza<?php o<?php status<?php do<?php depósito<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php depositos<?php SET<?php status<?php =<?php 'PAID',<?php updated_at<?php =<?php NOW()<?php WHERE<?php id<?php =<?php :id");<?php 
<?php $stmt->execute([':id'<?php =><?php $deposito['id']]);<?php 
<?php writeLog("DEPÓSITO<?php ATUALIZADO<?php PARA<?php PAID");<?php 
<?php 
<?php //<?php Credita<?php o<?php saldo<?php do<?php usuário<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php +<?php :valor<?php WHERE<?php id<?php =<?php :uid");<?php 
<?php $stmt->execute([<?php 
<?php ':valor'<?php =><?php $deposito['valor'],<?php 
<?php ':uid'<?php =><?php $deposito['user_id']<?php 
<?php ]);<?php 
<?php writeLog("SALDO<?php CREDITADO:<?php R$<?php "<?php .<?php $deposito['valor']<?php .<?php "<?php para<?php usuário<?php "<?php .<?php $deposito['user_id']);<?php 
<?php 
<?php //<?php VERIFICAÇÃO<?php PARA<?php CPA<?php (APENAS<?php PRIMEIRO<?php DEPÓSITO)<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php indicacao<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :uid");<?php 
<?php $stmt->execute([':uid'<?php =><?php $deposito['user_id']]);<?php 
<?php $usuario<?php =<?php $stmt->fetch();<?php 
<?php 
<?php writeLog("USUÁRIO<?php DATA:<?php "<?php .<?php print_r($usuario,<?php true));<?php 
<?php 
<?php if<?php ($usuario<?php &&<?php !empty($usuario['indicacao']))<?php {<?php 
<?php writeLog("USUÁRIO<?php TEM<?php INDICAÇÃO:<?php "<?php .<?php $usuario['indicacao']);<?php 
<?php 
<?php //<?php Verifica<?php se<?php este<?php usuário<?php JÁ<?php teve<?php algum<?php depósito<?php aprovado<?php anteriormente<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php as<?php total_pagos<?php FROM<?php depositos<?php WHERE<?php user_id<?php =<?php :uid<?php AND<?php status<?php =<?php 'PAID'<?php AND<?php id<?php !=<?php :current_id");<?php 
<?php $stmt->execute([<?php 
<?php ':uid'<?php =><?php $deposito['user_id'],<?php 
<?php ':current_id'<?php =><?php $deposito['id']<?php 
<?php ]);<?php 
<?php $depositosAnteriores<?php =<?php $stmt->fetch();<?php 
<?php 
<?php writeLog("DEPÓSITOS<?php ANTERIORES<?php PAGOS:<?php "<?php .<?php $depositosAnteriores['total_pagos']);<?php 
<?php 
<?php //<?php CPA<?php só<?php é<?php pago<?php se<?php este<?php for<?php o<?php PRIMEIRO<?php depósito<?php aprovado<?php do<?php usuário<?php 
<?php if<?php ($depositosAnteriores['total_pagos']<?php ==<?php 0)<?php {<?php 
<?php writeLog("É<?php O<?php PRIMEIRO<?php DEPÓSITO,<?php VERIFICANDO<?php AFILIADO");<?php 
<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php id,<?php comissao_cpa,<?php banido<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :afiliado_id");<?php 
<?php $stmt->execute([':afiliado_id'<?php =><?php $usuario['indicacao']]);<?php 
<?php $afiliado<?php =<?php $stmt->fetch();<?php 
<?php 
<?php writeLog("AFILIADO<?php DATA:<?php "<?php .<?php print_r($afiliado,<?php true));<?php 
<?php 
<?php if<?php ($afiliado<?php &&<?php $afiliado['banido']<?php !=<?php 1<?php &&<?php !empty($afiliado['comissao_cpa']))<?php {<?php 
<?php $comissao<?php =<?php $afiliado['comissao_cpa'];<?php 
<?php 
<?php //<?php Credita<?php a<?php comissão<?php CPA<?php para<?php o<?php afiliado<?php 
<?php $stmt<?php =<?php $pdo->prepare("UPDATE<?php usuarios<?php SET<?php saldo<?php =<?php saldo<?php +<?php :comissao<?php WHERE<?php id<?php =<?php :afiliado_id");<?php 
<?php $stmt->execute([<?php 
<?php ':comissao'<?php =><?php $comissao,<?php 
<?php ':afiliado_id'<?php =><?php $afiliado['id']<?php 
<?php ]);<?php 
<?php 
<?php //<?php Tenta<?php inserir<?php na<?php tabela<?php transacoes_afiliados<?php (removendo<?php o<?php campo<?php 'tipo'<?php caso<?php não<?php exista)<?php 
<?php try<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("INSERT<?php INTO<?php transacoes_afiliados<?php 
<?php (afiliado_id,<?php usuario_id,<?php deposito_id,<?php valor,<?php created_at)<?php 
<?php VALUES<?php (:afiliado_id,<?php :usuario_id,<?php :deposito_id,<?php :valor,<?php NOW())");<?php 
<?php $stmt->execute([<?php 
<?php ':afiliado_id'<?php =><?php $afiliado['id'],<?php 
<?php ':usuario_id'<?php =><?php $deposito['user_id'],<?php 
<?php ':deposito_id'<?php =><?php $deposito['id'],<?php 
<?php ':valor'<?php =><?php $comissao<?php 
<?php ]);<?php 
<?php }<?php catch<?php (Exception<?php $insertError)<?php {<?php 
<?php writeLog("ERRO<?php AO<?php INSERIR<?php TRANSAÇÃO<?php AFILIADO:<?php "<?php .<?php $insertError->getMessage());<?php 
<?php }<?php 
<?php 
<?php writeLog("CPA<?php PAGO:<?php Afiliado<?php {$afiliado['id']}<?php recebeu<?php R$<?php {$comissao}<?php pelo<?php primeiro<?php depósito<?php do<?php usuário<?php {$deposito['user_id']}");<?php 
<?php }<?php else<?php {<?php 
<?php writeLog("CPA<?php NÃO<?php PAGO:<?php Afiliado<?php inválido<?php ou<?php sem<?php comissão");<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php writeLog("CPA<?php NÃO<?php PAGO:<?php Usuário<?php {$deposito['user_id']}<?php já<?php teve<?php {$depositosAnteriores['total_pagos']}<?php depósito(s)<?php aprovado(s)<?php anteriormente");<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php writeLog("USUÁRIO<?php SEM<?php INDICAÇÃO");<?php 
<?php }<?php 
<?php 
<?php $pdo->commit();<?php 
<?php writeLog("TRANSAÇÃO<?php FINALIZADA<?php COM<?php SUCESSO");<?php 
<?php echo<?php json_encode(['message'<?php =><?php 'OK']);<?php 
<?php 
}<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php $pdo->rollBack();<?php 
<?php writeLog("ERRO<?php GERAL:<?php "<?php .<?php $e->getMessage());<?php 
<?php writeLog("STACK<?php TRACE:<?php "<?php .<?php $e->getTraceAsString());<?php 
<?php http_response_code(500);<?php 
<?php echo<?php json_encode(['error'<?php =><?php 'Erro<?php interno']);<?php 
<?php exit;<?php 
}