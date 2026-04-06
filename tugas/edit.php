<?php 
include '../config.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tugas WHERE id='$id'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Tugas</h2>
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <input type="hidden" name="file_lama" value="<?= $data['file_panduan'] ?>">

            <label>Judul Tugas</label>
            <input type="text" name="judul_tugas" value="<?= $data['judul_tugas'] ?>" required>

            <label>Pilih Kelas</label>
            <select name="id_kelas" required>
                <?php
                $kelas = mysqli_query($conn, "SELECT * FROM kelas");
                while($k = mysqli_fetch_assoc($kelas)) {
                    $selected = ($k['id'] == $data['id_kelas']) ? 'selected' : '';
                    echo "<option value='".$k['id']."' $selected>".$k['nama_kelas']."</option>";
                }
                ?>
            </select>

            <label>Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="4" required><?= $data['deskripsi'] ?></textarea>

            <label>Tenggat Waktu</label>
            <?php 
                $dt = date('Y-m-d\TH:i', strtotime($data['tenggat_waktu'])); 
            ?>
            <input type="datetime-local" name="tenggat_waktu" value="<?= $dt ?>" required>

            <label>Ganti File Panduan (Biarkan kosong jika tidak ingin mengganti)</label>
            <?php if($data['file_panduan']) echo "<small>File saat ini: ".$data['file_panduan']."</small><br>"; ?>
            <input type="file" name="file_panduan">

            <button type="submit" class="btn btn-add">Update Tugas</button>
            <a href="index.php" class="btn" style="background:#555;">Batal</a>
        </form>
    </div>
</body>
</html>