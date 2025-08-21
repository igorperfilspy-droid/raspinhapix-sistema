<?php
// Configurações do Banco de Dados
$host = 'localhost';
$dbname = 'raspinhapix';
$username = 'root';
$password = 'sua_senha_mysql';

// Configurações RushPay
$rushpay_secret_key = 'sua_secret_key_rushpay';
$rushpay_public_key = 'sua_public_key_rushpay';
$rushpay_endpoint = 'https://pay.rushpayoficial.com';

// Configurações xTracky
$xtracky_token = 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8';
$xtracky_webhook = 'https://api.xtracky.com/api/integrations/rushpay';

// URL do seu site
$site_url = 'https://seusite.com';

// Configurações de Email (opcional)
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_user = 'seu_email@gmail.com';
$smtp_pass = 'sua_senha_email';

// Configurações de Segurança
$jwt_secret = 'sua_chave_jwt_secreta';
$encryption_key = 'sua_chave_criptografia';

// Configurações do Sistema
$sistema_nome = 'RaspinhaPix';
$sistema_versao = '2.0';
$debug_mode = false;

// Configurações de Afiliados
$comissao_padrao = 5; // Porcentagem padrão de comissão
$min_saque = 10; // Valor mínimo para saque

// Configurações de Raspadinhas
$valor_min_cartela = 1; // Valor mínimo por cartela
$valor_max_cartela = 100; // Valor máximo por cartela
?>

