# SIBANSOS - Sistem Informasi Pendataan Bantuan Sosial

Aplikasi web (PHP native + MySQL) untuk mendata calon penerima bantuan sosial,
menilai kelayakannya secara otomatis, dan mencatat penyaluran bantuan.
Dibuat untuk Tugas UAS Pemrograman Web.

## Alur Input - Proses - Output
| Tahap  | Penjelasan |
|--------|------------|
| Input  | Petugas mengisi data penerima (NIK, penghasilan, tanggungan, status rumah) |
| Proses | Sistem menghitung **skor kelayakan 0-100** dan menentukan status (layak / dipertimbangkan / tidak layak) |
| Output | Data tersimpan di database, tampil di daftar, ringkasan, dan API JSON |

## Aturan skor
- Penghasilan: <= 1 jt = 50, <= 2 jt = 35, <= 3 jt = 20, di atasnya 0
- Tanggungan: 6 poin per orang (maksimal 5 orang = 30)
- Tempat tinggal: menumpang 20, sewa 12, milik sendiri 0
- Status: skor >= 60 layak, 40-59 dipertimbangkan, di bawah 40 tidak layak

## Fitur
- Login admin (password di-hash dengan `password_hash`)
- CRUD data penerima + pencarian dan filter status
- Pratinjau skor langsung saat mengisi formulir (JavaScript)
- Program bantuan dengan kuota
- Penyaluran bantuan (validasi: hanya penerima layak, kuota, tidak ganda)
- Ringkasan (statistik dan grafik batang)
- API JSON `GET /api/penerima.php` (opsional, dengan token)

## Cara menjalankan (XAMPP)
1. Salin folder `sibansos` ke `C:\xampp\htdocs\`
2. Nyalakan **Apache** dan **MySQL** di XAMPP Control Panel
3. Buka `http://localhost/phpmyadmin`, pilih tab **Import**, unggah `database.sql`
4. Buka `http://localhost/sibansos`
5. Masuk dengan **admin / admin123**

Jika nama folder atau kredensial MySQL berbeda, ubah `config/koneksi.php`.

## Struktur folder
```
sibansos/
  config/koneksi.php     koneksi database (PDO)
  includes/              fungsi, header, footer, pengecekan login
  assets/style.css       tampilan
  penerima/              CRUD penerima
  program/               program bantuan
  penyaluran/            penyaluran bantuan
  api/penerima.php       endpoint API
  database.sql           struktur dan data contoh
```

## Contoh API
```
GET /sibansos/api/penerima.php?token=sibansos-2026-token&status=layak
```
Token ada di `config/koneksi.php`. NIK pada respons disamarkan.

## Pembagian peran presentasi (saran)
- Front end: `assets/style.css`, `includes/header.php`, semua form dan tabel, JavaScript pratinjau skor
- Back end: `config/koneksi.php`, `includes/fungsi.php`, `*/simpan.php`, `api/penerima.php`, `database.sql`
