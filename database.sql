-- SIBANSOS - Sistem Informasi Pendataan Bantuan Sosial
-- Import file ini lewat phpMyAdmin (tab Import) atau: mysql -u root < database.sql
CREATE DATABASE IF NOT EXISTS sibansos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sibansos;

DROP TABLE IF EXISTS penyaluran;
DROP TABLE IF EXISTS penerima;
DROP TABLE IF EXISTS program_bantuan;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE penerima (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nik CHAR(16) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  alamat VARCHAR(255) NOT NULL,
  kelurahan VARCHAR(100) NOT NULL,
  pekerjaan VARCHAR(100) NOT NULL,
  penghasilan INT NOT NULL DEFAULT 0,
  tanggungan TINYINT NOT NULL DEFAULT 0,
  status_rumah ENUM('milik','sewa','menumpang') NOT NULL DEFAULT 'milik',
  skor TINYINT NOT NULL DEFAULT 0,
  status_kelayakan ENUM('layak','dipertimbangkan','tidak_layak') NOT NULL DEFAULT 'tidak_layak',
  dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE program_bantuan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jenis ENUM('tunai','sembako','lainnya') NOT NULL DEFAULT 'tunai',
  nilai INT NOT NULL DEFAULT 0,
  kuota INT NOT NULL DEFAULT 0,
  periode VARCHAR(50) NOT NULL
);

CREATE TABLE penyaluran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  penerima_id INT NOT NULL,
  program_id INT NOT NULL,
  tanggal DATE NOT NULL,
  status ENUM('dijadwalkan','tersalurkan','dibatalkan') NOT NULL DEFAULT 'dijadwalkan',
  catatan VARCHAR(255) NULL,
  UNIQUE KEY uq_penerima_program (penerima_id, program_id),
  FOREIGN KEY (penerima_id) REFERENCES penerima(id) ON DELETE CASCADE,
  FOREIGN KEY (program_id) REFERENCES program_bantuan(id) ON DELETE RESTRICT
);

-- Akun admin: username "admin", password "admin123"
INSERT INTO users (nama, username, password) VALUES
('Administrator', 'admin', '$2b$12$ODyLGi36RU6kKdayRMJ9o.3HDa9OCxu5MQ6jbglD5W4HlGeHY4z8O');

INSERT INTO penerima (nik, nama, alamat, kelurahan, pekerjaan, penghasilan, tanggungan, status_rumah, skor, status_kelayakan) VALUES
('3326010101900001','Siti Aminah','Jl. Pelita No. 12','Kauman','Buruh batik',800000,4,'menumpang',94,'layak'),
('3326010202850002','Budi Santoso','Gg. Melati 3','Sapuro','Pedagang kecil',1800000,3,'sewa',65,'layak'),
('3326010303920003','Dewi Lestari','Jl. Kenanga No. 7','Kauman','Karyawan toko',2500000,2,'milik',32,'tidak_layak'),
('3326010404880004','Agus Pratama','Jl. Cempaka No. 21','Landungsari','Kuli bangunan',2000000,5,'sewa',77,'layak'),
('3326010505950005','Rina Wulandari','Gg. Mawar 5','Sapuro','Penjahit',2800000,4,'milik',44,'dipertimbangkan');

INSERT INTO program_bantuan (nama, jenis, nilai, kuota, periode) VALUES
('Program Keluarga Harapan (PKH)','tunai',3000000,100,'Triwulan IV 2026'),
('Bantuan Pangan Non Tunai (BPNT)','sembako',200000,200,'November 2026'),
('BLT Dana Desa','tunai',300000,50,'November 2026');

INSERT INTO penyaluran (penerima_id, program_id, tanggal, status, catatan) VALUES
(1,1,'2026-10-15','tersalurkan','Diterima langsung'),
(4,2,'2026-10-20','tersalurkan',NULL),
(2,3,'2026-11-05','dijadwalkan','Menunggu verifikasi lapangan');
