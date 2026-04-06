<?php
session_start();
// PERUBAHAN 1: Naik satu folder untuk ambil config
include '../config.php'; 

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user  = $_SESSION['id_user'];
$role     = $_SESSION['role'];
$id_tugas = $_GET['id'];

// 1. Ambil Data Tugas
$q_tugas = mysqli_query($conn, "SELECT * FROM tugas WHERE id='$id_tugas'");
$tugas   = mysqli_fetch_assoc($q_tugas);

// 2. Ambil Data Pengumpulan
$submission = null;
if ($role == 'mahasiswa') {
    $q_sub = mysqli_query($conn, "SELECT * FROM pengumpulan_tugas WHERE id_tugas='$id_tugas' AND id_mahasiswa='$id_user'");
    $submission = mysqli_fetch_assoc($q_sub);
}

// ==========================================
// LOGIKA BACKEND
// ==========================================

// A. MAHASISWA UPLOAD
if (isset($_POST['submit_tugas']) && $role == 'mahasiswa') {
    
    $nama_file = $_FILES['file_tugas']['name'];
    $tmp_file  = $_FILES['file_tugas']['tmp_name'];
    
    // PERUBAHAN 2: Path Upload jadi lebih simpel karena kita sudah di dalam folder pengumpulan_tugas
    // Gunakan __DIR__ . "/uploads/" (Folder uploads ada di sebelah file ini)
    $target_dir = __DIR__ . "/uploads/"; 
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $nama_baru   = time() . "_" . $nama_file;
    $target_file = $target_dir . $nama_baru;
    
    // PERUBAHAN 3: Path Database Tetap Lengkap (Supaya bisa diakses dari mana saja)
    $path_db     = "pengumpulan_tugas/uploads/" . $nama_baru; 

    if (move_uploaded_file($tmp_file, $target_file)) {
        
        $cek = mysqli_query($conn, "SELECT * FROM pengumpulan_tugas WHERE id_tugas='$id_tugas' AND id_mahasiswa='$id_user'");
        
        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE pengumpulan_tugas SET file_tugas='$path_db', tanggal_upload=NOW() 
                      WHERE id_tugas='$id_tugas' AND id_mahasiswa='$id_user'";
        } else {
            $query = "INSERT INTO pengumpulan_tugas (id_tugas, id_mahasiswa, file_tugas, tanggal_upload) 
                      VALUES ('$id_tugas', '$id_user', '$path_db', NOW())";
        }
        
        if (mysqli_query($conn, $query)) {
            // Refresh ke halaman ini sendiri (detail.php)
            echo "<script>alert('Berhasil upload tugas!'); window.location.href='detail.php?id=$id_tugas';</script>";
        } else {
            echo "Error DB: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal upload file ke server.');</script>";
    }
}

// B. DOSEN INPUT NILAI
if (isset($_POST['submit_nilai']) && $role == 'dosen') {
    $id_mhs_dinilai = $_POST['id_mahasiswa_target'];
    $nilai_input    = $_POST['nilai'];
    $komentar       = $_POST['komentar_dosen'];

    $query = "UPDATE pengumpulan_tugas SET nilai='$nilai_input', komentar_dosen='$komentar' WHERE id_tugas='$id_tugas' AND id_mahasiswa='$id_mhs_dinilai'";
    mysqli_query($conn, $query);
    echo "<script>window.location.href='detail.php?id=$id_tugas';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Tugas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="navbar">
        <a href="../kelas.php?id=<?= $tugas['id_kelas']; ?>" style="color:#76EAE3;">← Kembali ke Kelas</a>
        <span>Detail Tugas</span>
    </div>

    <div class="container">
        <h2 style="color:#154FB2;"><?= $tugas['judul_tugas']; ?></h2>
        <div style="background:#f9f9f9; padding:15px; border-left:5px solid #154FB2; margin-bottom:20px;">
            <p><?= $tugas['deskripsi']; ?></p>
            <p><strong>Deadline:</strong> <?= $tugas['tenggat_waktu']; ?></p>
            <?php if($tugas['file_panduan']): ?>
                <a href="../<?= $tugas['file_panduan']; ?>" class="btn" style="font-size:12px;" target="_blank">Download Panduan Soal</a>
            <?php endif; ?>
        </div>

        <hr>

        <?php if ($role == 'mahasiswa'): ?>
            <h3>Form Pengumpulan Tugas</h3>
            
            <?php if ($submission): ?>
                <div class="card" style="border-left-color: #76EAE3;">
                    <p><strong>Status:</strong> <span class="badge">Sudah Mengumpulkan</span></p>
                    <p><strong>File Anda:</strong> <a href="../<?= $submission['file_tugas']; ?>" target="_blank">Lihat File</a></p>
                    
                    <div style="background:#D3EBED; padding:10px; border-radius:5px; margin-top:5px;">
                        <p><strong>Nilai:</strong> 
                            <?= ($submission['nilai'] !== null) ? $submission['nilai'] : "<em>Belum dinilai dosen</em>"; ?>
                        </p>
                        <p><strong>Komentar Dosen:</strong> <?= $submission['komentar_dosen'] ?? '-'; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" style="margin-top:20px;">
                <label>Upload File Tugas (PDF/DOCX):</label>
                <input type="file" name="file_tugas" required>
                <button type="submit" name="submit_tugas" class="btn">Kirim Tugas</button>
            </form>

        <?php elseif ($role == 'dosen' || $role == 'admin'): ?>
            <h3>Daftar Pengumpulan Mahasiswa</h3>
            <table border="1" cellpadding="10" cellspacing="0" style="width:100%">
                <thead style="background:#154FB2; color:white;">
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>File Tugas</th>
                        <th>Waktu Upload</th>
                        <th>Nilai & Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q_all_sub = mysqli_query($conn, "
                        SELECT p.*, u.nama 
                        FROM pengumpulan_tugas p 
                        JOIN users u ON p.id_mahasiswa = u.id 
                        WHERE p.id_tugas='$id_tugas'
                    ");
                    
                    if(mysqli_num_rows($q_all_sub) > 0):
                        while($row = mysqli_fetch_assoc($q_all_sub)): 
                    ?>
                    <tr>
                        <td><?= $row['nama']; ?></td>
                        <td><a href="../<?= $row['file_tugas']; ?>" target="_blank">Download</a></td>
                        <td><?= $row['tanggal_upload']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id_mahasiswa_target" value="<?= $row['id_mahasiswa']; ?>">
                                <input type="number" step="0.01" name="nilai" value="<?= $row['nilai']; ?>" placeholder="0-100" style="width:70px;">
                                <input type="text" name="komentar_dosen" value="<?= $row['komentar_dosen']; ?>" placeholder="Komentar..." style="width:150px;">
                                <button type="submit" name="submit_nilai" class="btn" style="padding:5px; font-size:12px;">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else: ?>
                        <tr><td colspan="4">Belum ada mahasiswa yang mengumpulkan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</body>
</html>