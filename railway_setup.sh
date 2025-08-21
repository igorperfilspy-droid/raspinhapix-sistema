#!/bin/bash

# Script de Setup Automático - RaspinhaPix Railway
# Execute este script após extrair o ZIP

echo "🚀 SETUP AUTOMÁTICO RASPINHAPIX RAILWAY v2.0"
echo "=============================================="

# Verificar se está no diretório correto
if [ ! -f "index.php" ]; then
    echo "❌ Erro: Execute este script no diretório do sistema extraído!"
    echo "   Certifique-se de estar em: raspinhapix_final_v2/"
    exit 1
fi

echo "✅ Diretório correto detectado"

# 1. Criar arquivo de conexão Railway
echo "📝 Criando arquivo de conexão Railway..."
cat > conexao.php << 'EOF'
<?php
// Conexão otimizada para Railway
$host = $_ENV['MYSQLHOST'] ?? 'localhost';
$port = $_ENV['MYSQLPORT'] ?? '3306';
$dbname = $_ENV['MYSQLDATABASE'] ?? 'railway';
$username = $_ENV['MYSQLUSER'] ?? 'root';
$password = $_ENV['MYSQLPASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
EOF

# 2. Criar Dockerfile
echo "🐳 Criando Dockerfile..."
cat > Dockerfile << 'EOF'
FROM php:8.1-apache

# Instalar extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    gd \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite
RUN a2enmod rewrite headers expires deflate

# Configurar PHP
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "date.timezone = America/Sao_Paulo" >> /usr/local/etc/php/conf.d/custom.ini

# Copiar arquivos do projeto
COPY . /var/www/html/

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/ \
    && chmod -R 777 /var/www/html/logs

# Expor porta 80
EXPOSE 80

# Comando de inicialização
CMD ["apache2-foreground"]
EOF

# 3. Criar .htaccess se não existir
if [ ! -f ".htaccess" ]; then
    echo "⚙️ Criando .htaccess..."
    cat > .htaccess << 'EOF'
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Configurações de segurança
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>

<Files "conexao.php">
    Order allow,deny
    Deny from all
</Files>

# Compressão
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
EOF
fi

# 4. Criar config.php Railway
echo "🔧 Criando config.php para Railway..."
cat > config.php << 'EOF'
<?php
// Configuração Railway - RaspinhaPix v2.0

// Banco de Dados Railway
$host = $_ENV['MYSQLHOST'] ?? 'localhost';
$port = $_ENV['MYSQLPORT'] ?? '3306';
$dbname = $_ENV['MYSQLDATABASE'] ?? 'railway';
$username = $_ENV['MYSQLUSER'] ?? 'root';
$password = $_ENV['MYSQLPASSWORD'] ?? '';

// RushPay
$rushpay_secret_key = $_ENV['RUSHPAY_SECRET_KEY'] ?? 'sua_secret_key';
$rushpay_public_key = $_ENV['RUSHPAY_PUBLIC_KEY'] ?? 'sua_public_key';
$rushpay_endpoint = $_ENV['RUSHPAY_ENDPOINT'] ?? 'https://pay.rushpayoficial.com';

// xTracky
$xtracky_token = $_ENV['XTRACKY_TOKEN'] ?? 'bf9188a4-c1ad-4101-bc6b-af11ab9c33b8';
$xtracky_webhook = $_ENV['XTRACKY_WEBHOOK'] ?? 'https://api.xtracky.com/api/integrations/rushpay';

// URL do site
$site_url = $_ENV['SITE_URL'] ?? 'https://seu-projeto.up.railway.app';

// Sistema
$sistema_nome = 'RaspinhaPix';
$sistema_versao = '2.0';
$debug_mode = $_ENV['DEBUG_MODE'] ?? false;

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Constantes
define('SITE_URL', $site_url);
define('RUSHPAY_SECRET', $rushpay_secret_key);
define('RUSHPAY_PUBLIC', $rushpay_public_key);
define('RUSHPAY_ENDPOINT', $rushpay_endpoint);
define('XTRACKY_TOKEN', $xtracky_token);
define('XTRACKY_WEBHOOK', $xtracky_webhook);
define('DEBUG_MODE', $debug_mode);
?>
EOF

# 5. Criar .gitignore
echo "📁 Criando .gitignore..."
cat > .gitignore << 'EOF'
# Arquivos de configuração sensíveis
config_local.php
.env

# Logs
logs/*.log
*.log

# Uploads
uploads/*
!uploads/.gitkeep

# Cache
cache/*
!cache/.gitkeep

# Temporários
tmp/*
!tmp/.gitkeep

# Sistema
.DS_Store
Thumbs.db
*.swp
*.swo

# IDE
.vscode/
.idea/
*.sublime-*

# Composer
vendor/
composer.lock

# Node
node_modules/
npm-debug.log
yarn-error.log
EOF

# 6. Criar README para Railway
echo "📖 Criando README Railway..."
cat > README_RAILWAY.md << 'EOF'
# 🚀 RaspinhaPix v2.0 - Railway Deploy

## ⚡ Deploy Rápido

1. **Upload para GitHub**
2. **Railway → New Project → Deploy from GitHub**
3. **Adicionar MySQL Database**
4. **Configurar variáveis de ambiente**
5. **Importar banco de dados**

## 🔧 Variáveis de Ambiente Necessárias

```env
# MySQL (copiar do Railway MySQL service)
MYSQLHOST=containers-us-west-xxx.railway.app
MYSQLPORT=6543
MYSQLDATABASE=railway
MYSQLUSER=root
MYSQLPASSWORD=senha_gerada

# RushPay
RUSHPAY_SECRET_KEY=sua_secret_key
RUSHPAY_PUBLIC_KEY=sua_public_key
RUSHPAY_ENDPOINT=https://pay.rushpayoficial.com

# xTracky
XTRACKY_TOKEN=bf9188a4-c1ad-4101-bc6b-af11ab9c33b8
XTRACKY_WEBHOOK=https://api.xtracky.com/api/integrations/rushpay

# Sistema
SITE_URL=https://seu-projeto.up.railway.app
DEBUG_MODE=false
```

## 📊 Sistema Funcionando

- ✅ RaspinhaPix completo
- ✅ Painel administrativo
- ✅ Integração RushPay
- ✅ Tracking xTracky
- ✅ SSL automático
- ✅ Backup automático

**Custo: ~$5-8/mês**
EOF

# 7. Verificar estrutura
echo "🔍 Verificando estrutura de arquivos..."
REQUIRED_FILES=("index.php" "admin/index.php" "api/payment.php" "classes/RushPay.php" "database_complete.sql")
MISSING_FILES=()

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        MISSING_FILES+=("$file")
    fi
done

if [ ${#MISSING_FILES[@]} -eq 0 ]; then
    echo "✅ Todos os arquivos necessários estão presentes"
else
    echo "❌ Arquivos faltando:"
    for file in "${MISSING_FILES[@]}"; do
        echo "   - $file"
    done
fi

# 8. Resumo final
echo ""
echo "🎉 SETUP CONCLUÍDO COM SUCESSO!"
echo "================================"
echo ""
echo "📁 Arquivos criados:"
echo "   ✅ conexao.php (Railway)"
echo "   ✅ Dockerfile"
echo "   ✅ .htaccess"
echo "   ✅ config.php (Railway)"
echo "   ✅ .gitignore"
echo "   ✅ README_RAILWAY.md"
echo ""
echo "🚀 PRÓXIMOS PASSOS:"
echo "   1. Upload para GitHub"
echo "   2. Deploy no Railway"
echo "   3. Adicionar MySQL"
echo "   4. Configurar variáveis"
echo "   5. Importar banco"
echo ""
echo "📖 Consulte: GUIA_RAILWAY_COMPLETO_V2.md"
echo ""
echo "✨ Sistema pronto para Railway!"
EOF

# Tornar o script executável
chmod +x railway_setup.sh

echo "✅ Script de setup criado: railway_setup.sh"

