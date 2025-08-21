<?php
session_start();
include("./conexao.php");

if ($_POST) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $senha = md5($_POST["senha"]);
    
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    
    if ($stmt->fetch()) {
        $erro = "Email já cadastrado!";
    } else {
        // Inserir usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, admin, saldo) VALUES (:nome, :email, :telefone, :senha, 0, 0.00)");
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":senha", $senha);
        
        if ($stmt->execute()) {
            $_SESSION["usuario_id"] = $pdo->lastInsertId();
            header("Location: /");
            exit;
        } else {
            $erro = "Erro ao cadastrar!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro - RaspinhaPix</title>
    <style>
        body { font-family: Arial; background: #000; color: #fff; padding: 50px; }
        .form { max-width: 400px; margin: 0 auto; background: #222; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 15px; margin: 10px 0; border: none; border-radius: 5px; }
        button { width: 100%; padding: 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; margin: 10px 0; }
        a { color: #28a745; }
    </style>
</head>
<body>
    <div class="form">
        <h1>📝 Cadastro</h1>
        <?php if (isset($erro)): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="telefone" placeholder="Telefone" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">REGISTRAR</button>
        </form>
        
        <p><a href="/login">Já tem conta? Faça login</a></p>
        <p><a href="/">← Voltar ao início</a></p>
    </div>
</body>
</html>
