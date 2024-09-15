<?php
session_start(); // Memulai session untuk melacak pengguna yang sedang login

// Memeriksa apakah pengguna sudah login dengan memeriksa keberadaan variabel session 'email'
if (!isset($_SESSION['email'])) {
    echo "<script>
            alert('Anda harus login sebagai peserta untuk mengakses halaman ini.');
            window.location.href = 'index.php'; // Mengarahkan pengguna ke halaman login jika belum login
          </script>";
}

// Membuat koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');
$email = $_SESSION['email']; // Mendapatkan email pengguna dari session

// Inisialisasi variabel
$gpa = 0;
$beasiswa = '';
$uploadFile = '';
$gpaOk = false;
$formLocked = false;
$submissionSuccess = false;  // Flag baru untuk status keberhasilan pengiriman data

// Mengambil data pengguna berdasarkan email yang sedang login
$sql = "SELECT nama, status, gpa, phone, semester, beasiswa, file FROM pendaftar WHERE email='$email'";
$result = $conn->query($sql);

// Memeriksa apakah data pengguna ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Mengambil data pengguna dalam bentuk array asosiatif
    $gpa = $user['gpa'];
    $beasiswa = $user['beasiswa'];
    $uploadFile = $user['file'];
    $gpaOk = $gpa >= 3; // Memeriksa apakah GPA memenuhi syarat (>= 3)

    // Jika pengguna sudah memilih jenis beasiswa (dan bukan 'Lainnya'), kunci pilihan beasiswa
    if (!empty($user['beasiswa']) && $user['beasiswa'] !== 'Lainnya') {
        $formLocked = true;
    }
} else {
    // Jika data pengguna tidak ditemukan, arahkan pengguna ke halaman logout
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='logout.php';</script>";
    exit();
}

// Menangani pengiriman formulir
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadFile = $_FILES['file']['name']; // Mendapatkan nama file yang diupload

    // Memindahkan file yang diupload ke direktori yang diinginkan
    if (!empty($uploadFile)) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($uploadFile);
        move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
    }

    // Hanya memperbarui jenis beasiswa jika GPA >= 3 dan jenis beasiswa belum dipilih
    if ($gpaOk && !$formLocked) {
        $beasiswa = $_POST['beasiswa'];
        $updateSql = "UPDATE pendaftar SET beasiswa='$beasiswa', file='$uploadFile' WHERE email='$email'";
    } else {
        $updateSql = "UPDATE pendaftar SET file='$uploadFile' WHERE email='$email'";
    }

    // Menjalankan query untuk memperbarui data dan memeriksa apakah berhasil
    if ($conn->query($updateSql) === TRUE) {
        $submissionSuccess = true; // Mengatur flag keberhasilan pengiriman data
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        // Menampilkan pesan kesalahan jika query gagal
        echo "<script>alert('Terjadi kesalahan: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil - Dashboard Beasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #667BC6;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            margin-bottom: 20px;
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

        .disabled {
            pointer-events: none;
            opacity: 0.6;
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

<!-- Container untuk menampilkan status ajuan -->
<div class="status-container">
    <p>Status Ajuan:</p>
    <!-- Menampilkan status ajuan dengan class berbeda berdasarkan nilai status -->
    <span class="status <?php echo $user['status'] === 'Diterima' ? 'status-verified' : 'status-unverified'; ?>">
        <?php echo $user['status'] === 'Diterima' ? 'Diterima' : 'Belum Terverifikasi'; ?>
    </span>
</div>

<!-- Tabel untuk menampilkan data GPA dan formulir beasiswa -->
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Semester</th>
            <th>IPK</th>
            <th>Beasiswa</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <!-- Menampilkan data pengguna jika tersedia, jika tidak, tampilkan string kosong -->
            <td><?php echo isset($user['nama']) ? $user['nama'] : ''; ?></td>
            <td><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></td>
            <td><?php echo isset($user['phone']) ? $user['phone'] : ''; ?></td>
            <td><?php echo isset($user['semester']) ? $user['semester'] : ''; ?></td>
            <td><?php echo isset($user['gpa']) ? $user['gpa'] : ''; ?></td>
            <!-- Menampilkan jenis beasiswa jika tersedia, jika tidak tampilkan 'Belum Dipilih' -->
            <td><?php echo !empty($user['beasiswa']) ? $user['beasiswa'] : 'Belum Dipilih'; ?></td>
            <!-- Menampilkan nama file jika ada, jika tidak tampilkan 'Belum Ada Berkas' -->
            <td><?php echo !empty($user['file']) ? $user['file'] : 'Belum Ada Berkas'; ?></td>
        </tr>
    </tbody>
</table>

        <div id="error-message" style="color: red; display: none; margin-bottom: 15px;">
   Maaf Anda tidak memenuhi syarat IPK
</div>
<!-- Success message for GPA >= 3 -->
<div id="success-message" style="color: green; display: <?php echo $submissionSuccess ? 'block' : 'none'; ?>; margin-bottom: 15px;">
    Pengajuan berhasil, harap tunggu pengecekan , bisa dilihat dari status ajuan.
</div>


        <form id="beasiswaForm" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="beasiswa">Tipe Beasiswa:</label>
                <select id="beasiswa" name="beasiswa" class="form-control" <?php echo $formLocked ? 'disabled' : ''; ?>>
                    <option value="">Pilih Beasiswa</option>
                    <option value="KIP" <?php echo $beasiswa === 'KIP' ? 'selected' : ''; ?>>KIP</option>
                    <option value="Olahraga" <?php echo $beasiswa === 'Olahraga' ? 'selected' : ''; ?>>Olahraga</option>
                    <option value="Akademik" <?php echo $beasiswa === 'Akademik' ? 'selected' : ''; ?>>Akademik</option>
                    <option value="Prestasi" <?php echo $beasiswa === 'Prestasi' ? 'selected' : ''; ?>>Prestasi</option>
                    <option value="Lainnya" <?php echo $beasiswa === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label for="file">Upload Berkas:</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

      <script>
document.addEventListener('DOMContentLoaded', function() {
    // Mengambil nilai GPA dari PHP dan menyimpannya dalam variabel
    const gpa = <?php echo json_encode($gpa); ?>;

    // Mengambil elemen form, input file, tombol submit, dan elemen pesan
    const form = document.getElementById('beasiswaForm');
    const fileInput = form.querySelector('input[type="file"]');
    const submitButton = form.querySelector('button[type="submit"]');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');
    const beasiswaSelect = form.querySelector('select[name="beasiswa"]');

    // Memeriksa apakah GPA kurang dari 3
    if (gpa < 3) {
        // Menonaktifkan elemen-elemen form jika GPA kurang dari 3
        beasiswaSelect.disabled = true;
        fileInput.disabled = true;
        submitButton.disabled = true;
        // Menampilkan pesan kesalahan
        errorMessage.style.display = 'block';
    }

    // Memeriksa apakah form telah berhasil dikirim (menggunakan flag PHP)
    const submissionSuccess = <?php echo json_encode($submissionSuccess); ?>;
    if (submissionSuccess) {
        // Menampilkan pesan sukses
        successMessage.style.display = 'block';
        // Menonaktifkan elemen form agar tidak bisa diubah lagi
        beasiswaSelect.disabled = true;
        fileInput.disabled = true;
        submitButton.disabled = true;
        // Menyembunyikan pesan kesalahan jika ada
        errorMessage.style.display = 'none';
    }
});
</script>


        <div class="btn-logout text-center">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
