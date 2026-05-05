<?php
class Waiter {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function register($data){
        $this->db->query('INSERT INTO waiters (name, email, password) VALUES (:name, :email, :password)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        return $this->db->execute();
    }

    public function findByEmail($email){
        $this->db->query('SELECT * FROM waiters WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    public function findById($id){
        $this->db->query('SELECT * FROM waiters WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllWaiters(){
        $this->db->query('SELECT * FROM waiters ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getApprovedWaiters(){
        $this->db->query("SELECT * FROM waiters WHERE status = 'approved'");
        return $this->db->resultSet();
    }

    public function getPendingWaiters(){
        $this->db->query("SELECT * FROM waiters WHERE status = 'pending'");
        return $this->db->resultSet();
    }

    public function updateStatus($id, $status){
        $this->db->query('UPDATE waiters SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteWaiter($id){
        $this->db->query('DELETE FROM waiters WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Find waiter with least active orders (for auto-assignment, max 4)
    public function getAvailableWaiter(){
        $this->db->query("
            SELECT w.id, w.name, COUNT(o.id) as active_orders
            FROM waiters w
            LEFT JOIN orders o ON o.waiter_id = w.id AND o.status NOT IN ('completed')
            WHERE w.status = 'approved'
            GROUP BY w.id
            HAVING COUNT(o.id) < 4
            ORDER BY active_orders ASC
            LIMIT 1
        ");
        return $this->db->single();
    }

    // Get earnings & tips for a waiter
    public function getEarnings($waiter_id){
        $this->db->query("
            SELECT SUM(o.tip_amount) as total_tips, COUNT(o.id) as total_orders
            FROM orders o
            WHERE o.waiter_id = :wid AND o.status = 'completed'
        ");
        $this->db->bind(':wid', $waiter_id);
        return $this->db->single();
    }

    // Get assigned orders for waiter
    public function getAssignedOrders($waiter_id){
        $this->db->query("
            SELECT o.*, u.name as customer_name, t.table_number
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN tables t ON o.table_id = t.id
            WHERE o.waiter_id = :wid AND o.status NOT IN ('completed')
            ORDER BY o.created_at DESC
        ");
        $this->db->bind(':wid', $waiter_id);
        return $this->db->resultSet();
    }

    public function getCompletedOrders($waiter_id){
        $this->db->query("
            SELECT o.*, u.name as customer_name, t.table_number
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN tables t ON o.table_id = t.id
            WHERE o.waiter_id = :wid AND o.status = 'completed'
            ORDER BY o.created_at DESC
        ");
        $this->db->bind(':wid', $waiter_id);
        return $this->db->resultSet();
    }

    public function countApproved(){
        $this->db->query("SELECT COUNT(*) as total FROM waiters WHERE status = 'approved'");
        return $this->db->single()->total;
    }

    public function countPending(){
        $this->db->query("SELECT COUNT(*) as total FROM waiters WHERE status = 'pending'");
        return $this->db->single()->total;
    }
}
