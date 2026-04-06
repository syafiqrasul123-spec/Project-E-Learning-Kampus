<?php
include '../config.php';

$id = $_POST['id'];
$kode = $_POST['kode_kelas'];
$nama = $_POST['nama_kelas'];
$dosen = !empty($_POST['id_dosen']) ? $_POST['id_dosen'] : "NULL";
$smstr = $_POST['semester'];
$ta = $_POST['tahun_ajaran'];
$desc = $_POST['deskripsi'];

$query = "UPDATE kelas SET 
            kode_kelas='$kode', 
            nama_kelas='$nama', 
            id_dosen=$dosen, 
            semester='$smstr', 
            tahun_ajaran='$ta', 
            deskripsi='$desc' 
          WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Gagal Update: " . mysqli_error($conn);
}
?>