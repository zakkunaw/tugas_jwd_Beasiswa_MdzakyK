<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "<script>
            alert('Anda harus login sebagai peserta untuk mengakses halaman ini.');
            window.location.href = 'index.php';
          </script>";
}

$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');
$email = $_SESSION['email'];

// Fetch user data based on the logged-in email
$sql = "SELECT nama, status FROM pendaftar WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='logout.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Beasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #667BC6;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            font-weight: 700;
        }

        .welcome-message {
            
            font-size: 18px;
            margin-bottom: 30px;
            align-items: center;
            justify-content: space-around;
            color: #495057;
            display: flex;
        }

        .status-container {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
        }

        .status {
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            color: white;
        }

        .status-verified {
            background-color: #28a745; /* Green for verified */
        }

        .status-unverified {
            background-color: #dc3545; /* Red for unverified */
        }

        .btn-logout {
            text-align: center;
            margin-top: 20px;
        }

        .btn-logout a {
            padding: 10px 30px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard Beasiswa</h1>
        <div class="welcome-message">
            <p>Selamat datang, <strong><?php echo $user['nama']; ?></strong>!</p>
            <p>Email: <strong><?php echo $_SESSION['email']; ?></strong></p>
        </div>

        <div class="status-container">
            <p>Status Ajuan:</p>
            <span class="status <?php echo $user['status'] === 'sudah_terverifikasi' ? 'status-verified' : 'status-unverified'; ?>">
                <?php echo $user['status'] === 'sudah_terverifikasi' ? 'Sudah Terverifikasi' : 'Belum Terverifikasi'; ?>
            </span>
        </div>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Semester</th>
            <th>IPK</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'beasiswa');
        // Fetch data only for the currently logged-in user based on their email
        $email = $_SESSION['email'];
        $sql = "SELECT * FROM pendaftar WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row['nama'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['phone'] . "</td>
                    <td>" . $row['semester'] . "</td>
                    <td>" . $row['gpa'] . "</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Data tidak ada</td></tr>";
        }
        ?>
    </tbody>
</table>


        <div class="btn-logout text-center">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
