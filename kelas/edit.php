<?php 
include '../config.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kelas WHERE id='$id'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kelas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Kelas</h2>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <label>Kode Kelas</label>
            <input type="text" name="kode_kelas" value="<?= $data['kode_kelas'] ?>" required>

            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" value="<?= $data['nama_kelas'] ?>" required>

            <label>Dosen Pengajar</label>
            <select name="id_dosen">
                <option value="">-- Pilih Dosen --</option>
                <?php
                $dosen = mysqli_query($conn, "SELECT * FROM users WHERE role IN ('dosen', 'admin')");
                while($d = mysqli_fetch_assoc($dosen)) {
                    $selected = ($d['id'] == $data['id_dosen']) ? 'selected' : '';
                    echo "<option value='".$d['id']."' $selected>".$d['nama']."</option>";
                }
                ?>
            </select>

            <label>Semester</label>
            <input type="text" name="semester" value="<?= $data['semester'] ?>">

            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" value="<?= $data['tahun_ajaran'] ?>">

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"><?= $data['deskripsi'] ?></textarea>

            <button type="submit" class="btn btn-add">Update Data</button>
            <a href="index.php" class="btn" style="background:#555;">Batal</a>
        </form>
    </div>
</body>
</html>