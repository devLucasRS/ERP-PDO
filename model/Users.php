<?php
class Users
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

function getUserById($pdo, $userId)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return false;
    }
}
function updateUser($pdo, $user_id, $name, $username, $email, $role, $status, $avatar)
{
    try {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET 
                name = :name, 
                username = :username, 
                email = :email,
                role = :role, 
                status = :status,
                avatar = :avatar
            WHERE 
                user_id = :user_id
        ");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':avatar', $avatar); // Remova o $ aqui
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    } catch (PDOException $e) {
        // Você pode tratar erros de banco de dados aqui
        // Por exemplo: echo "Erro: " . $e->getMessage();
        return false;
    }
}



}

?>
