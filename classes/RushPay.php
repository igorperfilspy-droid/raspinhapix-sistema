<?php

class<?php RushPay<?php {
<?php private<?php $pdo;
<?php private<?php $secretKey;
<?php private<?php $publicKey;
<?php private<?php $apiUrl;

<?php public<?php function<?php __construct($pdo)<?php {
<?php $this->pdo<?php =<?php $pdo;
<?php //<?php Chaves<?php via<?php variáveis<?php de<?php ambiente<?php (Railway)
<?php $this->secretKey<?php =<?php $_ENV['RUSHPAY_SECRET_KEY']<?php ??<?php getenv('RUSHPAY_SECRET_KEY')<?php ??<?php '213d1905-9ac0-4023-8dbd-0279918c7bcd';
<?php $this->publicKey<?php =<?php $_ENV['RUSHPAY_PUBLIC_KEY']<?php ??<?php getenv('RUSHPAY_PUBLIC_KEY')<?php ??<?php '50742ec4-8eac-4516-a957-b896209ce27c';
<?php $this->apiUrl<?php =<?php 'https://pay.rushpayoficial.com/api/v1';
<?php }

<?php public<?php function<?php createDeposit($amount,<?php $cpf,<?php $name,<?php $email,<?php $phone,<?php $callbackUrl,<?php $idempotencyKey,<?php $utmSource<?php =<?php null)<?php {
<?php $url<?php =<?php $this->apiUrl<?php .<?php '/transaction.purchase';
<?php 
<?php $payload<?php =<?php [
<?php 'name'<?php =><?php $name,
<?php 'email'<?php =><?php $email,
<?php 'cpf'<?php =><?php $cpf,
<?php 'phone'<?php =><?php $phone,
<?php 'paymentMethod'<?php =><?php 'PIX',
<?php 'amount'<?php =><?php intval($amount<?php *<?php 100),<?php //<?php Converter<?php para<?php centavos
<?php 'traceable'<?php =><?php true,
<?php 'postbackUrl'<?php =><?php $callbackUrl,
<?php 'externalId'<?php =><?php $idempotencyKey,
<?php 'items'<?php =><?php [
<?php [
<?php 'unitPrice'<?php =><?php intval($amount<?php *<?php 100),
<?php 'title'<?php =><?php 'Depósito<?php Raspadinha',
<?php 'quantity'<?php =><?php 1,
<?php 'tangible'<?php =><?php false
<?php ]
<?php ]
<?php ];

<?php //<?php Adicionar<?php UTM<?php Query<?php se<?php fornecido<?php (conforme<?php documentação<?php RushPay)
<?php if<?php ($utmSource)<?php {
<?php $payload['utmQuery']<?php =<?php 'utm_source='<?php .<?php urlencode($utmSource);
<?php }

<?php error_log("RushPay<?php Payload<?php Debug:<?php "<?php .<?php json_encode($payload));
<?php $ch<?php =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Content-Type:<?php application/json',
<?php 'Authorization:<?php '<?php .<?php $this->secretKey
<?php ],
<?php CURLOPT_TIMEOUT<?php =><?php 30,
<?php CURLOPT_CONNECTTIMEOUT<?php =><?php 10,
<?php CURLOPT_SSL_VERIFYPEER<?php =><?php true,<?php //<?php Importante<?php para<?php Railway
<?php CURLOPT_SSL_VERIFYHOST<?php =><?php 2
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError<?php =<?php curl_error($ch);
<?php curl_close($ch);

<?php if<?php ($curlError)<?php {
<?php throw<?php new<?php Exception('Erro<?php de<?php conexão<?php com<?php RushPay:<?php '<?php .<?php $curlError);
<?php }

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php HTTP<?php '<?php .<?php $httpCode<?php .<?php '<?php na<?php requisição<?php para<?php o<?php RushPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }

<?php $responseData<?php =<?php json_decode($response,<?php true);

<?php if<?php (!$responseData)<?php {
<?php throw<?php new<?php Exception('Resposta<?php inválida<?php do<?php RushPay.');
<?php }

<?php if<?php (!isset($responseData['id']))<?php {
<?php throw<?php new<?php Exception('Resposta<?php do<?php RushPay<?php não<?php contém<?php ID<?php da<?php transação.');
<?php }

<?php return<?php [
<?php 'transactionId'<?php =><?php $responseData['id'],
<?php 'qrcode'<?php =><?php $responseData['qrcode']<?php ??<?php null,
<?php 'pixCode'<?php =><?php $responseData['pixCode']<?php ??<?php null,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'status'<?php =><?php $responseData['status']<?php ??<?php 'PENDING',
<?php 'value'<?php =><?php $amount,
<?php 'expiresAt'<?php =><?php $responseData['expiresAt']<?php ??<?php null
<?php ];
<?php }

