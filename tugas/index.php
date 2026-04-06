<?php include '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Tugas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <h2>Daftar Tugas (Master Soal)</h2>
        <a href="create.php" class="btn btn-add">+ Buat Tugas Baru</a>
        <a href="../index.php" class="btn" style="background:#555;">Kembali ke Menu</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Tugas</th>
                    <th>Kelas</th>
                    <th>Deadline</th>
                    <th>File Panduan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Join ke tabel kelas untuk menampilkan nama kelasnya
                $query = "SELECT t.*, k.nama_kelas, k.kode_kelas 
                          FROM tugas t 
                          JOIN kelas k ON t.id_kelas = k.id 
                          ORDER BY t.id DESC";
                $result = mysqli_query($conn, $query);
                $no = 1;
                while($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <strong><?= $row['judul_tugas']; ?></strong><br>
                        <small>Dibuat: <?= $row['tanggal_dibuat']; ?></small>
                    </td>
                    <td><?= $row['nama_kelas']; ?> <span class="badge"><?= $row['kode_kelas']; ?></span></td>
                    <td style="color:red;"><?= $row['tenggat_waktu']; ?></td>
                    <td>
                        <?php if($row['file_panduan']): ?>
                            <a href="../<?= $row['file_panduan']; ?>" target="_blank" class="btn" style="font-size:10px; padding:5px;">Download</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Hapus tugas ini beserta semua pengumpulan mahasiswa?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>