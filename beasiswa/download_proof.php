<?php
session_start(); // Memulai sesi PHP untuk mengakses variabel sesi

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

// Menyiapkan query untuk mengambil jalur file dari database
$sql = "SELECT file FROM pendaftar WHERE id='$id'";
$result = $conn->query($sql);

// Memeriksa apakah data ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Menentukan jalur file yang disimpan di folder 'uploads'
    $filePath = 'uploads/' . $row['file'];

    // Memeriksa apakah file ada di folder
    if (file_exists($filePath)) {
        // Mengatur header untuk memulai unduhan file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Content-Length: ' . filesize($filePath));
        flush(); // Mengosongkan buffer output sistem
        readfile($filePath); // Membaca dan mengirimkan file ke output
        exit(); // Menghentikan eksekusi skrip setelah unduhan
    } else {
        // Menampilkan pesan jika file tidak ditemukan dan mengarahkan kembali ke dashboard admin
        echo "<script>alert('File tidak ditemukan di folder uploads!'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    // Menampilkan pesan jika data tidak ditemukan dan mengarahkan kembali ke dashboard admin
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='admin_dashboard.php';</script>";
}

// Menutup koneksi ke database
mysqli_close($conn);
?>
