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

### 2. Pindahkan Repository ke `htdocs`

Setelah di-clone atau di-download, pindahkan folder project ke direktori `htdocs` di XAMPP Anda:

```bash
# Pada Windows (asumsi XAMPP terinstal di C drive):
C:/xampp/htdocs/

# Pada macOS atau Linux:
sudo mv /path-ke-repository /opt/lampp/htdocs/


### 3. Import Database

Anda perlu mengimport file `beasiswa.sql` ke database MySQL:

1. Buka phpMyAdmin di browser dengan mengunjungi [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Buat database baru dengan nama `beasiswa`.
3. Klik database `beasiswa` yang baru dibuat, lalu buka tab **Import**.
4. Pilih file `beasiswa.sql` (yang sudah ada di repository) dan klik **Go**.

Langkah ini akan membuat tabel-tabel dan data yang dibutuhkan aplikasi.


### 4. Konfigurasi Koneksi Database (Opsional)

Secara default, aplikasi ini menggunakan konfigurasi berikut untuk koneksi database:

```php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'beasiswa';


### 5. Jalankan Aplikasi

Jalankan layanan Apache dan MySQL di XAMPP.

1. Buka XAMPP Control Panel, kemudian klik **Start** untuk Apache dan MySQL.
2. Buka browser dan kunjungi `http://localhost/beasiswa-app`.

Sekarang Anda dapat mencoba aplikasi dengan menggunakan kredensial login sebagai admin atau mendaftar sebagai peserta beasiswa.
