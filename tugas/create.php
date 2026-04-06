<?php include '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Buat Tugas Baru</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Buat Tugas Baru</h2>
        <form action="store.php" method="POST" enctype="multipart/form-data">
            
            <label>Judul Tugas</label>
            <input type="text" name="judul_tugas" required placeholder="Contoh: Analisis Video Klip">

            <label>Pilih Kelas</label>
            <select name="id_kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <?php
                $kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                while($k = mysqli_fetch_assoc($kelas)) {
                    echo "<option value='".$k['id']."'>".$k['nama_kelas']." - ".$k['kode_kelas']."</option>";
                }
                ?>
            </select>

            <label>Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="4" required></textarea>

            <label>Tenggat Waktu (Deadline)</label>
            <input type="datetime-local" name="tenggat_waktu" required>

            <label>File Panduan (PDF/DOCX - Opsional)</label>
            <input type="file" name="file_panduan">

            <button type="submit" class="btn btn-add">Simpan Tugas</button>
            <a href="index.php" class="btn" style="background:#555;">Batal</a>
        </form>
    </div>
</body>
</html>