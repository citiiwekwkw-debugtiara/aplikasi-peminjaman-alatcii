-- Database: peminjaman_alat
CREATE DATABASE IF NOT EXISTS peminjaman_alat;
USE peminjaman_alat;

-- 1. Tabel users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_md5 VARCHAR(32) NOT NULL,
    role ENUM('admin', 'petugas', 'peminjam') NOT NULL
) ENGINE=InnoDB;

-- 2. Tabel kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- 3. Tabel alat
CREATE TABLE alat (
    id_alat INT AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(100) NOT NULL,
    id_kategori INT,
    stok INT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Tabel peminjaman
CREATE TABLE peminjaman (
    id_pinjam INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    nama_peminjam VARCHAR(100),
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,
    status ENUM('menunggu', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak') DEFAULT 'menunggu',
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Tabel detail_peminjaman
CREATE TABLE detail_peminjaman (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pinjam INT,
    id_alat INT,
    jumlah INT NOT NULL,
    FOREIGN KEY (id_pinjam) REFERENCES peminjaman(id_pinjam) ON DELETE CASCADE,
    FOREIGN KEY (id_alat) REFERENCES alat(id_alat) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabel pengembalian
CREATE TABLE pengembalian (
    id_kembali INT AUTO_INCREMENT PRIMARY KEY,
    id_pinjam INT,
    tanggal_dikembalikan DATE NOT NULL,
    denda DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (id_pinjam) REFERENCES peminjaman(id_pinjam) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Tabel log_aktivitas
CREATE TABLE log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    aktivitas TEXT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL
) ENGINE=InnoDB;

-- SEED DATA (Default Admin)
-- Password 'admin123' -> 0192023a7bbd73250516f069df18b500
INSERT INTO users (nama, username, password_md5, role) VALUES 
('Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 'admin'),
('Petugas Utama', 'petugas', '570c396b3fc856eceb8aa7357f32af1a', 'petugas'),
('Peminjam ', 'peminjam', 'a0a2f49fce72297e6a424581b46cb8ba', 'peminjam'),
('Fourth Nattawat', 'fourth', 'a0a2f49fce72297e6a424581b46cb8ba', 'peminjam');

-- TRIGGER: Kurangi stok saat peminjaman disetujui
DELIMITER //
CREATE TRIGGER tr_peminjaman_disetujui 
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'dipinjam' AND OLD.status != 'dipinjam' THEN
        -- Kurangi stok alat berdasarkan detail
        UPDATE alat a
        JOIN detail_peminjaman dp ON a.id_alat = dp.id_alat
        SET a.stok = a.stok - dp.jumlah
        WHERE dp.id_pinjam = NEW.id_pinjam;
    END IF;
END //
DELIMITER ;

-- TRIGGER: Kembalikan stok saat alat dikembalikan
DELIMITER //
CREATE TRIGGER tr_peminjaman_dikembalikan 
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'dikembalikan' AND OLD.status != 'dikembalikan' THEN
        -- Tambahkan stok kembali
        UPDATE alat a
        JOIN detail_peminjaman dp ON a.id_alat = dp.id_alat
        SET a.stok = a.stok + dp.jumlah
        WHERE dp.id_pinjam = NEW.id_pinjam;
    END IF;
END //
DELIMITER ;

-- STORED PROCEDURE: Cetak Laporan Peminjaman per Periode
DELIMITER //
CREATE PROCEDURE sp_laporan_peminjaman(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT p.id_pinjam, u.nama as nama_peminjam, p.tanggal_pinjam, p.tanggal_kembali, p.status, 
           GROUP_CONCAT(CONCAT(a.nama_alat, ' (', dp.jumlah, ')') SEPARATOR ', ') as alat_dipinjam
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN detail_peminjaman dp ON p.id_pinjam = dp.id_pinjam
    JOIN alat a ON dp.id_alat = a.id_alat
    WHERE p.tanggal_pinjam BETWEEN start_date AND end_date
    GROUP BY p.id_pinjam;
END //
DELIMITER ;

-- FUNCTION: Hitung Denda
DELIMITER //
CREATE FUNCTION fn_hitung_denda(tgl_harusnya DATE, tgl_kembali DATE) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE selisih INT;
    DECLARE denda_per_hari DECIMAL(10,2) DEFAULT 5000.00;
    SET selisih = DATEDIFF(tgl_kembali, tgl_harusnya);
    IF selisih > 0 THEN
        RETURN selisih * denda_per_hari;
    ELSE
        RETURN 0;
    END IF;
END //
DELIMITER ;
