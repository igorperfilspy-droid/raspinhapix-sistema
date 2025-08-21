<?php
//<?php CONEXÃO OTIMIZADA PARA RAILWAY
//<?php Substitua o arquivo conexao.php original por este

//<?php Configurações do banco (Railway usa variáveis de ambiente)
$host =<?php $_ENV['DB_HOST']<?php ??<?php getenv('MYSQL_HOST')<?php ??<?php 'localhost';
$port =<?php $_ENV['DB_PORT']<?php ??<?php getenv('MYSQL_PORT')<?php ??<?php '3306';
$dbname =<?php $_ENV['DB_NAME']<?php ??<?php getenv('MYSQL_DATABASE')<?php ??<?php 'raspinhapix';
$username =<?php $_ENV['DB_USER']<?php ??<?php getenv('MYSQL_USER')<?php ??<?php 'root';
$password =<?php $_ENV['DB_PASS']<?php ??<?php getenv('MYSQL_PASSWORD')<?php ??<?php '';

//<?php URL do site (Railway)
$urlSite =<?php $_ENV['RAILWAY_STATIC_URL']<?php ??<?php 'https://localhost';

try {
<?php //<?php Conexão PDO com configurações otimizadas para Railway $dsn =<?php "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
<?php $options =<?php [
<?php PDO::ATTR_ERRMODE =><?php PDO::ERRMODE_EXCEPTION,
<?php PDO::ATTR_DEFAULT_FETCH_MODE =><?php PDO::FETCH_ASSOC,
<?php PDO::ATTR_EMULATE_PREPARES =><?php false,
<?php PDO::MYSQL_ATTR_INIT_COMMAND =><?php "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
<?php ];
<?php $pdo =<?php new PDO($dsn,<?php $username,<?php $password,<?php $options);
<?php //<?php Log de sucesso (apenas em desenvolvimento)
<?php if ($_ENV['RAILWAY_ENVIRONMENT']<?php ===<?php 'development')<?php {
<?php error_log("Conexão Railway estabelecida com sucesso!");
<?php }
<?php 
}<?php catch(PDOException $e)<?php {
<?php //<?php Log do erro error_log("Erro de conexão Railway:<?php "<?php .<?php $e->getMessage());
<?php //<?php Em produção,<?php mostrar erro genérico if ($_ENV['RAILWAY_ENVIRONMENT']<?php ===<?php 'production')<?php {
<?php die("Erro de conexão com o banco de dados.<?php Tente novamente em alguns minutos.");
<?php }<?php else {
<?php die("Erro de conexão:<?php "<?php .<?php $e->getMessage());
<?php }
}

//<?php Configurações globais para Railway
define('SITE_URL',<?php $urlSite);
define('IS_RAILWAY',<?php true);
define('ENVIRONMENT',<?php $_ENV['RAILWAY_ENVIRONMENT']<?php ??<?php 'production');

//<?php Timezone
date_default_timezone_set('America/Sao_Paulo');
?>

