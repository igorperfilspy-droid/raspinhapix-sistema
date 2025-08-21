<?php<?php 
header('Content-Type:<?php application/json');<?php 
header("Access-Control-Allow-Origin:<?php *");<?php 
header("Access-Control-Allow-Methods:<?php POST,<?php OPTIONS");<?php 
header("Access-Control-Allow-Headers:<?php Content-Type");<?php 
<?php 
//<?php CONFIGURAÇÃO<?php DE<?php LOGS<?php -<?php ALTERE<?php AQUI<?php PARA<?php ATIVAR/DESATIVAR<?php 
define('DEBUG_MODE',<?php false);<?php //<?php true<?php =<?php logs<?php ativos<?php |<?php false<?php =<?php logs<?php desativados<?php 
define('LOG_FILE',<?php 'logs_digitopay.txt');<?php 
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
try<?php {<?php 
<?php require_once<?php __DIR__<?php .<?php '/../conexao.php';<?php 
<?php 
<?php //<?php Receber<?php dados<?php do<?php webhook<?php 
<?php $input<?php =<?php file_get_contents('php://input');<?php 
<?php $data<?php =<?php json_decode($input,<?php true);<?php 
<?php 
<?php writeLog("PAYLOAD<?php DIGITOPAY:<?php "<?php .<?php print_r($data,<?php true));<?php 
<?php writeLog("----------------------------------------------------------");<?php 
<?php 
<?php if<?php (!$data)<?php {<?php 
<?php throw<?php new<?php Exception('Dados<?php inválidos<?php recebidos');<?php 
<?php }<?php 
<?php 
<?php //<?php Verificar<?php se<?php possui<?php os<?php campos<?php necessários<?php 
<?php if<?php (!isset($data['id'])<?php ||<?php !isset($data['status']))<?php {<?php 
<?php throw<?php new<?php Exception('Campos<?php obrigatórios<?php não<?php encontrados');<?php 
<?php }<?php 
<?php 
<?php $transactionId<?php =<?php $data['id'];<?php 
<?php $status<?php =<?php strtoupper($data['status']);<?php 
<?php $idempotencyKey<?php =<?php $data['idempotencyKey']<?php ??<?php null;<?php 
<?php 
<?php //<?php Mapear<?php status<?php da<?php DigitoPay<?php para<?php o<?php sistema<?php 
<?php $statusMap<?php =<?php [<?php 
<?php 'REALIZADO'<?php =><?php 'PAID',<?php 
<?php 'CANCELADO'<?php =><?php 'CANCELLED',<?php 
<?php 'EXPIRADO'<?php =><?php 'CANCELLED',<?php 
<?php 'PENDENTE'<?php =><?php 'PENDING',<?php 
<?php 'EM<?php PROCESSAMENTO'<?php =><?php 'PENDING',<?php 
<?php 'ANALISE'<?php =><?php 'PENDING',<?php 
<?php 'ERRO'<?php =><?php 'FAILED'<?php 
<?php ];<?php 
<?php 
<?php $newStatus<?php =<?php $statusMap[$status]<?php ??<?php 'PENDING';<?php 
<?php 
<?php writeLog("INICIANDO<?php PROCESSO<?php PARA<?php TXN:<?php "<?php .<?php $transactionId<?php .<?php "<?php |<?php STATUS:<?php "<?php .<?php $newStatus);<?php 
<?php 
<?php //<?php Buscar<?php a<?php transação<?php no<?php banco<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php id,<?php user_id,<?php valor,<?php status,<?php gateway<?php 
<?php FROM<?php depositos<?php 
<?php WHERE<?php transactionId<?php =<?php :transactionId<?php 
<?php OR<?php idempotency_key<?php =<?php :idempotencyKey<?php 
<?php LIMIT<?php 1<?php 
<?php ");<?php 
<?php 
<?php $stmt->execute([<?php 
<?php ':transactionId'<?php =><?php $transactionId,<?php 
<?php ':idempotencyKey'<?php =><?php $idempotencyKey<?php 
<?php ]);<?php 
<?php 
<?php $deposito<?php =<?php $stmt->fetch();<?php 
<?php 
<?php if<?php (!$deposito)<?php {<?php 
<?php //<?php Se<?php não<?php encontrou<?php por<?php transactionId<?php ou<?php idempotencyKey,<?php tentar<?php buscar<?php apenas<?php por<?php transactionId<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php id,<?php user_id,<?php valor,<?php status,<?php gateway<?php 
<?php FROM<?php depositos<?php 
<?php WHERE<?php transactionId<?php =<?php :transactionId<?php 
<?php LIMIT<?php 1<?php 
<?php ");<?php 
<?php 
<?php $stmt->execute([':transactionId'<?php =><?php $transactionId]);<?php 
<?php $deposito<?php =<?php $stmt->fetch();<?php 
<?php 
<?php if<?php (!$deposito)<?php {<?php 
<?php writeLog("ERRO:<?php Depósito<?php não<?php encontrado<?php para<?php TXN:<?php "<?php .<?php $transactionId);<?php 
<?php throw<?php new<?php Exception('Transação<?php não<?php encontrada:<?php '<?php .<?php $transactionId);<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php writeLog("DEPÓSITO<?php ENCONTRADO:<?php "<?php .<?php print_r($deposito,<?php true));<?php 
<?php 
<?php //<?php Verificar<?php se<?php é<?php gateway<?php DigitoPay<?php 
<?php if<?php ($deposito['gateway']<?php !==<?php 'digitopay')<?php {<?php 
<?php throw<?php new<?php Exception('Gateway<?php incorreto<?php para<?php esta<?php transação');<?php 
<?php }<?php 
<?php 
<?php //<?php Se<?php o<?php status<?php já<?php foi<?php processado,<?php retornar<?php sucesso<?php 
<?php if<?php ($deposito['status']<?php ===<?php $newStatus)<?php {<?php 
<?php echo<?php json_encode(['status'<?php =><?php 'success',<?php 'message'<?php =><?php 'Status<?php já<?php processado']);<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php //<?php Se<?php a<?php transação<?php foi<?php aprovada,<?php processar<?php com<?php lógica<?php completa<?php incluindo<?php CPA<?php 
<?php if<?php ($newStatus<?php ===<?php 'PAID')<?php {<?php 
<?php //<?php Verificar<?php se<?php o<?php valor<?php já<?php não<?php foi<?php creditado<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php COUNT(*)<?php as<?php count<?php 
<?php FROM<?php transacoes<?php 
<?php WHERE<?php tipo<?php =<?php 'DEPOSIT'<?php 
<?php AND<?php referencia<?php =<?php :transactionId<?php 
<?php AND<?php status<?php =<?php 'COMPLETED'<?php 
<?php ");<?php 
<?php 
<?php $stmt->execute([':transactionId'<?php =><?php $transactionId]);<?php 
<?php $jaProcessado<?php =<?php $stmt->fetchColumn();<?php 
<?php 
<?php if<?php ($jaProcessado<?php ==<?php 0)<?php {<?php 
<?php try<?php {<?php 
<?php $pdo->beginTransaction();<?php 
<?php 
<?php //<?php Atualizar<?php status<?php da<?php transação<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php UPDATE<?php depositos<?php 
<?php SET<?php status<?php =<?php :status,<?php 
<?php updated_at<?php =<?php NOW(),<?php 
<?php webhook_data<?php =<?php :webhook_data<?php 
<?php WHERE<?php id<?php =<?php :id<?php 
<?php ");<?php 
<?php 
<?php $result<?php =<?php $stmt->execute([<?php 
<?php ':status'<?php =><?php $newStatus,<?php 
<?php ':webhook_data'<?php =><?php $input,<?php 
<?php ':id'<?php =><?php $deposito['id']<?php 
<?php ]);<?php 
<?php 
<?php if<?php (!$result)<?php {<?php 
<?php throw<?php new<?php Exception('Erro<?php ao<?php atualizar<?php status<?php da<?php transação');<?php 
<?php }<?php 
<?php 
<?php writeLog("DEPÓSITO<?php ATUALIZADO<?php PARA<?php PAID");<?php 
<?php 
<?php //<?php Buscar<?php saldo<?php atual<?php do<?php usuário<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :user_id");<?php 
<?php $stmt->execute([':user_id'<?php =><?php $deposito['user_id']]);<?php 
<?php $saldoAtual<?php =<?php $stmt->fetchColumn();<?php 
<?php 
<?php if<?php ($saldoAtual<?php ===<?php false)<?php {<?php 
<?php throw<?php new<?php Exception('Usuário<?php não<?php encontrado');<?php 
<?php }<?php 
<?php 
<?php //<?php Atualizar<?php saldo<?php do<?php usuário<?php 
<?php $novoSaldo<?php =<?php $saldoAtual<?php +<?php $deposito['valor'];<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php UPDATE<?php usuarios<?php 
<?php SET<?php saldo<?php =<?php :novo_saldo<?php 
<?php WHERE<?php id<?php =<?php :user_id<?php 
<?php ");<?php 
<?php 
<?php $stmt->execute([<?php 
<?php ':novo_saldo'<?php =><?php $novoSaldo,<?php 
<?php ':user_id'<?php =><?php $deposito['user_id']<?php 
<?php ]);<?php 
<?php 
<?php writeLog("SALDO<?php CREDITADO:<?php R$<?php "<?php .<?php $deposito['valor']<?php .<?php "<?php para<?php usuário<?php "<?php .<?php $deposito['user_id']);<?php 
<?php 
<?php //<?php Registrar<?php a<?php transação<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php INSERT<?php INTO<?php transacoes<?php (<?php 
<?php user_id,<?php tipo,<?php valor,<?php saldo_anterior,<?php saldo_posterior,<?php 
<?php status,<?php referencia,<?php gateway,<?php descricao,<?php created_at<?php 
<?php )<?php VALUES<?php (<?php 
<?php :user_id,<?php 'DEPOSIT',<?php :valor,<?php :saldo_anterior,<?php :saldo_posterior,<?php 
<?php 'COMPLETED',<?php :referencia,<?php 'digitopay',<?php :descricao,<?php NOW()<?php 
<?php )<?php 
<?php ");<?php 
<?php 
<?php $stmt->execute([<?php 
<?php ':user_id'<?php =><?php $deposito['user_id'],<?php 
<?php ':valor'<?php =><?php $deposito['valor'],<?php 
<?php ':saldo_anterior'<?php =><?php $saldoAtual,<?php 
<?php ':saldo_posterior'<?php =><?php $novoSaldo,<?php 
<?php ':referencia'<?php =><?php $transactionId,<?php 
<?php ':descricao'<?php =><?php 'Depósito<?php via<?php DigitoPay<?php -<?php '<?php .<?php $transactionId<?php 
<?php ]);<?php 
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
<?php //<?php Tenta<?php inserir<?php na<?php tabela<?php transacoes_afiliados<?php 
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
<?php writeLog("CPA<?php NÃO<?php PAGO:<?php Usuário<?php {$deposito['user_id']}<?php já<?php teve<?php {$depositosAnteriores['total_pagos']}<?php depósito(s)<?php pago(s)<?php anteriormente");<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php writeLog("USUÁRIO<?php SEM<?php INDICAÇÃO");<?php 
<?php }<?php 
<?php 
<?php $pdo->commit();<?php 
<?php writeLog("TRANSAÇÃO<?php FINALIZADA<?php COM<?php SUCESSO");<?php 
<?php 
<?php }<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php $pdo->rollback();<?php 
<?php writeLog("ERRO<?php GERAL:<?php "<?php .<?php $e->getMessage());<?php 
<?php writeLog("STACK<?php TRACE:<?php "<?php .<?php $e->getTraceAsString());<?php 
<?php throw<?php new<?php Exception('Erro<?php ao<?php processar<?php aprovação:<?php '<?php .<?php $e->getMessage());<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php writeLog("TRANSAÇÃO<?php JÁ<?php PROCESSADA<?php ANTERIORMENTE");<?php 
<?php }<?php 
<?php }<?php else<?php {<?php 
<?php //<?php Para<?php outros<?php status<?php que<?php não<?php são<?php APPROVED,<?php apenas<?php atualizar<?php o<?php status<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php UPDATE<?php depositos<?php 
<?php SET<?php status<?php =<?php :status,<?php 
<?php updated_at<?php =<?php NOW(),<?php 
<?php webhook_data<?php =<?php :webhook_data<?php 
<?php WHERE<?php id<?php =<?php :id<?php 
<?php ");<?php 
<?php 
<?php $result<?php =<?php $stmt->execute([<?php 
<?php ':status'<?php =><?php $newStatus,<?php 
<?php ':webhook_data'<?php =><?php $input,<?php 
<?php ':id'<?php =><?php $deposito['id']<?php 
<?php ]);<?php 
<?php 
<?php if<?php (!$result)<?php {<?php 
<?php throw<?php new<?php Exception('Erro<?php ao<?php atualizar<?php status<?php da<?php transação');<?php 
<?php }<?php 
<?php 
<?php writeLog("STATUS<?php ATUALIZADO<?php PARA:<?php "<?php .<?php $newStatus);<?php 
<?php }<?php 
<?php 
<?php //<?php Resposta<?php de<?php sucesso<?php 
<?php echo<?php json_encode([<?php 
<?php 'status'<?php =><?php 'success',<?php 
<?php 'message'<?php =><?php 'Webhook<?php processado<?php com<?php sucesso',<?php 
<?php 'transaction_id'<?php =><?php $transactionId,<?php 
<?php 'new_status'<?php =><?php $newStatus<?php 
<?php ]);<?php 
<?php 
}<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php writeLog("ERRO<?php GERAL:<?php "<?php .<?php $e->getMessage());<?php 
<?php http_response_code(400);<?php 
<?php echo<?php json_encode([<?php 
<?php 'status'<?php =><?php 'error',<?php 
<?php 'message'<?php =><?php $e->getMessage()<?php 
<?php ]);<?php 
}