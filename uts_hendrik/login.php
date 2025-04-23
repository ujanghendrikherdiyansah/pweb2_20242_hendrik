<?php
session_start();

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "db_sekolah");

// Cek jika koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek jika sudah login, arahkan ke dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: dashboard.php');
    exit;
}

// Proses login jika form disubmit
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $koneksi->prepare($query);

    if ($stmt === false) {
        die("Gagal mempersiapkan query: " . $koneksi->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Cocokkan password secara langsung (pastikan dalam dev; tidak disarankan di production)
        if ($user['password'] === $password) {
            // Set session login
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = "Password salah";
        }
    } else {
        $error_message = "Username tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sekolah Pintar</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5dc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h3 {
            color: #333;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #00ffd5;
            color: #000;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #00c9a7;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
        }

        .register-link {
            margin-top: 10px;
            font-size: 0.9em;
        }

        .register-link a {
            color: #00ffd5;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h3>Login - Sekolah Pintar</h3>

        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="error-message">
            <?php
            if (isset($error_message)) {
                echo $error_message;
            }
            ?>
        </div>

        <div class="register-link">
            <p>Belum Memiliki Akun? <a href="daftar_akun.php">Silahkan Daftar Akun Terlebih Dahulu</a></p>
        </div>

        <div class="footer">
            <p>&copy; 2025 Sekolah Pintar. Semua hak dilindungi undang-undang.</p>
        </div>
    </div>

</body>
</html>