<?php public<?php function<?php getPaymentDetails($transactionId)<?php {
<?php $url<?php =<?php $this->apiUrl<?php .<?php '/transaction/'<?php .<?php $transactionId;

<?php $ch<?php =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Authorization:<?php '<?php .<?php $this->secretKey
<?php ],
<?php CURLOPT_TIMEOUT<?php =><?php 30,
<?php CURLOPT_SSL_VERIFYPEER<?php =><?php true,
<?php CURLOPT_SSL_VERIFYHOST<?php =><?php 2
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php consultar<?php transação:<?php HTTP<?php '<?php .<?php $httpCode);
<?php }

<?php $responseData<?php =<?php json_decode($response,<?php true);

<?php if<?php (!$responseData)<?php {
<?php throw<?php new<?php Exception('Resposta<?php inválida<?php ao<?php consultar<?php transação.');
<?php }

<?php return<?php [
<?php 'id'<?php =><?php $responseData['id']<?php ??<?php null,
<?php 'status'<?php =><?php $responseData['status']<?php ??<?php null,
<?php 'amount'<?php =><?php $responseData['amount']<?php ??<?php null,
<?php 'pixCode'<?php =><?php $responseData['pixCode']<?php ??<?php null,
<?php 'qrcode'<?php =><?php $responseData['qrcode']<?php ??<?php null,
<?php 'createdAt'<?php =><?php $responseData['createdAt']<?php ??<?php null,
<?php 'updatedAt'<?php =><?php $responseData['updatedAt']<?php ??<?php null,
<?php 'paidAt'<?php =><?php $responseData['paidAt']<?php ??<?php null,
<?php 'cancelledAt'<?php =><?php $responseData['cancelledAt']<?php ??<?php null,
<?php 'expiredAt'<?php =><?php $responseData['expiredAt']<?php ??<?php null,
<?php 'refundedAt'<?php =><?php $responseData['refundedAt']<?php ??<?php null,
<?php 'rejectedAt'<?php =><?php $responseData['rejectedAt']<?php ??<?php null,
<?php 'chargebackAt'<?php =><?php $responseData['chargebackAt']<?php ??<?php null
<?php ];
<?php }

<?php public<?php function<?php processWebhook($webhookData)<?php {
<?php //<?php Processar<?php dados<?php do<?php webhook<?php da<?php RushPay
<?php $transactionId<?php =<?php $webhookData['id']<?php ??<?php null;
<?php $status<?php =<?php $webhookData['status']<?php ??<?php null;

<?php if<?php (!$transactionId<?php ||<?php !$status)<?php {
<?php throw<?php new<?php Exception('Dados<?php do<?php webhook<?php incompletos');
<?php }

<?php //<?php Mapear<?php status<?php da<?php RushPay<?php para<?php nosso<?php sistema
<?php $statusMap<?php =<?php [
<?php 'PAID'<?php =><?php 'PAID',
<?php 'PENDING'<?php =><?php 'PENDING',
<?php 'CANCELLED'<?php =><?php 'CANCELLED',
<?php 'EXPIRED'<?php =><?php 'EXPIRED'
<?php ];

<?php $mappedStatus<?php =<?php $statusMap[$status]<?php ??<?php 'UNKNOWN';

<?php return<?php [
<?php 'transactionId'<?php =><?php $transactionId,
<?php 'status'<?php =><?php $mappedStatus,
<?php 'originalData'<?php =><?php $webhookData
<?php ];
<?php }

<?php public<?php function<?php checkPaymentStatus($transactionId)<?php {
<?php $url<?php =<?php $this->apiUrl<?php .<?php '/transaction.getPayment?id='<?php .<?php urlencode($transactionId);
<?php 
<?php $headers<?php =<?php [
<?php 'Authorization:<?php '<?php .<?php $this->secretKey,
<?php 'Content-Type:<?php application/json'
<?php ];

<?php $ch<?php =<?php curl_init();
<?php curl_setopt($ch,<?php CURLOPT_URL,<?php $url);
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php $headers);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php 2);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError<?php =<?php curl_error($ch);
<?php curl_close($ch);

<?php //<?php Log<?php para<?php debug
<?php error_log("RushPay<?php Status<?php Check<?php -<?php URL:<?php $url");
<?php error_log("RushPay<?php Status<?php Check<?php -<?php HTTP<?php Code:<?php $httpCode");
<?php error_log("RushPay<?php Status<?php Check<?php -<?php Response:<?php $response");
<?php error_log("RushPay<?php Status<?php Check<?php -<?php Curl<?php Error:<?php $curlError");

<?php if<?php ($httpCode<?php ===<?php 200<?php &&<?php !empty($response))<?php {
<?php $data<?php =<?php json_decode($response,<?php true);
<?php if<?php ($data<?php &&<?php isset($data['status']))<?php {
<?php return<?php $data;
<?php }
<?php }

<?php //<?php Se<?php a<?php API<?php não<?php funcionar,<?php retornar<?php PENDING<?php (não<?php creditar)
<?php return<?php [
<?php 'id'<?php =><?php $transactionId,
<?php 'status'<?php =><?php 'PENDING',
<?php 'message'<?php =><?php 'Aguardando<?php confirmação<?php da<?php RushPay'
<?php ];
<?php }
}

?>

