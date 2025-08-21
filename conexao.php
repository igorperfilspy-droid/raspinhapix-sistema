<?php
//<?php CONEXÃO<?php OTIMIZADA<?php PARA<?php RAILWAY
//<?php Configurações<?php do<?php banco<?php (Railway<?php usa<?php variáveis<?php de<?php ambiente)
$host<?php =<?php $_ENV['DB_HOST']<?php ??<?php getenv('MYSQL_HOST')<?php ??<?php $_ENV['MYSQL_HOST']<?php ??<?php 'localhost';
$port<?php =<?php $_ENV['DB_PORT']<?php ??<?php getenv('MYSQL_PORT')<?php ??<?php $_ENV['MYSQL_PORT']<?php ??<?php '3306';
$dbname<?php =<?php $_ENV['DB_NAME']<?php ??<?php getenv('MYSQL_DATABASE')<?php ??<?php $_ENV['MYSQL_DATABASE']<?php ??<?php 'raspinhapix';
$username<?php =<?php $_ENV['DB_USER']<?php ??<?php getenv('MYSQL_USER')<?php ??<?php $_ENV['MYSQL_USER']<?php ??<?php 'root';
$password<?php =<?php $_ENV['DB_PASS']<?php ??<?php getenv('MYSQL_PASSWORD')<?php ??<?php $_ENV['MYSQL_PASSWORD']<?php ??<?php '';

//<?php URL<?php do<?php site<?php (Railway)
$urlSite<?php =<?php $_ENV['RAILWAY_STATIC_URL']<?php ??<?php $_ENV['RAILWAY_PUBLIC_DOMAIN']<?php ??<?php 'https://localhost';

try<?php {
<?php //<?php Conexão<?php PDO<?php com<?php configurações<?php otimizadas<?php para<?php Railway
<?php $dsn<?php =<?php "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
<?php $options<?php =<?php [
<?php PDO::ATTR_ERRMODE<?php =><?php PDO::ERRMODE_EXCEPTION,
<?php PDO::ATTR_DEFAULT_FETCH_MODE<?php =><?php PDO::FETCH_ASSOC,
<?php PDO::ATTR_EMULATE_PREPARES<?php =><?php false,
<?php PDO::MYSQL_ATTR_INIT_COMMAND<?php =><?php "SET<?php NAMES<?php utf8mb4<?php COLLATE<?php utf8mb4_unicode_ci"
<?php ];
<?php 
<?php $pdo<?php =<?php new<?php PDO($dsn,<?php $username,<?php $password,<?php $options);
<?php 
<?php //<?php Log<?php de<?php sucesso<?php (apenas<?php em<?php desenvolvimento)
<?php if<?php (($_ENV['RAILWAY_ENVIRONMENT']<?php ??<?php 'production')<?php ===<?php 'development')<?php {
<?php error_log("Conexão<?php Railway<?php estabelecida<?php com<?php sucesso!");
<?php }
<?php 
}<?php catch(PDOException<?php $e)<?php {
<?php //<?php Log<?php do<?php erro
<?php error_log("Erro<?php de<?php conexão<?php Railway:<?php "<?php .<?php $e->getMessage());
<?php 
<?php //<?php Em<?php produção,<?php mostrar<?php erro<?php genérico
<?php if<?php (($_ENV['RAILWAY_ENVIRONMENT']<?php ??<?php 'production')<?php ===<?php 'production')<?php {
<?php die("Erro<?php de<?php conexão<?php com<?php o<?php banco<?php de<?php dados.<?php Tente<?php novamente<?php em<?php alguns<?php minutos.");
<?php }<?php else<?php {
<?php die("Erro<?php de<?php conexão:<?php "<?php .<?php $e->getMessage());
<?php }
}

//<?php Buscar<?php configurações<?php do<?php site
try<?php {
<?php $site<?php =<?php $pdo->query("SELECT<?php nome_site,<?php logo,<?php deposito_min,<?php saque_min,<?php cpa_padrao,<?php revshare_padrao<?php FROM<?php config<?php LIMIT<?php 1")->fetch(PDO::FETCH_ASSOC);
<?php $nomeSite<?php =<?php $site['nome_site']<?php ??<?php 'RaspinhaPix';<?php 
<?php $logoSite<?php =<?php $site['logo']<?php ??<?php '';
<?php $depositoMin<?php =<?php $site['deposito_min']<?php ??<?php 10;
<?php $saqueMin<?php =<?php $site['saque_min']<?php ??<?php 50;
<?php $cpaPadrao<?php =<?php $site['cpa_padrao']<?php ??<?php 10;
<?php $revshare_padrao<?php =<?php $site['revshare_padrao']<?php ??<?php 10;
}<?php catch(PDOException<?php $e)<?php {
<?php //<?php Valores<?php padrão<?php caso<?php a<?php tabela<?php config<?php não<?php exista<?php ainda
<?php $nomeSite<?php =<?php 'RaspinhaPix';
<?php $logoSite<?php =<?php '';
<?php $depositoMin<?php =<?php 10;
<?php $saqueMin<?php =<?php 50;
<?php $cpaPadrao<?php =<?php 10;
<?php $revshare_padrao<?php =<?php 10;
<?php error_log("Erro<?php ao<?php buscar<?php configurações:<?php "<?php .<?php $e->getMessage());
}

//<?php Configurações<?php globais<?php para<?php Railway
define('SITE_URL',<?php $urlSite);
define('IS_RAILWAY',<?php true);
define('ENVIRONMENT',<?php $_ENV['RAILWAY_ENVIRONMENT']<?php ??<?php 'production');

//<?php Timezone
date_default_timezone_set('America/Sao_Paulo');
?>

