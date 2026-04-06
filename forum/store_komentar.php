<?php
session_start();
include '../config.php';

$id_forum = $_POST['id_forum'];
$id_user  = $_SESSION['id_user'];
$isi      = $_POST['isi_komentar'];

$query = "INSERT INTO komentar (id_forum, id_user, isi_komentar, tanggal_komentar) 
          VALUES ('$id_forum', '$id_user', '$isi', NOW())";

if (mysqli_query($conn, $query)) {
    // Optional: Insert Notifikasi disini (Nanti)
    header("Location: detail.php?id=$id_forum");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>