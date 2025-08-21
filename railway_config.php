<?php
// Configuração otimizada para Railway
// Este arquivo deve ser renomeado para config.php após upload

// Banco de Dados Railway (usando variáveis de ambiente)
$host = $_ENV['MYSQLHOST'] ?? 'localhost';
$port = $_ENV['MYSQLPORT'] ?? '3306';
$dbname = $_ENV['MYSQLDATABASE'] ?? 'railway';
$username = $_ENV['MYSQLUSER'] ?? 'root';
$password = $_ENV['MYSQLPASSWORD'] ?? '';

// RushPay (configurar nas variáveis de ambiente Railway)
$rushpay_secret_key = $_ENV['RUSHPAY_SECRET_KEY'] ?? 'sua_secret_key_rushpay';
$rushpay_public_key = $_ENV['RUSHPAY_PUBLIC_KEY'] ?? 'sua_public_key_rushpay';
$rushpay_endpoint = $_ENV['RUSHPAY_ENDPOINT'] ?? 'https://pay.rushpayoficial.com';

// xTracky (já configurado)
$xtracky_token = $_ENV['XTRACKY_TOKEN'] ?? 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8';
$xtracky_webhook = $_ENV['XTRACKY_WEBHOOK'] ?? 'https://api.xtracky.com/api/integrations/rushpay';

// URL do site (Railway)
$site_url = $_ENV['SITE_URL'] ?? 'https://seu-projeto.up.railway.app';

// Configurações de Email (opcional)
$smtp_host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
$smtp_port = $_ENV['SMTP_PORT'] ?? 587;
$smtp_user = $_ENV['SMTP_USER'] ?? '';
$smtp_pass = $_ENV['SMTP_PASS'] ?? '';

// Configurações de Segurança
$jwt_secret = $_ENV['JWT_SECRET'] ?? bin2hex(random_bytes(32));
$encryption_key = $_ENV['ENCRYPTION_KEY'] ?? bin2hex(random_bytes(16));

// Configurações do Sistema
$sistema_nome = 'RaspinhaPix';
$sistema_versao = '2.0';
$debug_mode = $_ENV['DEBUG_MODE'] ?? false;

// Configurações de Afiliados
$comissao_padrao = $_ENV['COMISSAO_PADRAO'] ?? 5; // Porcentagem padrão
$min_saque = $_ENV['MIN_SAQUE'] ?? 10; // Valor mínimo para saque

// Configurações de Raspadinhas
$valor_min_cartela = $_ENV['VALOR_MIN_CARTELA'] ?? 1;
$valor_max_cartela = $_ENV['VALOR_MAX_CARTELA'] ?? 100;

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações PHP para Railway
ini_set('display_errors', $debug_mode ? 1 : 0);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

// Headers de segurança
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    
    // HTTPS redirect (Railway)
    if (!isset($_SERVER['HTTPS']) && $_ENV['RAILWAY_ENVIRONMENT'] ?? false) {
        $redirectURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $redirectURL");
        exit();
    }
}

// Função para log de debug (Railway)
function debug_log($message, $data = null) {
    if ($_ENV['DEBUG_MODE'] ?? false) {
        $log = date('Y-m-d H:i:s') . ' - ' . $message;
        if ($data) {
            $log .= ' - ' . json_encode($data);
        }
        error_log($log);
    }
}

// Verificar se está no Railway
$is_railway = isset($_ENV['RAILWAY_ENVIRONMENT']);

// Configurações específicas do Railway
if ($is_railway) {
    // Configurar session para Railway
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    
    // Configurar upload para Railway
    ini_set('upload_tmp_dir', '/tmp');
    ini_set('upload_max_filesize', '10M');
    ini_set('post_max_size', '10M');
}

// Constantes do sistema
define('SITE_URL', $site_url);
define('RUSHPAY_SECRET', $rushpay_secret_key);
define('RUSHPAY_PUBLIC', $rushpay_public_key);
define('RUSHPAY_ENDPOINT', $rushpay_endpoint);
define('XTRACKY_TOKEN', $xtracky_token);
define('XTRACKY_WEBHOOK', $xtracky_webhook);
define('SISTEMA_NOME', $sistema_nome);
define('SISTEMA_VERSAO', $sistema_versao);
define('DEBUG_MODE', $debug_mode);

// Log de inicialização
debug_log('Sistema iniciado', [
    'version' => $sistema_versao,
    'environment' => $is_railway ? 'Railway' : 'Local',
    'php_version' => PHP_VERSION,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>

