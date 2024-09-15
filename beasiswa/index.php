<?php
// Memulai sesi untuk menyimpan data pengguna setelah login
session_start();

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
    $emailOrName = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari admin berdasarkan nama
    $sqlAdmin = "SELECT * FROM admin WHERE nama = '$emailOrName'";
    $resultAdmin = $conn->query($sqlAdmin);

    // Jika admin ditemukan
    if ($resultAdmin->num_rows > 0) {
        // Mengambil data admin
        $row = $resultAdmin->fetch_assoc();

        // Memeriksa apakah password yang dimasukkan cocok dengan password di database
        if ($password == $row['password']) {
            // Menyimpan informasi admin dalam sesi
            $_SESSION['role'] = 'admin';
            $_SESSION['email'] = $row['email']; 
            $_SESSION['nama'] = $row['nama'];

            // Mengarahkan ke halaman dashboard admin
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Jika password salah, menampilkan pesan kesalahan
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        // Jika admin tidak ditemukan, mencari pengguna di tabel pendaftar
        $sqlUser = "SELECT * FROM pendaftar WHERE email = '$emailOrName'";
        $resultUser = $conn->query($sqlUser);

        // Jika pengguna ditemukan
        if ($resultUser->num_rows > 0) {
            // Mengambil data pengguna
            $row = $resultUser->fetch_assoc();
            // Memeriksa apakah password yang dimasukkan cocok dengan password di database
            if (password_verify($password, $row['password'])) {
                // Menyimpan informasi pengguna dalam sesi
                $_SESSION['email'] = $row['email'];
                $_SESSION['nama'] = $row['nama'];

                // Mengarahkan ke halaman dashboard pengguna
                header("Location: dashboard.php");
                exit();
            } else {
                // Jika password salah, menampilkan pesan kesalahan
                echo "<script>alert('Password salah!');</script>";
            }
        } else {
            // Jika email tidak ditemukan, menampilkan pesan kesalahan
            echo "<script>alert('Email tidak ditemukan!');</script>";
        }
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
    <title>Login Beasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffff;
        }

        .login {
            width: 380px;
            height: 500px;
            margin: 90px auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #004085;
            border: 1px solid #EBF4F6;
            border-radius: 8px;
            box-shadow: 6px 6px 0px 0px rgba(100, 57, 255, 1);
        }

        .login h1 {
            margin-bottom: 20px;
            color: white;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 10px;
            color: white;
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-left {
            margin-right: 9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login">
                    <h1>Login Beasiswa</h1>
                    <form action="index.php" method="POST">
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="text" class="form-control" name="username" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password :</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm btn-left">Login</button>
                    </form>
                    <div class="mt-3">
                        <a href="daftarbeasiswa.php" class="btn btn-link text-white">Belum punya akun? Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
