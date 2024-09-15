<?php
session_start();

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Jika belum login sebagai admin, tampilkan alert dan arahkan ke halaman login
    echo "<script>
            alert('Anda harus login sebagai admin untuk mengakses halaman ini.');
            window.location.href = 'index.php';
          </script>";
    exit(); // Hentikan eksekusi script jika belum login
}

// Koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Tampilkan pesan error jika koneksi gagal
}

// Ambil ID pendaftar dan status saat ini dari URL
$id = $_GET['id'];
$currentStatus = $_GET['status'];

// Tentukan status baru berdasarkan status saat ini
// Jika status saat ini adalah 'belum_terverifikasi', ubah jadi 'Diterima'
// Jika status saat ini adalah 'Diterima', ubah jadi 'belum_terverifikasi'
$newStatus = $currentStatus == 'belum_terverifikasi' ? 'Diterima' : 'belum_terverifikasi';

// Update status pendaftar di database
$updateSql = "UPDATE pendaftar SET status='$newStatus' WHERE id='$id'";
if ($conn->query($updateSql) === TRUE) {
    // Jika update berhasil, tampilkan alert dan kembali ke dashboard admin
    echo "<script>
            alert('Status berhasil diperbarui!');
            window.location.href = 'admin_dashboard.php';
          </script>";
} else {
    // Jika terjadi error saat update, tampilkan pesan error
    echo "Error updating record: " . $conn->error;
}

// Tutup koneksi database
$conn->close();
?>
