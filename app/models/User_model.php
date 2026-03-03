<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function login($username, $password) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE username = :username AND password_md5 = :password");
        $this->db->bind('username', $username);
        $this->db->bind('password', md5($password));
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id_user = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function ubahDataUser($data) {
        $query = "UPDATE users SET nama = :nama, username = :username, role = :role";
        
        // Hanya update password jika diisi
        if(!empty($data['password'])) {
            $query .= ", password_md5 = :pass";
        }
        
        $query .= " WHERE id_user = :id_user";
        
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('role', $data['role']);
        $this->db->bind('id_user', $data['id_user']);
        
        if(!empty($data['password'])) {
            $this->db->bind('pass', md5($data['password']));
        }
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataUser($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id_user = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
