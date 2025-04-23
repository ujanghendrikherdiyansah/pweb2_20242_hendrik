<?php
session_start();

$is_logged_in = isset($_SESSION['role']) && isset($_SESSION['nama']);

if ($is_logged_in) {
  $role = $_SESSION['role'];
  $nama = $_SESSION['nama'];

  $is_admin = $role == 'admin-system';
  $is_akademik = $role == 'akademik';
  $is_keuangan = $role == 'keuangan';
  $is_sarana = $role == 'sarana';

  // Role untuk akses Data Pendaftaran
  $is_pmb = $role == 'petugas-pmb' || $role == 'kepala-pmb' || $role == 'pimpinan' || $role == 'operator-tes';

  $konfigurasi_system = $is_admin ? "<li><a href='konfigurasi_system.php'><b style='color:#FF4C4C'>Konfigurasi System</b></a></li>" : '';

  $full_access = "<span class='badge badge-access'>Full Access</span>";
  $view_only = "<span class='badge badge-view'>View Only</span>";

  $mode_data_akademik = $is_admin || $is_akademik ? $full_access : $view_only;
  $mode_data_keuangan = $is_admin || $is_keuangan ? $full_access : $view_only;
  $mode_data_sarana   = $is_admin || $is_sarana   ? $full_access : $view_only;

  // Akses Jadwal PMB: Full Access hanya untuk admin-system
  $mode_jadwal_pmb = $is_admin ? $full_access : $view_only;

  // Akses Data Pendaftaran: Full Access untuk petugas-pmb, kepala-pmb, pimpinan, operator-tes
  $mode_data_pendaftaran = $is_pmb ? $full_access : $view_only;

  // Akses tambahan untuk halaman-halaman baru
  $mode_inputedit_data = $is_pmb || $is_admin ? $full_access : $view_only;
  $mode_verifikasi_dokumen = $is_pmb || $is_admin ? $full_access : $view_only;
  $mode_jadwal_tes = $is_pmb || $is_admin ? $full_access : $view_only;
  $mode_hasil_tes = $is_pmb || $is_admin ? $full_access : $view_only;
  $mode_setting_biaya_pendaftaran = $is_keuangan || $is_admin ? $full_access : $view_only;
  $mode_verifikasi_pembayaran = $is_keuangan || $is_admin ? $full_access : $view_only;
  $mode_cetak_rekapdana = $is_keuangan || $is_admin ? $full_access : $view_only;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Sekolah SMK</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('images/sekolah.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
    }
    .container {
      background-color: rgba(0, 0, 0, 0.55);
      margin: 60px auto;
      padding: 35px 40px;
      border-radius: 18px;
      max-width: 850px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    h1 {
      margin-top: 0;
      font-size: 2.5em;
      color: #f7c600;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
    }
    ul {
      list-style: none;
      padding: 0;
      margin: 20px 0;
    }
    ul li {
      margin: 15px 0;
    }
    a {
      color: #00ffd5;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
    .footer {
      text-align: center;
      margin-top: 50px;
      color: #ddd;
      font-size: 0.9em;
    }
    .logout, .login {
      margin-top: 20px;
    }
    .btn {
      background-color: #f1c40f;
      color: #000;
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .btn:hover {
      background-color: #f39c12;
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

  <div class="container">
    <h1>Selamat Datang di<br>Dashboard SMK</h1>

    <?php if ($is_logged_in): ?>
      <p>Halo, <b><?= $nama ?></b>! Role Anda: <b style="color:#ffd700"><?= $role ?></b></p>

      <div class="logout">
        <a href="logout.php" class="btn">Logout</a>
      </div>

      <hr>
      <h3>Data yang Bisa Anda Akses</h3>
      <ul>
        <?= $konfigurasi_system ?>
        <li><a href='data_akademik.php'>Data Akademik <?= $mode_data_akademik ?></a></li>
        <li><a href='data_keuangan.php'>Data Keuangan <?= $mode_data_keuangan ?></a></li>
        <li><a href='data_sarana.php'>Data Sarana <?= $mode_data_sarana ?></a></li>
        <li><a href='jadwal_pmb.php'>Jadwal Penerimaan Mahasiswa Baru <?= $mode_jadwal_pmb ?></a></li>
        <li><a href='data_pendaftaran.php'>Data Pendaftaran <?= $mode_data_pendaftaran ?></a></li> <!-- Link Data Pendaftaran -->

        <!-- Tambahan Fitur Akses -->
        <li><a href='inputedit_data.php'>Input/Edit Data <?= $mode_inputedit_data ?></a></li>
        <li><a href='verifikasi_dokumen.php'>Verifikasi Dokumen <?= $mode_verifikasi_dokumen ?></a></li>
        <li><a href='jadwal_tes.php'>Jadwal Tes <?= $mode_jadwal_tes ?></a></li>
        <li><a href='hasil_tes.php'>Hasil Tes <?= $mode_hasil_tes ?></a></li>
        <li><a href='setting_biaya_pendaftaran.php'>Setting Biaya Pendaftaran <?= $mode_setting_biaya_pendaftaran ?></a></li>
        <li><a href='verifikasi_pembayaran.php'>Verifikasi Pembayaran <?= $mode_verifikasi_pembayaran ?></a></li>
        <li><a href='cetak_rekapdana.php'>Cetak Rekap Dana <?= $mode_cetak_rekapdana ?></a></li>
      </ul>

    <?php else: ?>
      <p>Anda belum login. Silakan klik tombol di bawah untuk masuk.</p>
      <div class="login">
        <a href="login.php" class="btn">Login</a>
      </div>
    <?php endif; ?>
  </div>

  <div class="footer">
    © 2025 Sekolah Pintar. Semua hak dilindungi undang-undang.
  </div>

</body>
</html>
