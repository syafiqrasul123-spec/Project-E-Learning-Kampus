<?php
// Tampilkan semua error agar tidak blank putih
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Cek apakah user sudah login dan role-nya Dosen/Admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'dosen' && $_SESSION['role'] != 'admin')) {
    echo "<script>alert('Akses Ditolak! Halaman ini hanya untuk Dosen.'); window.location.href='../index.php';</script>";
    exit;
}

// Koneksi Database Menggunakan Path Absolut (Lebih Aman)
// dirname(__DIR__) berarti naik satu folder ke atas dari folder 'kelas'
include dirname(__DIR__) . '/config.php'; 

// Cek koneksi manual (Debugging)
if (!$conn) {
    die("Error Koneksi Database di folder kelas: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Data Kelas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php" style="color:#76EAE3;">&larr; Kembali ke Dashboard</a>
        <span>Panel Dosen: Kelola Kelas</span>
    </div>

    <div class="container" style="max-width: 1000px; margin-top:20px;">
        <h2 style="color:#154FB2;">Daftar Kelas</h2>
        
        <div style="margin-bottom: 20px;">
            <a href="create.php" class="btn btn-add">+ Tambah Kelas Baru</a>
        </div>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-color:#ddd;">
            <thead>
                <tr style="background-color: #154FB2; color: white;">
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Kelas</th>
                    <th>Dosen</th>
                    <th>Semester</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query Data Kelas
                $query = "SELECT k.*, u.nama AS nama_dosen 
                          FROM kelas k 
                          LEFT JOIN users u ON k.id_dosen = u.id 
                          ORDER BY k.id DESC";
                
                $result = mysqli_query($conn, $query);

                // Cek jika query gagal
                if (!$result) {
                    die("Query Error: " . mysqli_error($conn));
                }

                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="badge"><?= $row['kode_kelas']; ?></span></td>
                    <td><?= $row['nama_kelas']; ?></td>
                    <td><?= $row['nama_dosen'] ?? '<span style="color:red;">Kosong</span>'; ?></td>
                    <td><?= $row['semester']; ?> (<?= $row['tahun_ajaran']; ?>)</td>
                    <td>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-edit" style="font-size:12px;">Edit</a>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-delete" style="font-size:12px;" onclick="return confirm('Yakin hapus kelas ini? Data materi & tugas di dalamnya akan ikut terhapus!');">Hapus</a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                } else {
                    echo "<tr><td colspan='6' align='center'>Belum ada data kelas. Silakan tambah baru.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>