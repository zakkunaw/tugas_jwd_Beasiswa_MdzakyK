<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');
$id = $_GET['id'];

// Fetch the proof file path from the database
$sql = "SELECT file FROM pendaftar WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // The file is stored in the 'uploads' folder
    $filePath = 'uploads/' . $row['file'];

    if (file_exists($filePath)) {
        // Set headers to initiate file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Content-Length: ' . filesize($filePath));
        flush(); // Flush system output buffer
        readfile($filePath);
        exit();
    } else {
        echo "<script>alert('File tidak ditemukan di folder uploads!'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='admin_dashboard.php';</script>";
}

mysqli_close($conn);
?>
