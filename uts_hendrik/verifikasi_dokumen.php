<?php
session_start();

// Simulasi login (hapus saat integrasi login sesungguhnya)
// $_SESSION['role'] = 'petugas-pmb';
// $_SESSION['nama'] = 'Pak Budi';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// Hanya petugas-pmb & kepala-pmb bisa memverifikasi
$can_verify = in_array($role, ['petugas-pmb', 'kepala-pmb']);

// Data dummy (gunakan session untuk menyimpan simulasi data)
if (!isset($_SESSION['dokumen'])) {
  $_SESSION['dokumen'] = [
    ['nama' => 'Andi Pratama', 'dokumen' => 'KTP.pdf', 'status' => 'Belum Diverifikasi'],
    ['nama' => 'Sari Melati', 'dokumen' => 'Ijazah.pdf', 'status' => 'Terverifikasi'],
    ['nama' => 'Budi Hartono', 'dokumen' => 'KK.pdf', 'status' => 'Belum Diverifikasi'],
  ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_verify) {
  $index = $_POST['index'];
  $status_baru = $_POST['status'];
  if (isset($_SESSION['dokumen'][$index])) {
    $_SESSION['dokumen'][$index]['status'] = $status_baru;
    $message = "Status dokumen berhasil diperbarui.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Verifikasi Dokumen</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f0f0;
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
    .btn-verifikasi {
      background-color: #2ecc71;
      color: white;
    }
    .btn-kembali {
      background-color: #7f8c8d;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      display: inline-block;
      margin-bottom: 20px;
      border-radius: 6px;
    }
    .message {
      padding: 10px;
      background-color: #dff0d8;
      color: #3c763d;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #3c763d;
    }
  </style>
</head>
<body>

  <h2>Verifikasi Dokumen Pendaftaran</h2>
  <p>Halo <b><?= htmlspecialchars($nama) ?></b>, Role: <b><?= htmlspecialchars($role) ?></b></p>

  <a href="dashboard.php" class="btn-kembali">← Kembali</a>

  <?php if (!empty($message)): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Pendaftar</th>
        <th>Nama Dokumen</th>
        <th>Status Verifikasi</th>
        <?php if ($can_verify): ?>
          <th>Aksi</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($_SESSION['dokumen'] as $index => $d): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($d['nama']) ?></td>
          <td><?= htmlspecialchars($d['dokumen']) ?></td>
          <td><?= htmlspecialchars($d['status']) ?></td>
          <?php if ($can_verify): ?>
            <td>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="index" value="<?= $index ?>">
                <select name="status">
                  <option value="Belum Diverifikasi" <?= $d['status'] === 'Belum Diverifikasi' ? 'selected' : '' ?>>Belum Diverifikasi</option>
                  <option value="Terverifikasi" <?= $d['status'] === 'Terverifikasi' ? 'selected' : '' ?>>Terverifikasi</option>
                </select>
                <button type="submit" class="btn btn-verifikasi">Simpan</button>
              </form>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btn-kembali" style="margin-top: 20px;">← Kembali</a>

</body>
</html>
