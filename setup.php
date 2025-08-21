<?php
//<?php =====================================================
//<?php SETUP<?php AUTOMÁTICO<?php PARA<?php RAILWAY<?php -<?php RASPINHAPIX
//<?php Execute<?php este<?php arquivo<?php UMA<?php VEZ<?php SÓ<?php para<?php configurar<?php o<?php banco
//<?php =====================================================

//<?php Incluir<?php conexão
include<?php 'conexao.php';

//<?php Verificar<?php se<?php já<?php foi<?php executado
$check<?php =<?php $pdo->query("SHOW<?php TABLES<?php LIKE<?php 'config'")->rowCount();
if<?php ($check<?php ><?php 0)<?php {
<?php $config_check<?php =<?php $pdo->query("SELECT<?php COUNT(*)<?php FROM<?php config")->fetchColumn();
<?php if<?php ($config_check<?php ><?php 0)<?php {
<?php die("
<?php <h1>✅<?php SISTEMA<?php JÁ<?php CONFIGURADO!</h1>
<?php <p>O<?php banco<?php de<?php dados<?php já<?php está<?php configurado<?php e<?php funcionando.</p>
<?php <p><strong>IMPORTANTE:</strong><?php Delete<?php este<?php arquivo<?php setup.php<?php por<?php segurança!</p>
<?php <p><a<?php href='/'>Acessar<?php o<?php Sistema</a></p>
<?php ");
<?php }
}

echo<?php "<h1>🚀<?php CONFIGURANDO<?php RASPINHAPIX<?php NO<?php RAILWAY</h1>";
echo<?php "<p>Executando<?php configuração<?php automática...</p>";

try<?php {
<?php //<?php Ler<?php o<?php arquivo<?php SQL<?php original
<?php $sql_file<?php =<?php 'database_complete.sql';
<?php if<?php (!file_exists($sql_file))<?php {
<?php die("<p<?php style='color:red'>❌<?php Arquivo<?php database_complete.sql<?php não<?php encontrado!</p>");
<?php }
<?php 
<?php $sql_content<?php =<?php file_get_contents($sql_file);
<?php 
<?php //<?php Limpar<?php comandos<?php problemáticos
<?php $sql_content<?php =<?php str_replace('START<?php TRANSACTION;',<?php '',<?php $sql_content);
<?php $sql_content<?php =<?php str_replace('COMMIT;',<?php '',<?php $sql_content);
<?php $sql_content<?php =<?php str_replace('SET<?php SQL_MODE<?php =<?php "NO_AUTO_VALUE_ON_ZERO";',<?php '',<?php $sql_content);
<?php $sql_content<?php =<?php str_replace('SET<?php time_zone<?php =<?php "+00:00";',<?php '',<?php $sql_content);
<?php 
<?php //<?php Remover<?php comentários<?php e<?php linhas<?php vazias
<?php $lines<?php =<?php explode("\n",<?php $sql_content);
<?php $clean_lines<?php =<?php [];
<?php 
<?php foreach<?php ($lines<?php as<?php $line)<?php {
<?php $line<?php =<?php trim($line);
<?php if<?php (empty($line)<?php ||<?php strpos($line,<?php '--')<?php ===<?php 0<?php ||<?php strpos($line,<?php '/*')<?php ===<?php 0<?php ||<?php strpos($line,<?php '*/')<?php !==<?php false)<?php {
<?php continue;
<?php }
<?php if<?php (strpos($line,<?php '/*!')<?php ===<?php 0)<?php {
<?php continue;
<?php }
<?php $clean_lines[]<?php =<?php $line;
<?php }
<?php 
<?php $clean_sql<?php =<?php implode("\n",<?php $clean_lines);
<?php 
<?php //<?php Dividir<?php em<?php comandos<?php individuais
<?php $commands<?php =<?php explode(';',<?php $clean_sql);
<?php 
<?php $success_count<?php =<?php 0;
<?php $error_count<?php =<?php 0;
<?php 
<?php echo<?php "<h2>📋<?php Executando<?php comandos<?php SQL...</h2>";
<?php echo<?php "<div<?php style='max-height:<?php 400px;<?php overflow-y:<?php scroll;<?php border:<?php 1px<?php solid<?php #ccc;<?php padding:<?php 10px;<?php background:<?php #f9f9f9;'>";
<?php 
<?php foreach<?php ($commands<?php as<?php $command)<?php {
<?php $command<?php =<?php trim($command);
<?php if<?php (empty($command))<?php continue;
<?php 
<?php try<?php {
<?php $pdo->exec($command);
<?php $success_count++;
<?php echo<?php "<p<?php style='color:<?php green;'>✅<?php Comando<?php executado<?php com<?php sucesso</p>";
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php $error_count++;
<?php echo<?php "<p<?php style='color:<?php orange;'>⚠️<?php Aviso:<?php "<?php .<?php htmlspecialchars($e->getMessage())<?php .<?php "</p>";
<?php }
<?php }
<?php 
<?php echo<?php "</div>";
<?php 
<?php //<?php Verificar<?php se<?php as<?php tabelas<?php principais<?php foram<?php criadas
<?php $tables_check<?php =<?php [
<?php 'config'<?php =><?php 'Configurações<?php do<?php sistema',
<?php 'usuarios'<?php =><?php 'Usuários',
<?php 'raspadinhas'<?php =><?php 'Raspadinhas',
<?php 'raspadinha_premios'<?php =><?php 'Prêmios<?php das<?php raspadinhas',
<?php 'depositos'<?php =><?php 'Depósitos',
<?php 'saques'<?php =><?php 'Saques'
<?php ];
<?php 
<?php echo<?php "<h2>🔍<?php Verificando<?php tabelas<?php criadas:</h2>";
<?php $all_ok<?php =<?php true;
<?php 
<?php foreach<?php ($tables_check<?php as<?php $table<?php =><?php $desc)<?php {
<?php $exists<?php =<?php $pdo->query("SHOW<?php TABLES<?php LIKE<?php '$table'")->rowCount();
<?php if<?php ($exists<?php ><?php 0)<?php {
<?php $count<?php =<?php $pdo->query("SELECT<?php COUNT(*)<?php FROM<?php $table")->fetchColumn();
<?php echo<?php "<p<?php style='color:<?php green;'>✅<?php $desc<?php ($table):<?php $count<?php registros</p>";
<?php }<?php else<?php {
<?php echo<?php "<p<?php style='color:<?php red;'>❌<?php $desc<?php ($table):<?php Não<?php encontrada</p>";
<?php $all_ok<?php =<?php false;
<?php }
<?php }
<?php 
<?php //<?php Verificar<?php usuário<?php admin
<?php $admin_check<?php =<?php $pdo->query("SELECT<?php COUNT(*)<?php FROM<?php usuarios<?php WHERE<?php admin<?php =<?php 1")->fetchColumn();
<?php if<?php ($admin_check<?php ==<?php 0)<?php {
<?php //<?php Criar<?php usuário<?php admin<?php se<?php não<?php existir
<?php $pdo->exec("INSERT<?php INTO<?php usuarios<?php (nome,<?php email,<?php senha,<?php admin,<?php saldo,<?php created_at)<?php VALUES<?php ('Administrador',<?php 'admin@raspinhapix.com',<?php MD5('123456'),<?php 1,<?php 0,<?php NOW())");
<?php echo<?php "<p<?php style='color:<?php blue;'>👤<?php Usuário<?php admin<?php criado:<?php admin@raspinhapix.com<?php /<?php senha:<?php 123456</p>";
<?php }
<?php 
<?php //<?php Verificar<?php configurações
<?php $config_check<?php =<?php $pdo->query("SELECT<?php COUNT(*)<?php FROM<?php config")->fetchColumn();
<?php if<?php ($config_check<?php ==<?php 0)<?php {
<?php //<?php Inserir<?php configuração<?php padrão<?php se<?php não<?php existir
<?php $pdo->exec("INSERT<?php INTO<?php config<?php (nome_site,<?php logo,<?php deposito_min,<?php saque_min,<?php cpa_padrao,<?php revshare_padrao)<?php VALUES<?php ('RaspinhaPix',<?php '/assets/upload/logo.png',<?php 10,<?php 50,<?php 0,<?php 10)");
<?php echo<?php "<p<?php style='color:<?php blue;'>⚙️<?php Configurações<?php padrão<?php inseridas</p>";
<?php }
<?php 
<?php echo<?php "<h2>📊<?php Resumo<?php da<?php Configuração:</h2>";
<?php echo<?php "<p>✅<?php Comandos<?php executados<?php com<?php sucesso:<?php $success_count</p>";
<?php echo<?php "<p>⚠️<?php Avisos/Erros:<?php $error_count</p>";
<?php 
<?php if<?php ($all_ok)<?php {
<?php echo<?php "
<?php <div<?php style='background:<?php #d4edda;<?php border:<?php 1px<?php solid<?php #c3e6cb;<?php padding:<?php 15px;<?php border-radius:<?php 5px;<?php margin:<?php 20px<?php 0;'>
<?php <h2<?php style='color:<?php #155724;'>🎉<?php CONFIGURAÇÃO<?php CONCLUÍDA<?php COM<?php SUCESSO!</h2>
<?php <p><strong>Seu<?php sistema<?php RaspinhaPix<?php está<?php funcionando!</strong></p>
<?php <p><strong>Login<?php Admin:</strong><?php admin@raspinhapix.com</p>
<?php <p><strong>Senha<?php Admin:</strong><?php 123456</p>
<?php <p><strong>IMPORTANTE:</strong><?php Delete<?php este<?php arquivo<?php setup.php<?php por<?php segurança!</p>
<?php <p><a<?php href='/'<?php style='background:<?php #007bff;<?php color:<?php white;<?php padding:<?php 10px<?php 20px;<?php text-decoration:<?php none;<?php border-radius:<?php 5px;'>🚀<?php Acessar<?php o<?php Sistema</a></p>
<?php </div>";
<?php }<?php else<?php {
<?php echo<?php "
<?php <div<?php style='background:<?php #f8d7da;<?php border:<?php 1px<?php solid<?php #f5c6cb;<?php padding:<?php 15px;<?php border-radius:<?php 5px;<?php margin:<?php 20px<?php 0;'>
<?php <h2<?php style='color:<?php #721c24;'>⚠️<?php CONFIGURAÇÃO<?php INCOMPLETA</h2>
<?php <p>Algumas<?php tabelas<?php não<?php foram<?php criadas<?php corretamente.</p>
<?php <p>Verifique<?php os<?php erros<?php acima<?php e<?php tente<?php novamente.</p>
<?php </div>";
<?php }
<?php 
}<?php catch<?php (Exception<?php $e)<?php {
<?php echo<?php "<p<?php style='color:<?php red;'>❌<?php Erro<?php geral:<?php "<?php .<?php htmlspecialchars($e->getMessage())<?php .<?php "</p>";
}
?>

