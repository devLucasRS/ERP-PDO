<?php
session_start();

require_once 'Users.php';
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../view/login.php");
    exit();
}

$user = $_SESSION['user'];

if ($user['status'] == 0) {
    header("Location: ../view/confirm.php");
    exit();
}

$users = new Users($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role']; // Alterado para 'role' conforme seu formulário
    $status = $_POST['status'];
    $avatar = $_POST['avatar'];

    $avatar = $_FILES['avatar'];

    if ($avatar['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($avatar['name']);

        if (isset($_FILES['avatar'])) {
            $avatar = $_FILES['avatar'];
    
            // Verifica se um arquivo foi selecionado
            if ($avatar['error'] == UPLOAD_ERR_OK) {
                // Restante do código para mover o arquivo e atualizar o usuário
            } else {
                echo "Erro no upload do arquivo.\n";
            }
        } else {
            echo "Campo 'avatar' não está presente no formulário.\n";
        }
        
    if (move_uploaded_file($avatar['tmp_name'], $uploadFile)) {
        echo "Arquivo válido e enviado com sucesso.\n";

        $result = $users->updateUser($pdo, $user_id, $name, $username, $email, $role, $status, $uploadFile);

        if ($result) {
            header("Location: ../view/genusers.php?success=1");
            exit();
        } else {
            header("Location: ../view/genusers.php?error=1");
            exit();
        }
    } else {
        header("Location: ../view/genusers.php");
        exit();
    }
}
}
?>
