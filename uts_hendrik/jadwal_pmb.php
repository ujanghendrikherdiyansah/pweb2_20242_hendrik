<?php
session_start();

// Proteksi login
if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$is_admin = $role === 'admin-system';

// Contoh data jadwal - biasanya ini diambil dari database
$jadwal = [
    [
        'id' => 1,
        'tahap' => 'Pendaftaran Online',
        'mulai' => '01 Mei 2025',
        'selesai' => '30 Juni 2025'
    ],
    [
        'id' => 2,
        'tahap' => 'Seleksi Berkas',
        'mulai' => '01 Juli 2025',
        'selesai' => '05 Juli 2025'
    ],
    [
        'id' => 3,
        'tahap' => 'Tes Tulis',
        'mulai' => '10 Juli 2025',
        'selesai' => '10 Juli 2025'
    ],
    [
        'id' => 4,
        'tahap' => 'Pengumuman Hasil',
        'mulai' => '15 Juli 2025',
        'selesai' => '15 Juli 2025'
    ],
    [
        'id' => 5,
        'tahap' => 'Daftar Ulang',
        'mulai' => '16 Juli 2025',
        'selesai' => '25 Juli 2025'
    ],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jadwal PMB - Sekolah Pintar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: #00ffd5;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 30px;
        }
        h2 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-edit {
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div><strong>📘 Sekolah Pintar - Jadwal PMB</strong></div>
        <div>
            <a href="dashboard.php">Beranda</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>📅 Jadwal Penerimaan Mahasiswa Baru</h2>
        <p>Berikut adalah tahapan dan jadwal resmi untuk proses PMB tahun ini di <strong>Sekolah Pintar</strong>:</p>

        <table>
            <tr>
                <th>No</th>
                <th>Tahapan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($jadwal as $index => $item): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $item['tahap'] ?></td>
                <td><?= $item['mulai'] ?></td>
                <td><?= $item['selesai'] ?></td>
                <td>
                    <?php if ($is_admin): ?>
                        <a href="edit_jadwal.php?id=<?= $item['id'] ?>" class="btn-edit">Edit</a>
                    <?php else: ?>
                        <span style="color: gray; font-style: italic;">View Only</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>