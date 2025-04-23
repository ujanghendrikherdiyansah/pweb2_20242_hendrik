<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit;
}

// Ambil role dari session
$role = $_SESSION['role'];

// Koneksi ke database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'db_sekolah';

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika sedang dalam mode edit
$edit_mode = false;
$data_edit = null;
if ($role === 'sarana' && isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = $_GET['edit'];
    $result_edit = $conn->query("SELECT * FROM sarana WHERE id = $id_edit");
    if ($result_edit && $result_edit->num_rows > 0) {
        $data_edit = $result_edit->fetch_assoc();
    }
}

// Menambah sarana hanya jika role sarana
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $role === 'sarana') {
    $nama_sarana = $_POST['nama_sarana'];
    $jumlah_sarana = $_POST['jumlah_sarana'];
    $keterangan_sarana = $_POST['keterangan_sarana'];

    $sql_insert = "INSERT INTO sarana (nama_sarana, jumlah_sarana, keterangan_sarana) 
                   VALUES ('$nama_sarana', $jumlah_sarana, '$keterangan_sarana')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>alert('Data sarana berhasil ditambahkan'); window.location.href='data_sarana.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Memperbarui data sarana
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']) && $role === 'sarana') {
    $id = $_POST['id'];
    $nama_sarana = $_POST['nama_sarana'];
    $jumlah_sarana = $_POST['jumlah_sarana'];
    $keterangan_sarana = $_POST['keterangan_sarana'];

    $sql_update = "UPDATE sarana SET nama_sarana='$nama_sarana', jumlah_sarana=$jumlah_sarana, keterangan_sarana='$keterangan_sarana' WHERE id=$id";
    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Data sarana berhasil diperbarui'); window.location.href='data_sarana.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Menghapus data hanya jika role sarana
if ($role === 'sarana' && isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql_delete = "DELETE FROM sarana WHERE id = $id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Data sarana berhasil dihapus'); window.location.href = 'data_sarana.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Ambil data sarana
$sql = "SELECT * FROM sarana";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Sarana Kampus</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ecf8f4; /* Warna latar belakang lembut */
        }

        .header {
            background-color:rgb(0, 0, 0); /* Hijau khas untuk sarana */
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .btn-back {
            position: absolute;
            left: 20px;
            top: 20px;
            background-color: #e74c3c;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            background-color: #c0392b;
        }

        .container {
            margin: 30px;
        }

        .section {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            margin-bottom: 15px;
            font-size: 20px;
            color:rgb(154, 174, 39); /* Warna hijau untuk sarana */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: center;
        }

        th {
            background-color:rgb(238, 218, 41); /* Hijau untuk header tabel */
            color: white;
        }

        button {
            background-color: #2980b9;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1abc9c;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .aksi button {
            margin: 2px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    <h1>Data Sarana Kampus</h1>
</div>

<div class="container">

    <!-- Form Tambah atau Edit Sarana -->
    <?php if ($role === 'sarana'): ?>
    <div class="section">
        <h2><?= $edit_mode ? 'Edit' : 'Tambah' ?> Sarana Kampus</h2>
        <form method="POST">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $data_edit['id'] ?>">
            <?php endif; ?>
            <label for="nama-sarana">Nama Sarana</label>
            <input type="text" name="nama_sarana" id="nama-sarana" required value="<?= $edit_mode ? htmlspecialchars($data_edit['nama_sarana']) : '' ?>">

            <label for="jumlah-sarana">Jumlah Sarana</label>
            <input type="number" name="jumlah_sarana" id="jumlah-sarana" required value="<?= $edit_mode ? $data_edit['jumlah_sarana'] : '' ?>">

            <label for="keterangan-sarana">Keterangan</label>
            <input type="text" name="keterangan_sarana" id="keterangan-sarana" value="<?= $edit_mode ? htmlspecialchars($data_edit['keterangan_sarana']) : '' ?>">

            <button type="submit" name="<?= $edit_mode ? 'update' : 'submit' ?>">
                <?= $edit_mode ? 'Simpan Perubahan' : 'Tambah Sarana' ?>
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Daftar Sarana -->
    <div class="section">
        <h2>Daftar Sarana</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Sarana</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <?php if ($role === 'sarana'): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_sarana']) ?></td>
                        <td><?= $row['jumlah_sarana'] ?></td>
                        <td><?= htmlspecialchars($row['keterangan_sarana']) ?: '-' ?></td>
                        <?php if ($role === 'sarana'): ?>
                        <td class="aksi">
                            <a href="?edit=<?= $row['id'] ?>"><button>Edit</button></a>
                            <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><button>Hapus</button></a>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="<?= $role === 'sarana' ? 5 : 4 ?>">Belum ada data sarana.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

<?php
$conn->close();
?>