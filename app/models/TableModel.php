<?php
class TableModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Get all tables
    public function getAllTables(){
        $this->db->query('SELECT * FROM tables ORDER BY table_number');
        return $this->db->resultSet();
    }

    // Find best available table for given group size
    // Algorithm: find smallest capacity table >= group_size that is available
    public function getAvailableTable($groupSize){
        $this->db->query("
            SELECT * FROM tables
            WHERE capacity >= :size AND status = 'available'
            ORDER BY capacity ASC
            LIMIT 1
        ");
        $this->db->bind(':size', $groupSize);
        return $this->db->single();
    }

    // Mark table as occupied
    public function occupyTable($id){
        $this->db->query("UPDATE tables SET status = 'occupied' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Free a table
    public function freeTable($id){
        $this->db->query("UPDATE tables SET status = 'available' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function addTable($table_number, $capacity){
        $this->db->query('INSERT INTO tables (table_number, capacity) VALUES (:num, :cap)');
        $this->db->bind(':num', $table_number);
        $this->db->bind(':cap', $capacity);
        return $this->db->execute();
    }

    public function deleteTable($id){
        $this->db->query('DELETE FROM tables WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getTableById($id){
        $this->db->query('SELECT * FROM tables WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function countAvailable(){
        $this->db->query("SELECT COUNT(*) as total FROM tables WHERE status = 'available'");
        return $this->db->single()->total;
    }
}
