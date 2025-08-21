# 🚀 RASPINHAPIX - SISTEMA COMPLETO FINAL

## 📦 CONTEÚDO DO PACOTE

### ✅ SISTEMA COMPLETO:
- **RaspinhaPix** - Sistema de raspadinhas online
- **Painel Admin** - Dashboard administrativo completo
- **Integração RushPay** - PIX com UTMs funcionando
- **Integração xTracky** - Tracking de afiliados
- **Sistema de Afiliados** - Comissões automáticas
- **Múltiplos Gateways** - PixUP, DigitoPay, Gateway Próprio

### 🗂️ ARQUIVOS INCLUÍDOS:
- `database_final.sql` - Banco de dados completo
- `config_final.php` - Arquivo de configuração
- Todos os arquivos PHP do sistema
- Assets, CSS, JavaScript
- APIs e callbacks funcionais

## 🛠️ INSTALAÇÃO RÁPIDA

### 1️⃣ UPLOAD DOS ARQUIVOS
```bash
# Extrair no diretório web
unzip RASPINHAPIX_FINAL_COMPLETO.zip
cp -r raspinhapix_final_completo/* /var/www/html/
```

### 2️⃣ BANCO DE DADOS
```bash
# Criar banco
mysql -u root -p
CREATE DATABASE raspinhapix;
exit

# Importar dados
mysql -u root -p raspinhapix < database_final.sql
```

### 3️⃣ CONFIGURAÇÃO
```bash
# Copiar configuração
cp config_final.php config.php

# Editar com suas credenciais
nano config.php
```

### 4️⃣ PERMISSÕES
```bash
# Definir permissões
chmod 755 -R /var/www/html/
chown www-data:www-data -R /var/www/html/
```

## 🔧 CONFIGURAÇÕES NECESSÁRIAS

### 📊 BANCO DE DADOS:
- **Host:** localhost (ou seu host)
- **Banco:** raspinhapix
- **Usuário:** root (ou seu usuário)
- **Senha:** (sua senha)

### 🔑 RUSHPAY (Já configurado):
- **Secret Key:** 213d1905-9ac0-4023-8dbd-0279918c7bcd
- **Public Key:** 50742ec4-8eac-4516-a957-b896209ce27c
- **Webhook:** https://seusite.com/callback/rushpay.php

### 📈 XTRACKY (Já configurado):
- **Token:** bf9188a4-c1ad-4101-bc6b-af11ab9c33b8
- **API:** https://api.xtracky.com/api/integrations/api

## 🎯 ACESSO AO SISTEMA

### 👤 USUÁRIO ADMIN:
- **Email:** teste@teste.com
- **Senha:** 123456
- **Nível:** Administrador

### 🌐 URLs IMPORTANTES:
- **Sistema:** https://seusite.com/
- **Admin:** https://seusite.com/admin/
- **Login:** https://seusite.com/login
- **Cadastro:** https://seusite.com/cadastro

## 🚀 DEPLOY NA RAILWAY

### 1️⃣ PREPARAR REPOSITÓRIO:
```bash
git init
git add .
git commit -m "Sistema RaspinhaPix completo"
git push origin main
```

### 2️⃣ CONFIGURAR RAILWAY:
- Conectar repositório GitHub
- Adicionar MySQL database
- Configurar variáveis de ambiente

### 3️⃣ VARIÁVEIS DE AMBIENTE:
```
MYSQLHOST=seu_host
MYSQLDATABASE=raspinhapix
MYSQLUSER=seu_usuario
MYSQLPASSWORD=sua_senha
MYSQLPORT=3306
RUSHPAY_SECRET_KEY=213d1905-9ac0-4023-8dbd-0279918c7bcd
RUSHPAY_PUBLIC_KEY=50742ec4-8eac-4516-a957-b896209ce27c
XTRACKY_TOKEN=bf9188a4-c1ad-4101-bc6b-af11ab9c33b8
```

## ✅ FUNCIONALIDADES TESTADAS

### 🎰 SISTEMA PRINCIPAL:
- ✅ Cadastro e login de usuários
- ✅ Sistema de raspadinhas
- ✅ Depósitos via PIX (RushPay)
- ✅ Sistema de afiliados
- ✅ Tracking UTMs (xTracky)

### 🎛️ PAINEL ADMIN:
- ✅ Dashboard com estatísticas
- ✅ Gerenciar usuários
- ✅ Gerenciar depósitos/saques
- ✅ Configurar raspadinhas
- ✅ Configurar gateways (RushPay incluído)
- ✅ Sistema de afiliados
- ✅ Gerenciar banners

### 🔗 INTEGRAÇÕES:
- ✅ RushPay - PIX com UTMs
- ✅ xTracky - Tracking completo
- ✅ Webhook RushPay funcionando
- ✅ APIs de pagamento
- ✅ Callbacks automáticos

## 🆘 SUPORTE

### 📋 CHECKLIST DE PROBLEMAS:
- [ ] Banco de dados importado corretamente
- [ ] Arquivo config.php configurado
- [ ] Permissões de arquivo corretas
- [ ] Apache/Nginx funcionando
- [ ] PHP 7.4+ instalado
- [ ] Extensões PHP necessárias

### 🔧 LOGS IMPORTANTES:
- Apache: `/var/log/apache2/error.log`
- PHP: `/var/log/php/error.log`
- Sistema: Verificar painel admin

## 🎉 SISTEMA PRONTO!

Após seguir todos os passos, você terá:
- ✅ Sistema de raspadinhas funcionando 24/7
- ✅ Painel administrativo completo
- ✅ Integrações RushPay + xTracky ativas
- ✅ Sistema profissional pronto para produção

**VALOR ESTIMADO DO SISTEMA: R$ 15.000 - R$ 25.000**

