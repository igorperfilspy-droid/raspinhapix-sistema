# 🚀 RASPINHAPIX - GUIA DE INSTALAÇÃO COMPLETO

## 📋 REQUISITOS DO SERVIDOR

### Servidor VPS/Dedicado:
- **Sistema:** Ubuntu 20.04+ ou CentOS 7+
- **RAM:** Mínimo 2GB (Recomendado 4GB+)
- **Armazenamento:** Mínimo 20GB SSD
- **Processador:** 2 vCPUs ou mais

### Software Necessário:
- **Apache 2.4+** ou **Nginx**
- **PHP 8.0+** com extensões:
  - php-mysql
  - php-curl
  - php-json
  - php-mbstring
  - php-xml
  - php-zip
- **MySQL 8.0+** ou **MariaDB 10.4+**
- **Composer** (gerenciador de dependências PHP)

---

## 🔧 INSTALAÇÃO PASSO A PASSO

### 1. PREPARAR O SERVIDOR

#### Ubuntu/Debian:
```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Apache, PHP e MySQL
sudo apt install apache2 php8.1 php8.1-mysql php8.1-curl php8.1-json php8.1-mbstring php8.1-xml php8.1-zip mysql-server -y

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Habilitar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### CentOS/RHEL:
```bash
# Atualizar sistema
sudo yum update -y

# Instalar repositórios
sudo yum install epel-release -y
sudo yum install https://rpms.remirepo.net/enterprise/remi-release-8.rpm -y

# Instalar Apache, PHP e MySQL
sudo yum install httpd php81-php php81-php-mysql php81-php-curl php81-php-json php81-php-mbstring php81-php-xml mysql-server -y

# Iniciar serviços
sudo systemctl start httpd mysql
sudo systemctl enable httpd mysql
```

### 2. CONFIGURAR MYSQL

```bash
# Configurar MySQL
sudo mysql_secure_installation

# Criar banco de dados
sudo mysql -u root -p
```

```sql
CREATE DATABASE raspinhapix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'raspinhapix_user'@'localhost' IDENTIFIED BY 'SuaSenhaSegura123!';
GRANT ALL PRIVILEGES ON raspinhapix.* TO 'raspinhapix_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. IMPORTAR BANCO DE DADOS

```bash
# Importar estrutura do banco
mysql -u raspinhapix_user -p raspinhapix < database_complete.sql
```

### 4. INSTALAR SISTEMA

```bash
# Fazer upload dos arquivos para /var/www/html/
sudo rm -rf /var/www/html/*
sudo cp -r * /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
```

### 5. CONFIGURAR SISTEMA

```bash
# Copiar arquivo de configuração
sudo cp /var/www/html/config_example.php /var/www/html/config.php
sudo nano /var/www/html/config.php
```

Edite o arquivo `config.php` com suas configurações:
- Dados do banco MySQL
- Chaves da RushPay
- Token da xTracky
- URL do seu site

### 6. CONFIGURAR APACHE

Criar arquivo de configuração do Apache:

```bash
sudo nano /etc/apache2/sites-available/raspinhapix.conf
```

```apache
<VirtualHost *:80>
    ServerName seudominio.com
    DocumentRoot /var/www/html
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/raspinhapix_error.log
    CustomLog ${APACHE_LOG_DIR}/raspinhapix_access.log combined
</VirtualHost>
```

```bash
# Habilitar site
sudo a2ensite raspinhapix.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

### 7. CONFIGURAR SSL (OPCIONAL MAS RECOMENDADO)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache -y

# Obter certificado SSL
sudo certbot --apache -d seudominio.com
```

---

## 🔑 CONFIGURAÇÕES DAS INTEGRAÇÕES

### RUSHPAY
1. Acesse o painel da RushPay
2. Obtenha suas chaves:
   - **Secret Key**
   - **Public Key**
3. Configure o webhook: `https://seudominio.com/callback/rushpay.php`

### XTRACKY
1. Acesse o painel da xTracky
2. Obtenha seu **Token**
3. Configure o webhook da RushPay para: `https://api.xtracky.com/api/integrations/rushpay`

