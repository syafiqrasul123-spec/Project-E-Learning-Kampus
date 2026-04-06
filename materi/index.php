<?php
session_start();
include '../config.php';

if($_SESSION['role'] != 'dosen' && $_SESSION['role'] != 'admin'){
    header("Location: ../index.php"); exit;
}
$id_dosen = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Materi</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <div class="navbar">
            <a href="../index.php" style="color:#76EAE3;">&larr; Dashboard</a>
            <span>Manajemen Materi</span>
        </div>
        <br>
        <h2>Daftar Materi Anda</h2>
        <a href="create.php" class="btn btn-add">+ Upload Materi Baru</a>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%">
            <thead>
                <tr style="background:#154FB2; color:white;">
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil materi berdasarkan kelas yang diajar dosen ini
                $query = "SELECT m.*, k.nama_kelas 
                          FROM materi m 
                          JOIN kelas k ON m.id_kelas = k.id 
                          WHERE k.id_dosen = '$id_dosen' 
                          ORDER BY m.id DESC";
                $result = mysqli_query($conn, $query);
                $no = 1;
                while($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <b><?= $row['judul']; ?></b><br>
                        <small><a href="../<?= $row['file_path']; ?>" target="_blank">Lihat File</a></small>
                    </td>
                    <td><?= $row['nama_kelas']; ?></td>
                    <td><?= $row['tanggal_upload']; ?></td>
                    <td>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Hapus materi ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>