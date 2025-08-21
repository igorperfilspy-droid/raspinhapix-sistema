<?php
session_start();
header("Content-Type: application/json");
include("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($input["email"]) || !isset($input["senha"])) {
        echo json_encode(["success" => false, "message" => "Email e senha são obrigatórios"]);
        exit;
    }
    
    $email = $input["email"];
    $senha = md5($input["senha"]);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha LIMIT 1");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            if ($usuario["banido"] == 1) {
                echo json_encode(["success" => false, "message" => "Usuário banido"]);
                exit;
            }
            
            $_SESSION["usuario_id"] = $usuario["id"];
            echo json_encode([
                "success" => true, 
                "message" => "Login realizado com sucesso",
                "redirect" => "/"
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Email ou senha incorretos"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erro no servidor"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido"]);
}
?>
