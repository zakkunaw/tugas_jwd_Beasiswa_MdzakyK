<?php
session_start(); // Memulai sesi untuk mengakses variabel sesi

// Memeriksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Jika tidak login sebagai admin, arahkan ke halaman dashboard admin
    header("Location: admin_dashboard.php");
    exit(); // Menghentikan eksekusi skrip setelah redirect
}

// Membuat koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');

// Mengambil ID pendaftar dari URL parameter
$id = $_GET['id'];

// Menyiapkan query untuk menghapus data pendaftar berdasarkan ID
$sql = "DELETE FROM pendaftar WHERE id='$id'";

// Menjalankan query untuk menghapus data
if (mysqli_query($conn, $sql)) {
    // Jika berhasil menghapus data, tampilkan pesan sukses dan arahkan kembali ke dashboard admin
    echo "<script>alert('Data mahasiswa berhasil dihapus!'); window.location.href='admin_dashboard.php';</script>";
} else {
    // Jika terjadi kesalahan saat menghapus data, tampilkan pesan kesalahan dan arahkan kembali ke dashboard admin
    echo "<script>alert('Terjadi kesalahan saat menghapus data!'); window.location.href='admin_dashboard.php';</script>";
}

// Menutup koneksi ke database
mysqli_close($conn);
?>
