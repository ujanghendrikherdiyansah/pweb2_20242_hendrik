<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$conn = new mysqli("localhost", "root", "", "db_sekolah");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $result = $conn->query("SELECT * FROM tb_akademik WHERE id = $id_edit");
    $edit_data = $result->fetch_assoc();
}

if (isset($_POST['tambah']) && $role === 'akademik') {
    $peserta = $_POST['peserta'];
    $kecamatan = $_POST['kecamatan'];
    $jenis_sekolah = $_POST['jenis_sekolah'];
    $prodi_pilihan = $_POST['prodi_pilihan'];
    $conn->query("INSERT INTO tb_akademik (peserta, kecamatan, jenis_sekolah, prodi_pilihan)
                  VALUES ('$peserta', '$kecamatan', '$jenis_sekolah', '$prodi_pilihan')");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['simpan_edit']) && $role === 'akademik') {
    $id = $_POST['id'];
    $peserta = $_POST['peserta'];
    $kecamatan = $_POST['kecamatan'];
    $jenis_sekolah = $_POST['jenis_sekolah'];
    $prodi_pilihan = $_POST['prodi_pilihan'];
    $conn->query("UPDATE tb_akademik SET peserta='$peserta', kecamatan='$kecamatan', jenis_sekolah='$jenis_sekolah', prodi_pilihan='$prodi_pilihan' WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['hapus']) && $role === 'akademik') {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM tb_akademik WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$data = $conn->query("SELECT * FROM tb_akademik");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data PMB</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color:rgb(64, 31, 231);
            color: #333;
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        input[type="text"] {
            width: 22%;
            padding: 10px;
            margin: 10px 1%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add {
            background-color: #007bff;
            color: white;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-dashboard {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
            display: inline-block;
        }

        .btn-dashboard:hover {
            background-color: #5a6268;
        }

        button:hover {
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
            font-weight: 600;
        }

        td .aksi-btns a {
            margin: 0 5px;
        }

        .disabled-btn {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            input[type="text"] {
                width: 48%;
                margin: 5px 1%;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <h1>Data Pendaftaran Mahasiswa Baru</h1>

    <div class="btn-container">
        <a href="dashboard.php" class="btn-dashboard">← Kembali ke Dashboard</a>

        <?php if ($role === 'akademik') : ?>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
                <input type="text" name="peserta" placeholder="Nama Peserta" required value="<?= $edit_data['peserta'] ?? '' ?>">
                <input type="text" name="kecamatan" placeholder="Kecamatan" required value="<?= $edit_data['kecamatan'] ?? '' ?>">
                <input type="text" name="jenis_sekolah" placeholder="Jenis Sekolah" required value="<?= $edit_data['jenis_sekolah'] ?? '' ?>">
                <input type="text" name="prodi_pilihan" placeholder="Prodi Pilihan" required value="<?= $edit_data['prodi_pilihan'] ?? '' ?>">
                <button type="submit" name="<?= $edit_data ? 'simpan_edit' : 'tambah' ?>" class="<?= $edit_data ? 'btn-edit' : 'btn-add' ?>">
                    <?= $edit_data ? 'Simpan Edit' : 'Tambah' ?>
                </button>
            </form>
        <?php else : ?>
            <p>Anda hanya memiliki hak akses untuk melihat data.</p>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peserta</th>
                    <th>Kecamatan</th>
                    <th>Jenis Sekolah</th>
                    <th>Prodi Pilihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $data->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['peserta'] ?></td>
                        <td><?= $row['kecamatan'] ?></td>
                        <td><?= $row['jenis_sekolah'] ?></td>
                        <td><?= $row['prodi_pilihan'] ?></td>
                        <td class="aksi-btns">
                            <?php if ($role === 'akademik') : ?>
                                <a href="?edit=<?= $row['id'] ?>"><button class="btn-edit">Edit</button></a>
                                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')"><button class="btn-delete">Hapus</button></a>
                            <?php else : ?>
                                <button class="btn-edit disabled-btn" disabled>Edit</button>
                                <button class="btn-delete disabled-btn" disabled>Hapus</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>