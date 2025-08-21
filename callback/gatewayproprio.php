<?php
session_start();
header('Content-Type:<?php application/json');
header("Access-Control-Allow-Origin:<?php *");
header("Access-Control-Allow-Methods:<?php POST,<?php OPTIONS");
header("Access-Control-Allow-Headers:<?php Content-Type");

//<?php CONFIGURAÇÃO DE LOGS -<?php ALTERE AQUI PARA ATIVAR/DESATIVAR
define('DEBUG_MODE',<?php false);<?php //<?php true =<?php logs ativos |<?php false =<?php logs desativados
define('LOG_FILE',<?php 'logs.txt');

//<?php Função para gravar logs apenas se DEBUG_MODE estiver ativo
function writeLog($message)<?php {
<?php if (DEBUG_MODE)<?php {
<?php file_put_contents(LOG_FILE,<?php date('d/m/Y H:i:s')<?php .<?php "<?php -<?php "<?php .<?php $message .<?php PHP_EOL,<?php FILE_APPEND);
<?php }
}

if ($_SERVER['REQUEST_METHOD']<?php ===<?php 'OPTIONS')<?php {
<?php http_response_code(200);
<?php exit;
}

if ($_SERVER['REQUEST_METHOD']<?php !==<?php 'POST')<?php {
<?php http_response_code(405);
<?php echo json_encode(['error'<?php =><?php 'Método não permitido']);
<?php exit;
}

$rawInput =<?php file_get_contents('php://input');
$data =<?php json_decode($rawInput,<?php true);

writeLog("PAYLOAD GATEWAY PRÓPRIO:<?php "<?php .<?php print_r($data,<?php true));
writeLog("----------------------------------------------------------");

if (!$data)<?php {
<?php http_response_code(400);
<?php echo json_encode(['error'<?php =><?php 'Payload inválido']);
<?php exit;
}

$transactionId =<?php $data['id']<?php ??<?php '';
$status =<?php $data['status']<?php ??<?php '';

if ($status !==<?php 'PAID'<?php ||<?php empty($transactionId))<?php {
<?php http_response_code(400);
<?php echo json_encode(['error'<?php =><?php 'Dados insuficientes ou transação não paga']);
<?php exit;
}

require_once __DIR__ .<?php '/../conexao.php';

