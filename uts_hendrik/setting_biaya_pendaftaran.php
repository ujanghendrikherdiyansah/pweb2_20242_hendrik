<?php
session_start();

// Simulasi login (hapus saat integrasi dengan login real)
// $_SESSION['role'] = 'kepala-pmb';
// $_SESSION['nama'] = 'Bu Lilis';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// Hanya kepala-pmb dan keuangan yang boleh ubah biaya
$can_edit = in_array($role, ['kepala-pmb', 'keuangan']);

// Simpan sementara di session (bisa diganti database nanti)
if (!isset($_SESSION['biaya_pendaftaran'])) {
  $_SESSION['biaya_pendaftaran'] = 150000; // default
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_edit) {
  $biaya_baru = intval($_POST['biaya']);
  if ($biaya_baru > 0) {
    $_SESSION['biaya_pendaftaran'] = $biaya_baru;
    $message = "Biaya pendaftaran berhasil diperbarui.";
  } else {
    $error = "Masukkan angka yang valid.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan Biaya Pendaftaran</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f0f0;
      padding: 30px;
    }
    h2 {
      color: #333;
    }
    form {
      margin-top: 20px;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      max-width: 400px;
    }
    input[type="number"] {
      padding: 8px;
      width: 100%;
      margin-bottom: 12px;
      font-size: 1em;
    }
    button {
      background-color: #3498db;
      color: #fff;
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .readonly {
      background-color: #eee;
      color: #777;
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
      background-color: #dff0d8;
      color: #3c763d;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #3c763d;
    }
    .error {
      background-color: #f2dede;
      color: #a94442;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #a94442;
    }
  </style>
</head>
<body>

  <h2>Pengaturan Biaya Pendaftaran</h2>
  <p>Halo, <b><?= htmlspecialchars($nama) ?></b>! Role Anda: <b><?= htmlspecialchars($role) ?></b></p>

  <a href="dashboard.php" class="btn-kembali">← Kembali</a>

  <?php if (!empty($message)): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <label for="biaya">Biaya Pendaftaran (Rp):</label>
    <input type="number" id="biaya" name="biaya"
           value="<?= $_SESSION['biaya_pendaftaran'] ?>"
           <?= $can_edit ? '' : 'readonly class="readonly"' ?>>
    <?php if ($can_edit): ?>
      <button type="submit">Simpan</button>
    <?php endif; ?>
  </form>

</body>
</html>
