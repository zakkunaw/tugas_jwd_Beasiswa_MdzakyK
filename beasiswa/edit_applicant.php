<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'beasiswa');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $semester = $_POST['semester'];
    $beasiswa = $_POST['beasiswa']; // Tambahkan beasiswa

    $sql = "UPDATE pendaftar SET nama='$nama', email='$email', phone='$phone', semester='$semester', beasiswa='$beasiswa' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href='admin_dashboard.php';
              </script>";
        exit();
    }
}

$id = $_GET['id'];
$sql = "SELECT * FROM pendaftar WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Applicant</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function checkGPA() {
            var gpa = parseFloat(document.getElementById("gpa").value);
            var beasiswaField = document.getElementById("beasiswa");

            // Jika IPK di bawah 3, set beasiswa readonly
            if (gpa < 3) {
                beasiswaField.setAttribute("readonly", "readonly");
            } else {
                beasiswaField.removeAttribute("readonly");
            }
        }

        // Jalankan saat halaman pertama kali dimuat
        window.onload = function() {
            checkGPA();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Applicant</h1>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" value="<?php echo $row['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <input type="text" class="form-control" name="semester" value="<?php echo $row['semester']; ?>" required>
            </div>
            <div class="form-group">
                <label>IPK</label>
                <input type="text" id="gpa" class="form-control" name="gpa" value="<?php echo $row['gpa']; ?>" readonly>
            </div>
            <div class="form-group">
                <label>Pilihan Beasiswa</label>
                <select id="beasiswa" name="beasiswa" class="form-control">
                    <option value="KIP" <?php if($row['beasiswa'] == 'KIP') echo 'selected'; ?>>KIP</option>
                    <option value="Olahraga" <?php if($row['beasiswa'] == 'Olahraga') echo 'selected'; ?>>Olahraga</option>
                    <option value="Prestasi" <?php if($row['beasiswa'] == 'Prestasi') echo 'selected'; ?>>Prestasi</option>
                    <option value="Akademik" <?php if($row['beasiswa'] == 'Akademik') echo 'selected'; ?>>Akademik</option>
                    
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
