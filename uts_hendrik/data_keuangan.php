<?php
session_start();

if (!isset($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

$is_keuangan = ($_SESSION['role'] === 'keuangan');

$host = "localhost";
$user = "root";
$pass = "";
$db = "db_sekolah";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_pembayaran']) && $is_keuangan) {
  $nama_peserta = $conn->real_escape_string($_POST['nama_peserta']);
  $id_pendaftaran = $_POST['id_pendaftaran'];
  $jumlah_pembayaran = $_POST['jumlah_pembayaran'];

  $status = $jumlah_pembayaran >= 1500000 ? 'Lunas' : 'Belum Lunas';
  $tanggal = $status == 'Lunas' ? date('Y-m-d') : NULL;

  $sql_insert = "INSERT INTO pembayaran 
  (nama_peserta, id_pendaftaran, status_pembayaran, tanggal_pembayaran, jumlah_pembayaran) 
  VALUES ('$nama_peserta', '$id_pendaftaran', '$status', " . ($tanggal ? "'$tanggal'" : "NULL") . ", '$jumlah_pembayaran')";
  $conn->query($sql_insert);
}

// Verifikasi pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifikasi']) && $is_keuangan) {
  $id = $_POST['id'];
  $tanggal = date('Y-m-d');
  $sql_verifikasi = "UPDATE pembayaran 
                     SET status_pembayaran = 'Lunas', tanggal_pembayaran = '$tanggal' 
                     WHERE id = '$id'";
  $conn->query($sql_verifikasi);
}

// Hapus pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus']) && $is_keuangan) {
  $id = $_POST['id'];
  $conn->query("DELETE FROM pembayaran WHERE id = '$id'");
}

$sql = "SELECT * FROM pembayaran";
$result = $conn->query($sql);

$total_lunas = 0;
$total_belum_lunas = 0;
$data = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    if ($row['status_pembayaran'] == 'Lunas') {
      $total_lunas += $row['jumlah_pembayaran'];
    } else {
      $total_belum_lunas += $row['jumlah_pembayaran'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keuangan PMB</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .header {
      background-color:rgb(197, 230, 13);
      color: white;
      padding: 15px;
      text-align: center;
    }
    .container {
      margin: 20px;
    }
    .section {
      background: white;
      padding: 20px;
      margin-bottom: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color:rgb(36, 187, 129);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color:rgb(215, 230, 16);
      color: white;
    }
    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 8px;
      margin: 5px 0 15px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #f39c12;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #e67e22;
    }
    .btn-dashboard {
      display: inline-block;
      margin-bottom: 20px;
      text-decoration: none;
      background-color: #555;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
    }
    .btn-dashboard:hover {
      background-color: #333;
    }
    form.inline {
      display: inline;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Dashboard Keuangan PMB</h1>
  </div>

  <div class="container">

    <!-- Tombol kembali -->
    <a href="dashboard.php" class="btn-dashboard">← Kembali ke Dashboard</a>

    <!-- Monitoring Pembayaran -->
    <div class="section">
      <h2>Monitoring Pembayaran</h2>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Peserta</th>
            <th>ID Pendaftaran</th>
            <th>Status Pembayaran</th>
            <th>Tanggal Pembayaran</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data)): $no = 1; foreach ($data as $row): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
              <td><?= htmlspecialchars($row['id_pendaftaran']) ?></td>
              <td><?= $row['status_pembayaran'] ?></td>
              <td><?= $row['tanggal_pembayaran'] ?: '-' ?></td>
              <td>Rp <?= number_format($row['jumlah_pembayaran'], 0, ',', '.') ?></td>
              <td>
                <?php if ($row['status_pembayaran'] !== 'Lunas' && $is_keuangan): ?>
                  <form method="POST" class="inline">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="verifikasi">Verifikasi</button>
                  </form>
                <?php elseif ($row['status_pembayaran'] === 'Lunas'): ?>
                  <span style="color:green;">✔</span>
                <?php else: ?>
                  <span style="color:gray;">(Belum Lunas)</span>
                <?php endif; ?>

                <?php if ($is_keuangan): ?>
                  <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="hapus" style="background-color:#e74c3c;">Hapus</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="7">Belum ada data pembayaran.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Rekapitulasi -->
    <div class="section">
      <h2>Rekapitulasi Pembayaran</h2>
      <p>Total Pembayaran <b>Lunas</b>: <b style="color:green">Rp <?= number_format($total_lunas, 0, ',', '.') ?></b></p>
      <p>Total Pembayaran <b>Belum Lunas</b>: <b style="color:red">Rp <?= number_format($total_belum_lunas, 0, ',', '.') ?></b></p>
    </div>

    <!-- Tambah Pembayaran -->
    <?php if ($is_keuangan): ?>
      <div class="section">
        <h2>Tambah Pembayaran Manual</h2>
        <form method="POST">
          <input type="hidden" name="tambah_pembayaran" value="1">

          <label>Nama Peserta</label>
          <input type="text" name="nama_peserta" required>

          <label>ID Pendaftaran</label>
          <input type="text" name="id_pendaftaran" required>

          <label>Jumlah Pembayaran</label>
          <input type="number" name="jumlah_pembayaran" required>

          <button type="submit">Tambah Pembayaran</button>
        </form>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>