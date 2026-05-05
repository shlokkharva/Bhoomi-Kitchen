<?php
class Order {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Place a new order — returns new order ID
    public function placeOrder($data){
        $this->db->query('
            INSERT INTO orders (user_id, waiter_id, table_id, total_amount, tax_amount, service_charge, tip_amount)
            VALUES (:uid, :wid, :tid, :total, :tax, :sc, :tip)
        ');
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':wid', $data['waiter_id']);
        $this->db->bind(':tid', $data['table_id']);
        $this->db->bind(':total', $data['total_amount']);
        $this->db->bind(':tax', $data['tax_amount']);
        $this->db->bind(':sc', $data['service_charge']);
        $this->db->bind(':tip', $data['tip_amount']);
        if($this->db->execute()){
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Add items to an order
    public function addOrderItem($order_id, $menu_item_id, $quantity, $price){
        $this->db->query('INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (:oid, :mid, :qty, :price)');
        $this->db->bind(':oid', $order_id);
        $this->db->bind(':mid', $menu_item_id);
        $this->db->bind(':qty', $quantity);
        $this->db->bind(':price', $price);
        return $this->db->execute();
    }

    // Get order by ID with full info
    public function getOrderById($id){
        $this->db->query('
            SELECT o.*, u.name as customer_name, u.email as customer_email,
                   w.name as waiter_name, t.table_number
            FROM orders o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN waiters w ON o.waiter_id = w.id
            LEFT JOIN tables t ON o.table_id = t.id
            WHERE o.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get items of an order
    public function getOrderItems($order_id){
        $this->db->query('
            SELECT oi.*, m.name as item_name, m.image_url
            FROM order_items oi
            JOIN menu_items m ON oi.menu_item_id = m.id
            WHERE oi.order_id = :oid
        ');
        $this->db->bind(':oid', $order_id);
        return $this->db->resultSet();
    }

    // Update order status
    public function updateStatus($order_id, $status){
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Update tip amount
    public function updateTip($order_id, $tip){
        $this->db->query('UPDATE orders SET tip_amount = :tip WHERE id = :id');
        $this->db->bind(':tip', $tip);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Get all orders for a user (history)
    public function getUserOrders($user_id){
        $this->db->query('
            SELECT o.*, t.table_number, w.name as waiter_name
            FROM orders o
            LEFT JOIN tables t ON o.table_id = t.id
            LEFT JOIN waiters w ON o.waiter_id = w.id
            WHERE o.user_id = :uid
            ORDER BY o.created_at DESC
        ');
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }

    // Get all orders (admin)
    public function getAllOrders(){
        $this->db->query('
            SELECT o.*, u.name as customer_name, w.name as waiter_name, t.table_number
            FROM orders o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN waiters w ON o.waiter_id = w.id
            LEFT JOIN tables t ON o.table_id = t.id
            ORDER BY o.created_at DESC
        ');
        return $this->db->resultSet();
    }

    // Count totals
    public function countOrders(){
        $this->db->query('SELECT COUNT(*) as total FROM orders');
        return $this->db->single()->total;
    }

    public function countTodayOrders(){
        $this->db->query('SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURRENT_DATE');
        return $this->db->single()->total;
    }

    // Revenue analytics
    public function getTotalRevenue(){
        $this->db->query("SELECT SUM(total_amount + tip_amount) as revenue FROM orders WHERE status = 'completed'");
        return $this->db->single()->revenue ?? 0;
    }

    public function getDailyRevenue(){
        $this->db->query("
            SELECT DATE(o.created_at) as date, SUM(o.total_amount + o.tip_amount) as revenue
            FROM orders o
            WHERE o.status = 'completed'
            GROUP BY DATE(o.created_at)
            ORDER BY date DESC
            LIMIT 30
        ");
        return $this->db->resultSet();
    }

    public function getRevenueByPaymentMethod(){
        $this->db->query("
            SELECT p.method, SUM(p.amount) as total
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            WHERE o.status = 'completed'
            GROUP BY p.method
        ");
        return $this->db->resultSet();
    }

    public function getTotalTips(){
        $this->db->query("SELECT SUM(tip_amount) as total FROM orders WHERE status = 'completed'");
        return $this->db->single()->total ?? 0;
    }

    // Get active order for user
    public function getActiveOrder($user_id){
        $this->db->query("SELECT * FROM orders WHERE user_id = :uid AND status NOT IN ('completed') ORDER BY created_at DESC LIMIT 1");
        $this->db->bind(':uid', $user_id);
        return $this->db->single();
    }
}