---

## 📁 ESTRUTURA DE ARQUIVOS

```
/var/www/html/
├── admin/              # Painel administrativo
├── afiliados/          # Sistema de afiliados
├── api/                # APIs do sistema
│   ├── payment.php     # API de pagamento (RushPay integrado)
│   └── auto_process_payment.php # Verificação automática
├── assets/             # Arquivos estáticos (CSS, JS, imagens)
├── callback/           # Webhooks
│   └── rushpay.php     # Webhook RushPay
├── classes/            # Classes PHP
│   └── RushPay.php     # Classe RushPay integrada
├── conexao.php         # Conexão com banco de dados
├── index.php           # Página principal
└── config.php          # Configurações (criar baseado no example)
```

---

## ✅ VERIFICAÇÕES PÓS-INSTALAÇÃO

### 1. Testar Conexão com Banco
Acesse: `https://seudominio.com/test_db.php`

### 2. Testar Sistema
Acesse: `https://seudominio.com/`

### 3. Testar Integração RushPay
- Faça um depósito teste
- Verifique se o PIX é gerado
- Confirme se o UTM está sendo enviado

### 4. Testar xTracky
- Acesse com UTMs na URL: `https://seudominio.com/?utm_source=teste&click_id=123`
- Faça um depósito
- Verifique no painel xTracky se os eventos chegaram

---

## 🔧 CONFIGURAÇÕES AVANÇADAS

### Permissões de Arquivos
```bash
sudo find /var/www/html -type f -exec chmod 644 {} \;
sudo find /var/www/html -type d -exec chmod 755 {} \;
sudo chmod 755 /var/www/html/assets/
sudo chmod -R 777 /var/www/html/assets/uploads/
```

### Backup Automático
```bash
# Criar script de backup
sudo nano /usr/local/bin/backup_raspinhapix.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u raspinhapix_user -p'SuaSenhaSegura123!' raspinhapix > /backup/raspinhapix_$DATE.sql
tar -czf /backup/files_$DATE.tar.gz /var/www/html/
find /backup -name "*.sql" -mtime +7 -delete
find /backup -name "*.tar.gz" -mtime +7 -delete
```

```bash
sudo chmod +x /usr/local/bin/backup_raspinhapix.sh
sudo crontab -e
# Adicionar: 0 2 * * * /usr/local/bin/backup_raspinhapix.sh
```

---

## 🚨 SOLUÇÃO DE PROBLEMAS

### Erro de Conexão com Banco
- Verifique credenciais no `config.php`
- Confirme se o MySQL está rodando: `sudo systemctl status mysql`

### PIX não está sendo gerado
- Verifique logs: `tail -f /var/log/apache2/error.log`
- Confirme chaves da RushPay no `config.php`

### UTMs não chegam na xTracky
- Verifique se o webhook está configurado na RushPay
- Confirme token da xTracky no `config.php`

### Erro 500
- Verifique permissões dos arquivos
- Confirme se todas as extensões PHP estão instaladas

---

## 📞 SUPORTE

### Logs Importantes
- **Apache:** `/var/log/apache2/error.log`
- **PHP:** `/var/log/php_errors.log`
- **Sistema:** Logs são salvos via `error_log()` no PHP

### Comandos Úteis
```bash
# Verificar status dos serviços
sudo systemctl status apache2 mysql

# Reiniciar serviços
sudo systemctl restart apache2 mysql

# Verificar logs em tempo real
sudo tail -f /var/log/apache2/error.log
```

---

## 🎯 SISTEMA COMPLETO INCLUI

✅ **Sistema RaspinhaPix Completo**
✅ **Integração RushPay com UTMs**
✅ **Integração xTracky**
✅ **Sistema de Afiliados**
✅ **Painel Administrativo**
✅ **Webhooks Configurados**
✅ **Banco de Dados Completo**
✅ **Documentação Completa**

---

**🚀 PRONTO PARA USAR!**

Após seguir este guia, seu sistema RaspinhaPix estará 100% funcional com todas as integrações configuradas e pronto para receber pagamentos via PIX com tracking completo!

