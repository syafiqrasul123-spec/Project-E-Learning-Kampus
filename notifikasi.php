<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }
$id_user = $_SESSION['id_user'];

// Ambil notifikasi user ini
$query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE id_user='$id_user' ORDER BY tanggal DESC");

// Tandai semua sudah dibaca (Opsional, agar notif hilang status 'belum'-nya saat halaman dibuka)
mysqli_query($conn, "UPDATE notifikasi SET status_baca='sudah' WHERE id_user='$id_user'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Saya</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php" style="color:#76EAE3;">← Kembali ke Dashboard</a>
        <span>Notifikasi</span>
    </div>

    <div class="container">
        <h2 style="color:#154FB2;">Notifikasi</h2>
        
        <?php if(mysqli_num_rows($query) > 0): ?>
            <ul style="list-style:none; padding:0;">
            <?php while($row = mysqli_fetch_assoc($query)): ?>
                <li style="background: <?= ($row['status_baca']=='belum') ? '#e3f2fd' : '#fff'; ?>; padding:15px; border-bottom:1px solid #ddd; margin-bottom:5px;">
                    <h4 style="margin:0; color:#154FB2;"><?= $row['judul']; ?></h4>
                    <p style="margin:5px 0;"><?= $row['pesan']; ?></p>
                    <small style="color:#777;"><?= $row['tanggal']; ?></small>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Belum ada notifikasi.</p>
        <?php endif; ?>
    </div>
</body>
</html>