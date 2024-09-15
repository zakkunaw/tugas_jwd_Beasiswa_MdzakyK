<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');
$id = $_GET['id'];

// Delete the applicant from the database
$sql = "DELETE FROM pendaftar WHERE id='$id'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Data mahasiswa berhasil dihapus!'); window.location.href='admin_dashboard.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan saat menghapus data!'); window.location.href='admin_dashboard.php';</script>";
}

mysqli_close($conn);
?>
