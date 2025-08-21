<?php<?php 
/**<?php 
<?php *<?php DigitoPay<?php Withdraw<?php Webhook<?php -<?php Versão<?php Final<?php 
<?php *<?php callback/digitopay_withdraw.php<?php 
<?php *<?php 
<?php *<?php Este<?php webhook<?php recebe<?php notificações<?php da<?php DigitoPay<?php sobre<?php o<?php status<?php dos<?php saques<?php 
<?php *<?php e<?php atualiza<?php automaticamente<?php o<?php status<?php no<?php banco<?php de<?php dados<?php 
<?php */<?php 
<?php 
header('Content-Type:<?php application/json');<?php 
<?php 
//<?php Log<?php detalhado<?php para<?php debug<?php (opcional<?php -<?php remover<?php em<?php produção<?php se<?php não<?php precisar)<?php 
$enableDebugLog<?php =<?php true;<?php 
$logFile<?php =<?php __DIR__<?php .<?php '/../logs/digitopay_webhook.log';<?php 
<?php 
function<?php writeLog($message)<?php {<?php 
<?php global<?php $logFile,<?php $enableDebugLog;<?php 
<?php if<?php (!$enableDebugLog)<?php return;<?php 
<?php 
<?php if<?php (!is_dir(dirname($logFile)))<?php {<?php 
<?php mkdir(dirname($logFile),<?php 0755,<?php true);<?php 
<?php }<?php 
<?php 
<?php $timestamp<?php =<?php date('Y-m-d<?php H:i:s');<?php 
<?php file_put_contents($logFile,<?php "[$timestamp]<?php $message\n",<?php FILE_APPEND);<?php 
}<?php 
<?php 
//<?php Log<?php do<?php acesso<?php 
writeLog("===<?php WEBHOOK<?php ACCESSED<?php ===");<?php 
writeLog("Method:<?php "<?php .<?php $_SERVER['REQUEST_METHOD']);<?php 
writeLog("User-Agent:<?php "<?php .<?php ($_SERVER['HTTP_USER_AGENT']<?php ??<?php 'Unknown'));<?php 
writeLog("Raw<?php Input:<?php "<?php .<?php file_get_contents('php://input'));<?php 
<?php 
//<?php Verificar<?php método<?php HTTP<?php 
if<?php ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {<?php 
<?php http_response_code(405);<?php 
<?php $response<?php =<?php ['error'<?php =><?php 'Método<?php não<?php permitido',<?php 'allowed'<?php =><?php 'POST'];<?php 
<?php writeLog("ERROR:<?php Método<?php inválido<?php -<?php "<?php .<?php $_SERVER['REQUEST_METHOD']);<?php 
<?php echo<?php json_encode($response);<?php 
<?php exit;<?php 
}<?php 
<?php 
try<?php {<?php 
<?php require_once<?php __DIR__<?php .<?php '/../conexao.php';<?php 
<?php 
<?php //<?php Receber<?php e<?php validar<?php dados<?php do<?php webhook<?php 
<?php $input<?php =<?php file_get_contents('php://input');<?php 
<?php $data<?php =<?php json_decode($input,<?php true);<?php 
<?php 
<?php writeLog("Parsed<?php webhook<?php data:<?php "<?php .<?php json_encode($data));<?php 
<?php 
<?php if<?php (!$data)<?php {<?php 
<?php throw<?php new<?php Exception('Dados<?php JSON<?php inválidos<?php recebidos');<?php 
<?php }<?php 
<?php 
<?php //<?php Verificar<?php campos<?php obrigatórios<?php 
<?php if<?php (!isset($data['id'])<?php ||<?php !isset($data['status']))<?php {<?php 
<?php throw<?php new<?php Exception('Campos<?php obrigatórios<?php não<?php encontrados<?php (id,<?php status)');<?php 
<?php }<?php 
<?php 
<?php $transactionId<?php =<?php $data['id'];<?php 
<?php $status<?php =<?php strtoupper(trim($data['status']));<?php 
<?php $idempotencyKey<?php =<?php $data['idempotencyKey']<?php ??<?php null;<?php 
<?php 
<?php writeLog("Processing<?php -<?php Transaction<?php ID:<?php $transactionId,<?php Status:<?php $status,<?php Idempotency:<?php $idempotencyKey");<?php 
<?php 
<?php //<?php Mapeamento<?php completo<?php de<?php status<?php DigitoPay<?php para<?php sistema<?php interno<?php 
<?php $statusMap<?php =<?php [<?php 
<?php 'REALIZADO'<?php =><?php 'PAID',<?php 
<?php 'CANCELADO'<?php =><?php 'CANCELLED',<?php 
<?php 'ERRO'<?php =><?php 'FAILED',<?php 
<?php 'PENDENTE'<?php =><?php 'PENDING',<?php 
<?php 'EM<?php PROCESSAMENTO'<?php =><?php 'PROCESSING',<?php 
<?php 'ANALISE'<?php =><?php 'PROCESSING',<?php 
<?php 'APPROVED'<?php =><?php 'PAID',<?php 
<?php 'REJECTED'<?php =><?php 'CANCELLED',<?php 
<?php 'COMPLETED'<?php =><?php 'PAID',<?php 
<?php 'FAILED'<?php =><?php 'FAILED',<?php 
<?php 'PROCESSING'<?php =><?php 'PROCESSING',<?php 
<?php 'SUCCESS'<?php =><?php 'PAID',<?php 
<?php 'CONFIRMED'<?php =><?php 'PAID'<?php 
<?php ];<?php 
<?php 
<?php $newStatus<?php =<?php $statusMap[$status]<?php ??<?php 'PROCESSING';<?php 
<?php writeLog("Status<?php mapping:<?php $status<?php -><?php $newStatus");<?php 
<?php 
<?php //<?php Buscar<?php o<?php saque<?php no<?php banco<?php usando<?php múltiplos<?php critérios<?php 
<?php $saque<?php =<?php null;<?php 
<?php $searchMethod<?php =<?php '';<?php 
<?php 
<?php //<?php 1.<?php Buscar<?php por<?php transaction_id_digitopay<?php (mais<?php confiável)<?php 
<?php if<?php ($transactionId)<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php id,<?php user_id,<?php valor,<?php status,<?php gateway<?php 
<?php FROM<?php saques<?php 
<?php WHERE<?php transaction_id_digitopay<?php =<?php :transaction_id<?php 
<?php AND<?php gateway<?php =<?php 'digitopay'<?php 
<?php LIMIT<?php 1<?php 
<?php ");<?php 
<?php $stmt->execute([':transaction_id'<?php =><?php $transactionId]);<?php 
<?php $saque<?php =<?php $stmt->fetch();<?php 
<?php if<?php ($saque)<?php $searchMethod<?php =<?php 'transaction_id_digitopay';<?php 
<?php }<?php 
<?php 
<?php //<?php 2.<?php Buscar<?php por<?php idempotency_key<?php se<?php não<?php encontrou<?php 
<?php if<?php (!$saque<?php &&<?php $idempotencyKey)<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php id,<?php user_id,<?php valor,<?php status,<?php gateway<?php 
<?php FROM<?php saques<?php 
<?php WHERE<?php digitopay_idempotency_key<?php =<?php :idempotency_key<?php 
<?php AND<?php gateway<?php =<?php 'digitopay'<?php 
<?php LIMIT<?php 1<?php 
<?php ");<?php 
<?php $stmt->execute([':idempotency_key'<?php =><?php $idempotencyKey]);<?php 
<?php $saque<?php =<?php $stmt->fetch();<?php 
<?php if<?php ($saque)<?php $searchMethod<?php =<?php 'idempotency_key';<?php 
<?php }<?php 
<?php 
<?php //<?php 3.<?php Último<?php recurso:<?php saque<?php mais<?php recente<?php em<?php processamento<?php (últimas<?php 2<?php horas)<?php 
<?php if<?php (!$saque)<?php {<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php id,<?php user_id,<?php valor,<?php status,<?php gateway<?php 
<?php FROM<?php saques<?php 
<?php WHERE<?php gateway<?php =<?php 'digitopay'<?php 
<?php AND<?php status<?php IN<?php ('PROCESSING',<?php 'EM<?php PROCESSAMENTO',<?php 'PENDING')<?php 
<?php AND<?php created_at<?php >=<?php DATE_SUB(NOW(),<?php INTERVAL<?php 2<?php HOUR)<?php 
<?php ORDER<?php BY<?php updated_at<?php DESC<?php 
<?php LIMIT<?php 1<?php 
<?php ");<?php 
<?php $stmt->execute();<?php 
<?php $saque<?php =<?php $stmt->fetch();<?php 
<?php if<?php ($saque)<?php $searchMethod<?php =<?php 'recent_processing';<?php 
<?php }<?php 
<?php 
<?php writeLog("Search<?php result:<?php "<?php .<?php ($saque<?php ?<?php "Found<?php via<?php $searchMethod"<?php :<?php "Not<?php found"));<?php 
<?php writeLog("Saque<?php data:<?php "<?php .<?php json_encode($saque));<?php 
<?php 
<?php if<?php (!$saque)<?php {<?php 
<?php $error<?php =<?php "Saque<?php não<?php encontrado<?php para<?php transactionId:<?php $transactionId";<?php 
<?php writeLog("ERROR:<?php $error");<?php 
<?php 
<?php //<?php Retornar<?php sucesso<?php mesmo<?php assim<?php para<?php evitar<?php reenvios<?php desnecessários<?php 
<?php echo<?php json_encode([<?php 
<?php 'status'<?php =><?php 'success',<?php 
<?php 'message'<?php =><?php 'Webhook<?php recebido<?php mas<?php saque<?php não<?php encontrado',<?php 
<?php 'transaction_id'<?php =><?php $transactionId<?php 
<?php ]);<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php //<?php Verificar<?php se<?php já<?php está<?php no<?php status<?php final<?php 
<?php if<?php ($saque['status']<?php ===<?php $newStatus)<?php {<?php 
<?php writeLog("Status<?php já<?php atualizado:<?php {$saque['status']}");<?php 
<?php echo<?php json_encode([<?php 
<?php 'status'<?php =><?php 'success',<?php 
<?php 'message'<?php =><?php 'Status<?php já<?php processado',<?php 
<?php 'saque_id'<?php =><?php $saque['id'],<?php 
<?php 'current_status'<?php =><?php $saque['status']<?php 
<?php ]);<?php 
<?php exit;<?php 
<?php }<?php 
<?php 
<?php //<?php Iniciar<?php transação<?php para<?php atualização<?php 
<?php $pdo->beginTransaction();<?php 
<?php 
<?php try<?php {<?php 
<?php //<?php Atualizar<?php status<?php do<?php saque<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php UPDATE<?php saques<?php 
<?php SET<?php status<?php =<?php :status,<?php 
<?php transaction_id_digitopay<?php =<?php COALESCE(transaction_id_digitopay,<?php :transaction_id),<?php 
<?php digitopay_idempotency_key<?php =<?php COALESCE(digitopay_idempotency_key,<?php :idempotency_key),<?php 
<?php webhook_data<?php =<?php :webhook_data,<?php 
<?php updated_at<?php =<?php NOW()<?php 
<?php WHERE<?php id<?php =<?php :id<?php 
<?php ");<?php 
<?php 
<?php $updateResult<?php =<?php $stmt->execute([<?php 
<?php ':status'<?php =><?php $newStatus,<?php 
<?php ':transaction_id'<?php =><?php $transactionId,<?php 
<?php ':idempotency_key'<?php =><?php $idempotencyKey,<?php 
<?php ':webhook_data'<?php =><?php $input,<?php 
<?php ':id'<?php =><?php $saque['id']<?php 
<?php ]);<?php 
<?php 
<?php if<?php (!$updateResult)<?php {<?php 
<?php throw<?php new<?php Exception('Falha<?php ao<?php atualizar<?php status<?php do<?php saque<?php no<?php banco');<?php 
<?php }<?php 
<?php 
<?php writeLog("Status<?php updated:<?php {$saque['status']}<?php -><?php $newStatus<?php for<?php saque<?php ID<?php {$saque['id']}");<?php 
<?php 
<?php //<?php Processar<?php estorno<?php se<?php saque<?php foi<?php cancelado<?php ou<?php falhou<?php 
<?php if<?php (in_array($newStatus,<?php ['CANCELLED',<?php 'FAILED']))<?php {<?php 
<?php writeLog("Processing<?php refund<?php for<?php failed/cancelled<?php withdrawal");<?php 
<?php 
<?php //<?php Verificar<?php se<?php estorno<?php já<?php foi<?php processado<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php SELECT<?php COUNT(*)<?php as<?php count<?php 
<?php FROM<?php transacoes<?php 
<?php WHERE<?php tipo<?php =<?php 'REFUND'<?php 
<?php AND<?php referencia<?php =<?php :transaction_id<?php 
<?php AND<?php status<?php =<?php 'COMPLETED'<?php 
<?php ");<?php 
<?php $stmt->execute([':transaction_id'<?php =><?php $transactionId]);<?php 
<?php $jaEstornado<?php =<?php $stmt->fetchColumn();<?php 
<?php 
<?php if<?php ($jaEstornado<?php ==<?php 0)<?php {<?php 
<?php //<?php Buscar<?php saldo<?php atual<?php do<?php usuário<?php 
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php saldo<?php FROM<?php usuarios<?php WHERE<?php id<?php =<?php :user_id<?php FOR<?php UPDATE");<?php 
<?php $stmt->execute([':user_id'<?php =><?php $saque['user_id']]);<?php 
<?php $saldoAtual<?php =<?php $stmt->fetchColumn();<?php 
<?php 
<?php if<?php ($saldoAtual<?php ===<?php false)<?php {<?php 
<?php throw<?php new<?php Exception('Usuário<?php não<?php encontrado<?php para<?php estorno');<?php 
<?php }<?php 
<?php 
<?php //<?php Devolver<?php valor<?php para<?php o<?php usuário<?php 
<?php $novoSaldo<?php =<?php $saldoAtual<?php +<?php $saque['valor'];<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php UPDATE<?php usuarios<?php 
<?php SET<?php saldo<?php =<?php :novo_saldo,<?php updated_at<?php =<?php NOW()<?php 
<?php WHERE<?php id<?php =<?php :user_id<?php 
<?php ");<?php 
<?php $stmt->execute([<?php 
<?php ':novo_saldo'<?php =><?php $novoSaldo,<?php 
<?php ':user_id'<?php =><?php $saque['user_id']<?php 
<?php ]);<?php 
<?php 
<?php //<?php Registrar<?php transação<?php de<?php estorno<?php 
<?php $stmt<?php =<?php $pdo->prepare("<?php 
<?php INSERT<?php INTO<?php transacoes<?php (<?php 
<?php user_id,<?php tipo,<?php valor,<?php saldo_anterior,<?php saldo_posterior,<?php 
<?php status,<?php referencia,<?php gateway,<?php descricao,<?php created_at<?php 
<?php )<?php VALUES<?php (<?php 
<?php :user_id,<?php 'REFUND',<?php :valor,<?php :saldo_anterior,<?php :saldo_posterior,<?php 
<?php 'COMPLETED',<?php :referencia,<?php 'digitopay',<?php :descricao,<?php NOW()<?php 
<?php )<?php 
<?php ");<?php 
<?php 
<?php $descricao<?php =<?php 'Estorno<?php automático<?php -<?php Saque<?php DigitoPay<?php '<?php .<?php 
<?php ($newStatus<?php ===<?php 'CANCELLED'<?php ?<?php 'cancelado'<?php :<?php 'falhou')<?php .<?php 
<?php '<?php -<?php '<?php .<?php $transactionId;<?php 
<?php 
<?php $stmt->execute([<?php 
<?php ':user_id'<?php =><?php $saque['user_id'],<?php 
<?php ':valor'<?php =><?php $saque['valor'],<?php 
<?php ':saldo_anterior'<?php =><?php $saldoAtual,<?php 
<?php ':saldo_posterior'<?php =><?php $novoSaldo,<?php 
<?php ':referencia'<?php =><?php $transactionId,<?php 
<?php ':descricao'<?php =><?php $descricao<?php 
<?php ]);<?php 
<?php 
<?php writeLog("Refund<?php processed:<?php R$<?php {$saque['valor']}<?php returned<?php to<?php user<?php {$saque['user_id']}");<?php 
<?php }<?php else<?php {<?php 
<?php writeLog("Refund<?php already<?php processed<?php for<?php transaction<?php $transactionId");<?php 
<?php }<?php 
<?php }<?php 
<?php 
<?php $pdo->commit();<?php 
<?php writeLog("Transaction<?php committed<?php successfully");<?php 
<?php 
<?php }<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php $pdo->rollback();<?php 
<?php writeLog("Transaction<?php rolled<?php back:<?php "<?php .<?php $e->getMessage());<?php 
<?php throw<?php new<?php Exception('Erro<?php ao<?php processar<?php webhook:<?php '<?php .<?php $e->getMessage());<?php 
<?php }<?php 
<?php 
<?php //<?php Resposta<?php de<?php sucesso<?php 
<?php $response<?php =<?php [<?php 
<?php 'status'<?php =><?php 'success',<?php 
<?php 'message'<?php =><?php 'Webhook<?php processado<?php com<?php sucesso',<?php 
<?php 'transaction_id'<?php =><?php $transactionId,<?php 
<?php 'saque_id'<?php =><?php $saque['id'],<?php 
<?php 'old_status'<?php =><?php $saque['status'],<?php 
<?php 'new_status'<?php =><?php $newStatus,<?php 
<?php 'search_method'<?php =><?php $searchMethod,<?php 
<?php 'timestamp'<?php =><?php date('Y-m-d<?php H:i:s')<?php 
<?php ];<?php 
<?php 
<?php writeLog("SUCCESS:<?php "<?php .<?php json_encode($response));<?php 
<?php echo<?php json_encode($response);<?php 
<?php 
}<?php catch<?php (Exception<?php $e)<?php {<?php 
<?php http_response_code(400);<?php 
<?php 
<?php $error<?php =<?php [<?php 
<?php 'status'<?php =><?php 'error',<?php 
<?php 'message'<?php =><?php $e->getMessage(),<?php 
<?php 'timestamp'<?php =><?php date('Y-m-d<?php H:i:s')<?php 
<?php ];<?php 
<?php 
<?php writeLog("ERROR:<?php "<?php .<?php json_encode($error));<?php 
<?php echo<?php json_encode($error);<?php 
}<?php 
?>