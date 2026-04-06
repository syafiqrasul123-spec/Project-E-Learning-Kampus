<?php
include '../config.php';

$judul = $_POST['judul_tugas'];
$kelas = $_POST['id_kelas'];
$desc  = $_POST['deskripsi'];
$deadline = $_POST['tenggat_waktu'];

// Logika Upload File
// Logika Upload File Modular
$file_panduan = NULL; 
if(!empty($_FILES['file_panduan']['name'])) {
    $nama_file = $_FILES['file_panduan']['name'];
    $tmp_file  = $_FILES['file_panduan']['tmp_name'];
    
    // 1. Tentukan folder fisik (Relative terhadap file store.php ini)
    // Pastikan folder 'uploads' sudah dibuat di dalam folder 'tugas'
    $target_dir = "uploads/"; 
    
    // Cek folder, buat jika belum ada
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $nama_baru = time() . "_" . $nama_file;
    $target_file = $target_dir . $nama_baru;
    
    // 2. Upload File
    if(move_uploaded_file($tmp_file, $target_file)) {
        // 3. Simpan ke Database (Harus pakai path lengkap dari root: 'tugas/uploads/...')
        // Kenapa? Supaya kalau dibuka dari dashboard utama link-nya tidak rusak.
        $file_panduan = "tugas/uploads/" . $nama_baru;
    }
}

$query = "INSERT INTO tugas (id_kelas, judul_tugas, deskripsi, tenggat_waktu, file_panduan, tanggal_dibuat) 
          VALUES ('$kelas', '$judul', '$desc', '$deadline', '$file_panduan', NOW())";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>