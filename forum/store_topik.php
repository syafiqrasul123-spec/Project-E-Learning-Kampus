<?php
session_start();
include '../config.php';

$id_kelas = $_POST['id_kelas'];
$id_user = $_SESSION['id_user'];
$judul = $_POST['judul'];
$isi = "Mari Berdiskusi Tentang: " . $judul;

$query = "INSERT INTO forum_diskusi (id_kelas, id_user, judul_topik, isi_pesan, tanggal_post) 
          VALUES ('$id_kelas', '$id_user', '$judul', '$isi', NOW())";

mysqli_query($conn, $query);
header("Location: ../kelas.php=$id_kelas");
?>
