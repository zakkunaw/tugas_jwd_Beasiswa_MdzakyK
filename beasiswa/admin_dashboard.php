<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>
            alert('Anda harus login sebagai admin untuk mengakses halaman ini.');
            window.location.href = 'index.php';
          </script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #667BC6;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1500px;
            height: 100vh;
            margin: 0 auto;
            border-radius: 10px;
            padding: 30px;
            background-color: #fff;
            position: relative;
        }

        .dashboard-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
            font-weight: 700;
        }

        .welcome-message {
            text-align: left;
            display: flex;
            flex-direction: column;
            font-size: 20px;
            margin-bottom: 30px;
            color: #495057;
        }

        .status-belum {
            color: red;
        }

        .status-sudah {
            color: green;
        }

        .btn {
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .text-center {
            margin-top: 20px;
        }

        .btn-logout {
            margin-top: 20px;
            position: absolute;
            left: 30px;
        }
    </style>
    <script>
        function confirmVerificationChange(name, status) {
            const newStatus = status === 'sudah_terverifikasi' ? 'belum_terverifikasi' : 'sudah_terverifikasi';
            return confirm(`Apakah Anda yakin ingin mengubah status ${name} menjadi ${newStatus}?`);
        }

        function confirmDelete(name) {
            return confirm(`Apakah Anda yakin ingin menghapus data mahasiswa ${name}? Tindakan ini tidak bisa dibatalkan.`);
        }

        function showDownloadAlert() {
            alert('Download bukti persyaratan sedang diproses...');
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <div class="welcome-message">
            <p>Welcome, <strong><?php echo $_SESSION['nama']; ?></strong>!</p>
            <p>Email: <strong><?php echo $_SESSION['email']; ?></strong></p>
        </div>
        <h2>Applicant List</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Semester</th>
                    <th>IPK</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = mysqli_connect('localhost', 'root', '', 'beasiswa');
                $sql = "SELECT * FROM pendaftar";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Determine the status class based on the status value
                        $statusClass = $row['status'] == 'belum_terverifikasi' ? 'status-belum' : 'status-sudah';
                        $statusLabel = $row['status'] == 'belum_terverifikasi' ? 'Belum Terverifikasi' : 'Sudah Terverifikasi';

                        echo "<tr>
                            <td>" . $row['nama'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['phone'] . "</td>
                            <td>" . $row['semester'] . "</td>
                            <td>" . $row['gpa'] . "</td>
                            <td class='" . $statusClass . "'>" . $statusLabel . "</td>
                            <td>
                                <a href='edit_applicant.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='verify_applicant.php?id=" . $row['id'] . "&status=" . $row['status'] . "' class='btn btn-success btn-sm' onclick=\"return confirmVerificationChange('" . $row['nama'] . "', '" . $row['status'] . "')\">Verify</a>
                                <a href='download_proof.php?id=" . $row['id'] . "' class='btn btn-info btn-sm' onclick=\"showDownloadAlert()\">Download</a>
                                <a href='delete_applicant.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirmDelete('" . $row['nama'] . "')\">Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="text-center">
            <a href="logout.php" class="btn btn-danger btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>
