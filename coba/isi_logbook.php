<?php
    include 'config.php';
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemagang') {
        header("Location: login.php");
        exit; 
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isi Logbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="display: block;">
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">👤</div>
                <h3 style="margin-bottom: 5px;"><?php echo $_SESSION['nama']; ?></h3>
                <p style="font-size: 12px; color: #666; margin: 2px 0;"><?php echo $_SESSION['nim']; ?></p>
                <p style="font-size: 12px; color: #666; margin: 2px 0;"><?php echo $_SESSION['asal']; ?></p>
            </div>
            
            <nav style="margin-top: 30px; padding: 0 20px;">
                <p style="font-size: 10px; color: #999; text-transform: uppercase; font-weight: bold;">Menu Utama</p>
                <ul style="list-style: none; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;"><a href="#" style="text-decoration: none; color: #333; font-size: 14px;">🏠 Beranda</a></li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;"><a href="presensi.php" style="text-decoration: none; color: #333; font-size: 14px;">📅 Presensi</a></li>
                </ul>
            </nav>

            <div style="margin-top: auto; text-align: center; padding-bottom: 20px;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus" style="width: 150px; ">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333;">Form Logbook</h2>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>

            <div class="content-body">
                <div style="background: white; padding: 30px; border-radius: 15px; max-width: 800px; margin: 0 auto;">
                    <form method="POST">
                        <label>Nama Pendamping Magang</label>
                        <input type="text" name="p_magang" placeholder="Masukkan nama..." required>
                        <label>Nama Pendamping Ruangan</label>
                        <input type="text" name="p_ruangan" placeholder="Masukkan nama..." required>
                        <label>Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" required>
                        <label>Tempat Ruangan</label>
                        <input type="text" name="ruangan" placeholder="Nama ruangan..." required>
                        <label>Uraian Kegiatan</label>
                        <textarea name="kegiatan" placeholder="Tuliskan kegiatan hari ini..." rows="5" required></textarea>
                        
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <a href="index.php" class="btn" style="background:#ccc !important; text-align:center;">Batal</a>
                            <button type="submit" name="submit_log">Kirim Logbook</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php
    if(isset($_POST['submit_log'])){
        $user_id = $_SESSION['user_id'];
        $nama_pendamping_magang = $_POST['p_magang'];
        $nama_pendamping_ruangan = $_POST['p_ruangan'];
        $hari_tanggal = $_POST['tanggal'];
        $tempat_ruangan = $_POST['ruangan'];
        $uraian_kegiatan = $_POST['kegiatan'];

        mysqli_query($conn, "INSERT INTO logbooks (user_id, hari_tanggal, nama_pendamping_magang, nama_pendamping_ruangan, tempat_ruangan, uraian_kegiatan) 
        VALUES ('$user_id', '$hari_tanggal', '$nama_pendamping_magang', '$nama_pendamping_ruangan', '$tempat_ruangan', '$uraian_kegiatan')");
        echo "<script>alert('Logbook Berhasil Disimpan!'); window.location='index.php';</script>";
    }
    ?>
</body>
</html>