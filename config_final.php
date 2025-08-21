<?php
/**
<?php *<?php CONFIGURAÇÃO<?php RASPINHAPIX<?php -<?php SISTEMA<?php COMPLETO
<?php *<?php 
<?php *<?php Sistema<?php de<?php Raspadinhas<?php Online<?php com:
<?php *<?php -<?php Integração<?php RushPay<?php (PIX<?php com<?php UTMs)
<?php *<?php -<?php Integração<?php xTracky<?php (Tracking<?php de<?php afiliados)
<?php *<?php -<?php Painel<?php administrativo<?php completo
<?php *<?php -<?php Sistema<?php de<?php afiliados
<?php *<?php -<?php Múltiplos<?php gateways<?php de<?php pagamento
<?php */

//<?php =====<?php CONFIGURAÇÃO<?php DO<?php BANCO<?php DE<?php DADOS<?php =====
define('DB_HOST',<?php 'localhost');<?php //<?php Host<?php do<?php banco<?php (Railway:<?php usar<?php variável<?php de<?php ambiente)
define('DB_NAME',<?php 'raspinhapix');<?php //<?php Nome<?php do<?php banco
define('DB_USER',<?php 'root');<?php //<?php Usuário<?php do<?php banco<?php (Railway:<?php usar<?php variável<?php de<?php ambiente)
define('DB_PASS',<?php '123456');<?php //<?php Senha<?php do<?php banco<?php (Railway:<?php usar<?php variável<?php de<?php ambiente)

//<?php =====<?php CONFIGURAÇÃO<?php RUSHPAY<?php =====
//<?php Chaves<?php já<?php configuradas<?php no<?php painel<?php admin,<?php mas<?php podem<?php ser<?php alteradas<?php aqui<?php se<?php necessário
define('RUSHPAY_SECRET_KEY',<?php '213d1905-9ac0-4023-8dbd-0279918c7bcd');
define('RUSHPAY_PUBLIC_KEY',<?php '50742ec4-8eac-4516-a957-b896209ce27c');
define('RUSHPAY_ENDPOINT',<?php 'https://pay.rushpayoficial.com');

//<?php =====<?php CONFIGURAÇÃO<?php XTRACKY<?php =====
define('XTRACKY_TOKEN',<?php 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8');
define('XTRACKY_API_URL',<?php 'https://api.xtracky.com/api/integrations/api');

//<?php =====<?php CONFIGURAÇÃO<?php DO<?php SISTEMA<?php =====
define('SITE_URL',<?php 'https://seusite.com');<?php //<?php URL<?php do<?php seu<?php site
define('SITE_NAME',<?php 'RaspinhaPix');<?php //<?php Nome<?php do<?php site
define('ADMIN_EMAIL',<?php 'admin@seusite.com');<?php //<?php Email<?php do<?php administrador

//<?php =====<?php CONFIGURAÇÃO<?php DE<?php SEGURANÇA<?php =====
define('JWT_SECRET',<?php 'sua_chave_secreta_jwt_aqui');<?php //<?php Chave<?php para<?php JWT<?php (altere!)
define('ENCRYPT_KEY',<?php 'sua_chave_criptografia');<?php //<?php Chave<?php para<?php criptografia<?php (altere!)

//<?php =====<?php CONFIGURAÇÃO<?php DE<?php EMAIL<?php =====
define('SMTP_HOST',<?php 'smtp.gmail.com');<?php //<?php Servidor<?php SMTP
define('SMTP_PORT',<?php 587);<?php //<?php Porta<?php SMTP
define('SMTP_USER',<?php 'seu_email@gmail.com');<?php //<?php Usuário<?php SMTP
define('SMTP_PASS',<?php 'sua_senha_app');<?php //<?php Senha<?php do<?php app<?php SMTP

//<?php =====<?php CONFIGURAÇÃO<?php RAILWAY<?php (Para<?php deploy<?php na<?php Railway)<?php =====
/*
Para<?php usar<?php na<?php Railway,<?php descomente<?php e<?php configure<?php as<?php variáveis<?php de<?php ambiente:

define('DB_HOST',<?php $_ENV['MYSQLHOST']<?php ??<?php 'localhost');
define('DB_NAME',<?php $_ENV['MYSQLDATABASE']<?php ??<?php 'raspinhapix');
define('DB_USER',<?php $_ENV['MYSQLUSER']<?php ??<?php 'root');
define('DB_PASS',<?php $_ENV['MYSQLPASSWORD']<?php ??<?php '123456');
define('DB_PORT',<?php $_ENV['MYSQLPORT']<?php ??<?php 3306);

//<?php Adicione<?php estas<?php variáveis<?php no<?php painel<?php Railway:
//<?php MYSQLHOST=seu_host_mysql
//<?php MYSQLDATABASE=raspinhapix
//<?php MYSQLUSER=seu_usuario
//<?php MYSQLPASSWORD=sua_senha
//<?php MYSQLPORT=3306
//<?php RUSHPAY_SECRET_KEY=sua_secret_key
//<?php RUSHPAY_PUBLIC_KEY=sua_public_key
//<?php XTRACKY_TOKEN=seu_token_xtracky
*/

//<?php =====<?php NÃO<?php ALTERE<?php ABAIXO<?php DESTA<?php LINHA<?php =====
//<?php Configurações<?php automáticas<?php do<?php sistema
error_reporting(E_ALL<?php &<?php ~E_NOTICE<?php &<?php ~E_WARNING);
ini_set('display_errors',<?php 0);
date_default_timezone_set('America/Sao_Paulo');
session_start();

?>

