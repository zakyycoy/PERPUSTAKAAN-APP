-- 1. Membuat Database (Jika belum ada)
CREATE DATABASE IF NOT EXISTS perpustakaan_pro;
USE perpustakaan_pro;

-- Matikan pengecekan foreign key sementara agar bisa menghapus tabel yang berelasi
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Hapus tabel jika sudah ada (agar tidak error #1050)
DROP TABLE IF EXISTS detail_peminjaman;
DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS anggota;
DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS penerbit;
DROP TABLE IF EXISTS kategori;

-- Aktifkan kembali pengecekan foreign key
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------
-- MULAI MEMBUAT ULANG TABEL DENGAN STRUKTUR BERSIH
-- ---------------------------------------------------------

-- 1. Tabel Kategori
CREATE TABLE kategori (
    id_kategori INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- 2. Tabel Penerbit
CREATE TABLE penerbit (
    id_penerbit INT PRIMARY KEY AUTO_INCREMENT,
    nama_penerbit VARCHAR(100) NOT NULL,
    kota VARCHAR(50)
) ENGINE=InnoDB;

-- 3. Tabel Buku
CREATE TABLE buku (
    id_buku INT PRIMARY KEY AUTO_INCREMENT,
    id_kategori INT,
    id_penerbit INT,
    judul VARCHAR(100) NOT NULL,
    penulis VARCHAR(100),
    stok INT DEFAULT 0,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL,
    FOREIGN KEY (id_penerbit) REFERENCES penerbit(id_penerbit) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Tabel Anggota
CREATE TABLE anggota (
    id_anggota INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    no_telp VARCHAR(15),
    alamat TEXT,
    status_keanggotaan ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB;

-- 5. Tabel User
CREATE TABLE user (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_petugas VARCHAR(100),
    level ENUM('admin', 'petugas') DEFAULT 'petugas'
) ENGINE=InnoDB;

-- 6. Tabel Peminjaman
CREATE TABLE peminjaman (
    id_peminjaman INT PRIMARY KEY AUTO_INCREMENT,
    id_anggota INT,
    id_user INT,
    tgl_pinjam DATE NOT NULL,
    tgl_kembali DATE NOT NULL,
    status_pinjam ENUM('proses', 'selesai') DEFAULT 'proses',
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. Tabel Detail Peminjaman
CREATE TABLE detail_peminjaman (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    id_peminjaman INT,
    id_buku INT,
    jumlah INT DEFAULT 1,
    FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- INPUT DATA AWAL (DUMMY DATA)
-- ---------------------------------------------------------

INSERT INTO user (username, password, nama_petugas, level) 
VALUES ('admin', 'admin123', 'Admin Perpus', 'admin');

INSERT INTO kategori (nama_kategori) VALUES ('Sains'), ('Novel'), ('Komputer');
INSERT INTO penerbit (nama_penerbit, kota) VALUES ('Gramedia', 'Jakarta'), ('Informatika', 'Bandung');

INSERT INTO buku (id_kategori, id_penerbit, judul, penulis, stok) 
VALUES (3, 2, 'Belajar PHP Dasar', 'Budi Programmer', 15),
       (2, 1, 'Laskar Pelangi', 'Andrea Hirata', 7);

INSERT INTO anggota (nama, email, no_telp, alamat) 
VALUES ('Andi Seger', 'andi@email.com', '081234567', 'Cibinong, Bogor');