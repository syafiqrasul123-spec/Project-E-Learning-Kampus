<?php
session_start();
include '../config.php';

if (!isset($_GET['id'])) { header("Location: ../index.php"); exit; }
$id_forum = $_GET['id'];
$id_user  = $_SESSION['id_user'];

// Ambil Data Topik
$q_topik = mysqli_query($conn, "SELECT f.*, u.nama, f.id_kelas FROM forum_diskusi f JOIN users u ON f.id_user = u.id WHERE f.id='$id_forum'");
$topik   = mysqli_fetch_assoc($q_topik);

// Ambil Komentar
$q_komentar = mysqli_query($conn, "SELECT k.*, u.nama FROM komentar k JOIN users u ON k.id_user = u.id WHERE k.id_forum='$id_forum' ORDER BY k.tanggal_komentar ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diskusi: <?= $topik['judul_topik']; ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="navbar">
        <a href="../kelas.php?id=<?= $topik['id_kelas']; ?>" style="color:#76EAE3;">← Kembali ke Kelas</a>
        <span>Forum Diskusi</span>
    </div>

    <div class="container">
        <h2 style="color:#9c27b0;"><?= $topik['judul_topik']; ?></h2>
        <p><small>Diposting oleh <b><?= $topik['nama']; ?></b> pada <?= $topik['tanggal_post']; ?></small></p>
        <div style="background:#f9f9f9; padding:15px; border-left:5px solid #9c27b0;">
            <?= nl2br($topik['isi_pesan']); ?>
        </div>
        
        <hr>
        <h3>Komentar</h3>
        
        <?php while($kom = mysqli_fetch_assoc($q_komentar)): ?>
            <div style="margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:10px;">
                <p><strong><?= $kom['nama']; ?></strong> <small style="color:#777;">(<?= $kom['tanggal_komentar']; ?>)</small></p>
                <p><?= nl2br($kom['isi_komentar']); ?></p>
            </div>
        <?php endwhile; ?>

        <?php if($_SESSION['role'] == 'mahasiswa'): ?>
        <form action="store_komentar.php" method="POST" style="margin-top:20px; background:#eee; padding:15px; border-radius:5px;">
            <input type="hidden" name="id_forum" value="<?= $id_forum; ?>">
            <label>Balas Komentar:</label>
            <textarea name="isi_komentar" rows="3" required placeholder="Tulis komentar Anda..." style="width:100%;"></textarea>
            <button type="submit" class="btn" style="background:#9c27b0; margin-top:10px;">Kirim Balasan</button>
        </form>
    <?php else: ?>
        <hr>
        <p style="text-align:center; color:#777; padding:20px; background:#f9f9f9; border-radius:5px;">
            <em>Mode Dosen: Anda tidak dapat membalas diskusi ini.</em>
        </p>
    <?php endif; ?>

</div>
</body>
</html>