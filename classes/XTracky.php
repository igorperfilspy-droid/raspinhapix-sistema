<?php
class XTracky {
    private $pdo;
    private $apiUrl = 'https://api.xtracky.com/api/integrations/api';
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Captura e salva UTM parameters na sessão
     */
    public function captureUTMs() {
        if (!session_id()) {
            session_start();
        }
        
        // Capturar UTMs da URL
        $utmParams = [
            'utm_source' => $_GET['utm_source'] ?? null,
            'utm_medium' => $_GET['utm_medium'] ?? null,
            'utm_campaign' => $_GET['utm_campaign'] ?? null,
            'utm_term' => $_GET['utm_term'] ?? null,
            'utm_content' => $_GET['utm_content'] ?? null,
            'click_id' => $_GET['click_id'] ?? null
        ];
        
        // Salvar na sessão apenas se houver UTMs
        foreach ($utmParams as $key => $value) {
            if ($value !== null) {
                $_SESSION[$key] = $value;
            }
        }
        
        return $utmParams;
    }
    
    /**
     * Recupera UTM source da sessão
     */
    public function getUTMSource() {
        if (!session_id()) {
            session_start();
        }
        
        return $_SESSION['utm_source'] ?? null;
    }
    
    /**
     * Recupera todos os UTMs da sessão
     */
    public function getAllUTMs() {
        if (!session_id()) {
            session_start();
        }
        
        return [
            'utm_source' => $_SESSION['utm_source'] ?? null,
            'utm_medium' => $_SESSION['utm_medium'] ?? null,
            'utm_campaign' => $_SESSION['utm_campaign'] ?? null,
            'utm_term' => $_SESSION['utm_term'] ?? null,
            'utm_content' => $_SESSION['utm_content'] ?? null,
            'click_id' => $_SESSION['click_id'] ?? null
        ];
    }
    
    /**
     * Envia evento para xTracky API
     */
    public function sendEvent($orderId, $amount, $status, $utmSource = null) {
        try {
            // Se não foi fornecido utm_source, pegar da sessão
            if ($utmSource === null) {
                $utmSource = $this->getUTMSource();
            }
            
            // Se não há utm_source, não enviar evento
            if (!$utmSource) {
                error_log('XTracky: Nenhum utm_source encontrado para o evento');
                return false;
            }
            
            $payload = [
                'orderId' => $orderId,
                'amount' => intval($amount * 100), // Converter para centavos
                'status' => $status, // 'waiting_payment' ou 'paid'
                'utm_source' => $utmSource
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            // Log para debug
            error_log("XTracky Event - Payload: " . json_encode($payload));
            error_log("XTracky Event - HTTP Code: $httpCode");
            error_log("XTracky Event - Response: $response");
            error_log("XTracky Event - Curl Error: $curlError");
            
            if ($httpCode >= 200 && $httpCode < 300) {
                return json_decode($response, true);
            } else {
                throw new Exception("HTTP Error: $httpCode - $response");
            }
            
        } catch (Exception $e) {
            error_log('XTracky Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envia evento de venda aguardando pagamento
     */
    public function sendWaitingPayment($orderId, $amount) {
        return $this->sendEvent($orderId, $amount, 'waiting_payment');
    }
    
    /**
     * Envia evento de venda aprovada
     */
    public function sendPaid($orderId, $amount) {
        return $this->sendEvent($orderId, $amount, 'paid');
    }
}

