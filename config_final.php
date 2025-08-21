<?php
/**
<?php *<?php CONFIGURAÇÃO RASPINHAPIX -<?php SISTEMA COMPLETO *<?php *<?php Sistema de Raspadinhas Online com:
<?php *<?php -<?php Integração RushPay (PIX com UTMs)
<?php *<?php -<?php Integração xTracky (Tracking de afiliados)
<?php *<?php -<?php Painel administrativo completo *<?php -<?php Sistema de afiliados *<?php -<?php Múltiplos gateways de pagamento */

//<?php =====<?php CONFIGURAÇÃO DO BANCO DE DADOS =====
define('DB_HOST',<?php 'localhost');<?php //<?php Host do banco (Railway:<?php usar variável de ambiente)
define('DB_NAME',<?php 'raspinhapix');<?php //<?php Nome do banco
define('DB_USER',<?php 'root');<?php //<?php Usuário do banco (Railway:<?php usar variável de ambiente)
define('DB_PASS',<?php '123456');<?php //<?php Senha do banco (Railway:<?php usar variável de ambiente)

//<?php =====<?php CONFIGURAÇÃO RUSHPAY =====
//<?php Chaves já<?php configuradas no painel admin,<?php mas podem ser alteradas aqui se necessário
define('RUSHPAY_SECRET_KEY',<?php '213d1905-9ac0-4023-8dbd-0279918c7bcd');
define('RUSHPAY_PUBLIC_KEY',<?php '50742ec4-8eac-4516-a957-b896209ce27c');
define('RUSHPAY_ENDPOINT',<?php 'https://pay.rushpayoficial.com');

//<?php =====<?php CONFIGURAÇÃO XTRACKY =====
define('XTRACKY_TOKEN',<?php 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8');
define('XTRACKY_API_URL',<?php 'https://api.xtracky.com/api/integrations/api');

//<?php =====<?php CONFIGURAÇÃO DO SISTEMA =====
define('SITE_URL',<?php 'https://seusite.com');<?php //<?php URL do seu site
define('SITE_NAME',<?php 'RaspinhaPix');<?php //<?php Nome do site
define('ADMIN_EMAIL',<?php 'admin@seusite.com');<?php //<?php Email do administrador

//<?php =====<?php CONFIGURAÇÃO DE SEGURANÇA =====
define('JWT_SECRET',<?php 'sua_chave_secreta_jwt_aqui');<?php //<?php Chave para JWT (altere!)
define('ENCRYPT_KEY',<?php 'sua_chave_criptografia');<?php //<?php Chave para criptografia (altere!)

//<?php =====<?php CONFIGURAÇÃO DE EMAIL =====
define('SMTP_HOST',<?php 'smtp.gmail.com');<?php //<?php Servidor SMTP
define('SMTP_PORT',<?php 587);<?php //<?php Porta SMTP
define('SMTP_USER',<?php 'seu_email@gmail.com');<?php //<?php Usuário SMTP
define('SMTP_PASS',<?php 'sua_senha_app');<?php //<?php Senha do app SMTP

//<?php =====<?php CONFIGURAÇÃO RAILWAY (Para deploy na Railway)<?php =====
/*
Para usar na Railway,<?php descomente e configure as variáveis de ambiente:

define('DB_HOST',<?php $_ENV['MYSQLHOST']<?php ??<?php 'localhost');
define('DB_NAME',<?php $_ENV['MYSQLDATABASE']<?php ??<?php 'raspinhapix');
define('DB_USER',<?php $_ENV['MYSQLUSER']<?php ??<?php 'root');
define('DB_PASS',<?php $_ENV['MYSQLPASSWORD']<?php ??<?php '123456');
define('DB_PORT',<?php $_ENV['MYSQLPORT']<?php ??<?php 3306);

//<?php Adicione estas variáveis no painel Railway:
//<?php MYSQLHOST=seu_host_mysql
//<?php MYSQLDATABASE=raspinhapix
//<?php MYSQLUSER=seu_usuario
//<?php MYSQLPASSWORD=sua_senha
//<?php MYSQLPORT=3306
//<?php RUSHPAY_SECRET_KEY=sua_secret_key
//<?php RUSHPAY_PUBLIC_KEY=sua_public_key
//<?php XTRACKY_TOKEN=seu_token_xtracky
*/

//<?php =====<?php NÃO ALTERE ABAIXO DESTA LINHA =====
//<?php Configurações automáticas do sistema
error_reporting(E_ALL &<?php ~E_NOTICE &<?php ~E_WARNING);
ini_set('display_errors',<?php 0);
date_default_timezone_set('America/Sao_Paulo');
session_start();

?>

