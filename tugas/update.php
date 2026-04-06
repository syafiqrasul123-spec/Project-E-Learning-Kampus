<?php
include '../config.php';

$id    = $_POST['id'];
$judul = $_POST['judul_tugas'];
$kelas = $_POST['id_kelas'];
$desc  = $_POST['deskripsi'];
$deadline = $_POST['tenggat_waktu'];
$file_panduan = $_POST['file_lama']; // Default pakai file lama

// Cek jika user upload file baru
// Logika Upload File Modular (Update)
if(!empty($_FILES['file_panduan']['name'])) {
    $nama_file = $_FILES['file_panduan']['name'];
    $tmp_file  = $_FILES['file_panduan']['tmp_name'];
    
    $target_dir = "uploads/"; // Simpan di dalam folder tugas/uploads/
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $nama_baru = time() . "_" . $nama_file;
    $target_file = $target_dir . $nama_baru;
    
    if(move_uploaded_file($tmp_file, $target_file)) {
        $file_panduan = "tugas/uploads/" . $nama_baru; // Path Database
    }
}

$query = "UPDATE tugas SET 
            id_kelas='$kelas', 
            judul_tugas='$judul', 
            deskripsi='$desc', 
            tenggat_waktu='$deadline', 
            file_panduan='$file_panduan' 
          WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>