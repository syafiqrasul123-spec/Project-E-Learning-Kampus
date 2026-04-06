<?php
include '../config.php';

$kode = $_POST['kode_kelas'];
$nama = $_POST['nama_kelas'];
$dosen = !empty($_POST['id_dosen']) ? $_POST['id_dosen'] : "NULL"; // Handle jika kosong
$smstr = $_POST['semester'];
$ta = $_POST['tahun_ajaran'];
$desc = $_POST['deskripsi'];

$query = "INSERT INTO kelas (kode_kelas, nama_kelas, id_dosen, semester, tahun_ajaran, deskripsi) 
          VALUES ('$kode', '$nama', $dosen, '$smstr', '$ta', '$desc')";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>