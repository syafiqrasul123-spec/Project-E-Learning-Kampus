<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role    = $_SESSION['role'];

// LOGIKA PEMISAH QUERY
if ($role == 'dosen') {
    // A. JIKA DOSEN: Ambil kelas yang DIAJAR oleh dosen ini (Cek tabel kelas langsung)
    $query_kelas = mysqli_query($conn, "
        SELECT k.*, u.nama as nama_dosen 
        FROM kelas k
        LEFT JOIN users u ON k.id_dosen = u.id
        WHERE k.id_dosen = '$id_user'
    ");
} else {
    // B. JIKA MAHASISWA: Ambil kelas yang DIIKUTI (Cek tabel mahasiswa_kelas)
    $query_kelas = mysqli_query($conn, "
        SELECT k.*, u.nama as nama_dosen 
        FROM kelas k
        JOIN mahasiswa_kelas mk ON k.id = mk.id_kelas
        LEFT JOIN users u ON k.id_dosen = u.id
        WHERE mk.id_mahasiswa = '$id_user'
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - EduLink</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <div>
            <span>Halo, <?php echo $_SESSION['nama']; ?> (<?php echo ucfirst($role); ?>)</span>
            
            <?php if($role == 'dosen' || $role == 'admin'): ?>
                | <a href="kelas/index.php" style="color:#D3EBED; font-weight:bold;">Kelola Data Kelas</a>
                | <a href="tugas/index.php" style="color:#D3EBED; font-weight:bold;">Kelola Tugas</a>
            <?php endif; ?>
        </div>
        <a href="logout.php" style="color:#ffcccc;">Logout</a>
    </div>

    <div class="container">
        <h2><?php echo ($role == 'dosen') ? 'Kelas yang Anda Ajar' : 'Kelas Saya'; ?></h2>
        
        <?php while($row = mysqli_fetch_assoc($query_kelas)): ?>
            <div class="card">
                <h3><?php echo $row['nama_kelas']; ?> <span class="badge"><?php echo $row['kode_kelas']; ?></span></h3>
                
                <?php if($role == 'mahasiswa'): ?>
                    <p><strong>Dosen:</strong> <?php echo $row['nama_dosen'] ?? 'Belum ada dosen'; ?></p>
                <?php endif; ?>
                
                <p><?php echo $row['deskripsi']; ?></p>
                
                <div style="margin-top:10px;">
                    <?php if($role == 'mahasiswa'): ?>
                        <a href="kelas.php?id=<?php echo $row['id']; ?>" class="btn">Masuk Kelas</a>
                    <?php else: ?>
                        <a href="kelas.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color:#154FB2;">Lihat Aktivitas</a>
                        <a href="tugas/create.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color:#28a745;">+ Buat Tugas</a>
                        <a href="materi/create.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color:#17a2b8;">Upload Materi</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
        
        <?php if(mysqli_num_rows($query_kelas) == 0): ?>
            <p>
                <?php echo ($role == 'dosen') 
                    ? 'Anda belum mengajar kelas manapun.' 
                    : 'Anda belum terdaftar di kelas manapun.'; 
                ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>