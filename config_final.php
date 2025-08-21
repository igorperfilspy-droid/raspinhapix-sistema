<?php
/**
 * CONFIGURAÇÃO RASPINHAPIX - SISTEMA COMPLETO
 * 
 * Sistema de Raspadinhas Online com:
 * - Integração RushPay (PIX com UTMs)
 * - Integração xTracky (Tracking de afiliados)
 * - Painel administrativo completo
 * - Sistema de afiliados
 * - Múltiplos gateways de pagamento
 */

// ===== CONFIGURAÇÃO DO BANCO DE DADOS =====
define('DB_HOST', 'localhost');        // Host do banco (Railway: usar variável de ambiente)
define('DB_NAME', 'raspinhapix');      // Nome do banco
define('DB_USER', 'root');             // Usuário do banco (Railway: usar variável de ambiente)
define('DB_PASS', '123456');           // Senha do banco (Railway: usar variável de ambiente)

// ===== CONFIGURAÇÃO RUSHPAY =====
// Chaves já configuradas no painel admin, mas podem ser alteradas aqui se necessário
define('RUSHPAY_SECRET_KEY', '213d1905-9ac0-4023-8dbd-0279918c7bcd');
define('RUSHPAY_PUBLIC_KEY', '50742ec4-8eac-4516-a957-b896209ce27c');
define('RUSHPAY_ENDPOINT', 'https://pay.rushpayoficial.com');

// ===== CONFIGURAÇÃO XTRACKY =====
define('XTRACKY_TOKEN', 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8');
define('XTRACKY_API_URL', 'https://api.xtracky.com/api/integrations/api');

// ===== CONFIGURAÇÃO DO SISTEMA =====
define('SITE_URL', 'https://seusite.com');           // URL do seu site
define('SITE_NAME', 'RaspinhaPix');                  // Nome do site
define('ADMIN_EMAIL', 'admin@seusite.com');          // Email do administrador

// ===== CONFIGURAÇÃO DE SEGURANÇA =====
define('JWT_SECRET', 'sua_chave_secreta_jwt_aqui');  // Chave para JWT (altere!)
define('ENCRYPT_KEY', 'sua_chave_criptografia');     // Chave para criptografia (altere!)

// ===== CONFIGURAÇÃO DE EMAIL =====
define('SMTP_HOST', 'smtp.gmail.com');               // Servidor SMTP
define('SMTP_PORT', 587);                            // Porta SMTP
define('SMTP_USER', 'seu_email@gmail.com');          // Usuário SMTP
define('SMTP_PASS', 'sua_senha_app');                // Senha do app SMTP

// ===== CONFIGURAÇÃO RAILWAY (Para deploy na Railway) =====
/*
Para usar na Railway, descomente e configure as variáveis de ambiente:

define('DB_HOST', $_ENV['MYSQLHOST'] ?? 'localhost');
define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? 'raspinhapix');
define('DB_USER', $_ENV['MYSQLUSER'] ?? 'root');
define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '123456');
define('DB_PORT', $_ENV['MYSQLPORT'] ?? 3306);

// Adicione estas variáveis no painel Railway:
// MYSQLHOST=seu_host_mysql
// MYSQLDATABASE=raspinhapix
// MYSQLUSER=seu_usuario
// MYSQLPASSWORD=sua_senha
// MYSQLPORT=3306
// RUSHPAY_SECRET_KEY=sua_secret_key
// RUSHPAY_PUBLIC_KEY=sua_public_key
// XTRACKY_TOKEN=seu_token_xtracky
*/

// ===== NÃO ALTERE ABAIXO DESTA LINHA =====
// Configurações automáticas do sistema
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);
date_default_timezone_set('America/Sao_Paulo');
session_start();

?>

