<?php
session_start();
session_unset();
session_destroy(); // Menghapus semua sesi login

// Redirect kembali ke halaman login
header("Location: login.php");
exit;
?>