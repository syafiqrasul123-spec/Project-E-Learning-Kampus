<?php
include '../config.php';
$id = $_GET['id'];

// (Opsional) Hapus file fisik panduan jika ingin bersih-bersih server
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT file_panduan FROM tugas WHERE id='$id'"));
if($data['file_panduan'] && file_exists("../" . $data['file_panduan'])) {
    unlink("../" . $data['file_panduan']);
}

$query = "DELETE FROM tugas WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>