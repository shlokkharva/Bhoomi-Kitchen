<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Register User
    public function register($data){
        $this->db->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Login User - Find by email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if($this->db->rowCount() > 0){
            return $row;
        } else {
            return false;
        }
    }

    public function findById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllUsers(){
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function deleteUser($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function countUsers(){
        $this->db->query('SELECT COUNT(*) as total FROM users');
        return $this->db->single()->total;
    }
}
