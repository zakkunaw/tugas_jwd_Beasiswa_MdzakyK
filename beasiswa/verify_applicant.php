<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');

$id = $_GET['id'];
$current_status = $_GET['status'];

$new_status = ($current_status == 'sudah_terverifikasi') ? 'belum_terverifikasi' : 'sudah_terverifikasi';

$sql = "UPDATE pendaftar SET status='$new_status' WHERE id='$id'";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Status berhasil diubah!');
            window.location.href='admin_dashboard.php';
          </script>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
