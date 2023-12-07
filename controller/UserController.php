<?php
require_once('../config/db.php');
require_once('../model/User.php');

class UserController
{
    private $userModel;

    public function __construct()
    {
        global $pdo;
        $this->userModel = new User($pdo);
    }

    public function registerUser($username, $name, $email, $password, $avatar)
    {
        $this->userModel->register($username, $name, $email, $password, $avatar);
    }

    public function loginUser($email, $password)
    {
        return $this->userModel->login($email, $password);
    }

    public function confirmUser($userId)
    {
        $this->userModel->confirm($userId);
    }
}
