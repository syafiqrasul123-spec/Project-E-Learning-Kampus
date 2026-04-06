<?php
include '../config.php';

$id = $_GET['id'];
$query = "DELETE FROM kelas WHERE id='$id'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
} else {
    echo "Gagal Hapus: " . mysqli_error($conn);
}
?>