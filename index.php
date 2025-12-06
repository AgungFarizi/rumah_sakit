<?php
require 'config.php';
cekLogin();

$pasien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pasien"))['t'];
$dokter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM dokter"))['t'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Rumah Sakit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    🏥 <b>RS SEHAT</b> &nbsp;&nbsp;|
    <a href="index.php">🏠 Dashboard</a>
    <a href="pasien.php">👥 Pasien</a>
    <a href="dokter.php">👨‍⚕️ Dokter</a>
    <a href="logout.php">🚪 Logout</a>
</nav>
<div class="container">
    <h2>📊 Dashboard</h2>
    <div class="stats">
        <div class="card">
            <div style="font-size:40px;">👥</div>
            <div style="font-size:35px; font-weight:bold;"><?=$pasien?></div>
            <div>Total Pasien</div>
        </div>
        <div class="card kuning">
            <div style="font-size:40px;">👨‍⚕️</div>
            <div style="font-size:35px; font-weight:bold;"><?=$dokter?></div>
            <div>Total Dokter</div>
        </div>
    </div>
    
    <h2 style="margin-top:30px;">📋 Pasien Terbaru</h2>
    <?php $recent = mysqli_query($conn, "SELECT p.*, d.nama as dokter FROM pasien p LEFT JOIN dokter d ON p.id_dokter=d.id ORDER BY p.id DESC LIMIT 5"); ?>
    <table>
        <tr><th>Nama</th><th>JK</th><th>Dokter</th></tr>
        <?php while($r = mysqli_fetch_assoc($recent)): ?>
        <tr>
            <td><?= $r['nama'] ?></td>
            <td><?= $r['jenis_kelamin']=='L'?'Laki-laki':'Perempuan' ?></td>
            <td><?= $r['dokter'] ?? '-' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>