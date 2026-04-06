<?php 
session_start();
include '../config.php';

// Cek akses dosen
if($_SESSION['role'] != 'dosen' && $_SESSION['role'] != 'admin'){
    header("Location: ../index.php"); exit;
}

$id_dosen = $_SESSION['id_user'];
$selected_kelas = $_GET['id_kelas'] ?? ''; // Ambil ID kelas dari URL dashboard
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Materi Baru</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php" style="color:#76EAE3;">&larr; Kembali ke Dashboard</a>
        <span>Upload Materi Pembelajaran</span>
    </div>

    <div class="container">
        <h2 style="color:#154FB2;">Upload Materi Baru</h2>
        
        <form action="store.php" method="POST" enctype="multipart/form-data">
            
            <label>Judul Materi</label>
            <input type="text" name="judul" required placeholder="Contoh: Pengenalan Algoritma">

            <label>Pilih Kelas</label>
            <select name="id_kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <?php
                // Hanya tampilkan kelas yang diajar oleh dosen ini
                $query = mysqli_query($conn, "SELECT * FROM kelas WHERE id_dosen='$id_dosen'");
                while($row = mysqli_fetch_assoc($query)) {
                    // Auto-select jika ID kelas cocok dengan URL
                    $selected = ($row['id'] == $selected_kelas) ? 'selected' : '';
                    echo "<option value='".$row['id']."' $selected>".$row['nama_kelas']." - ".$row['kode_kelas']."</option>";
                }
                ?>
            </select>

            <label>Deskripsi (Opsional)</label>
            <textarea name="deskripsi" rows="4" placeholder="Penjelasan singkat materi..."></textarea>

            <label>File Materi (PDF/PPT/DOCX/Video)</label>
            <input type="file" name="file_materi" required>

            <button type="submit" class="btn btn-add" style="margin-top:10px;">Upload & Simpan</button>
        </form>
    </div>
</body>
</html>