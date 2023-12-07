<?php

class Ticket {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public static function contarTicketsAbertos() {
        global $conn;

        $result = $conn->query("SELECT COUNT(*) as totalAberto FROM Tickets WHERE status = 'Aberto'");
        $row = $result->fetch_assoc();

        return $row ? $row["totalAberto"] : 0;
    }
    public function createTicket($userId, $assunto, $email, $departamento, $ticket_anexo, $mensagem) {
        $name_ticket = 'INC' . sprintf('%04d', rand(1, 9999));
        $ticket_anexoName = uniqid() . '_' . $ticket_anexo['name'];
        $uploadPath = '../uploads/ticketsanexos/' . $ticket_anexoName;
        move_uploaded_file($ticket_anexo['tmp_name'], $uploadPath);
        $stmt = $this->pdo->prepare("
            INSERT INTO tickets (user_id, name_ticket, assunto, email, departamento, ticket_anexo, mensagem, data_criacao, status)
            VALUES (:user_id, :name_ticket, :assunto, :email, :departamento, :ticket_anexo, :mensagem, NOW(), 'Aberto')
        ");
    
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':name_ticket', $name_ticket);
        $stmt->bindParam(':assunto', $assunto);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':departamento', $departamento);
        $stmt->bindParam(':ticket_anexo', $ticket_anexoName);
        $stmt->bindParam(':mensagem', $mensagem);
        $stmt->execute();

        $ticketId = $this->pdo->lastInsertId();
        $this->updateSLA($ticketId);
        echo json_encode(['status' => 'success', 'message' => 'Chamado criado com sucesso!']);
        return $ticketId;
    }
    
    
    private function getWorkingDays($startDate, $endDate) {
        $workingDays = 0;
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        while ($currentDate <= $endDate) {
            $dayOfWeek = date("N", $currentDate);
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $workingDays++;
            }
    
            $currentDate = strtotime("+1 day", $currentDate);
        }
    
        return $workingDays;
    }

    
    private function updateSLA($ticketId) {
        $ticketDetails = $this->getTicketDetails($ticketId);
        $workingDays = $this->getWorkingDays($ticketDetails['data_criacao'], date('Y-m-d H:i:s'));
        $stmt = $this->pdo->prepare("
            UPDATE tickets
            SET sla = :sla
            WHERE id_ticket = :id_ticket
        ");
    
        $stmt->bindParam(':sla', $workingDays);
        $stmt->bindParam(':id_ticket', $ticketId);
        $stmt->execute();
    }
    

    public function getTicketDetails($ticketId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM tickets WHERE id_ticket = :id_ticket
        ");

        $stmt->bindParam(':id_ticket', $ticketId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllTickets() {
        $pdo = $this->pdo; 

        $query = "SELECT * FROM tickets";
        $statement = $pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTicketById($ticketId)
    {
        $query = "SELECT t.*, u.name FROM tickets t
                  LEFT JOIN users u ON t.user_id = u.user_id
                  WHERE t.id_ticket = :id_ticket";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_ticket', $ticketId, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
            return $ticket;
        } else {
            return null;
        }
    }

    public function adicionarResposta($id_ticket, $user_id, $resposta) {
        $data_resposta = date("Y-m-d H:i:s");
    
        $stmt = $this->pdo->prepare("INSERT INTO respostas_tickets (id_ticket, user_id, resposta, data_resposta) VALUES (:id_ticket, :user_id, :resposta, :data_resposta)");
        $stmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);  // Certifique-se de que esta linha está presente
        $stmt->bindParam(':resposta', $resposta, PDO::PARAM_STR);
        $stmt->bindParam(':data_resposta', $data_resposta, PDO::PARAM_STR);
    
        return $stmt->execute();
    }

    public function atualizarStatus($ticketId, $novoStatus)
    {
        $sql = "UPDATE tickets SET status = :novoStatus WHERE id_ticket = :ticketId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':ticketId', $ticketId, PDO::PARAM_INT);
        $stmt->bindParam(':novoStatus', $novoStatus, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getUsuarioById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRespostasByTicketIdOrdered($ticketId)
{
    $query = "SELECT * FROM respostas_tickets WHERE id_ticket = :id_ticket ORDER BY data_resposta ASC";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':id_ticket', $ticketId, PDO::PARAM_INT);
    
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro na execução da consulta: " . $e->getMessage();
        return [];
    }
}
}

?>
