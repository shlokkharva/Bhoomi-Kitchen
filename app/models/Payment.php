<?php
class Payment {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function recordPayment($order_id, $method, $amount){
        $this->db->query('INSERT INTO payments (order_id, method, amount) VALUES (:oid, :method, :amount)');
        $this->db->bind(':oid', $order_id);
        $this->db->bind(':method', $method);
        $this->db->bind(':amount', $amount);
        return $this->db->execute();
    }

    public function getPaymentByOrder($order_id){
        $this->db->query('SELECT * FROM payments WHERE order_id = :oid');
        $this->db->bind(':oid', $order_id);
        return $this->db->single();
    }

    public function getRevenueByMethod(){
        $this->db->query('SELECT method, SUM(amount) as total FROM payments GROUP BY method');
        return $this->db->resultSet();
    }

    public function getMonthlyRevenue(){
        $driver = defined('DB_DRIVER') ? DB_DRIVER : 'mysql';
        if ($driver === 'pgsql') {
            $this->db->query("
                SELECT TO_CHAR(payment_date, 'YYYY-MM') as month, SUM(amount) as total
                FROM payments
                GROUP BY month
                ORDER BY month DESC
                LIMIT 12
            ");
        } else {
            $this->db->query('
                SELECT DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as total
                FROM payments
                GROUP BY month
                ORDER BY month DESC
                LIMIT 12
            ');
        }
        return $this->db->resultSet();
    }
}
