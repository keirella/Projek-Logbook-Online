<?php
    include 'config.php'; 
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemagang') {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $query_stats = mysqli_query($conn, "SELECT COUNT(*) as total FROM logbooks WHERE user_id = '$user_id'");
    $stats = mysqli_fetch_assoc($query_stats);

    $query_recent = mysqli_query($conn, "SELECT hari_tanggal, uraian_kegiatan, approved_petugas FROM logbooks WHERE user_id = '$user_id' ORDER BY hari_tanggal DESC LIMIT 2");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pemagang</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 5px solid #ff8c00;
        }
        .stat-card h4 { margin: 0; color: #666; font-size: 14px; }
        .stat-card p { margin: 10px 0 0; font-size: 24px; font-weight: bold; color: #333; }

        .recent-activity {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; color: #666; border-bottom: 2px solid #eee; padding: 10px; }
        td { padding: 12px 10px; border-bottom: 1px solid #eee; font-size: 14px; }
        .status-badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            background: #e1f5fe;
            color: #039be5;
        }
    
        .status-pending {
            background: #fff3e0;
            color: #ef6c00;
        }

        .logo-yanpus {
            width: 80px; 
            height: auto !important; 
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body> 
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
                    <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;"><a href="home_pemagang.php" style="text-decoration: none; color: #333; font-size: 14px;">🏠 Beranda</a></li>
                    <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;"><a href="presensi.php" style="text-decoration: none; color: #333; font-size: 14px;">📅 Presensi</a></li>
                </ul>
            </nav>

            <div style="margin-top: auto; text-align: center; padding-bottom: 20px;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus" style="width: 150px; ">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Dashboard Pemagang</h2>
                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="logout-link">Logout</a>
            </nav>

            <div class="content-body">
                <div style="margin-bottom: 30px;">
                    <h1 style="margin-bottom: 5px;">Selamat Datang, <?php echo $_SESSION['nama']; ?>! 👋</h1>
                    <p style="color: #666;">Berikut adalah ringkasan aktivitas magangmu hari ini.</p>
                </div>

                <div class="stats-container">
                    <div class="stat-card">
                        <h4>Total Logbook</h4>
                        <p><?php echo $stats['total']; ?></p> 
                    </div>
                    <div class="stat-card" style="border-left-color: #4caf50;">
                        <h4>Status Magang</h4>
                        <p style="font-size: 18px; color: #4caf50;">Aktif</p>
                    </div>
                    <div class="stat-card" style="border-left-color: #2196f3;">
                        <h4>Sisa Hari</h4>
                        <p>- Hari</p> </div>
                </div>

                <div class="grid-menu" style="margin-bottom: 30px;">
                    <a href="isi_logbook.php" class="btn btn-dash" style="justify-content: center;">📝 Isi Logbook Baru</a>
                    <a href="riwayat.php" class="btn btn-dash" style="justify-content: center; background: #fff; color: #ff8c00; border: 2px solid #ff8c00;">🕒 Lihat Riwayat</a>
                </div>

                <div class="recent-activity">
                    <h3 style="margin-top: 0;">Logbook Terakhir</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(mysqli_num_rows($query_recent) > 0) {
                                while($row = mysqli_fetch_assoc($query_recent)) {
                                    $status_text = ($row['approved_petugas'] == 1) ? "Terverifikasi" : "Pending";
                                    $status_class = ($row['approved_petugas'] == 1) ? "" : "status-pending";
                                    
                                    echo "<tr>";
                                    echo "<td>" . date('d M Y', strtotime($row['hari_tanggal'])) . "</td>";
                                    echo "<td>" . htmlspecialchars(substr($row['uraian_kegiatan'], 0, 50)) . "...</td>";
                                    echo "<td><span class='status-badge $status_class'>$status_text</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' style='text-align:center;'>Belum ada data kegiatan.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>