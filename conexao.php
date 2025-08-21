<?php
// Conexão PDO compatível com Railway (lê MYSQLHOST/MYSQLDATABASE/... e também variantes com _)

function env_get($keys, $default = null) {
    foreach ((array)$keys as $k) {
        // tenta getenv
        $v = getenv($k);
        if ($v !== false && $v !== '') return $v;
        // tenta $_ENV
        if (isset($_ENV[$k]) && $_ENV[$k] !== '') return $_ENV[$k];
    }
    return $default;
}

// Lê ambas as variantes de nomes
$host     = env_get(['DB_HOST', 'MYSQLHOST', 'MYSQL_HOST'], '127.0.0.1');
$port     = (int) env_get(['DB_PORT', 'MYSQLPORT', 'MYSQL_PORT'], 3306);
$dbname   = env_get(['DB_NAME', 'MYSQLDATABASE', 'MYSQL_DATABASE'], 'raspinhapix');
$username = env_get(['DB_USER', 'MYSQLUSER', 'MYSQL_USER'], 'root');
$password = env_get(['DB_PASS', 'MYSQLPASSWORD', 'MYSQL_PASSWORD'], '');

// Opcional: URL pública do site (se usar em algum lugar)
$urlSite  = env_get(['RAILWAY_STATIC_URL', 'RAILWAY_PUBLIC_DOMAIN'], 'https://localhost');

try {
    // Ativa erros em ambiente de dev (não deixe em produção)
    if (env_get(['DEBUG', 'APP_DEBUG'], '0') === '1') {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    // Se quiser logar sucesso apenas em dev
    if (env_get(['DEBUG', 'APP_DEBUG'], '0') === '1') {
        error_log("[DB] Conectado em {$host}:{$port}/{$dbname} como {$username}");
    }

    // (exemplo) carregar configs do site — deixe como estava no seu código:
    try {
        $site = $pdo->query("SELECT nome_site, logo, deposito_min, saque_min, cpa_padrao, revshare_padrao FROM config LIMIT 1")->fetch();
        $nomeSite        = $site['nome_site']       ?? 'RaspinhaPix';
        $logoSite        = $site['logo']            ?? '';
        $depositoMin     = $site['deposito_min']    ?? 10;
        $saqueMin        = $site['saque_min']       ?? 50;
        $cpaPadrao       = $site['cpa_padrao']      ?? 10;
        $revshare_padrao = $site['revshare_padrao'] ?? 10;
    } catch (Throwable $e) {
        // Tabela pode ainda não existir — define defaults
        $nomeSite        = 'RaspinhaPix';
        $logoSite        = '';
        $depositoMin     = 10;
        $saqueMin        = 50;
        $cpaPadrao       = 10;
        $revshare_padrao = 10;
        error_log("[DB] Erro buscando config: " . $e->getMessage());
    }

    define('SITE_URL', $urlSite);
    define('IS_RAILWAY', true);
    define('ENVIRONMENT', env_get(['RAILWAY_ENVIRONMENT', 'APP_ENV'], 'production'));
    date_default_timezone_set('America/Sao_Paulo');

} catch (Throwable $e) {
    error_log("[DB] Falha na conexão: " . $e->getMessage());
    if (env_get(['DEBUG', 'APP_DEBUG'], '0') === '1') {
        die("Erro de conexão: " . htmlspecialchars($e->getMessage()));
    }
    // mensagem genérica em produção
    http_response_code(500);
    die("Erro interno. Tente novamente em instantes.");
}
