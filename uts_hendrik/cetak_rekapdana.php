<?php
session_start();

// Cek login
if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// Data dummy pembayaran
$data_pembayaran = [
  [
    'nama' => 'Andi Pratama',
    'jumlah' => 1000000,
    'status' => 'Lunas'
  ],
  [
    'nama' => 'Sari Melati',
    'jumlah' => 800000,
    'status' => 'Menunggu'
  ],
  [
    'nama' => 'Budi Hartono',
    'jumlah' => 1200000,
    'status' => 'Lunas'
  ]
];

// Tentukan akses berdasarkan role
$is_kepala_pmb = $role === 'kepala-pmb';
$is_keuangan = $role === 'keuangan';
$is_pimpinan = $role === 'pimpinan';
$is_pmb = $role === 'petugas-pmb';
$is_operator_tes = $role === 'operator-tes';

// Akses hanya untuk kepala pmb, keuangan, pimpinan
$full_access = ($is_kepala_pmb || $is_keuangan || $is_pimpinan) ? "<span class='badge badge-access'>Full Access</span>" : "<span class='badge badge-view'>View Only</span>";

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Rekap Dana</title>
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
    .btn-print {
      background-color: #3498db;
      color: white;
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

  <h2>Cetak Rekap Dana Pembayaran</h2>
  <p>Halo, <b><?= $nama ?></b>! Role Anda: <b><?= $role ?></b></p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jumlah Pembayaran</th>
        <th>Status</th>
        <th>Akses</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Filter data pembayaran dengan status Lunas
      $lunas = array_filter($data_pembayaran, function($d) {
        return $d['status'] === 'Lunas';
      });

      // Hitung total pembayaran Lunas
      $total = array_sum(array_column($lunas, 'jumlah'));
      
      foreach ($data_pembayaran as $index => $row):
      ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= $row['nama'] ?></td>
          <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
          <td><?= $row['status'] ?></td>
          <td><?= $full_access ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <hr>
  <h3>Total Pembayaran Lunas: Rp <?= number_format($total, 0, ',', '.') ?></h3>

  <?php if ($is_kepala_pmb || $is_keuangan || $is_pimpinan): ?>
    <div>
      <button class="btn btn-print" onclick="window.print()">Cetak Rekap</button>
    </div>
  <?php endif; ?>

  <br>
  <a href="dashboard.php" class="btn">Kembali</a>

</body>
</html>
