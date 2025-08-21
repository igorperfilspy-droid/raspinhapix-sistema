<?php
//<?php CLASSE RUSHPAY OTIMIZADA PARA RAILWAY
//<?php Substitua o arquivo classes/RushPay.php original por este

class RushPay {
<?php private $pdo;
<?php private $secretKey;
<?php private $publicKey;
<?php private $apiUrl;

<?php public function __construct($pdo)<?php {
<?php $this->pdo =<?php $pdo;
<?php //<?php Chaves via variáveis de ambiente (Railway)
<?php $this->secretKey =<?php $_ENV['RUSHPAY_SECRET_KEY']<?php ??<?php getenv('RUSHPAY_SECRET_KEY')<?php ??<?php '213d1905-9ac0-4023-8dbd-0279918c7bcd';
<?php $this->publicKey =<?php $_ENV['RUSHPAY_PUBLIC_KEY']<?php ??<?php getenv('RUSHPAY_PUBLIC_KEY')<?php ??<?php '50742ec4-8eac-4516-a957-b896209ce27c';
<?php $this->apiUrl =<?php 'https://pay.rushpayoficial.com/api/v1';
<?php //<?php Log de inicialização (apenas em desenvolvimento)
<?php if (($_ENV['RAILWAY_ENVIRONMENT']<?php ??<?php 'production')<?php ===<?php 'development')<?php {
<?php error_log("RushPay inicializado na Railway");
<?php }
<?php }

<?php public function createDeposit($amount,<?php $cpf,<?php $name,<?php $email,<?php $phone,<?php $callbackUrl,<?php $idempotencyKey,<?php $utmSource =<?php null)<?php {
<?php $url =<?php $this->apiUrl .<?php '/transaction.purchase';
<?php $payload =<?php [
<?php 'name'<?php =><?php $name,
<?php 'email'<?php =><?php $email,
<?php 'cpf'<?php =><?php $cpf,
<?php 'phone'<?php =><?php $phone,
<?php 'paymentMethod'<?php =><?php 'PIX',
<?php 'amount'<?php =><?php intval($amount *<?php 100),<?php //<?php Converter para centavos 'traceable'<?php =><?php true,
<?php 'postbackUrl'<?php =><?php $callbackUrl,
<?php 'externalId'<?php =><?php $idempotencyKey,
<?php 'items'<?php =><?php [
<?php [
<?php 'unitPrice'<?php =><?php intval($amount *<?php 100),
<?php 'title'<?php =><?php 'Depósito Raspadinha',
<?php 'quantity'<?php =><?php 1,
<?php 'tangible'<?php =><?php false ]
<?php ]
<?php ];

<?php //<?php Adicionar UTM Query se fornecido (conforme documentação RushPay)
<?php if ($utmSource)<?php {
<?php $payload['utmQuery']<?php =<?php 'utm_source='<?php .<?php urlencode($utmSource);
<?php }

<?php error_log("RushPay Payload Debug:<?php "<?php .<?php json_encode($payload));
<?php $ch =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Content-Type:<?php application/json',
<?php 'Authorization:<?php '<?php .<?php $this->secretKey ],
<?php CURLOPT_TIMEOUT =><?php 30,
<?php CURLOPT_CONNECTTIMEOUT =><?php 10,
<?php CURLOPT_SSL_VERIFYPEER =><?php true,<?php //<?php Importante para Railway CURLOPT_SSL_VERIFYHOST =><?php 2 ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError =<?php curl_error($ch);
<?php curl_close($ch);

<?php if ($curlError)<?php {
<?php throw new Exception('Erro de conexão com RushPay:<?php '<?php .<?php $curlError);
<?php }

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro HTTP '<?php .<?php $httpCode .<?php '<?php na requisição para o RushPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }

<?php $responseData =<?php json_decode($response,<?php true);

<?php if (!$responseData)<?php {
<?php throw new Exception('Resposta inválida do RushPay.');
<?php }

<?php if (!isset($responseData['id']))<?php {
<?php throw new Exception('Resposta do RushPay não contém ID da transação.');
<?php }

