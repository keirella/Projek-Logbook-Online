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
    <title>Dashboard Pemagang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">👤</div>
                <h3><?php echo $_SESSION['nama']; ?></h3>
                <p style="font-size: 12px; color: #666;"><?php echo $_SESSION['nim']; ?></p>
                <p style="font-size: 12px; color: #666;"><?php echo $_SESSION['asal']; ?></p>
            </div>
            <div style="margin-top: auto; text-align: center;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus">
                <p style="font-size: 10px;">Balai Yanpus</p>
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Dashboard Pemagang</h2>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>

            <div class="content-body">
                <h1>Selamat Datang, <?php echo $_SESSION['nama']; ?>!</h1>
                <div class="grid-menu">
                    <a href="isi_logbook.php" class="btn btn-dash">📝 Isi Logbook Baru</a>
                    <a href="riwayat.php" class="btn btn-dash">🕒 Lihat Riwayat</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>