<?php 
$host = "localhost";
$user = "root";
$pass = "";
$db = 'edulink_campus';

$conn = mysqli_connect( $host, $user, $pass, $db);

if(!$conn) {
    die("Koneksi Gagal: ". mysqli_connect_error());
}
?>