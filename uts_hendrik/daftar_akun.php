<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "db_sekolah");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Cek apakah username sudah ada
    $cek = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username sudah digunakan!";
    } else {
        // Simpan akun ke database
        $stmt = $koneksi->prepare("INSERT INTO user (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            $success_message = "Akun berhasil dibuat! <a href='login.php'>Login di sini</a>.";
        } else {
            $error_message = "Gagal mendaftarkan akun.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - Sekolah Pintar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .form-container {
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

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #fffbe6;
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

        .message {
            margin-top: 10px;
            font-size: 0.9em;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }

        .login-link a {
            color: #00ffd5;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Daftar Akun Baru</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin-system">Admin System</option>
                <option value="akademik">Akademik</option>
                <option value="keuangan">Keuangan</option>
                <option value="sarana">Sarana</option>
                <option value="pengunjung">Pengunjung</option>
                <option value="pendaftar">Pendaftar</option>
                <option value="orangtua">Orangtua</option>
                <option value="petugas-pmb">Petugas PMB</option>
                <option value="kepala-pmb">Kepala PMB</option>
                <option value="pimpinan">Pimpinan</option>
                <option value="operator-tes">Operator Tes</option>
            </select>
            <button type="submit">Daftar</button>
        </form>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>

        <div class="footer">
            <p>&copy; 2025 Sekolah Pintar. Semua hak dilindungi undang-undang.</p>
        </div>
    </div>
</body>
</html>
