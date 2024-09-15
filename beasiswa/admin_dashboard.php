<?php
session_start();

// Check jika user itu admin
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

        .status-ipk {
            color: orange;
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
    // Fungsi untuk mengonfirmasi perubahan status
    function confirmVerificationChange(name, status) {
        // Menentukan status baru berdasarkan status saat ini
        const newStatus = status === 'Diterima' ? 'Belum Terverifikasi' : 'Diterima';
        
        // Menampilkan dialog konfirmasi untuk mengubah status
        return confirm(`Apakah Anda yakin ingin mengubah status ${name} menjadi ${newStatus}?`);
    }

    // Fungsi untuk mengonfirmasi penghapusan data
    function confirmDelete(name) {
        // Menampilkan dialog konfirmasi untuk menghapus data
        return confirm(`Apakah Anda yakin ingin menghapus data mahasiswa ${name}? Tindakan ini tidak bisa dibatalkan.`);
    }

    // Fungsi untuk menampilkan pesan saat proses download
    function showDownloadAlert() {
        // Menampilkan alert bahwa proses download sedang berlangsung
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
                    <th>Status Ajuan</th>
                    <th>Pilihan Beasiswa</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
               <?php
    // Membuat koneksi ke database
    $conn = mysqli_connect('localhost', 'root', '', 'beasiswa');

    // Query untuk mengambil semua data dari tabel 'pendaftar'
    $sql = "SELECT * FROM pendaftar";
    $result = $conn->query($sql);

    // Memeriksa apakah ada data yang diambil
    if ($result->num_rows > 0) {
            // Mengambil setiap baris data dari hasil query
            while ($row = $result->fetch_assoc()) {
                $ipk = $row['gpa']; // Mengambil nilai GPA dari baris data
                $statusClass = ''; // Variabel untuk menyimpan kelas status
                $statusLabel = ''; // Variabel untuk menyimpan label status
                $actions = ''; // Variabel untuk menyimpan tombol-tombol aksi
                $beasiswa = $row['beasiswa']; // Mengambil pilihan beasiswa dari baris data

                // Memeriksa apakah GPA kurang dari 3
                if ($ipk < 3) {
                    $statusClass = 'status-ipk'; // Mengatur kelas status jika GPA < 3
                    $statusLabel = 'Tidak Memenuhi Syarat IPK'; // Label status jika GPA < 3
                    $beasiswa = 'Lainnya'; // Mengubah beasiswa menjadi 'Lainnya' otomatis
                } else {
                        // Menentukan kelas status dan label berdasarkan status saat ini
                        $statusClass = $row['status'] == 'belum_terverifikasi' ? 'status-belum' : 'status-sudah';
                        $statusLabel = $row['status'] == 'belum_terverifikasi' ? 'Belum Terverifikasi' : 'Diterima';

                        // Menambahkan tombol Verifikasi jika GPA >= 3
                        $actions .= "<a href='verify_applicant.php?id=" . $row['id'] . "&status=" . $row['status'] . "' class='btn btn-success btn-sm' onclick=\"return confirmVerificationChange('" . $row['nama'] . "', '" . $row['status'] . "')\">Verify</a> ";
                    }

                    // Menambahkan tombol Edit, Unduh, dan Hapus untuk semua data
                    $actions .= "
                        <a href='edit_applicant.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                        <a href='download_proof.php?id=" . $row['id'] . "' class='btn btn-info btn-sm' onclick=\"showDownloadAlert()\">Unduh Berkas</a>
                        <a href='delete_applicant.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirmDelete('" . $row['nama'] . "')\">Delete</a>
                    ";

                    // Menampilkan baris tabel dengan data dari baris yang diambil
                    echo "<tr>
                        <td>" . $row['nama'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phone'] . "</td>
                        <td>" . $row['semester'] . "</td>
                        <td>" . $row['gpa'] . "</td>
                        <td class='" . $statusClass . "'>" . $statusLabel . "</td>
                        <td>" . $beasiswa . "</td>
                        <td>" . $actions . "</td>
                    </tr>";
                }
            } else {
                // Jika tidak ada data, tampilkan pesan "No data available"
                echo "<tr><td colspan='8' class='text-center'>No data available</td></tr>";
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
