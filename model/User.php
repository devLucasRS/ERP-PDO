<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function register($username, $name, $email, $password, $avatar)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, name, email, password, avatar) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $name, $email, $hashedPassword, $avatar]);
    }

    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            $this->updateUserStatus($user['user_id'], 'Online');
    
            return $user;
        }
    
        return false;
    }
    
    // Função para atualizar o status do usuário
    private function updateUserStatus($userId, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET user_status = ? WHERE user_id = ?");
        $stmt->execute([$status, $userId]);
    }

    public function confirm($userId)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = 1 WHERE id = ?");
        $stmt->execute([$userId]);
    }


}
