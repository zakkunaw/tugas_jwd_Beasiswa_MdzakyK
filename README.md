# tugas_jwd_Beasiswa_MdzakyK
Tugas JWD Pembuatan Software Beasiswa
# Tugas JWD Beasiswa MdzakyK

Tugas JWD Pembuatan Software Beasiswa

## Aplikasi Beasiswa

Aplikasi web sederhana untuk pengelolaan pendaftaran dan verifikasi beasiswa, yang memungkinkan mahasiswa mendaftar, melihat status, dan melakukan operasi CRUD dasar. Aplikasi ini dibagi menjadi peran admin dan mahasiswa.

## Prasyarat

Pastikan Anda telah menginstal perangkat berikut di mesin Anda:

- [XAMPP](https://www.apachefriends.org/index.html) atau lingkungan server lokal serupa (Apache, MySQL, PHP)
- PHP (versi 7 atau lebih tinggi)
- Database MySQL

## Langkah Instalasi

### 1. Clone atau Download Repository

Clone repository ke komputer lokal Anda atau unduh file ZIP dan ekstrak.

```bash
git clone https://github.com/zakkunaw/tugas_jwd_Beasiswa_MdzakyK.git

2. Pindahkan Repository ke htdocs
Setelah di-clone atau di-download, pindahkan folder project ke direktori htdocs di XAMPP Anda:

# Pada Windows (asumsi XAMPP terinstal di C drive):
C:/xampp/htdocs/

# Pada macOS atau Linux:
sudo mv /path-ke-repository /opt/lampp/htdocs/


3. Import Database
Anda perlu mengimport file beasiswa.sql ke database MySQL.

Buka phpMyAdmin di browser dengan mengunjungi http://localhost/phpmyadmin.
Buat database baru dengan nama beasiswa.
Klik database beasiswa yang baru dibuat, lalu buka tab Import.
Pilih file beasiswa.sql (yang sudah ada di repository) dan klik Go.
Langkah ini akan membuat tabel-tabel dan data yang dibutuhkan aplikasi.

4. Konfigurasi Koneksi Database (Opsional)
Secara default, aplikasi ini menggunakan konfigurasi berikut untuk koneksi database:

php
Salin kode
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'beasiswa';
Jika pengaturan MySQL Anda menggunakan kredensial yang berbeda, Anda bisa mengubahnya di beberapa file PHP terkait, seperti login.php, admin_dashboard.php, dan dashboard.php. Cari fungsi mysqli_connect() dan sesuaikan parameter sesuai pengaturan Anda.

5. Jalankan Aplikasi
Jalankan layanan Apache dan MySQL di XAMPP.

Buka XAMPP Control Panel, kemudian klik Start untuk Apache dan MySQL.
Buka browser dan kunjungi http://localhost/beasiswa-app.
Sekarang Anda dapat mencoba aplikasi dengan menggunakan kredensial login sebagai admin atau mendaftar sebagai peserta beasiswa.

Fitur Aplikasi
Login sebagai Admin: Admin dapat masuk untuk memanage data pendaftar.
Dashboard Mahasiswa: Mahasiswa dapat melihat status verifikasi beasiswa mereka setelah login.
CRUD Admin: Admin dapat mengubah, menghapus, dan mengelola data pendaftar.
Verifikasi Status: Admin dapat memverifikasi atau membatalkan verifikasi status pendaftar.
Unduh Berkas Bukti: Admin dapat mengunduh berkas bukti yang diupload oleh pendaftar.
