<?php
session_start();

// Simulasi login untuk testing (hapus jika sudah login beneran)
// $_SESSION['role'] = 'kepala-pmb';
// $_SESSION['nama'] = 'Pak Joko';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];

// Hanya kepala-pmb dan operator-tes yang punya full access
$can_edit = in_array($role, ['kepala-pmb', 'operator-tes']);

// Data dummy hasil tes (gunakan database nyata jika diperlukan)
if (!isset($_SESSION['hasil_tes'])) {
  $_SESSION['hasil_tes'] = [
    ['nama' => 'Andi Pratama', 'nilai' => 78, 'keterangan' => 'Lulus'],
    ['nama' => 'Sari Melati', 'nilai' => 65, 'keterangan' => 'Lulus'],
    ['nama' => 'Budi Hartono', 'nilai' => 45, 'keterangan' => 'Tidak Lulus'],
  ];
}

// Tambah hasil tes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_edit) {
  $nama_peserta = $_POST['nama'] ?? '';
  $nilai = $_POST['nilai'] ?? '';
  $keterangan = $_POST['keterangan'] ?? '';

  if ($nama_peserta && is_numeric($nilai) && $keterangan) {
    $_SESSION['hasil_tes'][] = [
      'nama' => $nama_peserta,
      'nilai' => $nilai,
      'keterangan' => $keterangan
    ];
    $message = "Data hasil tes berhasil ditambahkan.";
  } else {
    $error = "Semua field harus diisi dengan benar.";
  }
}

// Hapus data
if (isset($_GET['hapus']) && $can_edit) {
  $index = (int) $_GET['hapus'];
  if (isset($_SESSION['hasil_tes'][$index])) {
    unset($_SESSION['hasil_tes'][$index]);
    $_SESSION['hasil_tes'] = array_values($_SESSION['hasil_tes']);
    $message = "Data berhasil dihapus.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Tes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background: #f4f4f4;
    }
    h2 {
      color: #333;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      background: #fff;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }
    th {
      background-color: #f7c600;
    }
    form {
      margin-top: 20px;
      background: #fff;
      padding: 20px;
      max-width: 400px;
      border-radius: 8px;
    }
    input, select {
      padding: 8px;
      margin-bottom: 10px;
      width: 100%;
    }
    button {
      padding: 8px 12px;
      border: none;
      background: #3498db;
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
    .message { background-color: #d4edda; color: #155724; }
    .error { background-color: #f8d7da; color: #721c24; }
  </style>
</head>
<body>

  <h2>Hasil Tes Penerimaan Mahasiswa</h2>
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
        <th>Nama Peserta</th>
        <th>Nilai</th>
        <th>Keterangan</th>
        <?php if ($can_edit): ?>
          <th>Aksi</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($_SESSION['hasil_tes'] as $i => $hasil): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($hasil['nama']) ?></td>
          <td><?= htmlspecialchars($hasil['nilai']) ?></td>
          <td><?= htmlspecialchars($hasil['keterangan']) ?></td>
          <?php if ($can_edit): ?>
            <td>
              <a href="?hapus=<?= $i ?>" onclick="return confirm('Hapus data ini?')">
                <button class="btn-delete">Hapus</button>
              </a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if ($can_edit): ?>
    <form method="post">
      <h3>Tambah Hasil Tes</h3>
      <label>Nama Peserta:</label>
      <input type="text" name="nama" required>
      <label>Nilai:</label>
      <input type="number" name="nilai" required min="0" max="100">
      <label>Keterangan:</label>
      <select name="keterangan" required>
        <option value="">-- Pilih --</option>
        <option value="Lulus">Lulus</option>
        <option value="Tidak Lulus">Tidak Lulus</option>
      </select>
      <button type="submit">Simpan</button>
    </form>
  <?php endif; ?>

</body>
</html>
