# 🎰 RASPINHAPIX - SISTEMA COMPLETO

Sistema completo de raspadinhas online com integração RushPay e xTracky.

## 🚀 INSTALAÇÃO RÁPIDA

1. **Fazer upload dos arquivos** para seu servidor web
2. **Importar banco de dados:** `mysql -u usuario -p banco < database_complete.sql`
3. **Configurar:** Copie `config_example.php` para `config.php` e edite
4. **Acessar:** Seu sistema estará funcionando!

## 📋 REQUISITOS

- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- Extensões: curl, json, mysql, mbstring

## 🔧 CONFIGURAÇÕES

### RushPay
- Secret Key e Public Key no `config.php`
- Webhook: `https://seusite.com/callback/rushpay.php`

### xTracky
- Token no `config.php`
- Webhook RushPay: `https://api.xtracky.com/api/integrations/rushpay`

## 📖 DOCUMENTAÇÃO

Veja `GUIA_INSTALACAO_COMPLETO.md` para instruções detalhadas.

## ✅ FUNCIONALIDADES

- ✅ Sistema de raspadinhas
- ✅ Pagamentos PIX via RushPay
- ✅ Tracking xTracky com UTMs
- ✅ Sistema de afiliados
- ✅ Painel administrativo
- ✅ Webhooks automáticos

**🎯 SISTEMA 100% FUNCIONAL E TESTADO!**