try {
<?php $pdo->beginTransaction();
<?php writeLog("INICIANDO PROCESSO PARA TXN:<?php "<?php .<?php $transactionId);
<?php $stmt =<?php $pdo->prepare("SELECT id,<?php user_id,<?php valor,<?php status FROM depositos WHERE transactionId =<?php :txid AND gateway =<?php 'gatewayproprio'<?php LIMIT 1 FOR UPDATE");
<?php $stmt->execute([':txid'<?php =><?php $transactionId]);
<?php $deposito =<?php $stmt->fetch();
<?php if (!$deposito)<?php {
<?php $pdo->commit();
<?php writeLog("ERRO:<?php Depósito não encontrado para TXN:<?php "<?php .<?php $transactionId);
<?php http_response_code(404);
<?php echo json_encode(['error'<?php =><?php 'Depósito não encontrado']);
<?php exit;
<?php }
<?php writeLog("DEPÓSITO ENCONTRADO:<?php "<?php .<?php print_r($deposito,<?php true));
<?php if ($deposito['status']<?php ===<?php 'PAID')<?php {
<?php $pdo->commit();
<?php echo json_encode(['message'<?php =><?php 'Este pagamento já<?php foi aprovado']);
<?php exit;
<?php }
<?php //<?php Atualiza o status do depósito $stmt =<?php $pdo->prepare("UPDATE depositos SET status =<?php 'PAID',<?php updated_at =<?php NOW()<?php WHERE id =<?php :id");
<?php $stmt->execute([':id'<?php =><?php $deposito['id']]);
<?php writeLog("DEPÓSITO ATUALIZADO PARA PAID");
<?php //<?php Credita o saldo do usuário $stmt =<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php saldo +<?php :valor WHERE id =<?php :uid");
<?php $stmt->execute([
<?php ':valor'<?php =><?php $deposito['valor'],
<?php ':uid'<?php =><?php $deposito['user_id']
<?php ]);
<?php writeLog("SALDO CREDITADO:<?php R$<?php "<?php .<?php $deposito['valor']<?php .<?php "<?php para usuário "<?php .<?php $deposito['user_id']);
<?php //<?php VERIFICAÇÃO PARA CPA (APENAS PRIMEIRO DEPÓSITO)
<?php $stmt =<?php $pdo->prepare("SELECT indicacao FROM usuarios WHERE id =<?php :uid");
<?php $stmt->execute([':uid'<?php =><?php $deposito['user_id']]);
<?php $usuario =<?php $stmt->fetch();
<?php writeLog("USUÁRIO DATA:<?php "<?php .<?php print_r($usuario,<?php true));
<?php if ($usuario &&<?php !empty($usuario['indicacao']))<?php {
<?php writeLog("USUÁRIO TEM INDICAÇÃO:<?php "<?php .<?php $usuario['indicacao']);
<?php //<?php Verifica se este usuário JÁ<?php teve algum depósito aprovado anteriormente $stmt =<?php $pdo->prepare("SELECT COUNT(*)<?php as total_pagos FROM depositos WHERE user_id =<?php :uid AND status =<?php 'PAID'<?php AND id !=<?php :current_id");
<?php $stmt->execute([
<?php ':uid'<?php =><?php $deposito['user_id'],
<?php ':current_id'<?php =><?php $deposito['id']
<?php ]);
<?php $depositosAnteriores =<?php $stmt->fetch();
<?php writeLog("DEPÓSITOS ANTERIORES PAGOS:<?php "<?php .<?php $depositosAnteriores['total_pagos']);
<?php //<?php CPA só<?php é<?php pago se este for o PRIMEIRO depósito aprovado do usuário if ($depositosAnteriores['total_pagos']<?php ==<?php 0)<?php {
<?php writeLog("É<?php O PRIMEIRO DEPÓSITO,<?php VERIFICANDO AFILIADO");
<?php $stmt =<?php $pdo->prepare("SELECT id,<?php comissao_cpa,<?php banido FROM usuarios WHERE id =<?php :afiliado_id");
<?php $stmt->execute([':afiliado_id'<?php =><?php $usuario['indicacao']]);
<?php $afiliado =<?php $stmt->fetch();
<?php writeLog("AFILIADO DATA:<?php "<?php .<?php print_r($afiliado,<?php true));
<?php if ($afiliado &&<?php $afiliado['banido']<?php !=<?php 1 &&<?php !empty($afiliado['comissao_cpa']))<?php {
<?php $comissao =<?php $afiliado['comissao_cpa'];
<?php //<?php Credita a comissão CPA para o afiliado $stmt =<?php $pdo->prepare("UPDATE usuarios SET saldo =<?php saldo +<?php :comissao WHERE id =<?php :afiliado_id");
<?php $stmt->execute([
<?php ':comissao'<?php =><?php $comissao,
<?php ':afiliado_id'<?php =><?php $afiliado['id']
<?php ]);
<?php //<?php Tenta inserir na tabela transacoes_afiliados (removendo o campo 'tipo'<?php caso não exista)
<?php try {
<?php $stmt =<?php $pdo->prepare("INSERT INTO transacoes_afiliados (afiliado_id,<?php usuario_id,<?php deposito_id,<?php valor,<?php created_at)
<?php VALUES (:afiliado_id,<?php :usuario_id,<?php :deposito_id,<?php :valor,<?php NOW())");
<?php $stmt->execute([
<?php ':afiliado_id'<?php =><?php $afiliado['id'],
<?php ':usuario_id'<?php =><?php $deposito['user_id'],
<?php ':deposito_id'<?php =><?php $deposito['id'],
<?php ':valor'<?php =><?php $comissao ]);
<?php }<?php catch (Exception $insertError)<?php {
<?php writeLog("ERRO AO INSERIR TRANSAÇÃO AFILIADO:<?php "<?php .<?php $insertError->getMessage());
<?php }
<?php writeLog("CPA PAGO:<?php Afiliado {$afiliado['id']}<?php recebeu R$<?php {$comissao}<?php pelo primeiro depósito do usuário {$deposito['user_id']}");
<?php }<?php else {
<?php writeLog("CPA NÃO PAGO:<?php Afiliado inválido ou sem comissão");
<?php }
<?php }<?php else {
<?php writeLog("CPA NÃO PAGO:<?php Usuário {$deposito['user_id']}<?php já<?php teve {$depositosAnteriores['total_pagos']}<?php depósito(s)<?php aprovado(s)<?php anteriormente");
<?php }
<?php }<?php else {
<?php writeLog("USUÁRIO SEM INDICAÇÃO");
<?php }
<?php $pdo->commit();
<?php writeLog("TRANSAÇÃO FINALIZADA COM SUCESSO");
<?php echo json_encode(['message'<?php =><?php 'OK']);
<?php 
}<?php catch (Exception $e)<?php {
<?php $pdo->rollBack();
<?php writeLog("ERRO GERAL:<?php "<?php .<?php $e->getMessage());
<?php writeLog("STACK TRACE:<?php "<?php .<?php $e->getTraceAsString());
<?php http_response_code(500);
<?php echo json_encode(['error'<?php =><?php 'Erro interno']);
<?php exit;
}