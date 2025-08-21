<?php
session_start();
include("./conexao.php");

if ($_POST) {
    $email = $_POST["email"];
    $senha = md5($_POST["senha"]);
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senha);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        $_SESSION["usuario_id"] = $usuario["id"];
        header("Location: /");
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - RaspinhaPix</title>
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
        <h1>🔑 Login</h1>
        <?php if (isset($erro)): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required value="admin@teste.com">
            <input type="password" name="senha" placeholder="Senha" required value="admin123">
            <button type="submit">ENTRAR</button>
        </form>
        
        <p><a href="/cadastro">Não tem conta? Registre-se</a></p>
        <p><a href="/">← Voltar ao início</a></p>
    </div>
</body>
</html>
