<?php
session_start();

// Simulasi login (hapus saat sudah implementasi sistem login)
// $_SESSION['role'] = 'keuangan';
// $_SESSION['nama'] = 'Bu Dita';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
$can_verify = in_array($role, ['kepala-pmb', 'keuangan']);

// Data dummy bukti pembayaran
if (!isset($_SESSION['bukti_pembayaran'])) {
  $_SESSION['bukti_pembayaran'] = [
    ['nama' => 'Andi Pratama', 'jumlah' => 250000, 'bukti' => 'bukti1.jpg', 'status' => 'Menunggu Verifikasi'],
    ['nama' => 'Sari Melati', 'jumlah' => 250000, 'bukti' => 'bukti2.jpg', 'status' => 'Terverifikasi'],
  ];
}

// Proses verifikasi
if (isset($_GET['verifikasi']) && $can_verify) {
  $index = (int) $_GET['verifikasi'];
  if (isset($_SESSION['bukti_pembayaran'][$index])) {
    $_SESSION['bukti_pembayaran'][$index]['status'] = 'Terverifikasi';
    $message = "Bukti pembayaran berhasil diverifikasi.";
  }
}

// Hapus data
if (isset($_GET['hapus']) && $can_verify) {
  $index = (int) $_GET['hapus'];
  if (isset($_SESSION['bukti_pembayaran'][$index])) {
    unset($_SESSION['bukti_pembayaran'][$index]);
    $_SESSION['bukti_pembayaran'] = array_values($_SESSION['bukti_pembayaran']);
    $message = "Data bukti pembayaran dihapus.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Verifikasi Bukti Pembayaran</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      padding: 30px;
    }
    h2 { color: #333; }
    table {
      border-collapse: collapse;
      width: 100%;
      background: #fff;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th { background-color: #f7c600; }
    .btn {
      padding: 6px 10px;
      font-size: 0.9em;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      color: white;
    }
    .btn-verifikasi { background-color: #27ae60; }
    .btn-delete { background-color: #e74c3c; }
    .btn-kembali {
      background-color: #7f8c8d;
      text-decoration: none;
      padding: 8px 16px;
      color: white;
      display: inline-block;
      margin-bottom: 20px;
      border-radius: 6px;
    }
    .message {
      background: #d4edda;
      padding: 10px;
      color: #155724;
      margin-bottom: 10px;
      border-radius: 6px;
    }
  </style>
</head>
<body>

  <h2>Verifikasi Bukti Pembayaran</h2>
  <p>Halo, <b><?= htmlspecialchars($nama) ?></b>! Role Anda: <b><?= htmlspecialchars($role) ?></b></p>
  <a href="dashboard.php" class="btn-kembali">← Kembali</a>

  <?php if (!empty($message)): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jumlah</th>
        <th>Bukti</th>
        <th>Status</th>
        <?php if ($can_verify): ?>
          <th>Aksi</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($_SESSION['bukti_pembayaran'] as $i => $data): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($data['nama']) ?></td>
          <td>Rp<?= number_format($data['jumlah'], 0, ',', '.') ?></td>
          <td>
            <a href="uploads/<?= htmlspecialchars($data['bukti']) ?>" target="_blank">
              Lihat Bukti
            </a>
          </td>
          <td><?= htmlspecialchars($data['status']) ?></td>
          <?php if ($can_verify): ?>
            <td>
              <?php if ($data['status'] !== 'Terverifikasi'): ?>
                <a href="?verifikasi=<?= $i ?>" onclick="return confirm('Verifikasi bukti pembayaran ini?')">
                  <button class="btn btn-verifikasi">Verifikasi</button>
                </a>
              <?php endif; ?>
              <a href="?hapus=<?= $i ?>" onclick="return confirm('Hapus data ini?')">
                <button class="btn btn-delete">Hapus</button>
              </a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</body>
</html>
