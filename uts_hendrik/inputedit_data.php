<?php
session_start();

// Simulasi login (hapus saat integrasi login sebenarnya)
// $_SESSION['role'] = 'petugas-pmb';
// $_SESSION['nama'] = 'Budi';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

$can_edit = in_array($role, ['petugas-pmb', 'kepala-pmb']);

$success_message = '';
$error_message = '';

// Simulasi penyimpanan data ke session
if (!isset($_SESSION['pendaftar'])) {
  $_SESSION['pendaftar'] = [];
}

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_edit) {
  $new_data = [
    'nama' => $_POST['nama'],
    'asal_sekolah' => $_POST['asal_sekolah'],
    'jurusan' => $_POST['jurusan'],
    'tahun_lulus' => $_POST['tahun_lulus'],
    'no_wa' => $_POST['no_wa'],
    'status' => $_POST['status']
  ];

  if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
    $_SESSION['pendaftar'][$_POST['edit_index']] = $new_data;
    $success_message = "Data berhasil diubah.";
  } else {
    $_SESSION['pendaftar'][] = $new_data;
    $success_message = "Data berhasil ditambahkan.";
  }
}

$edit_data = null;
$edit_index = null;
if ($can_edit && isset($_GET['edit'])) {
  $edit_index = $_GET['edit'];
  if (isset($_SESSION['pendaftar'][$edit_index])) {
    $edit_data = $_SESSION['pendaftar'][$edit_index];
  } else {
    $error_message = "Data tidak ditemukan.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input/Edit Pendaftaran</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      padding: 30px;
    }
    h2 {
      color: #333;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      background-color: #3498db;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      margin-bottom: 20px;
      text-decoration: none;
      display: inline-block;
    }
    .btn:disabled {
      background-color: #aaa;
    }
    .btn-kembali {
      background-color: #7f8c8d;
    }
    form {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
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
    .message {
      padding: 10px;
      background-color: #dff0d8;
      border: 1px solid #3c763d;
      color: #3c763d;
      margin-bottom: 20px;
      border-radius: 6px;
    }
  </style>
</head>
<body>

  <h2>Form Pendaftaran Calon Mahasiswa</h2>
  <p>Halo <b><?= htmlspecialchars($nama) ?></b>, Role: <b><?= htmlspecialchars($role) ?></b></p>

  <a href="dashboard.php" class="btn btn-kembali">← Kembali</a>

  <?php if ($success_message): ?>
    <div class="message"><?= $success_message ?></div>
  <?php elseif ($error_message): ?>
    <div class="message" style="background-color: #f2dede; border-color: #a94442; color: #a94442;"><?= $error_message ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>Nama Lengkap</label>
    <input type="text" name="nama" value="<?= $edit_data['nama'] ?? '' ?>" <?= $can_edit ? '' : 'disabled' ?> required>

    <label>Asal Sekolah</label>
    <input type="text" name="asal_sekolah" value="<?= $edit_data['asal_sekolah'] ?? '' ?>" <?= $can_edit ? '' : 'disabled' ?> required>

    <label>Jurusan Pilihan</label>
    <input type="text" name="jurusan" value="<?= $edit_data['jurusan'] ?? '' ?>" <?= $can_edit ? '' : 'disabled' ?> required>

    <label>Tahun Lulus</label>
    <input type="number" name="tahun_lulus" value="<?= $edit_data['tahun_lulus'] ?? '' ?>" <?= $can_edit ? '' : 'disabled' ?> required>

    <label>No. WhatsApp</label>
    <input type="text" name="no_wa" value="<?= $edit_data['no_wa'] ?? '' ?>" <?= $can_edit ? '' : 'disabled' ?> required>

    <label>Status</label>
    <select name="status" <?= $can_edit ? '' : 'disabled' ?>>
      <option <?= ($edit_data['status'] ?? '') === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
      <option <?= ($edit_data['status'] ?? '') === 'Diterima' ? 'selected' : '' ?>>Diterima</option>
      <option <?= ($edit_data['status'] ?? '') === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
    </select>

    <?php if ($can_edit): ?>
      <input type="hidden" name="edit_index" value="<?= htmlspecialchars($edit_index ?? '') ?>">
      <button type="submit" class="btn"><?= $edit_data ? 'Simpan Perubahan' : 'Tambah Data' ?></button>
    <?php endif; ?>
  </form>

  <h3>Daftar Pendaftar</h3>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Asal Sekolah</th>
        <th>Jurusan</th>
        <th>Tahun Lulus</th>
        <th>No. WA</th>
        <th>Status</th>
        <?php if ($can_edit): ?>
          <th>Aksi</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($_SESSION['pendaftar'] as $i => $p): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($p['nama']) ?></td>
          <td><?= htmlspecialchars($p['asal_sekolah']) ?></td>
          <td><?= htmlspecialchars($p['jurusan']) ?></td>
          <td><?= htmlspecialchars($p['tahun_lulus']) ?></td>
          <td><?= htmlspecialchars($p['no_wa']) ?></td>
          <td><?= htmlspecialchars($p['status']) ?></td>
          <?php if ($can_edit): ?>
            <td><a href="?edit=<?= $i ?>">Edit</a></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btn btn-kembali" style="margin-top: 20px;">← Kembali</a>

</body>
</html>
