<?php
//<?php =====================================================
//<?php SETUP AUTOMÁTICO PARA RAILWAY -<?php RASPINHAPIX
//<?php Execute este arquivo UMA VEZ SÓ<?php para configurar o banco
//<?php =====================================================

//<?php Incluir conexão
include 'conexao.php';

//<?php Verificar se já<?php foi executado
$check =<?php $pdo->query("SHOW TABLES LIKE 'config'")->rowCount();
if ($check ><?php 0)<?php {
<?php $config_check =<?php $pdo->query("SELECT COUNT(*)<?php FROM config")->fetchColumn();
<?php if ($config_check ><?php 0)<?php {
<?php die("
<?php <h1>✅<?php SISTEMA JÁ<?php CONFIGURADO!</h1>
<?php <p>O banco de dados já<?php está<?php configurado e funcionando.</p>
<?php <p><strong>IMPORTANTE:</strong><?php Delete este arquivo setup.php por segurança!</p>
<?php <p><a href='/'>Acessar o Sistema</a></p>
<?php ");
<?php }
}

echo "<h1>🚀<?php CONFIGURANDO RASPINHAPIX NO RAILWAY</h1>";
echo "<p>Executando configuração automática...</p>";

try {
<?php //<?php Ler o arquivo SQL original $sql_file =<?php 'database_complete.sql';
<?php if (!file_exists($sql_file))<?php {
<?php die("<p style='color:red'>❌<?php Arquivo database_complete.sql não encontrado!</p>");
<?php }
<?php $sql_content =<?php file_get_contents($sql_file);
<?php //<?php Limpar comandos problemáticos $sql_content =<?php str_replace('START TRANSACTION;',<?php '',<?php $sql_content);
<?php $sql_content =<?php str_replace('COMMIT;',<?php '',<?php $sql_content);
<?php $sql_content =<?php str_replace('SET SQL_MODE =<?php "NO_AUTO_VALUE_ON_ZERO";',<?php '',<?php $sql_content);
<?php $sql_content =<?php str_replace('SET time_zone =<?php "+00:00";',<?php '',<?php $sql_content);
<?php //<?php Remover comentários e linhas vazias $lines =<?php explode("\n",<?php $sql_content);
<?php $clean_lines =<?php [];
<?php foreach ($lines as $line)<?php {
<?php $line =<?php trim($line);
<?php if (empty($line)<?php ||<?php strpos($line,<?php '--')<?php ===<?php 0 ||<?php strpos($line,<?php '/*')<?php ===<?php 0 ||<?php strpos($line,<?php '*/')<?php !==<?php false)<?php {
<?php continue;
<?php }
<?php if (strpos($line,<?php '/*!')<?php ===<?php 0)<?php {
<?php continue;
<?php }
<?php $clean_lines[]<?php =<?php $line;
<?php }
<?php $clean_sql =<?php implode("\n",<?php $clean_lines);
<?php //<?php Dividir em comandos individuais $commands =<?php explode(';',<?php $clean_sql);
<?php $success_count =<?php 0;
<?php $error_count =<?php 0;
<?php echo "<h2>📋<?php Executando comandos SQL...</h2>";
<?php echo "<div style='max-height:<?php 400px;<?php overflow-y:<?php scroll;<?php border:<?php 1px solid #ccc;<?php padding:<?php 10px;<?php background:<?php #f9f9f9;'>";
<?php foreach ($commands as $command)<?php {
<?php $command =<?php trim($command);
<?php if (empty($command))<?php continue;
<?php try {
<?php $pdo->exec($command);
<?php $success_count++;
<?php echo "<p style='color:<?php green;'>✅<?php Comando executado com sucesso</p>";
<?php }<?php catch (PDOException $e)<?php {
<?php $error_count++;
<?php echo "<p style='color:<?php orange;'>⚠️<?php Aviso:<?php "<?php .<?php htmlspecialchars($e->getMessage())<?php .<?php "</p>";
<?php }
<?php }
<?php echo "</div>";
<?php //<?php Verificar se as tabelas principais foram criadas $tables_check =<?php [
<?php 'config'<?php =><?php 'Configurações do sistema',
<?php 'usuarios'<?php =><?php 'Usuários',
<?php 'raspadinhas'<?php =><?php 'Raspadinhas',
<?php 'raspadinha_premios'<?php =><?php 'Prêmios das raspadinhas',
<?php 'depositos'<?php =><?php 'Depósitos',
<?php 'saques'<?php =><?php 'Saques'
<?php ];
<?php echo "<h2>🔍<?php Verificando tabelas criadas:</h2>";
<?php $all_ok =<?php true;
<?php foreach ($tables_check as $table =><?php $desc)<?php {
<?php $exists =<?php $pdo->query("SHOW TABLES LIKE '$table'")->rowCount();
<?php if ($exists ><?php 0)<?php {
<?php $count =<?php $pdo->query("SELECT COUNT(*)<?php FROM $table")->fetchColumn();
<?php echo "<p style='color:<?php green;'>✅<?php $desc ($table):<?php $count registros</p>";
<?php }<?php else {
<?php echo "<p style='color:<?php red;'>❌<?php $desc ($table):<?php Não encontrada</p>";
<?php $all_ok =<?php false;
<?php }
<?php }
<?php //<?php Verificar usuário admin $admin_check =<?php $pdo->query("SELECT COUNT(*)<?php FROM usuarios WHERE admin =<?php 1")->fetchColumn();
<?php if ($admin_check ==<?php 0)<?php {
<?php //<?php Criar usuário admin se não existir $pdo->exec("INSERT INTO usuarios (nome,<?php email,<?php senha,<?php admin,<?php saldo,<?php created_at)<?php VALUES ('Administrador',<?php 'admin@raspinhapix.com',<?php MD5('123456'),<?php 1,<?php 0,<?php NOW())");
<?php echo "<p style='color:<?php blue;'>👤<?php Usuário admin criado:<?php admin@raspinhapix.com /<?php senha:<?php 123456</p>";
<?php }
<?php //<?php Verificar configurações $config_check =<?php $pdo->query("SELECT COUNT(*)<?php FROM config")->fetchColumn();
<?php if ($config_check ==<?php 0)<?php {
<?php //<?php Inserir configuração padrão se não existir $pdo->exec("INSERT INTO config (nome_site,<?php logo,<?php deposito_min,<?php saque_min,<?php cpa_padrao,<?php revshare_padrao)<?php VALUES ('RaspinhaPix',<?php '/assets/upload/logo.png',<?php 10,<?php 50,<?php 0,<?php 10)");
<?php echo "<p style='color:<?php blue;'>⚙️<?php Configurações padrão inseridas</p>";
<?php }
<?php echo "<h2>📊<?php Resumo da Configuração:</h2>";
<?php echo "<p>✅<?php Comandos executados com sucesso:<?php $success_count</p>";
<?php echo "<p>⚠️<?php Avisos/Erros:<?php $error_count</p>";
<?php if ($all_ok)<?php {
<?php echo "
<?php <div style='background:<?php #d4edda;<?php border:<?php 1px solid #c3e6cb;<?php padding:<?php 15px;<?php border-radius:<?php 5px;<?php margin:<?php 20px 0;'>
<?php <h2 style='color:<?php #155724;'>🎉<?php CONFIGURAÇÃO CONCLUÍDA COM SUCESSO!</h2>
<?php <p><strong>Seu sistema RaspinhaPix está<?php funcionando!</strong></p>
<?php <p><strong>Login Admin:</strong><?php admin@raspinhapix.com</p>
<?php <p><strong>Senha Admin:</strong><?php 123456</p>
<?php <p><strong>IMPORTANTE:</strong><?php Delete este arquivo setup.php por segurança!</p>
<?php <p><a href='/'<?php style='background:<?php #007bff;<?php color:<?php white;<?php padding:<?php 10px 20px;<?php text-decoration:<?php none;<?php border-radius:<?php 5px;'>🚀<?php Acessar o Sistema</a></p>
<?php </div>";
<?php }<?php else {
<?php echo "
<?php <div style='background:<?php #f8d7da;<?php border:<?php 1px solid #f5c6cb;<?php padding:<?php 15px;<?php border-radius:<?php 5px;<?php margin:<?php 20px 0;'>
<?php <h2 style='color:<?php #721c24;'>⚠️<?php CONFIGURAÇÃO INCOMPLETA</h2>
<?php <p>Algumas tabelas não foram criadas corretamente.</p>
<?php <p>Verifique os erros acima e tente novamente.</p>
<?php </div>";
<?php }
<?php 
}<?php catch (Exception $e)<?php {
<?php echo "<p style='color:<?php red;'>❌<?php Erro geral:<?php "<?php .<?php htmlspecialchars($e->getMessage())<?php .<?php "</p>";
}
?>

