<?php
// Koneksi database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'db_sekolah';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Simpan konfigurasi umum
if (isset($_POST['simpan_konfigurasi'])) {
    $nama = $_POST['nama_institusi'];
    $tahun = $_POST['tahun_akademik'];
    $periode = $_POST['periode_pendaftaran'];

    $conn->query("DELETE FROM konfigurasi"); // hanya satu baris konfigurasi
    $sql = "INSERT INTO konfigurasi (nama_institusi, tahun_akademik, periode_pendaftaran)
            VALUES ('$nama', '$tahun', '$periode')";
    $conn->query($sql);
}

// Tambah program studi
if (isset($_POST['tambah_prodi'])) {
    $nama_prodi = $_POST['nama_prodi'];
    if (!empty($nama_prodi)) {
        $conn->query("INSERT INTO program_studi (nama_prodi) VALUES ('$nama_prodi')");
    }
}

// Ubah password admin
if (isset($_POST['ubah_password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("UPDATE admin SET password = '$password' WHERE username = 'admin'");
}

// Ambil data konfigurasi
$konfig = $conn->query("SELECT * FROM konfigurasi LIMIT 1")->fetch_assoc();
$prodi_list = $conn->query("SELECT * FROM program_studi");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfigurasi Sistem PMB</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .header { background-color:rgb(255, 8, 0); color: white; padding: 15px; text-align: center; }
        .container { margin: 20px; }
        .section { margin-bottom: 30px; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); border: 1px solid #d9534f; }
        .section h2 { margin: 0 0 10px; font-size: 18px; color: #d9534f; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, select, textarea, button {
            width: 100%; padding: 10px; margin-bottom: 10px;
            border: 1px solid #ddd; border-radius: 5px; font-size: 14px;
        }
        button {
            background-color:rgb(255, 13, 4); color: white; cursor: pointer; border: none;
        }
        button:hover { background-color:rgb(255, 8, 0); }
        ul { list-style-type: square; padding-left: 20px; }
    </style>
</head>
<body>
<div class="header">
    <h1>Konfigurasi Sistem PMB</h1>
</div>

<div class="container">

    <!-- Konfigurasi Umum -->
    <div class="section">
        <h2>Konfigurasi Umum</h2>
        <form method="POST">
            <label for="nama-institusi">Nama Institusi</label>
            <input type="text" name="nama_institusi" id="nama-institusi" placeholder="Masukkan Nama Institusi" value="<?= $konfig['nama_institusi'] ?? '' ?>" required>

            <label for="tahun-akademik">Tahun Akademik</label>
            <input type="text" name="tahun_akademik" id="tahun-akademik" placeholder="Contoh : 2025/2026" value="<?= $konfig['tahun_akademik'] ?? '' ?>" required>

            <label for="periode-pendaftaran">Periode Pendaftaran</label>
            <input type="text" name="periode_pendaftaran" id="periode-pendaftaran" placeholder="Contoh : Jan - Mar 2025" value="<?= $konfig['periode_pendaftaran'] ?? '' ?>" required>

            <button type="submit" name="simpan_konfigurasi">Simpan Konfigurasi</button>
        </form>
    </div>

    <!-- Konfigurasi Prodi -->
    <div class="section">
        <h2>Konfigurasi Program Studi</h2>
        <form method="POST">
            <label for="prodi">Tambah Program Studi</label>
            <input type="text" name="nama_prodi" id="prodi" placeholder="Nama Program Studi" required>
            <button type="submit" name="tambah_prodi">Tambah</button>
        </form>

        <h3>Daftar Program Studi</h3>
        <ul>
            <?php while ($row = $prodi_list->fetch_assoc()): ?>
                <li><?= htmlspecialchars($row['nama_prodi']) ?></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Konfigurasi Keamanan -->
    <div class="section">
        <h2>Konfigurasi Keamanan</h2>
        <form method="POST">
            <label for="password">Ubah Password Admin</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password baru" required>
            <button type="submit" name="ubah_password">Update Password</button>
        </form>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>