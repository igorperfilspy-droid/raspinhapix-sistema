<?php

class RushPay {
    private $pdo;
    private $secretKey;
    private $publicKey;
    private $apiUrl;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->secretKey = '213d1905-9ac0-4023-8dbd-0279918c7bcd';
        $this->publicKey = '50742ec4-8eac-4516-a957-b896209ce27c';
        $this->apiUrl = 'https://pay.rushpayoficial.com/api/v1';
    }

    public function createDeposit($amount, $cpf, $name, $email, $phone, $callbackUrl, $idempotencyKey, $utmSource = null) {
        $url = $this->apiUrl . '/transaction.purchase';
        
        $payload = [
            'name' => $name,
            'email' => $email,
            'cpf' => $cpf,
            'phone' => $phone,
            'paymentMethod' => 'PIX',
            'amount' => intval($amount * 100), // Converter para centavos
            'traceable' => true,
            'postbackUrl' => $callbackUrl,
            'externalId' => $idempotencyKey,
            'items' => [
                [
                    'unitPrice' => intval($amount * 100),
                    'title' => 'Depósito Raspadinha',
                    'quantity' => 1,
                    'tangible' => false
                ]
            ]
        ];

        // Adicionar UTM Query se fornecido (conforme documentação RushPay)
        if ($utmSource) {
            $payload['utmQuery'] = 'utm_source=' . urlencode($utmSource);
        }

        error_log("RushPay Payload Debug: " . json_encode($payload));
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: ' . $this->secretKey
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception('Erro de conexão com RushPay: ' . $curlError);
        }

        if ($httpCode !== 200) {
            throw new Exception('Erro HTTP ' . $httpCode . ' na requisição para o RushPay. Resposta: ' . $response);
        }

        $responseData = json_decode($response, true);

        if (!$responseData) {
            throw new Exception('Resposta inválida do RushPay.');
        }

        if (!isset($responseData['id'])) {
            throw new Exception('Resposta do RushPay não contém ID da transação.');
        }

        return [
            'transactionId' => $responseData['id'],
            'qrcode' => $responseData['qrcode'] ?? null,
            'pixCode' => $responseData['pixCode'] ?? null,
            'idempotencyKey' => $idempotencyKey,
            'status' => $responseData['status'] ?? 'PENDING',
            'value' => $amount,
            'expiresAt' => $responseData['expiresAt'] ?? null
        ];
    }

    public function getPaymentDetails($transactionId) {
        $url = $this->apiUrl . '/transaction/' . $transactionId;

        error_log("RushPay Payload Debug: " . json_encode($payload));
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->secretKey
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('Erro ao consultar transação: HTTP ' . $httpCode);
        }

        $responseData = json_decode($response, true);

        if (!$responseData) {
            throw new Exception('Resposta inválida ao consultar transação.');
        }

        return [
            'id' => $responseData['id'] ?? null,
            'status' => $responseData['status'] ?? null,
            'amount' => $responseData['amount'] ?? null,
            'pixCode' => $responseData['pixCode'] ?? null,
            'qrcode' => $responseData['qrcode'] ?? null,
            'createdAt' => $responseData['createdAt'] ?? null,
            'updatedAt' => $responseData['updatedAt'] ?? null,
            'paidAt' => $responseData['paidAt'] ?? null,
            'cancelledAt' => $responseData['cancelledAt'] ?? null,
            'expiredAt' => $responseData['expiredAt'] ?? null,
            'refundedAt' => $responseData['refundedAt'] ?? null,
            'rejectedAt' => $responseData['rejectedAt'] ?? null,
            'chargebackAt' => $responseData['chargebackAt'] ?? null
        ];
    }

    public function processWebhook($webhookData) {
        // Processar dados do webhook da RushPay
        $transactionId = $webhookData['id'] ?? null;
        $status = $webhookData['status'] ?? null;

        if (!$transactionId || !$status) {
            throw new Exception('Dados do webhook incompletos');
        }

        // Mapear status da RushPay para nosso sistema
        $statusMap = [
            'PAID' => 'PAID',
            'PENDING' => 'PENDING',
            'CANCELLED' => 'CANCELLED',
            'EXPIRED' => 'EXPIRED'
        ];

        $mappedStatus = $statusMap[$status] ?? 'UNKNOWN';

        return [
            'transactionId' => $transactionId,
            'status' => $mappedStatus,
            'originalData' => $webhookData
        ];
    }

    public function checkPaymentStatus($transactionId) {
        $url = $this->apiUrl . '/transaction.getPayment?id=' . urlencode($transactionId);
        
        $headers = [
            'Authorization: ' . $this->secretKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Log para debug
        error_log("RushPay Status Check - URL: $url");
        error_log("RushPay Status Check - HTTP Code: $httpCode");
        error_log("RushPay Status Check - Response: $response");
        error_log("RushPay Status Check - Curl Error: $curlError");

        if ($httpCode === 200 && !empty($response)) {
            $data = json_decode($response, true);
            if ($data && isset($data['status'])) {
                return $data;
            }
        }

        // Se a API não funcionar, retornar PENDING (não creditar)
        return [
            'id' => $transactionId,
            'status' => 'PENDING',
            'message' => 'Aguardando confirmação da RushPay'
        ];
    }
}

?>

