# 🚀 GUIA DE INSTALAÇÃO COMPLETA - RaspinhaPix v2.0

## 📋 **CHECKLIST PRÉ-INSTALAÇÃO**

### ✅ **Requisitos do Servidor:**
- [ ] PHP 7.4+ (recomendado 8.0+)
- [ ] MySQL 5.7+ ou MariaDB 10.3+
- [ ] Apache/Nginx com mod_rewrite
- [ ] SSL/HTTPS configurado
- [ ] Extensões PHP: mysqli, curl, json, mbstring, openssl

### ✅ **Credenciais Necessárias:**
- [ ] Dados do banco MySQL
- [ ] Chaves RushPay (Secret + Public)
- [ ] Token xTracky (já incluído)
- [ ] Domínio/subdomínio configurado

---

## 🔧 **INSTALAÇÃO PASSO A PASSO**

### **PASSO 1: PREPARAR SERVIDOR**

#### 1.1 - Atualizar Sistema (Ubuntu/Debian):
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server php php-mysql php-curl php-json php-mbstring -y
```

#### 1.2 - Configurar Apache:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### 1.3 - Configurar MySQL:
```bash
sudo mysql_secure_installation
```

### **PASSO 2: UPLOAD DOS ARQUIVOS**

#### 2.1 - Extrair Sistema:
```bash
# Upload do ZIP para o servidor
unzip raspinhapix_final_v2.zip

