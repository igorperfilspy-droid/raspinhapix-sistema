<?php
/**
 * Conexão PDO otimizada para Railway (MySQL)
 * Lê as credenciais a partir das variáveis de ambiente do Railway:
 *   MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD
 */

$host = getenv("MYSQLHOST") ?: "mysql.railway.internal";
$db   = getenv("MYSQLDATABASE") ?: "railway";
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD");
$port = getenv("MYSQLPORT") ?: "3306";

$siteUrl =
    getenv('RAILWAY_PUBLIC_DOMAIN') ? 'https://' . getenv('RAILWAY_PUBLIC_DOMAIN') :
    (getenv('RAILWAY_STATIC_URL')   ?: 'https://localhost');

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    // Log só em dev
    if ((getenv('RAILWAY_ENVIRONMENT') ?: 'production') === 'development') {
        error_log('Conexão Railway estabelecida com sucesso.');
    }
} catch (PDOException $e) {
    // Log do erro sempre vai para os logs do Railway
    error_log('Erro de conexão Railway: ' . $e->getMessage());

    // Em produção, responde mensagem genérica
    if ((getenv('RAILWAY_ENVIRONMENT') ?: 'production') === 'production') {
        die('Erro de conexão com o banco de dados. Tente novamente em alguns minutos.');
    } else {
        die('Erro de conexão: ' . $e->getMessage());
    }
}

/**
 * (Opcional) Carrega configurações do site da tabela `config`
 * Define valores padrão caso a tabela não exista.
 */
$nomeSite   = 'RaspinhaPix';
$logoSite   = '';
$depositoMin = 10;
$saqueMin    = 50;
$cpaPadrao   = 10;
$revsharePadrao = 10;

try {
    $row = $pdo->query("SELECT nome_site, logo, deposito_min, saque_min, cpa_padrao, revshare_padrao FROM config LIMIT 1")->fetch();
    if ($row) {
        $nomeSite       = $row['nome_site']       ?? $nomeSite;
        $logoSite       = $row['logo']            ?? $logoSite;
        $depositoMin    = $row['deposito_min']    ?? $depositoMin;
        $saqueMin       = $row['saque_min']       ?? $saqueMin;
        $cpaPadrao      = $row['cpa_padrao']      ?? $cpaPadrao;
        $revsharePadrao = $row['revshare_padrao'] ?? $revsharePadrao;
    }
} catch (PDOException $e) {
    // Se a tabela não existir ainda, seguimos com os padrões
    error_log('Aviso: não foi possível ler a tabela config: ' . $e->getMessage());
}

// Constantes globais úteis
define('SITE_URL',    $siteUrl);
define('IS_RAILWAY',  true);
define('ENVIRONMENT', getenv('RAILWAY_ENVIRONMENT') ?: 'production');

// Timezone
date_default_timezone_set('America/Sao_Paulo');
