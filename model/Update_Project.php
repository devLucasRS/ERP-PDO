<?php
session_start();

require_once 'Projects.php';
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

$projects = new Projects($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST['project_id'];
    $project_name = $_POST['edit_project_name'];
    $client_name = $_POST['edit_client_name'];
    $project_desc = $_POST['project_desc'];
    $members = isset($_POST['edit_members']) ? $_POST['edit_members'] : array();
    $status = $_POST['edit_status'];

    // Atualizar o projeto
    $result = $projects->updateProject($project_id, $project_name, $client_name, $project_desc, $members, $status);

    if ($result) {
        // Redirecionar para a página de projetos com uma mensagem de sucesso
        header("Location: ../view/projetos.php?success=1");
        exit();
    } else {
        // Redirecionar para a página de projetos com uma mensagem de erro
        header("Location: ../view/projetos?error=1");
        exit();
    }
} else {
    // Se não for uma requisição POST, redirecionar para a página de projetos
    header("Location: ../view/projetos.php");
    exit();
}
?>
