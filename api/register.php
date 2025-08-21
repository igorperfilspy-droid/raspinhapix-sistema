<?php
session_start();
header("Content-Type: application/json");
include("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($input["nome"]) || !isset($input["email"]) || !isset($input["telefone"]) || !isset($input["senha"])) {
        echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios"]);
        exit;
    }
    
    $nome = $input["nome"];
    $email = $input["email"];
    $telefone = $input["telefone"];
    $senha = md5($input["senha"]);
    
    try {
        // Verificar se email já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            echo json_encode(["success" => false, "message" => "Email já cadastrado"]);
            exit;
        }
        
        // Inserir usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, admin, saldo) VALUES (:nome, :email, :telefone, :senha, 0, 0.00)");
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":senha", $senha);
        
        if ($stmt->execute()) {
            $_SESSION["usuario_id"] = $pdo->lastInsertId();
            echo json_encode([
                "success" => true, 
                "message" => "Cadastro realizado com sucesso",
                "redirect" => "/"
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao cadastrar"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erro no servidor"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido"]);
}
?>