<?php return [
<?php 'transactionId'<?php =><?php $responseData['id'],
<?php 'qrcode'<?php =><?php $responseData['qrcode']<?php ??<?php null,
<?php 'pixCode'<?php =><?php $responseData['pixCode']<?php ??<?php null,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'status'<?php =><?php $responseData['status']<?php ??<?php 'PENDING',
<?php 'value'<?php =><?php $amount,
<?php 'expiresAt'<?php =><?php $responseData['expiresAt']<?php ??<?php null ];
<?php }

<?php public function getPaymentDetails($transactionId)<?php {
<?php $url =<?php $this->apiUrl .<?php '/transaction/'<?php .<?php $transactionId;

<?php $ch =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Authorization:<?php '<?php .<?php $this->secretKey ],
<?php CURLOPT_TIMEOUT =><?php 30,
<?php CURLOPT_SSL_VERIFYPEER =><?php true,
<?php CURLOPT_SSL_VERIFYHOST =><?php 2 ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro ao consultar transação:<?php HTTP '<?php .<?php $httpCode);
<?php }

<?php $responseData =<?php json_decode($response,<?php true);

<?php if (!$responseData)<?php {
<?php throw new Exception('Resposta inválida ao consultar transação.');
<?php }

<?php return [
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
<?php 'chargebackAt'<?php =><?php $responseData['chargebackAt']<?php ??<?php null ];
<?php }

<?php public function processWebhook($webhookData)<?php {
<?php //<?php Processar dados do webhook da RushPay $transactionId =<?php $webhookData['id']<?php ??<?php null;
<?php $status =<?php $webhookData['status']<?php ??<?php null;

<?php if (!$transactionId ||<?php !$status)<?php {
<?php throw new Exception('Dados do webhook incompletos');
<?php }

<?php //<?php Mapear status da RushPay para nosso sistema $statusMap =<?php [
<?php 'PAID'<?php =><?php 'PAID',
<?php 'PENDING'<?php =><?php 'PENDING',
<?php 'CANCELLED'<?php =><?php 'CANCELLED',
<?php 'EXPIRED'<?php =><?php 'EXPIRED'
<?php ];

<?php $mappedStatus =<?php $statusMap[$status]<?php ??<?php 'UNKNOWN';

<?php return [
<?php 'transactionId'<?php =><?php $transactionId,
<?php 'status'<?php =><?php $mappedStatus,
<?php 'originalData'<?php =><?php $webhookData ];
<?php }

<?php public function checkPaymentStatus($transactionId)<?php {
<?php $url =<?php $this->apiUrl .<?php '/transaction.getPayment?id='<?php .<?php urlencode($transactionId);
<?php $headers =<?php [
<?php 'Authorization:<?php '<?php .<?php $this->secretKey,
<?php 'Content-Type:<?php application/json'
<?php ];

<?php $ch =<?php curl_init();
<?php curl_setopt($ch,<?php CURLOPT_URL,<?php $url);
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php $headers);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php 2);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError =<?php curl_error($ch);
<?php curl_close($ch);

<?php //<?php Log para debug (apenas em desenvolvimento)
<?php if (($_ENV['RAILWAY_ENVIRONMENT']<?php ??<?php 'production')<?php ===<?php 'development')<?php {
<?php error_log("RushPay Status Check -<?php URL:<?php $url");
<?php error_log("RushPay Status Check -<?php HTTP Code:<?php $httpCode");
<?php error_log("RushPay Status Check -<?php Response:<?php $response");
<?php error_log("RushPay Status Check -<?php Curl Error:<?php $curlError");
<?php }

<?php if ($httpCode ===<?php 200 &&<?php !empty($response))<?php {
<?php $data =<?php json_decode($response,<?php true);
<?php if ($data &&<?php isset($data['status']))<?php {
<?php return $data;
<?php }
<?php }

<?php //<?php Se a API não funcionar,<?php retornar PENDING (não creditar)
<?php return [
<?php 'id'<?php =><?php $transactionId,
<?php 'status'<?php =><?php 'PENDING',
<?php 'message'<?php =><?php 'Aguardando confirmação da RushPay'
<?php ];
<?php }
}
?>

