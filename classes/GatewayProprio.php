<?php

class<?php GatewayProprio<?php {
<?php private<?php $pdo;
<?php private<?php $apiKey;
<?php private<?php $baseUrl;

<?php public<?php function<?php __construct($pdo)<?php {
<?php $this->pdo<?php =<?php $pdo;
<?php $this->loadCredentials();
<?php }

<?php private<?php function<?php loadCredentials()<?php {
<?php $stmt<?php =<?php $this->pdo->query("SELECT<?php url,<?php api_key<?php FROM<?php gatewayproprio<?php LIMIT<?php 1");
<?php $credentials<?php =<?php $stmt->fetch();

<?php if<?php (!$credentials)<?php {
<?php throw<?php new<?php Exception('Credenciais<?php do<?php Gateway<?php Próprio<?php não<?php encontradas.');
<?php }

<?php $this->baseUrl<?php =<?php rtrim($credentials['url'],<?php '/');
<?php $this->apiKey<?php =<?php $credentials['api_key'];
<?php }

<?php public<?php function<?php createDeposit($amount,<?php $cpf,<?php $nome,<?php $email,<?php $callbackUrl,<?php $idempotencyKey)<?php {
<?php $url<?php =<?php $this->baseUrl<?php .<?php '/api/v1/cashin';

<?php $payload<?php =<?php [
<?php 'nome'<?php =><?php $nome,
<?php 'cpf'<?php =><?php $cpf,
<?php 'valor'<?php =><?php number_format($amount,<?php 2,<?php '.',<?php ''),
<?php 'descricao'<?php =><?php 'Pagamento<?php Raspadinha',
<?php 'postback'<?php =><?php $callbackUrl,
<?php 'split'<?php =><?php [
<?php [
<?php 'target'<?php =><?php 'yarkan',
<?php 'percentage'<?php =><?php 10
<?php ]
<?php ]
<?php ];

<?php $ch<?php =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Content-Type:<?php application/json',
<?php 'Apikey:<?php '<?php .<?php $this->apiKey
<?php ],
<?php CURLOPT_TIMEOUT<?php =><?php 30,
<?php CURLOPT_CONNECTTIMEOUT<?php =><?php 10
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError<?php =<?php curl_error($ch);
<?php curl_close($ch);

<?php if<?php ($curlError)<?php {
<?php throw<?php new<?php Exception('Erro<?php na<?php requisição<?php cURL:<?php '<?php .<?php $curlError);
<?php }

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php HTTP<?php '<?php .<?php $httpCode<?php .<?php '<?php na<?php requisição<?php para<?php o<?php Gateway<?php Próprio.');
<?php }

<?php $responseData<?php =<?php json_decode($response,<?php true);

<?php if<?php (!$responseData)<?php {
<?php throw<?php new<?php Exception('Resposta<?php inválida<?php do<?php Gateway<?php Próprio.');
<?php }

<?php if<?php (!isset($responseData['id'],<?php $responseData['pix']))<?php {
<?php throw<?php new<?php Exception('Resposta<?php do<?php Gateway<?php Próprio<?php não<?php contém<?php os<?php dados<?php necessários.');
<?php }

<?php return<?php [
<?php 'transactionId'<?php =><?php $responseData['id'],
<?php 'qrcode'<?php =><?php $responseData['pix'],
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'status'<?php =><?php $responseData['status']<?php ??<?php 'PENDING',
<?php 'value'<?php =><?php $responseData['value']<?php ??<?php $amount
<?php ];
<?php }

<?php public<?php function<?php checkTransactionStatus($transactionId)<?php {
<?php $url<?php =<?php $this->baseUrl<?php .<?php '/api/v1/transaction/'<?php .<?php $transactionId;

<?php $ch<?php =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Apikey:<?php '<?php .<?php $this->apiKey
<?php ],
<?php CURLOPT_TIMEOUT<?php =><?php 30,
<?php CURLOPT_CONNECTTIMEOUT<?php =><?php 10
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php consultar<?php status<?php da<?php transação.');
<?php }

<?php return<?php json_decode($response,<?php true);
<?php }
}

?>