<?php

class Alat_model {
    private $table = 'alat';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllAlat() {
        $this->db->query("SELECT a.*, k.nama_kategori FROM " . $this->table . " a LEFT JOIN kategori k ON a.id_kategori = k.id_kategori");
        return $this->db->resultSet();
    }

    public function getAlatById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id_alat = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahDataAlat($data) {
        $query = "INSERT INTO alat (nama_alat, id_kategori, stok, gambar) VALUES (:nama_alat, :id_kategori, :stok, :gambar)";
        $this->db->query($query);
        $this->db->bind('nama_alat', $data['nama_alat']);
        $this->db->bind('id_kategori', $data['id_kategori']);
        $this->db->bind('stok', $data['stok']);
        $this->db->bind('gambar', $data['gambar']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataAlat($id) {
        $query = "DELETE FROM alat WHERE id_alat = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahDataAlat($data) {
        $query = "UPDATE alat SET nama_alat = :nama_alat, id_kategori = :id_kategori, stok = :stok, gambar = :gambar WHERE id_alat = :id_alat";
        $this->db->query($query);
        $this->db->bind('nama_alat', $data['nama_alat']);
        $this->db->bind('id_kategori', $data['id_kategori']);
        $this->db->bind('stok', $data['stok']);
        $this->db->bind('gambar', $data['gambar']);
        $this->db->bind('id_alat', $data['id_alat']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
