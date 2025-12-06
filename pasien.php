<?php
require 'config.php';
cekLogin();

// CREATE & UPDATE
if ($_POST) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jk = $_POST['jenis_kelamin'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $id_dokter = $_POST['id_dokter'];
    
    if ($_POST['id']) {
        mysqli_query($conn, "UPDATE pasien SET nama='$nama', jenis_kelamin='$jk', alamat='$alamat', id_dokter=$id_dokter WHERE id=".$_POST['id']);
        $sukses = "Data berhasil diupdate!";
    } else {
        mysqli_query($conn, "INSERT INTO pasien (nama, jenis_kelamin, alamat, id_dokter) VALUES ('$nama','$jk','$alamat',$id_dokter)");
        $sukses = "Data berhasil ditambahkan!";
    }
}

// DELETE
if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM pasien WHERE id=".$_GET['hapus']);
    header('Location: pasien.php');
}

// EDIT
$edit = null;
if (isset($_GET['edit'])) {
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pasien WHERE id=".$_GET['edit']));
}

// EXPORT
if (isset($_GET['export'])) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_pasien.xls");
}

// SEARCH & FILTER
$where = "1=1";
if (!empty($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $where .= " AND p.nama LIKE '%$cari%'";
}
if (!empty($_GET['jk'])) {
    $where .= " AND p.jenis_kelamin='".$_GET['jk']."'";
}

$data = mysqli_query($conn, "SELECT p.*, d.nama as dokter FROM pasien p LEFT JOIN dokter d ON p.id_dokter=d.id WHERE $where ORDER BY p.id DESC");
$dokter_list = mysqli_query($conn, "SELECT * FROM dokter");

if (isset($_GET['export'])) {
    echo "<table border='1'><tr><th>No</th><th>Nama</th><th>Jenis Kelamin</th><th>Alamat</th><th>Dokter</th></tr>";
    $no=1; while($r = mysqli_fetch_assoc($data)) {
        $jk = $r['jenis_kelamin']=='L'?'Laki-laki':'Perempuan';
        echo "<tr><td>$no</td><td>{$r['nama']}</td><td>$jk</td><td>{$r['alamat']}</td><td>{$r['dokter']}</td></tr>";
        $no++;
    }
    echo "</table>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Pasien - Rumah Sakit</title>
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
    
    <h2><?= $edit ? '✏️ Edit Pasien' : '➕ Tambah Pasien' ?></h2>
    <form method="POST" class="form-box">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <input type="text" name="nama" placeholder="Nama Pasien" required value="<?= $edit['nama'] ?? '' ?>">
        <select name="jenis_kelamin" required>
            <option value="">-- Jenis Kelamin --</option>
            <option value="L" <?= ($edit['jenis_kelamin']??'')=='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= ($edit['jenis_kelamin']??'')=='P'?'selected':'' ?>>Perempuan</option>
        </select>
        <input type="text" name="alamat" placeholder="Alamat" value="<?= $edit['alamat'] ?? '' ?>">
        <select name="id_dokter" required>
            <option value="">-- Pilih Dokter --</option>
            <?php mysqli_data_seek($dokter_list,0); while($d = mysqli_fetch_assoc($dokter_list)): ?>
            <option value="<?=$d['id']?>" <?= ($edit['id_dokter']??'')==$d['id']?'selected':'' ?>><?=$d['nama']?> (<?=$d['spesialis']?>)</option>
            <?php endwhile; ?>
        </select>
        <button type="submit">💾 Simpan</button>
        <?php if($edit): ?><a href="pasien.php" class="btn kuning">❌ Batal</a><?php endif; ?>
    </form>

    <h2>📋 Daftar Pasien</h2>
    
    <form method="GET" class="filter-box">
        <input type="text" name="cari" placeholder="🔍 Cari nama..." value="<?= $_GET['cari'] ?? '' ?>">
        <select name="jk">
            <option value="">-- Semua JK --</option>
            <option value="L" <?= ($_GET['jk']??'')=='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= ($_GET['jk']??'')=='P'?'selected':'' ?>>Perempuan</option>
        </select>
        <button type="submit">🔍 Cari</button>
        <a href="pasien.php" class="btn kuning">🔄 Reset</a>
        <a href="?export=1&cari=<?=$_GET['cari']??''?>&jk=<?=$_GET['jk']??''?>" class="btn">📥 Export Excel</a>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Dokter</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($r = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $r['nama'] ?></td>
            <td><?= $r['jenis_kelamin']=='L'?'Laki-laki':'Perempuan' ?></td>
            <td><?= $r['alamat'] ?></td>
            <td><?= $r['dokter'] ?? '-' ?></td>
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