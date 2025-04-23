<?php
session_start();

// Cek login
if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// Role yang memiliki akses penuh
$is_pmb = in_array($role, ['petugas-pmb', 'kepala-pmb', 'pimpinan', 'operator-tes']);
$is_admin = $role === 'admin-system';

// Data dummy pendaftar
$data_pendaftar = [
  [
    'nama' => 'Andi Pratama',
    'asal_sekolah' => 'SMAN 1 Bandung',
    'jurusan' => 'TKJ',
    'tahun_lulus' => 2023,
    'no_wa' => '081234567890',
    'status' => 'Menunggu'
  ],
  [
    'nama' => 'Sari Melati',
    'asal_sekolah' => 'SMK Negeri 2 Cimahi',
    'jurusan' => 'AKL',
    'tahun_lulus' => 2022,
    'no_wa' => '082112223334',
    'status' => 'Diterima'
  ],
  [
    'nama' => 'Budi Hartono',
    'asal_sekolah' => 'SMAN 3 Garut',
    'jurusan' => 'RPL',
    'tahun_lulus' => 2021,
    'no_wa' => '085766665555',
    'status' => 'Ditolak'
  ]
];

// Tentukan badge akses
$full_access_badge = "<span class='badge badge-access'>Full Access</span>";
$view_only_badge = "<span class='badge badge-view'>View Only</span>";
$access_badge = ($is_pmb || $is_admin) ? $full_access_badge : $view_only_badge;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pendaftaran</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      padding: 30px;
    }
    h2 {
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #f7c600;
    }
    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      font-size: 0.9em;
      cursor: pointer;
    }
    .btn-edit {
      background-color: #3498db;
      color: white;
    }
    .btn-delete {
      background-color: #e74c3c;
      color: white;
    }
    .btn-back {
      background-color: #7f8c8d;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 6px;
      display: inline-block;
      margin-top: 10px;
    }
    .btn-back:hover {
      background-color: #636e72;
    }
    .badge {
      padding: 4px 8px;
      font-size: 0.8em;
      border-radius: 6px;
      margin-left: 8px;
    }
    .badge-access {
      background-color: #27ae60;
      color: #fff;
    }
    .badge-view {
      background-color: #bdc3c7;
      color: #2c3e50;
    }
  </style>
</head>
<body>

  <h2>Data Pendaftaran Mahasiswa Baru</h2>
  <a href="dashboard.php" class="btn btn-back">← Kembali ke Dashboard</a>
  <p>Halo, <b><?= htmlspecialchars($nama) ?></b>! Role Anda: <b><?= htmlspecialchars($role) ?></b></p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Asal Sekolah</th>
        <th>Jurusan Pilihan</th>
        <th>Tahun Lulus</th>
        <th>No. WA</th>
        <th>Status</th>
        <th>Akses</th>
        <?php if ($is_pmb || $is_admin): ?>
          <th>Aksi</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data_pendaftar as $index => $row): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
          <td><?= htmlspecialchars($row['jurusan']) ?></td>
          <td><?= htmlspecialchars($row['tahun_lulus']) ?></td>
          <td><?= htmlspecialchars($row['no_wa']) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= $access_badge ?></td>
          <?php if ($is_pmb || $is_admin): ?>
            <td>
              <button class="btn btn-edit">Edit</button>
              <button class="btn btn-delete">Hapus</button>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</body>
</html>
