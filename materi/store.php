<?php
session_start();
include '../config.php';

if(!isset($_POST['judul'])) { header("Location: index.php"); exit; }

$judul    = $_POST['judul'];
$id_kelas = $_POST['id_kelas'];
$deskripsi= $_POST['deskripsi'];

// Logika Upload File
$file_path = NULL;

if(!empty($_FILES['file_materi']['name'])) {
    $nama_file = $_FILES['file_materi']['name'];
    $tmp_file  = $_FILES['file_materi']['tmp_name'];
    
    // Simpan fisik file di folder 'uploads' dalam modul materi
    $target_dir = "uploads/";
    
    // Buat folder jika belum ada (Safe guard)
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $nama_baru = time() . "_" . $nama_file;
    $target_file = $target_dir . $nama_baru;

    if(move_uploaded_file($tmp_file, $target_file)) {
        // Path yang disimpan di database harus lengkap dari root folder proyek
        // Format: materi/uploads/namafile.pdf
        $file_path = "materi/uploads/" . $nama_baru;
    }
}

// Insert ke Database
$query = "INSERT INTO materi (id_kelas, judul, deskripsi, file_path, tanggal_upload) 
          VALUES ('$id_kelas', '$judul', '$deskripsi', '$file_path', NOW())";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Materi Berhasil Diupload!'); window.location.href='../kelas.php?id=$id_kelas';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>