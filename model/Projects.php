<?php

class Projects
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUsersFromProjects() {
        $users = array();

        $sql = "SELECT user_id, username FROM users";
        $statement = $this->pdo->query($sql);

        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
        }

        return $users;
    }

    public function insertProject($project_name, $client_name, $project_desc, $members, $status) {
        try {
            // Inicie a transação
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO projects (project_name, client_name, project_desc, status) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$project_name, $client_name, $project_name, $status]);
            $project_id = $this->pdo->lastInsertId();

            foreach ($members as $member) {
                $sql = "INSERT INTO project_members (project_id, user_id) VALUES (?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$project_id, $member]);
            }

            $this->pdo->commit();

            return $project_id; 
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function getAllProjects() {
        $projects = array();
        $sql = "SELECT * FROM projects";
        $result = $this->pdo->query($sql);

        if ($result) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $members = $this->getProjectMembers($row['project_id']);
                $row['members'] = $members;
                $projects[] = $row;
            }
        }

        return $projects;
    }

    private function getProjectMembers($projectId) {
        $members = array();
        $sql = "SELECT u.user_id, u.username, u.avatar 
                FROM project_members pm
                JOIN users u ON pm.user_id = u.user_id
                WHERE pm.project_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$projectId]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $members[] = $row;
        }

        return $members;
    }

    public function updateProject($project_id, $project_name, $client_name, $project_desc, $members, $status) {
        try {
            // Atualizar o projeto na tabela de projetos
            $sql = "UPDATE projects SET project_name = :project_name, project_desc = :project_desc, client_name = :client_name, status = :status WHERE project_id = :project_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':project_id', $project_id);
            $stmt->bindParam(':project_name', $project_name);
            $stmt->bindParam(':client_name', $client_name);
            $stmt->bindParam(':project_desc', $project_desc);
            $stmt->bindParam(':status', $status);
            $stmt->execute();

            // Remover membros antigos do projeto
            $sqlDeleteMembers = "DELETE FROM project_members WHERE project_id = :project_id";
            $stmtDeleteMembers = $this->pdo->prepare($sqlDeleteMembers);
            $stmtDeleteMembers->bindParam(':project_id', $project_id);
            $stmtDeleteMembers->execute();

            // Adicionar novos membros ao projeto
            foreach ($members as $member) {
                $sqlAddMember = "INSERT INTO project_members (project_id, user_id) VALUES (:project_id, :user_id)";
                $stmtAddMember = $this->pdo->prepare($sqlAddMember);
                $stmtAddMember->bindParam(':project_id', $project_id);
                $stmtAddMember->bindParam(':user_id', $member);
                $stmtAddMember->execute();
            }

            return true;
        } catch (PDOException $e) {
            // Log de erro ou tratamento adequado
            return false;
        }
    }

    public function getProjectById($project_id) {
        try {
            // Consultar o projeto na tabela de projetos
            $sql = "SELECT * FROM projects WHERE project_id = :project_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':project_id', $project_id);
            $stmt->execute();

            // Verificar se o projeto existe
            if ($stmt->rowCount() > 0) {
                $project = $stmt->fetch(PDO::FETCH_ASSOC);

                // Recuperar membros do projeto
                $sqlMembers = "SELECT user_id FROM project_members WHERE project_id = :project_id";
                $stmtMembers = $this->pdo->prepare($sqlMembers);
                $stmtMembers->bindParam(':project_id', $project_id);
                $stmtMembers->execute();

                $members = array();
                while ($row = $stmtMembers->fetch(PDO::FETCH_ASSOC)) {
                    $members[] = $row['user_id'];
                }

                $project['members'] = $members;

                return $project;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }
}