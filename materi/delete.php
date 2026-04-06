<?php
session_start();
include '../config.php';

// Cek param ID
if(!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id = $_GET['id'];
// Tangkap id_kelas jika ada (untuk redirect balik)
$id_kelas_redirect = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;

// 1. Ambil info file dulu untuk hapus fisik filenya
$q = mysqli_query($conn, "SELECT file_path FROM materi WHERE id='$id'");
$data = mysqli_fetch_assoc($q);

// 2. Hapus file fisik jika ada
if($data['file_path'] && file_exists("../" . $data['file_path'])) {
    unlink("../" . $data['file_path']); 
}

// 3. Hapus dari database
$query = "DELETE FROM materi WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    // LOGIKA REDIRECT BARU
    if ($id_kelas_redirect) {
        // Jika ada ID Kelas, kembalikan ke halaman kelas tadi
        header("Location: ../kelas.php?id=" . $id_kelas_redirect);
    } else {
        // Jika tidak ada (misal dihapus dari menu Kelola Materi), kembalikan ke index materi
        header("Location: index.php");
    }
} else {
    echo "Gagal hapus: " . mysqli_error($conn);
}
?>