# Copiar para diretório web
sudo cp -r raspinhapix_final_v2/* /var/www/html/
```

#### 2.2 - Configurar Permissões:
```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
sudo chmod 644 /var/www/html/config.php
```

### **PASSO 3: CONFIGURAR BANCO DE DADOS**

#### 3.1 - Criar Banco:
```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE raspinhapix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'raspinha_user'@'localhost' IDENTIFIED BY 'senha_forte_aqui';
GRANT ALL PRIVILEGES ON raspinhapix.* TO 'raspinha_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 3.2 - Importar Estrutura:
```bash
mysql -u raspinha_user -p raspinhapix < /var/www/html/database_complete.sql
```

### **PASSO 4: CONFIGURAR SISTEMA**

#### 4.1 - Copiar Configuração:
```bash
cd /var/www/html/
cp config_example.php config.php
```

#### 4.2 - Editar Configurações:
```bash
nano config.php
```

**Configurar:**
```php
// Banco de Dados
$host = 'localhost';
$dbname = 'raspinhapix';
$username = 'raspinha_user';
$password = 'senha_forte_aqui';

// RushPay
$rushpay_secret_key = 'sua_secret_key_aqui';
$rushpay_public_key = 'sua_public_key_aqui';

// URL do Site
$site_url = 'https://seudominio.com';
```

### **PASSO 5: CONFIGURAR VIRTUAL HOST**

#### 5.1 - Criar VirtualHost:
```bash
sudo nano /etc/apache2/sites-available/raspinhapix.conf
```

```apache
<VirtualHost *:80>
    ServerName seudominio.com
    ServerAlias www.seudominio.com
    DocumentRoot /var/www/html
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/raspinhapix_error.log
    CustomLog ${APACHE_LOG_DIR}/raspinhapix_access.log combined
</VirtualHost>
```

#### 5.2 - Ativar Site:
```bash
sudo a2ensite raspinhapix.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

### **PASSO 6: CONFIGURAR SSL (HTTPS)**

#### 6.1 - Instalar Certbot:
```bash
sudo apt install certbot python3-certbot-apache -y
```

#### 6.2 - Obter Certificado:
```bash
sudo certbot --apache -d seudominio.com -d www.seudominio.com
```

---

## ⚙️ **CONFIGURAÇÕES AVANÇADAS**

### **RUSHPAY - CONFIGURAÇÃO COMPLETA**

#### 1. Obter Credenciais:
- Acesse: https://rushpayoficial.com
- Crie conta e obtenha Secret Key e Public Key

#### 2. Configurar no Sistema:
```php
// No config.php
$rushpay_secret_key = 'sua_secret_key';
$rushpay_public_key = 'sua_public_key';
$rushpay_endpoint = 'https://pay.rushpayoficial.com';
```

#### 3. Configurar Webhook (Opcional):
- URL: `https://seudominio.com/callback/rushpay.php`
- Configurar no painel RushPay

### **XTRACKY - CONFIGURAÇÃO**

#### 1. Token Pré-configurado:
```php
$xtracky_token = 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8';
$xtracky_webhook = 'https://api.xtracky.com/api/integrations/rushpay';
```

#### 2. Teste de Funcionamento:
- Acesse: `https://seudominio.com/?utm_source=teste&click_id=123`
- Gere um PIX e verifique se aparece na xTracky

---

## 🎛️ **CONFIGURAR PAINEL ADMIN**

### **PASSO 1: CRIAR USUÁRIO ADMIN**

#### 1.1 - Acessar Banco:
```bash
mysql -u raspinha_user -p raspinhapix
```

#### 1.2 - Criar Admin:
```sql
INSERT INTO usuarios (nome, email, senha, admin, saldo, data_cadastro) 
VALUES ('Administrador', 'admin@seudominio.com', MD5('senha_admin_forte'), 1, 0, NOW());
```

### **PASSO 2: ACESSAR PAINEL**

#### 2.1 - Login:
- URL: `https://seudominio.com/admin/`
- Email: admin@seudominio.com
- Senha: senha_admin_forte

#### 2.2 - Configurações Iniciais:
1. **Gateway:** Configurar RushPay
2. **Raspadinhas:** Criar cartelas
3. **Banners:** Upload de imagens
4. **Afiliados:** Configurar comissões

---

## 🔧 **OTIMIZAÇÕES DE PERFORMANCE**

### **PHP.INI Otimizado:**
```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 50M
post_max_size = 50M
max_input_vars = 3000
```

### **MySQL Otimizado:**
```sql
-- my.cnf
[mysqld]
innodb_buffer_pool_size = 1G
query_cache_size = 64M
query_cache_type = 1
```

### **Apache Otimizado:**
```apache
# .htaccess
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>
```

---

## 🛡️ **SEGURANÇA E BACKUP**

### **Configurações de Segurança:**

#### 1. Firewall:
```bash
sudo ufw enable
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
```

#### 2. Fail2Ban:
```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
```

#### 3. Backup Automático:
```bash
#!/bin/bash
# backup_raspinhapix.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u raspinha_user -p raspinhapix > /backup/raspinhapix_$DATE.sql
tar -czf /backup/files_$DATE.tar.gz /var/www/html/
```

### **Monitoramento:**

#### 1. Logs Importantes:
- `/var/log/apache2/raspinhapix_error.log`
- `/var/log/mysql/error.log`
- `/var/www/html/logs/`

#### 2. Monitoramento de Performance:
```bash
# Instalar htop
sudo apt install htop -y

# Monitorar processos
htop
```

---

## 🧪 **TESTES PÓS-INSTALAÇÃO**

### **CHECKLIST DE TESTES:**

#### ✅ **Sistema Principal:**
- [ ] Página inicial carrega
- [ ] Cadastro funciona
- [ ] Login funciona
- [ ] Raspadinhas aparecem

#### ✅ **Pagamentos:**
- [ ] Geração PIX funciona
- [ ] UTMs são enviados
- [ ] Webhook processa pagamentos
- [ ] Saldo é creditado

#### ✅ **Painel Admin:**
- [ ] Login admin funciona
- [ ] Dashboard carrega
- [ ] Configurações salvam
- [ ] Relatórios funcionam

#### ✅ **Integrações:**
- [ ] RushPay gera PIX
- [ ] xTracky recebe eventos
- [ ] Afiliados funcionam
- [ ] Webhooks respondem

---

## 🆘 **SOLUÇÃO DE PROBLEMAS**

### **Problemas Comuns:**

#### 1. **Erro 500 - Internal Server Error**
```bash
# Verificar logs
tail -f /var/log/apache2/raspinhapix_error.log

# Verificar permissões
sudo chown -R www-data:www-data /var/www/html/
```

#### 2. **Erro de Conexão com Banco**
```bash
# Testar conexão
mysql -u raspinha_user -p raspinhapix

# Verificar configurações
nano /var/www/html/config.php
```

#### 3. **PIX não Gera**
- Verificar credenciais RushPay
- Testar conectividade com API
- Verificar logs de erro

#### 4. **Admin não Acessa**
- Verificar usuário admin no banco
- Conferir senha (MD5)
- Verificar sessões PHP

### **Comandos de Diagnóstico:**
```bash
# Status dos serviços
sudo systemctl status apache2
sudo systemctl status mysql

# Verificar PHP
php -v
php -m | grep mysql

# Testar conectividade
curl -I https://seudominio.com
```

---

## 📞 **SUPORTE TÉCNICO**

### **Informações para Suporte:**
- Versão do PHP: `php -v`
- Versão do MySQL: `mysql --version`
- Sistema Operacional: `lsb_release -a`
- Logs de erro relevantes
- Configurações do sistema

### **Backup Antes de Mudanças:**
```bash
# Sempre fazer backup antes de alterações
cp -r /var/www/html/ /backup/html_backup_$(date +%Y%m%d)
mysqldump -u raspinha_user -p raspinhapix > /backup/db_backup_$(date +%Y%m%d).sql
```

---

## 🎉 **INSTALAÇÃO CONCLUÍDA!**

Após seguir todos os passos, seu sistema RaspinhaPix estará funcionando 100% com:

✅ **Sistema completo funcionando**
✅ **Integrações RushPay + xTracky ativas**
✅ **Painel administrativo acessível**
✅ **SSL/HTTPS configurado**
✅ **Backup e segurança implementados**

**Seu sistema está pronto para receber usuários e gerar lucro!** 🚀

