<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['user']['user_id'];

unset($_SESSION['user']);
session_destroy();

require_once('../config/db.php');

updateUserStatus($pdo, $userId, 'Offline');

header("Location: ../index.php");
exit();


function updateUserStatus($pdo, $userId, $status)
{
    $stmt = $pdo->prepare("UPDATE users SET user_status = ? WHERE user_id = ?");
    $stmt->execute([$status, $userId]);
}
?>
