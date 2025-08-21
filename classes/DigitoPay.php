<?php
class<?php DigitoPay<?php {
<?php private<?php $url;
<?php private<?php $clientId;
<?php private<?php $clientSecret;
<?php private<?php $accessToken;
<?php private<?php $pdo;

<?php public<?php function<?php __construct($pdo)<?php {
<?php $this->pdo<?php =<?php $pdo;
<?php $this->loadCredentials();
<?php }

<?php private<?php function<?php loadCredentials()<?php {
<?php $stmt<?php =<?php $this->pdo->query("SELECT<?php url,<?php client_id,<?php client_secret<?php FROM<?php digitopay<?php LIMIT<?php 1");
<?php $credentials<?php =<?php $stmt->fetch();

<?php if<?php (!$credentials)<?php {
<?php throw<?php new<?php Exception('Credenciais<?php DigitoPay<?php não<?php encontradas.');
<?php }

<?php $this->url<?php =<?php rtrim($credentials['url'],<?php '/');
<?php $this->clientId<?php =<?php $credentials['client_id'];
<?php $this->clientSecret<?php =<?php $credentials['client_secret'];
<?php }

<?php private<?php function<?php authenticate()<?php {
<?php $ch<?php =<?php curl_init($this->url<?php .<?php '/api/token/api');
<?php 
<?php $payload<?php =<?php [
<?php 'clientId'<?php =><?php $this->clientId,
<?php 'secret'<?php =><?php $this->clientSecret
<?php ];

<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Falha<?php na<?php autenticação<?php DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php $authData<?php =<?php json_decode($response,<?php true);
<?php 
<?php //<?php Log<?php da<?php resposta<?php para<?php debug
<?php error_log("DigitoPay<?php Auth<?php Response:<?php "<?php .<?php $response);
<?php 
<?php if<?php (!$authData)<?php {
<?php throw<?php new<?php Exception('Resposta<?php inválida<?php da<?php DigitoPay:<?php '<?php .<?php $response);
<?php }
<?php 
<?php //<?php A<?php DigitoPay<?php pode<?php retornar<?php diferentes<?php formatos<?php de<?php resposta
<?php //<?php Verificar<?php os<?php possíveis<?php campos<?php onde<?php o<?php token<?php pode<?php estar
<?php $token<?php =<?php null;
<?php 
<?php if<?php (isset($authData['token']))<?php {
<?php $token<?php =<?php $authData['token'];
<?php }<?php elseif<?php (isset($authData['access_token']))<?php {
<?php $token<?php =<?php $authData['access_token'];
<?php }<?php elseif<?php (isset($authData['accessToken']))<?php {
<?php $token<?php =<?php $authData['accessToken'];
<?php }<?php elseif<?php (isset($authData['data']['token']))<?php {
<?php $token<?php =<?php $authData['data']['token'];
<?php }<?php elseif<?php (isset($authData['data']['access_token']))<?php {
<?php $token<?php =<?php $authData['data']['access_token'];
<?php }
<?php 
<?php if<?php (!$token)<?php {
<?php throw<?php new<?php Exception('Token<?php não<?php encontrado<?php na<?php resposta<?php da<?php DigitoPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }

<?php $this->accessToken<?php =<?php $token;
<?php return<?php $this->accessToken;
<?php }

<?php public<?php function<?php createDeposit($amount,<?php $cpf,<?php $nome,<?php $email,<?php $callbackUrl,<?php $idempotencyKey<?php =<?php null)<?php {
<?php //<?php Autenticar<?php primeiro
<?php $this->authenticate();

<?php //<?php Gerar<?php idempotency<?php key<?php se<?php não<?php fornecida
<?php if<?php (!$idempotencyKey)<?php {
<?php $idempotencyKey<?php =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php }

<?php //<?php Data<?php de<?php vencimento<?php (24<?php horas<?php a<?php partir<?php de<?php agora)
<?php $dueDate<?php =<?php date('c',<?php strtotime('+24<?php hours'));

<?php $payload<?php =<?php [
<?php 'dueDate'<?php =><?php $dueDate,
<?php 'paymentOptions'<?php =><?php ['PIX'],
<?php 'person'<?php =><?php [<?php 'cpf'<?php =><?php $cpf,<?php 'name'<?php =><?php $nome<?php ],
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

<?php $ch<?php =<?php curl_init($this->url<?php .<?php '/api/deposit');
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Authorization:<?php Bearer<?php '<?php .<?php $this->accessToken,
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php //<?php Log<?php da<?php resposta<?php para<?php debug
<?php error_log("DigitoPay<?php Deposit<?php Response<?php (HTTP<?php $httpCode):<?php "<?php .<?php $response);

<?php if<?php ($httpCode<?php !==<?php 200<?php &&<?php $httpCode<?php !==<?php 201)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php criar<?php depósito<?php DigitoPay<?php (HTTP<?php '<?php .<?php $httpCode<?php .<?php '):<?php '<?php .<?php $response);
<?php }

<?php $depositData<?php =<?php json_decode($response,<?php true);

<?php if<?php (!$depositData)<?php {
<?php throw<?php new<?php Exception('Resposta<?php inválida<?php da<?php DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php //<?php CORREÇÃO:<?php Baseado<?php no<?php erro,<?php a<?php resposta<?php tem<?php esta<?php estrutura:
<?php //<?php {"id":"dffcebc5-364b-4ae5-8413-5704fc1a1167","pixCopiaECola":"...","qrCodeBase64":"...","success":true,"message":"..."}
<?php 
<?php $transactionId<?php =<?php null;
<?php $qrcode<?php =<?php null;
<?php 
<?php //<?php Verificar<?php se<?php existe<?php o<?php campo<?php 'id'<?php diretamente
<?php if<?php (isset($depositData['id']))<?php {
<?php $transactionId<?php =<?php $depositData['id'];
<?php }
<?php 
<?php //<?php Verificar<?php diferentes<?php campos<?php para<?php o<?php QR<?php Code
<?php if<?php (isset($depositData['pixCopiaECola']))<?php {
<?php $qrcode<?php =<?php $depositData['pixCopiaECola'];
<?php }<?php elseif<?php (isset($depositData['qrCode']))<?php {
<?php $qrcode<?php =<?php $depositData['qrCode'];
<?php }<?php elseif<?php (isset($depositData['qrcode']))<?php {
<?php $qrcode<?php =<?php $depositData['qrcode'];
<?php }<?php elseif<?php (isset($depositData['qrCodeBase64']))<?php {
<?php //<?php Se<?php for<?php base64,<?php vamos<?php usar<?php o<?php pixCopiaECola<?php que<?php é<?php mais<?php útil
<?php $qrcode<?php =<?php $depositData['pixCopiaECola']<?php ??<?php $depositData['qrCodeBase64'];
<?php }

<?php //<?php Verificar<?php se<?php encontramos<?php os<?php dados<?php necessários
<?php if<?php (!$transactionId)<?php {
<?php throw<?php new<?php Exception('ID<?php da<?php transação<?php não<?php encontrado<?php na<?php resposta<?php da<?php DigitoPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }
<?php 
<?php if<?php (!$qrcode)<?php {
<?php throw<?php new<?php Exception('QR<?php Code<?php não<?php encontrado<?php na<?php resposta<?php da<?php DigitoPay.<?php Resposta:<?php '<?php .<?php $response);
<?php }

<?php //<?php Log<?php para<?php debug
<?php error_log("DigitoPay<?php Transaction<?php ID:<?php "<?php .<?php $transactionId);
<?php error_log("DigitoPay<?php QR<?php Code:<?php "<?php .<?php substr($qrcode,<?php 0,<?php 50)<?php .<?php "...");

<?php return<?php [
<?php 'transactionId'<?php =><?php $transactionId,
<?php 'qrcode'<?php =><?php $qrcode,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'dueDate'<?php =><?php $dueDate,
<?php 'fullResponse'<?php =><?php $depositData
<?php ];
<?php }

<?php public<?php function<?php createWithdraw($amount,<?php $cpf,<?php $nome,<?php $pixKey,<?php $pixKeyType,<?php $callbackUrl,<?php $idempotencyKey<?php =<?php null)<?php {
<?php //<?php Autenticar<?php primeiro
<?php $this->authenticate();

<?php //<?php Gerar<?php idempotency<?php key<?php se<?php não<?php fornecida
<?php if<?php (!$idempotencyKey)<?php {
<?php $idempotencyKey<?php =<?php uniqid()<?php .<?php '-'<?php .<?php time();
<?php }

<?php $payload<?php =<?php [
<?php 'paymentOptions'<?php =><?php ['PIX'],
<?php 'person'<?php =><?php [
<?php 'pixKeyTypes'<?php =><?php $pixKeyType,
<?php 'pixKey'<?php =><?php $pixKey,
<?php 'name'<?php =><?php $nome,
<?php 'cpf'<?php =><?php $cpf
<?php ],
<?php 'value'<?php =><?php floatval($amount),
<?php 'callbackUrl'<?php =><?php $callbackUrl,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey
<?php ];

<?php $ch<?php =<?php curl_init($this->url<?php .<?php '/api/withdraw');
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Authorization:<?php Bearer<?php '<?php .<?php $this->accessToken,
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200<?php &&<?php $httpCode<?php !==<?php 201)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php criar<?php saque<?php DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php $withdrawData<?php =<?php json_decode($response,<?php true);

<?php return<?php [
<?php 'transactionId'<?php =><?php $withdrawData['id']<?php ??<?php null,
<?php 'idempotencyKey'<?php =><?php $idempotencyKey,
<?php 'status'<?php =><?php $withdrawData['status']<?php ??<?php 'EM<?php PROCESSAMENTO'
<?php ];
<?php }

<?php public<?php function<?php consultTransaction($idempotencyKey<?php =<?php null,<?php $transactionId<?php =<?php null)<?php {
<?php $this->authenticate();

<?php $queryParams<?php =<?php [];
<?php if<?php ($idempotencyKey)<?php {
<?php $queryParams['idempotencyKey']<?php =<?php $idempotencyKey;
<?php }
<?php if<?php ($transactionId)<?php {
<?php $queryParams['id']<?php =<?php $transactionId;
<?php }

<?php $url<?php =<?php $this->url<?php .<?php '/api/getTransaction';
<?php if<?php (!empty($queryParams))<?php {
<?php $url<?php .=<?php '?'<?php .<?php http_build_query($queryParams);
<?php }

<?php $ch<?php =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Authorization:<?php Bearer<?php '<?php .<?php $this->accessToken
<?php ]
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php consultar<?php transação<?php DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php return<?php json_decode($response,<?php true);
<?php }

<?php public<?php function<?php refundTransaction($transactionId)<?php {
<?php $this->authenticate();

<?php $payload<?php =<?php [
<?php 'id'<?php =><?php $transactionId
<?php ];

<?php $ch<?php =<?php curl_init($this->url<?php .<?php '/api/refund');
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_POST<?php =><?php true,
<?php CURLOPT_POSTFIELDS<?php =><?php json_encode($payload),
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Authorization:<?php Bearer<?php '<?php .<?php $this->accessToken,
<?php 'Content-Type:<?php application/json'
<?php ]
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php estornar<?php transação<?php DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php return<?php json_decode($response,<?php true);
<?php }

<?php public<?php function<?php consultPixKey($pixKey,<?php $pixType)<?php {
<?php $this->authenticate();

<?php $queryParams<?php =<?php [
<?php 'pixKey'<?php =><?php $pixKey,
<?php 'pixType'<?php =><?php $pixType
<?php ];

<?php $url<?php =<?php $this->url<?php .<?php '/api/getPixKey?'<?php .<?php http_build_query($queryParams);

<?php $ch<?php =<?php curl_init($url);
<?php curl_setopt_array($ch,<?php [
<?php CURLOPT_RETURNTRANSFER<?php =><?php true,
<?php CURLOPT_HTTPHEADER<?php =><?php [
<?php 'Authorization:<?php Bearer<?php '<?php .<?php $this->accessToken
<?php ]
<?php ]);

<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php curl_close($ch);

<?php if<?php ($httpCode<?php !==<?php 200)<?php {
<?php throw<?php new<?php Exception('Erro<?php ao<?php consultar<?php chave<?php PIX<?php DigitoPay:<?php '<?php .<?php $response);
<?php }

<?php return<?php json_decode($response,<?php true);
<?php }
}