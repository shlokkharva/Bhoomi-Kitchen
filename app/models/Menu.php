<?php
class Menu {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllItems(){
        $this->db->query('
            SELECT m.*, c.name as category_name
            FROM menu_items m
            LEFT JOIN menu_categories c ON m.category_id = c.id
            ORDER BY c.name, m.name
        ');
        return $this->db->resultSet();
    }

    public function getItemsByCategory($cat_id){
        $this->db->query('SELECT * FROM menu_items WHERE category_id = :cid');
        $this->db->bind(':cid', $cat_id);
        return $this->db->resultSet();
    }

    public function getItemById($id){
        $this->db->query('SELECT m.*, c.name as category_name FROM menu_items m LEFT JOIN menu_categories c ON m.category_id = c.id WHERE m.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addItem($data){
        $this->db->query('INSERT INTO menu_items (category_id, name, description, price, image_url) VALUES (:cat, :name, :desc, :price, :img)');
        $this->db->bind(':cat', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':desc', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':img', $data['image_url']);
        return $this->db->execute();
    }

    public function updateItem($data){
        $this->db->query('UPDATE menu_items SET category_id=:cat, name=:name, description=:desc, price=:price, image_url=:img WHERE id=:id');
        $this->db->bind(':cat', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':desc', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':img', $data['image_url']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function deleteItem($id){
        $this->db->query('DELETE FROM menu_items WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAllCategories(){
        $this->db->query('SELECT * FROM menu_categories ORDER BY name');
        return $this->db->resultSet();
    }

    public function addCategory($name){
        $this->db->query('INSERT INTO menu_categories (name) VALUES (:name)');
        $this->db->bind(':name', $name);
        return $this->db->execute();
    }

    public function deleteCategory($id){
        $this->db->query('DELETE FROM menu_categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function countItems(){
        $this->db->query('SELECT COUNT(*) as total FROM menu_items');
        return $this->db->single()->total;
    }
}
