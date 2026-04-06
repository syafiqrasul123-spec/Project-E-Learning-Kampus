<?php include '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kelas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Kelas Baru</h2>
        <form action="store.php" method="POST">
            <label>Kode Kelas</label>
            <input type="text" name="kode_kelas" required placeholder="Contoh: TRMM3A">

            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" required placeholder="Contoh: Multimedia Interaktif">

            <label>Dosen Pengajar</label>
            <select name="id_dosen">
                <option value="">-- Pilih Dosen --</option>
                <?php
                // Ambil list user yang role-nya dosen atau admin
                $dosen = mysqli_query($conn, "SELECT * FROM users WHERE role IN ('dosen', 'admin')");
                while($d = mysqli_fetch_assoc($dosen)) {
                    echo "<option value='".$d['id']."'>".$d['nama']."</option>";
                }
                ?>
            </select>

            <label>Semester</label>
            <input type="number" name="semester" placeholder="1 - 8">

            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" placeholder="2025/2026">

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"></textarea>

            <button type="submit" class="btn btn-add">Simpan</button>
            <a href="index.php" class="btn" style="background:#555;">Batal</a>
        </form>
    </div>
</body>
</html>