<?php
class Admin {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function findByUsername($username){
        $this->db->query('SELECT * FROM admins WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    public function findById($id){
        $this->db->query('SELECT * FROM admins WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
