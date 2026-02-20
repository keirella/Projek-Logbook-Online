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
    <title>Riwayat Presensi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard_style.css">
    <style>
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
            <nav class="nav-menu">
                <div class="menu-label">Menu Utama</div>
                <a href="home_pemagang.php" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
                <a href="riwayat.php" class="nav-item"><span class="nav-icon">📖</span> Riwayat Kegiatan</a>
                <a href="riwayat_presensi.php" class="nav-item active"><span class="nav-icon">📅</span> Riwayat Presensi</a>
            </nav>
            <div style="margin-top: auto; text-align: center; padding-bottom: 20px;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus" style="width: 150px; ">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Riwayat Presensi</h2>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="home_pemagang.php" class="btn btn-back" style="margin: 0; padding: 8px 15px !important; width: auto;">Kembali</a>
                </div>
            </nav>

            <div class="content-body">
                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto;">
                    <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fce4ec; padding-bottom: 10px;">Catatan Kehadiran</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status Kehadiran</th>
                                <th>Jam Masuk Pagi</th>
                                <th>Jam Masuk Siang</th>
                                <th>Jam Pulang</th>
                                <th>Ruangan</th>
                                <th>Status Pendamping</th>
                                <th>Status Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $uid = $_SESSION['user_id'];
                            $res = mysqli_query($conn, "SELECT * FROM kehadiran WHERE user_id='$uid' ORDER BY tanggal DESC");
                            
                            if(mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_assoc($res)){
                                    $s_pendamping = $row['approved_pendamping'] == 1 ? '<span class="status-btn bg-green">Approved</span>' : '<span class="status-btn bg-red">Pending</span>';
                                    $s_petugas = $row['approved_petugas'] == 1 ? '<span class="status-btn bg-green">Approved</span>' : '<span class="status-btn bg-red">Pending</span>';

                                    echo "<tr>
                                        <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
                                        <td>".($row['status'] ?? '-')."</td>
                                        <td>".($row['jam_masuk_pagi'] ?? '-')."</td>
                                        <td>".($row['jam_masuk_siang'] ?? '-')."</td>
                                        <td>".($row['jam_pulang'] ?? '-')."</td>
                                        <td>{$row['ruangan']}</td>
                                        <td>$s_pendamping</td>
                                        <td>$s_petugas</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align:center; padding: 20px; color: #999;'>Belum ada riwayat presensi.</td></tr>";
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