<?php
session_start();

// Simulasi login (hapus saat integrasi login nyata)
// $_SESSION['role'] = 'petugas-pmb';
// $_SESSION['nama'] = 'Pak Budi';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// Role dengan hak akses penuh
$can_edit = in_array($role, ['petugas-pmb', 'kepala-pmb']);

// Data dummy jadwal tes (gunakan database atau session untuk penyimpanan asli)
if (!isset($_SESSION['jadwal_tes'])) {
  $_SESSION['jadwal_tes'] = [
    ['tanggal' => '2025-05-10', 'waktu' => '09:00', 'lokasi' => 'Aula 1'],
    ['tanggal' => '2025-05-12', 'waktu' => '13:00', 'lokasi' => 'Ruang Lab']
  ];
}

// Tambah jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_edit) {
  $tanggal = $_POST['tanggal'] ?? '';
  $waktu = $_POST['waktu'] ?? '';
  $lokasi = $_POST['lokasi'] ?? '';

  if ($tanggal && $waktu && $lokasi) {
    $_SESSION['jadwal_tes'][] = ['tanggal' => $tanggal, 'waktu' => $waktu, 'lokasi' => $lokasi];
    $message = "Jadwal berhasil ditambahkan.";
  } else {
    $error = "Semua field harus diisi.";
  }
}

// Hapus jadwal
if (isset($_GET['hapus']) && $can_edit) {
  $index = (int) $_GET['hapus'];
  if (isset($_SESSION['jadwal_tes'][$index])) {
    unset($_SESSION['jadwal_tes'][$index]);
    $_SESSION['jadwal_tes'] = array_values($_SESSION['jadwal_tes']); // reset index
    $message = "Jadwal berhasil dihapus.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Tes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background: #f8f8f8;
    }
    h2 { color: #333; }
    table {
      border-collapse: collapse;
      width: 100%;
      background: #fff;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th { background-color: #f7c600; }
    form {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      margin-top: 20px;
      max-width: 500px;
    }
    input {
      padding: 8px;
      width: 100%;
      margin-bottom: 10px;
    }
    button {
      padding: 8px 14px;
      background: #3498db;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn-delete {
      background: #e74c3c;
      padding: 6px 10px;
      font-size: 0.9em;
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
    .message, .error {
      margin-top: 10px;
      padding: 10px;
      border-radius: 6px;
    }
    .message { background-color: #dff0d8; color: #3c763d; }
    .error { background-color: #f2dede; color: #a94442; }
  </style>
</head>
<body>

  <h2>Jadwal Tes Penerimaan</h2>
  <p>Halo, <b><?= htmlspecialchars($nama) ?></b>! Role Anda: <b><?= htmlspecialchars($role) ?></b></p>
  <a href="dashboard.php" class="btn-kembali">← Kembali</a>

  <?php if (!empty($message)): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Lokasi</th>
        <?php if ($can_edit): ?>
          <th>Aksi</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($_SESSION['jadwal_tes'] as $i => $jadwal): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($jadwal['tanggal']) ?></td>
          <td><?= htmlspecialchars($jadwal['waktu']) ?></td>
          <td><?= htmlspecialchars($jadwal['lokasi']) ?></td>
          <?php if ($can_edit): ?>
            <td><a href="?hapus=<?= $i ?>" onclick="return confirm('Hapus jadwal ini?')">
              <button class="btn-delete">Hapus</button>
            </a></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if ($can_edit): ?>
    <form method="post">
      <h3>Tambah Jadwal Tes</h3>
      <label>Tanggal:</label>
      <input type="date" name="tanggal" required>
      <label>Waktu:</label>
      <input type="time" name="waktu" required>
      <label>Lokasi:</label>
      <input type="text" name="lokasi" required>
      <button type="submit">Tambah Jadwal</button>
    </form>
  <?php endif; ?>

</body>
</html>
