<?php
require 'config.php';
cekLogin();

if ($_POST) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $sp = mysqli_real_escape_string($conn, $_POST['spesialis']);
    
    if ($_POST['id']) {
        mysqli_query($conn, "UPDATE dokter SET nama='$nama', spesialis='$sp' WHERE id=".$_POST['id']);
        $sukses = "Data berhasil diupdate!";
    } else {
        mysqli_query($conn, "INSERT INTO dokter (nama, spesialis) VALUES ('$nama','$sp')");
        $sukses = "Data berhasil ditambahkan!";
    }
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM dokter WHERE id=".$_GET['hapus']);
    header('Location: dokter.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dokter WHERE id=".$_GET['edit']));
}

$data = mysqli_query($conn, "SELECT * FROM dokter ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Dokter - Rumah Sakit</title>
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
    
    <?php if(isset($sukses)) echo "<p class='success'>✅ $sukses</p>"; ?>
    
    <h2><?= $edit ? '✏️ Edit Dokter' : '➕ Tambah Dokter' ?></h2>
    <form method="POST" class="form-box">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <input type="text" name="nama" placeholder="Nama Dokter (cth: Dr. Ahmad)" required value="<?= $edit['nama'] ?? '' ?>">
        <select name="spesialis" required>
            <option value="">-- Pilih Spesialis --</option>
            <option value="Umum" <?= ($edit['spesialis']??'')=='Umum'?'selected':'' ?>>Umum</option>
            <option value="Anak" <?= ($edit['spesialis']??'')=='Anak'?'selected':'' ?>>Anak</option>
            <option value="Bedah" <?= ($edit['spesialis']??'')=='Bedah'?'selected':'' ?>>Bedah</option>
            <option value="Kandungan" <?= ($edit['spesialis']??'')=='Kandungan'?'selected':'' ?>>Kandungan</option>
            <option value="Jantung" <?= ($edit['spesialis']??'')=='Jantung'?'selected':'' ?>>Jantung</option>
            <option value="Penyakit Dalam" <?= ($edit['spesialis']??'')=='Penyakit Dalam'?'selected':'' ?>>Penyakit Dalam</option>
        </select>
        <button type="submit">💾 Simpan</button>
        <?php if($edit): ?><a href="dokter.php" class="btn kuning">❌ Batal</a><?php endif; ?>
    </form>

    <h2>📋 Daftar Dokter</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Dokter</th>
            <th>Spesialis</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($r = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $r['nama'] ?></td>
            <td><?= $r['spesialis'] ?></td>
            <td class="aksi">
                <a href="?edit=<?= $r['id'] ?>" class="edit">✏️ Edit</a>
                <a href="?hapus=<?= $r['id'] ?>" class="hapus" onclick="return confirm('Yakin hapus data ini?')">🗑️ Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>