<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }

$id_kelas = $_GET['id'];
$id_user  = $_SESSION['id_user'];
$role     = $_SESSION['role'];

// 1. Info Kelas
$q_kelas = mysqli_query($conn, "SELECT * FROM kelas WHERE id='$id_kelas'");
$d_kelas = mysqli_fetch_assoc($q_kelas);
if (!$d_kelas) { echo "Kelas tidak ditemukan."; exit; }

// 2. Materi & Tugas
$q_materi = mysqli_query($conn, "SELECT * FROM materi WHERE id_kelas='$id_kelas' ORDER BY tanggal_upload DESC");
$q_tugas  = mysqli_query($conn, "SELECT * FROM tugas WHERE id_kelas='$id_kelas' ORDER BY tenggat_waktu DESC");

// 3. BARU: Kalender Akademik (Event mendatang)
$q_event = mysqli_query($conn, "SELECT * FROM kalender_akademik WHERE id_kelas='$id_kelas' ORDER BY tanggal_mulai ASC");

// 4. BARU: Forum Diskusi
$q_forum = mysqli_query($conn, "
    SELECT f.*, u.nama as pembuat, COUNT(k.id) as total_komentar 
    FROM forum_diskusi f
    JOIN users u ON f.id_user = u.id
    LEFT JOIN komentar k ON f.id = k.id_forum
    WHERE f.id_kelas='$id_kelas'
    GROUP BY f.id
    ORDER BY f.tanggal_post DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $d_kelas['nama_kelas']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php" style="color:#76EAE3;">← Kembali ke Dashboard</a>
        
        <div>
            <a href="notifikasi.php" style="margin-right:15px; color:#fff;">🔔 Notifikasi</a>
            <span><?php echo $d_kelas['nama_kelas']; ?></span>
        </div>
    </div>

    <div class="container">
        <h2 style="color:#154FB2;"><?php echo $d_kelas['nama_kelas']; ?> <span class="badge"><?php echo $d_kelas['kode_kelas']; ?></span></h2>
        <p><?php echo $d_kelas['deskripsi']; ?></p>

        <?php if(mysqli_num_rows($q_event) > 0): ?>
            <div style="background: #fff8e1; padding: 10px; border-left: 5px solid #ffc107; margin-bottom: 20px;">
                <h4 style="margin:0 0 10px 0;">📅 Agenda Kelas</h4>
                <ul style="margin:0; padding-left:20px;">
                    <?php while($evt = mysqli_fetch_assoc($q_event)): ?>
                        <li>
                            <strong><?= $evt['nama_event']; ?></strong>: <?= $evt['tanggal_mulai']; ?> s/d <?= $evt['tanggal_selesai']; ?>
                            <br><small><?= $evt['deskripsi']; ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <hr>

        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #76EAE3; padding-bottom:10px;">
            <h3 style="color:#154FB2; margin:0;">Materi Pembelajaran</h3>
            <?php if($role == 'dosen' || $role == 'admin'): ?>
                <div>
                    <a href="materi/index.php" class="btn" style="background:#555; font-size:12px;">Kelola</a>
                    <a href="materi/create.php?id_kelas=<?= $id_kelas; ?>" class="btn" style="font-size:12px;">+ Upload</a>
                </div>
            <?php endif; ?>
        </div>
        <br>
        <?php while($materi = mysqli_fetch_assoc($q_materi)): ?>
            <div class="card">
                <h4>📜 <?php echo $materi['judul']; ?></h4>
                <p><?php echo $materi['deskripsi']; ?></p>
                <small>Diunggah: <?php echo $materi['tanggal_upload']; ?></small><br><br>
                <?php if($materi['file_path']): ?>
                    <a href="<?php echo $materi['file_path']; ?>" class="btn" target="_blank">Download</a>
                    <?php if($role == 'dosen'): ?>
                        <a href="materi/delete.php?id=<?= $materi['id']; ?>&id_kelas=<?= $id_kelas; ?>" class="btn" style="background-color: #d9534f;" onclick="return confirm('Hapus?')">Hapus</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <h3 style="color:#154FB2; border-bottom: 2px solid #76EAE3; padding-bottom:10px; margin-top:40px;">Tugas & Submission</h3>
        <?php while($tugas = mysqli_fetch_assoc($q_tugas)): 
             // Cek status kumpul (Logic lama)
             $id_tgs = $tugas['id'];
             $cek = mysqli_query($conn, "SELECT * FROM pengumpulan_tugas WHERE id_tugas='$id_tgs' AND id_mahasiswa='$id_user'");
             $sub = mysqli_fetch_assoc($cek);
        ?>
            <div class="card" style="border-left-color: #76EAE3;">
                <h4>📝 <?php echo $tugas['judul_tugas']; ?></h4>
                <p>Deadline: <span style="color:red"><?= $tugas['tenggat_waktu']; ?></span></p>
                <a href="pengumpulan_tugas/detail.php?id=<?= $tugas['id']; ?>" class="btn">Lihat Detail</a>
            </div>
        <?php endwhile; ?>

        <h3 style="color:#154FB2; border-bottom: 2px solid #76EAE3; padding-bottom:10px; margin-top:40px;">Forum Diskusi</h3>

<?php if($role == 'mahasiswa'): ?>
    <form action="forum/store_topik.php" method="POST" style="margin-bottom:20px; background:#f0f0f0; padding:15px; border-radius:5px;">
        <input type="hidden" name="id_kelas" value="<?= $id_kelas; ?>">
        <input type="text" name="judul" placeholder="Buat topik diskusi baru..." required style="width:70%; display:inline-block;">
        <button type="submit" class="btn" style="width:25%;">Kirim</button>
    </form>
<?php else: ?>
    <p style="background:#fff3cd; padding:10px; border:1px solid #ffeeba; border-radius:5px; color:#856404; margin-bottom:20px;">
        ℹ️ <b>Mode Dosen:</b> Anda hanya dapat memantau diskusi mahasiswa (Read Only).
    </p>
<?php endif; ?>

<?php 
// Tambahan cek jika kosong biar rapi
if(mysqli_num_rows($q_forum) == 0) {
    echo "<p style='color:#777;'>Belum ada topik diskusi.</p>";
}
while($forum = mysqli_fetch_assoc($q_forum)): 
?>
            <div class="card" style="border-left: 5px solid #9c27b0;">
                <h4>💬 <a href="forum/detail.php?id=<?= $forum['id']; ?>" style="text-decoration:none; color:#333;"><?= $forum['judul_topik']; ?></a></h4>
                <p><small>Oleh: <b><?= $forum['pembuat']; ?></b> | <?= $forum['tanggal_post']; ?> | 🗨 <?= $forum['total_komentar']; ?> Komentar</small></p>
                <p><?= substr($forum['isi_pesan'], 0, 100); ?>...</p>
                <a href="forum/detail.php?id=<?= $forum['id']; ?>" class="btn" style="background:#9c27b0;">Lihat Diskusi</a>
            </div>
        <?php endwhile; ?>

    </div>
</body>
</html>