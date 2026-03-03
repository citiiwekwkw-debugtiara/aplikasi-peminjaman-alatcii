<?php

class Kategori_model {
    private $table = 'kategori';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllKategori() {
        $this->db->query("SELECT * FROM " . $this->table);
        return $this->db->resultSet();
    }

    public function tambahDataKategori($data) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES (:nama_kategori)";
        $this->db->query($query);
        $this->db->bind('nama_kategori', $data['nama_kategori']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataKategori($id) {
        $query = "DELETE FROM kategori WHERE id_kategori = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
