<?php
class<?php XTracky<?php {
<?php private<?php $pdo;
<?php private<?php $apiUrl<?php =<?php 'https://api.xtracky.com/api/integrations/api';
<?php 
<?php public<?php function<?php __construct($pdo)<?php {
<?php $this->pdo<?php =<?php $pdo;
<?php }
<?php 
<?php /**
<?php *<?php Captura<?php e<?php salva<?php UTM<?php parameters<?php na<?php sessão
<?php */
<?php public<?php function<?php captureUTMs()<?php {
<?php if<?php (!session_id())<?php {
<?php session_start();
<?php }
<?php 
<?php //<?php Capturar<?php UTMs<?php da<?php URL
<?php $utmParams<?php =<?php [
<?php 'utm_source'<?php =><?php $_GET['utm_source']<?php ??<?php null,
<?php 'utm_medium'<?php =><?php $_GET['utm_medium']<?php ??<?php null,
<?php 'utm_campaign'<?php =><?php $_GET['utm_campaign']<?php ??<?php null,
<?php 'utm_term'<?php =><?php $_GET['utm_term']<?php ??<?php null,
<?php 'utm_content'<?php =><?php $_GET['utm_content']<?php ??<?php null,
<?php 'click_id'<?php =><?php $_GET['click_id']<?php ??<?php null
<?php ];
<?php 
<?php //<?php Salvar<?php na<?php sessão<?php apenas<?php se<?php houver<?php UTMs
<?php foreach<?php ($utmParams<?php as<?php $key<?php =><?php $value)<?php {
<?php if<?php ($value<?php !==<?php null)<?php {
<?php $_SESSION[$key]<?php =<?php $value;
<?php }
<?php }
<?php 
<?php return<?php $utmParams;
<?php }
<?php 
<?php /**
<?php *<?php Recupera<?php UTM<?php source<?php da<?php sessão
<?php */
<?php public<?php function<?php getUTMSource()<?php {
<?php if<?php (!session_id())<?php {
<?php session_start();
<?php }
<?php 
<?php return<?php $_SESSION['utm_source']<?php ??<?php null;
<?php }
<?php 
<?php /**
<?php *<?php Recupera<?php todos<?php os<?php UTMs<?php da<?php sessão
<?php */
<?php public<?php function<?php getAllUTMs()<?php {
<?php if<?php (!session_id())<?php {
<?php session_start();
<?php }
<?php 
<?php return<?php [
<?php 'utm_source'<?php =><?php $_SESSION['utm_source']<?php ??<?php null,
<?php 'utm_medium'<?php =><?php $_SESSION['utm_medium']<?php ??<?php null,
<?php 'utm_campaign'<?php =><?php $_SESSION['utm_campaign']<?php ??<?php null,
<?php 'utm_term'<?php =><?php $_SESSION['utm_term']<?php ??<?php null,
<?php 'utm_content'<?php =><?php $_SESSION['utm_content']<?php ??<?php null,
<?php 'click_id'<?php =><?php $_SESSION['click_id']<?php ??<?php null
<?php ];
<?php }
<?php 
<?php /**
<?php *<?php Envia<?php evento<?php para<?php xTracky<?php API
<?php */
<?php public<?php function<?php sendEvent($orderId,<?php $amount,<?php $status,<?php $utmSource<?php =<?php null)<?php {
<?php try<?php {
<?php //<?php Se<?php não<?php foi<?php fornecido<?php utm_source,<?php pegar<?php da<?php sessão
<?php if<?php ($utmSource<?php ===<?php null)<?php {
<?php $utmSource<?php =<?php $this->getUTMSource();
<?php }
<?php 
<?php //<?php Se<?php não<?php há<?php utm_source,<?php não<?php enviar<?php evento
<?php if<?php (!$utmSource)<?php {
<?php error_log('XTracky:<?php Nenhum<?php utm_source<?php encontrado<?php para<?php o<?php evento');
<?php return<?php false;
<?php }
<?php 
<?php $payload<?php =<?php [
<?php 'orderId'<?php =><?php $orderId,
<?php 'amount'<?php =><?php intval($amount<?php *<?php 100),<?php //<?php Converter<?php para<?php centavos
<?php 'status'<?php =><?php $status,<?php //<?php 'waiting_payment'<?php ou<?php 'paid'
<?php 'utm_source'<?php =><?php $utmSource
<?php ];
<?php 
<?php $ch<?php =<?php curl_init();
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
<?php 
<?php $response<?php =<?php curl_exec($ch);
<?php $httpCode<?php =<?php curl_getinfo($ch,<?php CURLINFO_HTTP_CODE);
<?php $curlError<?php =<?php curl_error($ch);
<?php curl_close($ch);
<?php 
<?php //<?php Log<?php para<?php debug
<?php error_log("XTracky<?php Event<?php -<?php Payload:<?php "<?php .<?php json_encode($payload));
<?php error_log("XTracky<?php Event<?php -<?php HTTP<?php Code:<?php $httpCode");
<?php error_log("XTracky<?php Event<?php -<?php Response:<?php $response");
<?php error_log("XTracky<?php Event<?php -<?php Curl<?php Error:<?php $curlError");
<?php 
<?php if<?php ($httpCode<?php >=<?php 200<?php &&<?php $httpCode<?php <?php 300)<?php {
<?php return<?php json_decode($response,<?php true);
<?php }<?php else<?php {
<?php throw<?php new<?php Exception("HTTP<?php Error:<?php $httpCode<?php -<?php $response");
<?php }
<?php 
<?php }<?php catch<?php (Exception<?php $e)<?php {
<?php error_log('XTracky<?php Error:<?php '<?php .<?php $e->getMessage());
<?php return<?php false;
<?php }
<?php }
<?php 
<?php /**
<?php *<?php Envia<?php evento<?php de<?php venda<?php aguardando<?php pagamento
<?php */
<?php public<?php function<?php sendWaitingPayment($orderId,<?php $amount)<?php {
<?php return<?php $this->sendEvent($orderId,<?php $amount,<?php 'waiting_payment');
<?php }
<?php 
<?php /**
<?php *<?php Envia<?php evento<?php de<?php venda<?php aprovada
<?php */
<?php public<?php function<?php sendPaid($orderId,<?php $amount)<?php {
<?php return<?php $this->sendEvent($orderId,<?php $amount,<?php 'paid');
<?php }
}

