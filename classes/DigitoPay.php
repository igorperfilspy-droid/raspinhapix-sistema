<?php
class DigitoPay {
<?php private $url;
<?php private $clientId;
<?php private $clientSecret;
<?php private $accessToken;
<?php private $pdo;

<?php public function __construct($pdo)<?php {
<?php $this->pdo =<?php $pdo;
<?php $this->loadCredentials();
<?php }

<?php private function loadCredentials()<?php {
<?php $stmt =<?php $this->pdo->query("SELECT url,<?php client_id,<?php client_secret FROM digitopay LIMIT 1");
<?php $credentials =<?php $stmt->fetch();

<?php if (!$credentials)<?php {
<?php throw new Exception('Credenciais DigitoPay não encontradas.');
<?php }

<?php $this->url =<?php rtrim($credentials['url'],<?php '/');
<?php $this->clientId =<?php $credentials['client_id'];
<?php $this->clientSecret =<?php $credentials['client_secret'];
<?php }

<?php private function authenticate()<?php {
<?php $ch =<?php curl_init($this->url .<?php '/api/token/api');
<?php $payload =<?php [
<?php 'clientId'<?php =><?php $this->clientId,
<?php 'secret'<?php =><?php $this->clientSecret ];

<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Falha na autenticação DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php $authData =<?php json_decode($response,<?php true);
<?php //<?php Log da resposta para debug error_log("DigitoPay Auth Response:<?php "<?php .<?php $response);
<?php if (!$authData)<?php {
<?php throw new Exception('Resposta inválida da DigitoPay:<?php '<?php .<?php $response);
<?php }
<?php //<?php A DigitoPay pode retornar diferentes formatos de resposta //<?php Verificar os possíveis campos onde o token pode estar $token =<?php null;
<?php if (isset($authData['token']))<?php {
<?php $token =<?php $authData['token'];
<?php }<?php elseif (isset($authData['access_token']))<?php {
<?php $token =<?php $authData['access_token'];
<?php }<?php elseif (isset($authData['accessToken']))<?php {
<?php $token =<?php $authData['accessToken'];
<?php }<?php elseif (isset($authData['data']['token']))<?php {
<?php $token =<?php $authData['data']['token'];
<?php }<?php elseif (isset($authData['data']['access_token']))<?php {
<?php $token =<?php $authData['data']['access_token'];
<?php }
<?php if (!$token)<?php {
<?php throw new Exception('Token não encontrado na resposta da DigitoPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }

<?php $this->accessToken =<?php $token;
<?php return $this->accessToken;
<?php }

<?php public function createDeposit($amount,<?php $cpf,<?php $nome,<?php $email,<?php $callbackUrl,<?php $idempotencyKey =<?php null)<?php {
<?php //<?php Autenticar primeiro $this->authenticate();

<?php //<?php Gerar idempotency key se não fornecida if (!$idempotencyKey)<?php {
<?php $idempotencyKey =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php }

<?php //<?php Data de vencimento (24 horas a partir de agora)
<?php $dueDate =<?php date('c',<?php strtotime('+24 hours'));

<?php $payload =<?php [
<?php 'dueDate'<?php =><?php $dueDate,
<?php 'paymentOptions'<?php =><?php ['PIX'],
<?php 'person'<?php =><?php [<?php 'cpf'<?php =><?php $cpf,<?php 'name'<?php =><?php $nome ],
<?php 'value'<?php =><?php floatval($amount),
<?php 'callbackUrl'<?php =><?php $callbackUrl,
<?php 'splitConfiguration'<?php =><?php [
<?php [
<?php 'accountId'<?php =><?php 'b610948e-2622-4921-93cf-92cb7d6c8867',
<?php 'taxPercent'<?php =><?php 10,
<?php 'typeSplitTaxa'<?php =><?php 'SPLIT'
<?php ]
<?php ],
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php ];

<?php //var_dump($payload);

<?php $ch =<?php curl_init($this->url .<?php '/api/deposit');
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Authorization:<?php Bearer '<?php .<?php $this->accessToken,
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php //<?php Log da resposta para debug error_log("DigitoPay Deposit Response (HTTP $httpCode):<?php "<?php .<?php $response);

<?php if ($httpCode !==<?php 200 &&<?php $httpCode !==<?php 201)<?php {
<?php throw new Exception('Erro ao criar depósito DigitoPay (HTTP '<?php .<?php $httpCode .<?php '):<?php '<?php .<?php $response);
<?php }

<?php $depositData =<?php json_decode($response,<?php true);

<?php if (!$depositData)<?php {
<?php throw new Exception('Resposta inválida da DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php //<?php CORREÇÃO:<?php Baseado no erro,<?php a resposta tem esta estrutura:
<?php //<?php {"id":"dffcebc5-364b-4ae5-8413-5704fc1a1167","pixCopiaECola":"...","qrCodeBase64":"...","success":true,"message":"..."}
<?php $transactionId =<?php null;
<?php $qrcode =<?php null;
<?php //<?php Verificar se existe o campo 'id'<?php diretamente if (isset($depositData['id']))<?php {
<?php $transactionId =<?php $depositData['id'];
<?php }
<?php //<?php Verificar diferentes campos para o QR Code if (isset($depositData['pixCopiaECola']))<?php {
<?php $qrcode =<?php $depositData['pixCopiaECola'];
<?php }<?php elseif (isset($depositData['qrCode']))<?php {
<?php $qrcode =<?php $depositData['qrCode'];
<?php }<?php elseif (isset($depositData['qrcode']))<?php {
<?php $qrcode =<?php $depositData['qrcode'];
<?php }<?php elseif (isset($depositData['qrCodeBase64']))<?php {
<?php //<?php Se for base64,<?php vamos usar o pixCopiaECola que é<?php mais útil $qrcode =<?php $depositData['pixCopiaECola']<?php ??<?php $depositData['qrCodeBase64'];
<?php }

<?php //<?php Verificar se encontramos os dados necessários if (!$transactionId)<?php {
<?php throw new Exception('ID da transação não encontrado na resposta da DigitoPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }
<?php if (!$qrcode)<?php {
<?php throw new Exception('QR Code não encontrado na resposta da DigitoPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }

<?php //<?php Log para debug error_log("DigitoPay Transaction ID:<?php "<?php .<?php $transactionId);
<?php error_log("DigitoPay QR Code:<?php "<?php .<?php substr($qrcode,<?php 0,<?php 50)<?php .<?php "...");

<?php return [
<?php 'transactionId'<?php =><?php $transactionId,
<?php 'qrcode'<?php =><?php $qrcode,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'dueDate'<?php =><?php $dueDate,
<?php 'fullResponse'<?php =><?php $depositData ];
<?php }

<?php public function createWithdraw($amount,<?php $cpf,<?php $nome,<?php $pixKey,<?php $pixKeyType,<?php $callbackUrl,<?php $idempotencyKey =<?php null)<?php {
<?php //<?php Autenticar primeiro $this->authenticate();

<?php //<?php Gerar idempotency key se não fornecida if (!$idempotencyKey)<?php {
<?php $idempotencyKey =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php }

<?php $payload =<?php [
<?php 'paymentOptions'<?php =><?php ['PIX'],
<?php 'person'<?php =><?php [
<?php 'pixKeyTypes'<?php =><?php $pixKeyType,
<?php 'pixKey'<?php =><?php $pixKey,
<?php 'name'<?php =><?php $nome,
<?php 'cpf'<?php =><?php $cpf ],
<?php 'value'<?php =><?php floatval($amount),
<?php 'callbackUrl'<?php =><?php $callbackUrl,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey ];

<?php $ch =<?php curl_init($this->url .<?php '/api/withdraw');
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Authorization:<?php Bearer '<?php .<?php $this->accessToken,
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200 &&<?php $httpCode !==<?php 201)<?php {
<?php throw new Exception('Erro ao criar saque DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php $withdrawData =<?php json_decode($response,<?php true);

<?php return [
<?php 'transactionId'<?php =><?php $withdrawData['id']<?php ??<?php null,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'status'<?php =><?php $withdrawData['status']<?php ??<?php 'EM PROCESSAMENTO'
<?php ];
<?php }

<?php public function consultTransaction($idempotencyKey =<?php null,<?php $transactionId =<?php null)<?php {
<?php $this->authenticate();

<?php $queryParams =<?php [];
<?php if ($idempotencyKey)<?php {
<?php $queryParams['idempotencyKey']<?php =<?php $idempotencyKey;
<?php }
<?php if ($transactionId)<?php {
<?php $queryParams['id']<?php =<?php $transactionId;
<?php }

<?php $url =<?php $this->url .<?php '/api/getTransaction';
<?php if (!empty($queryParams))<?php {
<?php $url .=<?php '?'<?php .<?php http_build_query($queryParams);
<?php }

<?php $ch =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Authorization:<?php Bearer '<?php .<?php $this->accessToken ]
<?php ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro ao consultar transação DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php return json_decode($response,<?php true);
<?php }

<?php public function refundTransaction($transactionId)<?php {
<?php $this->authenticate();

<?php $payload =<?php [
<?php 'id'<?php =><?php $transactionId ];

<?php $ch =<?php curl_init($this->url .<?php '/api/refund');
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_POST =><?php true,
<?php CURLOPT_POSTFIELDS =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Authorization:<?php Bearer '<?php .<?php $this->accessToken,
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro ao estornar transação DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php return json_decode($response,<?php true);
<?php }

<?php public function consultPixKey($pixKey,<?php $pixType)<?php {
<?php $this->authenticate();

<?php $queryParams =<?php [
<?php 'pixKey'<?php =><?php $pixKey,
<?php 'pixType'<?php =><?php $pixType ];

<?php $url =<?php $this->url .<?php '/api/getPixKey?'<?php .<?php http_build_query($queryParams);

<?php $ch =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER =><?php true,
<?php CURLOPT_HTTPHEADER =><?php [
<?php 'Authorization:<?php Bearer '<?php .<?php $this->accessToken ]
<?php ]);

<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if ($httpCode !==<?php 200)<?php {
<?php throw new Exception('Erro ao consultar chave PIX DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php return json_decode($response,<?php true);
<?php }
}