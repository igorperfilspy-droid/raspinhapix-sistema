<?php
class XTracky {
<?php private $pdo;
<?php private $apiUrl =<?php 'https://api.xtracky.com/api/integrations/api';
<?php public function __construct($pdo)<?php {
<?php $this->pdo =<?php $pdo;
<?php }
<?php /**
<?php *<?php Captura e salva UTM parameters na sessão */
<?php public function captureUTMs()<?php {
<?php if (!session_id())<?php {
<?php session_start();
<?php }
<?php //<?php Capturar UTMs da URL $utmParams =<?php [
<?php 'utm_source'<?php =><?php $_GET['utm_source']<?php ??<?php null,
<?php 'utm_medium'<?php =><?php $_GET['utm_medium']<?php ??<?php null,
<?php 'utm_campaign'<?php =><?php $_GET['utm_campaign']<?php ??<?php null,
<?php 'utm_term'<?php =><?php $_GET['utm_term']<?php ??<?php null,
<?php 'utm_content'<?php =><?php $_GET['utm_content']<?php ??<?php null,
<?php 'click_id'<?php =><?php $_GET['click_id']<?php ??<?php null ];
<?php //<?php Salvar na sessão apenas se houver UTMs foreach ($utmParams as $key =><?php $value)<?php {
<?php if ($value !==<?php null)<?php {
<?php $_SESSION[$key]<?php =<?php $value;
<?php }
<?php }
<?php return $utmParams;
<?php }
<?php /**
<?php *<?php Recupera UTM source da sessão */
<?php public function getUTMSource()<?php {
<?php if (!session_id())<?php {
<?php session_start();
<?php }
<?php return $_SESSION['utm_source']<?php ??<?php null;
<?php }
<?php /**
<?php *<?php Recupera todos os UTMs da sessão */
<?php public function getAllUTMs()<?php {
<?php if (!session_id())<?php {
<?php session_start();
<?php }
<?php return [
<?php 'utm_source'<?php =><?php $_SESSION['utm_source']<?php ??<?php null,
<?php 'utm_medium'<?php =><?php $_SESSION['utm_medium']<?php ??<?php null,
<?php 'utm_campaign'<?php =><?php $_SESSION['utm_campaign']<?php ??<?php null,
<?php 'utm_term'<?php =><?php $_SESSION['utm_term']<?php ??<?php null,
<?php 'utm_content'<?php =><?php $_SESSION['utm_content']<?php ??<?php null,
<?php 'click_id'<?php =><?php $_SESSION['click_id']<?php ??<?php null ];
<?php }
<?php /**
<?php *<?php Envia evento para xTracky API */
<?php public function sendEvent($orderId,<?php $amount,<?php $status,<?php $utmSource =<?php null)<?php {
<?php try {
<?php //<?php Se não foi fornecido utm_source,<?php pegar da sessão if ($utmSource ===<?php null)<?php {
<?php $utmSource =<?php $this->getUTMSource();
<?php }
<?php //<?php Se não há<?php utm_source,<?php não enviar evento if (!$utmSource)<?php {
<?php error_log('XTracky:<?php Nenhum utm_source encontrado para o evento');
<?php return false;
<?php }
<?php $payload =<?php [
<?php 'orderId'<?php =><?php $orderId,
<?php 'amount'<?php =><?php intval($amount *<?php 100),<?php //<?php Converter para centavos 'status'<?php =><?php $status,<?php //<?php 'waiting_payment'<?php ou 'paid'
<?php 'utm_source'<?php =><?php $utmSource ];
<?php $ch =<?php curl_init();
<?php curl_setopt($ch,<?php CURLOPT_URL,<?php $this->apiUrl);
<?php curl_setopt($ch,<?php CURLOPT_POST,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_POSTFIELDS,<?php json_encode($payload));
<?php curl_setopt($ch,<?php CURLOPT_HTTPHEADER,<?php [
<?php 'Content-Type:<?php application/json',
<?php 'Accept:<?php application/json'
<?php ]);
<?php curl_setopt($ch,<?php CURLOPT_RETURNTRANSFER,<?php true);
<?php curl_setopt($ch,<?php CURLOPT_TIMEOUT,<?php 10);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYPEER,<?php false);
<?php curl_setopt($ch,<?php CURLOPT_SSL_VERIFYHOST,<?php false);
<?php $response =<?php curl_exec($ch);
<?php $httpCode =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError =<?php curl_error($ch);
<?php curl_close($ch);
<?php //<?php Log para debug error_log("XTracky Event -<?php Payload:<?php "<?php .<?php json_encode($payload));
<?php error_log("XTracky Event -<?php HTTP Code:<?php $httpCode");
<?php error_log("XTracky Event -<?php Response:<?php $response");
<?php error_log("XTracky Event -<?php Curl Error:<?php $curlError");
<?php if ($httpCode >=<?php 200 &&<?php $httpCode <?php 300)<?php {
<?php return json_decode($response,<?php true);
<?php }<?php else {
<?php throw new Exception("HTTP Error:<?php $httpCode -<?php $response");
<?php }
<?php }<?php catch (Exception $e)<?php {
<?php error_log('XTracky Error:<?php '<?php .<?php $e->getMessage());
<?php return false;
<?php }
<?php }
<?php /**
<?php *<?php Envia evento de venda aguardando pagamento */
<?php public function sendWaitingPayment($orderId,<?php $amount)<?php {
<?php return $this->sendEvent($orderId,<?php $amount,<?php 'waiting_payment');
<?php }
<?php /**
<?php *<?php Envia evento de venda aprovada */
<?php public function sendPaid($orderId,<?php $amount)<?php {
<?php return $this->sendEvent($orderId,<?php $amount,<?php 'paid');
<?php }
}

