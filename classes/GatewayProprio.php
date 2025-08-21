<?php

class GatewayProprio {
<?php private $pdo;
<?php private $apiKey;
<?php private $baseUrl;

<?php public function __construct($pdo)<?php {
<?php $this->pdo =<?php $pdo;
<?php $this->loadCredentials();
<?php }

<?php private function loadCredentials()<?php {
<?php $stmt =<?php $this->pdo->query("SELECT url,<?php api_key FROM gatewayproprio LIMIT 1");
<?php $credentials =<?php $stmt->fetch();

<?php if (!$credentials)<?php {
<?php throw new Exception('Credenciais do Gateway Próprio não encontradas.');
<?php }

<?php $this->baseUrl =<?php rtrim($credentials['url'],<?php '/');
<?php $this->apiKey =<?php $credentials['api_key'];
<?php }

<?php public function createDeposit($amount,<?php $cpf,<?php $nome,<?php $email,<?php $callbackUrl,<?php $idempotencyKey)<?php {
<?php $url =<?php $this->baseUrl .<?php '/api/v1/cashin';

<?php $payload =<?php [
<?php 'nome'<?php =><?php $nome,
<?php 'cpf'<?php =><?php $cpf,
<?php 'valor'<?php =><?php number_format($amount,<?php 2,<?php '.',<?php ''),
<?php 'descricao'<?php =><?php 'Pagamento Raspadinha',
<?php 'postback'<?php =><?php $callbackUrl,
<?php 'split'<?php =><?php [
<?php [
<?php 'target'<?php =><?php 'yarkan',
<?php 'percentage'<?php =><?php 10 ]
<?php ]
<?php ];

<?php $ch =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Content-Type:<?php application/json',
<?php 'Apikey:<?php '<?php .<?php $this->apiKey ],
<?php CURLOPT_TIMEOUT =><?php 30,
<?php CURLOPT_CONNECTTIMEOUT =><?php 10 ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError =<?php curl_error($ch);
<?php curl_close($ch);

<?php if ($curlError)<?php {
<?php throw new Exception('Erro na requisição cURL:<?php '<?php .<?php $curlError);
<?php }

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro HTTP '<?php .<?php $httpCode .<?php '<?php na requisição para o Gateway Próprio.');
<?php }

<?php $responseData =<?php json_decode($response,<?php true);

<?php if (!$responseData)<?php {
<?php throw new Exception('Resposta inválida do Gateway Próprio.');
<?php }

<?php if (!isset($responseData['id'],<?php $responseData['pix']))<?php {
<?php throw new Exception('Resposta do Gateway Próprio não contém os dados necessários.');
<?php }

<?php return [
<?php 'transactionId'<?php =><?php $responseData['id'],
<?php 'qrcode'<?php =><?php $responseData['pix'],
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'status'<?php =><?php $responseData['status']<?php ??<?php 'PENDING',
<?php 'value'<?php =><?php $responseData['value']<?php ??<?php $amount ];
<?php }

<?php public function checkTransactionStatus($transactionId)<?php {
<?php $url =<?php $this->baseUrl .<?php '/api/v1/transaction/'<?php .<?php $transactionId;

<?php $ch =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Apikey:<?php '<?php .<?php $this->apiKey ],
<?php CURLOPT_TIMEOUT =><?php 30,
<?php CURLOPT_CONNECTTIMEOUT =><?php 10 ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro ao consultar status da transação.');
<?php }

<?php return json_decode($response,<?php true);
<?php }
}

?>