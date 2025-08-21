<?php
// =====================================================
// SETUP AUTOMÁTICO PARA RAILWAY - RASPINHAPIX
// Execute este arquivo UMA VEZ SÓ para configurar o banco
// =====================================================

// Incluir conexão
include 'conexao.php';

// Verificar se já foi executado
$check = $pdo->query("SHOW TABLES LIKE 'config'")->rowCount();
if ($check > 0) {
    $config_check = $pdo->query("SELECT COUNT(*) FROM config")->fetchColumn();
    if ($config_check > 0) {
        die("
        <h1>✅ SISTEMA JÁ CONFIGURADO!</h1>
        <p>O banco de dados já está configurado e funcionando.</p>
        <p><strong>IMPORTANTE:</strong> Delete este arquivo setup.php por segurança!</p>
        <p><a href='/'>Acessar o Sistema</a></p>
        ");
    }
}

echo "<h1>🚀 CONFIGURANDO RASPINHAPIX NO RAILWAY</h1>";
echo "<p>Executando configuração automática...</p>";

try {
    // Ler o arquivo SQL original
    $sql_file = 'database_complete.sql';
    if (!file_exists($sql_file)) {
        die("<p style='color:red'>❌ Arquivo database_complete.sql não encontrado!</p>");
    }
    
    $sql_content = file_get_contents($sql_file);
    
    // Limpar comandos problemáticos
    $sql_content = str_replace('START TRANSACTION;', '', $sql_content);
    $sql_content = str_replace('COMMIT;', '', $sql_content);
    $sql_content = str_replace('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";', '', $sql_content);
    $sql_content = str_replace('SET time_zone = "+00:00";', '', $sql_content);
    
    // Remover comentários e linhas vazias
    $lines = explode("\n", $sql_content);
    $clean_lines = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0 || strpos($line, '*/') !== false) {
            continue;
        }
        if (strpos($line, '/*!') === 0) {
            continue;
        }
        $clean_lines[] = $line;
    }
    
    $clean_sql = implode("\n", $clean_lines);
    
    // Dividir em comandos individuais
    $commands = explode(';', $clean_sql);
    
    $success_count = 0;
    $error_count = 0;
    
    echo "<h2>📋 Executando comandos SQL...</h2>";
    echo "<div style='max-height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
    
    foreach ($commands as $command) {
        $command = trim($command);
        if (empty($command)) continue;
        
        try {
            $pdo->exec($command);
            $success_count++;
            echo "<p style='color: green;'>✅ Comando executado com sucesso</p>";
        } catch (PDOException $e) {
            $error_count++;
            echo "<p style='color: orange;'>⚠️ Aviso: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "</div>";
    
    // Verificar se as tabelas principais foram criadas
    $tables_check = [
        'config' => 'Configurações do sistema',
        'usuarios' => 'Usuários',
        'raspadinhas' => 'Raspadinhas',
        'raspadinha_premios' => 'Prêmios das raspadinhas',
        'depositos' => 'Depósitos',
        'saques' => 'Saques'
    ];
    
    echo "<h2>🔍 Verificando tabelas criadas:</h2>";
    $all_ok = true;
    
    foreach ($tables_check as $table => $desc) {
        $exists = $pdo->query("SHOW TABLES LIKE '$table'")->rowCount();
        if ($exists > 0) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "<p style='color: green;'>✅ $desc ($table): $count registros</p>";
        } else {
            echo "<p style='color: red;'>❌ $desc ($table): Não encontrada</p>";
            $all_ok = false;
        }
    }
    
    // Verificar usuário admin
    $admin_check = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE admin = 1")->fetchColumn();
    if ($admin_check == 0) {
        // Criar usuário admin se não existir
        $pdo->exec("INSERT INTO usuarios (nome, email, senha, admin, saldo, created_at) VALUES ('Administrador', 'admin@raspinhapix.com', MD5('123456'), 1, 0, NOW())");
        echo "<p style='color: blue;'>👤 Usuário admin criado: admin@raspinhapix.com / senha: 123456</p>";
    }
    
    // Verificar configurações
    $config_check = $pdo->query("SELECT COUNT(*) FROM config")->fetchColumn();
    if ($config_check == 0) {
        // Inserir configuração padrão se não existir
        $pdo->exec("INSERT INTO config (nome_site, logo, deposito_min, saque_min, cpa_padrao, revshare_padrao) VALUES ('RaspinhaPix', '/assets/upload/logo.png', 10, 50, 0, 10)");
        echo "<p style='color: blue;'>⚙️ Configurações padrão inseridas</p>";
    }
    
    echo "<h2>📊 Resumo da Configuração:</h2>";
    echo "<p>✅ Comandos executados com sucesso: $success_count</p>";
    echo "<p>⚠️ Avisos/Erros: $error_count</p>";
    
    if ($all_ok) {
        echo "
        <div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            <h2 style='color: #155724;'>🎉 CONFIGURAÇÃO CONCLUÍDA COM SUCESSO!</h2>
            <p><strong>Seu sistema RaspinhaPix está funcionando!</strong></p>
            <p><strong>Login Admin:</strong> admin@raspinhapix.com</p>
            <p><strong>Senha Admin:</strong> 123456</p>
            <p><strong>IMPORTANTE:</strong> Delete este arquivo setup.php por segurança!</p>
            <p><a href='/' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Acessar o Sistema</a></p>
        </div>";
    } else {
        echo "
        <div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            <h2 style='color: #721c24;'>⚠️ CONFIGURAÇÃO INCOMPLETA</h2>
            <p>Algumas tabelas não foram criadas corretamente.</p>
            <p>Verifique os erros acima e tente novamente.</p>
        </div>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro geral: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

