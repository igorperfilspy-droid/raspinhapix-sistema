<?php
// CONEXÃO OTIMIZADA PARA RAILWAY
// Substitua o arquivo conexao.php original por este

// Configurações do banco (Railway usa variáveis de ambiente)
$host = $_ENV['DB_HOST'] ?? getenv('MYSQL_HOST') ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? getenv('MYSQL_PORT') ?? '3306';
$dbname = $_ENV['DB_NAME'] ?? getenv('MYSQL_DATABASE') ?? 'raspinhapix';
$username = $_ENV['DB_USER'] ?? getenv('MYSQL_USER') ?? 'root';
$password = $_ENV['DB_PASS'] ?? getenv('MYSQL_PASSWORD') ?? '';

// URL do site (Railway)
$urlSite = $_ENV['RAILWAY_STATIC_URL'] ?? 'https://localhost';

try {
    // Conexão PDO com configurações otimizadas para Railway
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Log de sucesso (apenas em desenvolvimento)
    if ($_ENV['RAILWAY_ENVIRONMENT'] === 'development') {
        error_log("Conexão Railway estabelecida com sucesso!");
    }
    
} catch(PDOException $e) {
    // Log do erro
    error_log("Erro de conexão Railway: " . $e->getMessage());
    
    // Em produção, mostrar erro genérico
    if ($_ENV['RAILWAY_ENVIRONMENT'] === 'production') {
        die("Erro de conexão com o banco de dados. Tente novamente em alguns minutos.");
    } else {
        die("Erro de conexão: " . $e->getMessage());
    }
}

// Configurações globais para Railway
define('SITE_URL', $urlSite);
define('IS_RAILWAY', true);
define('ENVIRONMENT', $_ENV['RAILWAY_ENVIRONMENT'] ?? 'production');

// Timezone
date_default_timezone_set('America/Sao_Paulo');
?>

