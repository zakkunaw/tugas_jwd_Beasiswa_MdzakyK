<?php
// Memeriksa apakah permintaan HTTP adalah POST (artinya form telah dikirim)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "beasiswa";

    // Membuat koneksi ke database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Memeriksa apakah koneksi ke database berhasil
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Mengambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nohp = $_POST['nohp'];
    $semester = $_POST['semester'];
    $gpa = $_POST['gpa'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $file = $_FILES['file']['name']; // Nama file yang diupload
    $status = 'belum_terverifikasi'; // Status pendaftaran awal

    // Memeriksa apakah nama atau email sudah ada di database
    $checkQuery = "SELECT * FROM pendaftar WHERE nama='$nama' OR email='$email'";
    $checkResult = $conn->query($checkQuery);

    // Jika nama atau email sudah ada, tampilkan pesan kesalahan
    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Akun dengan nama atau email ini sudah pernah dibuat.'); window.location.href = 'daftarbeasiswa.php';</script>";
        exit;
    }

    // Memeriksa apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok!');</script>";
        exit;
    }

    // Mengenkripsi password sebelum disimpan di database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Mengupload file ke folder "uploads"
    if (move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $file)) {
        // Menyimpan data ke database
        $sql = "INSERT INTO pendaftar (nama, email, phone, semester, gpa, file, status, password)
                VALUES ('$nama', '$email', '$nohp', '$semester', '$gpa', '$file', '$status', '$hashed_password')";

        // Jika query berhasil, tampilkan pesan sukses dan arahkan ke halaman login
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Pendaftaran berhasil!'); window.location.href = 'index.php';</script>";
        } else {
            // Jika terjadi kesalahan saat menyimpan data, tampilkan pesan kesalahan
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Jika gagal mengupload file, tampilkan pesan kesalahan
        echo "<script>alert('File upload gagal');</script>";
    }

    // Menutup koneksi ke database
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Beasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            color: #fff;
        }   
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 90px auto;
            padding: 30px;
            background-color: #004085;
            border: 1px solid #EBF4F6;
            border-radius: 8px;
            box-shadow: 6px 6px 0px rgba(100,57,255,1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group-full {
            grid-column: span 2;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ffff;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        #gpa {
            font-weight: bold;
        }
    </style>

<script>
    let gpaGenerated = false; // Variabel untuk melacak apakah GPA sudah dihasilkan

    // Fungsi untuk menghasilkan GPA acak
    function generateRandomGPA() {
        // Mengambil nilai dari input nama dan input GPA
        const nameInput = document.getElementById("name").value;
        const gpaInput = document.getElementById("gpa");

        // Jika panjang nama minimal 3 karakter dan GPA belum dihasilkan
        if (nameInput.length >= 3 && !gpaGenerated) {
            // Menentukan nilai minimum dan maksimum untuk GPA
            const min = 1.00;
            const max = 4.00;

            // Menghasilkan angka acak antara min dan max, kemudian membulatkannya menjadi 2 desimal
            const randomGPA = (Math.random() * (max - min) + min).toFixed(2);

            // Menampilkan GPA yang dihasilkan di input GPA
            gpaInput.value = randomGPA;

            // Menandai bahwa GPA sudah dihasilkan
            gpaGenerated = true;
        }

        // Jika nama dikosongkan, reset GPA ke 0.00 dan gpaGenerated diatur ke false
        if (nameInput.trim() === "") {
            gpaInput.value = "0.00";
            gpaGenerated = false;
        }
    }
</script>

</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">DAFTAR BEASISWA</h2>
            <form action="daftarbeasiswa.php" method="post" enctype="multipart/form-data" onsubmit="validateForm(event)">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Masukkan Nama</label>
                        <input type="text" class="form-control" name="nama" id="name" placeholder="Masukkan Nama" required oninput="generateRandomGPA()">
                    </div>
                    <div class="form-group">
                        <label for="email">Masukkan Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor HP</label>
                        <input type="text" class="form-control" name="nohp" id="phone" placeholder="Nomor HP" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester Saat Ini</label>
                        <select class="form-control" name="semester" id="semester" required>
                            <option value="">Pilih</option>
                            <option>Semester 1</option>
                            <option>Semester 2</option>
                            <option>Semester 3</option>
                            <option>Semester 4</option>
                            <option>Semester 5</option>
                            <option>Semester 6</option>
                            <option>Semester 7</option>
                            <option>Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gpa">IPK Terakhir</label>
                        <input type="text" class="form-control" name="gpa" id="gpa" placeholder="IPK Terakhir" readonly value="0.00">
                    </div>
                    <div class="form-group">
                        <label for="file">Upload Berkas Syarat</label>
                        <input type="file" class="form-control-file" name="file" id="file" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Masukkan Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required>
                    </div>
                </div>
                <div class="form-group-full">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                    <button type="reset" class="btn btn-secondary">Batal</button>
                </div>
            </form>
            <div class="mt-3">
                <a href="index.php" class="btn btn-link text-white">Sudah punya akun? Masuk di sini</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
