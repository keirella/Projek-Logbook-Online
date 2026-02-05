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
    <title>Riwayat Logbook</title>
    <link rel="stylesheet" href="style.css">
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
                <h2 style="color: #333; margin: 0;">Riwayat Kegiatan</h2>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="index.php" class="btn btn-back" style="margin: 0; padding: 8px 15px !important; width: auto;">Kembali</a>
                </div>
            </nav>

            <div class="content-body">
                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto;">
                    <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fce4ec; padding-bottom: 10px;">Daftar Aktivitas Anda</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Hari/Tanggal</th>
                                <th>Pendamping Magang</th>
                                <th>Petugas Ruangan</th>
                                <th>Ruangan</th>
                                <th>Uraian</th>
                                <th>Status Magang</th>
                                <th>Status Ruang</th>
                                <th>Feedback Pendamping</th>
                                <th>Feedback Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $uid = $_SESSION['user_id'];
                            $res = mysqli_query($conn, "SELECT * FROM logbooks WHERE user_id='$uid' ORDER BY hari_tanggal DESC");
                            
                            if(mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_assoc($res)){
                                    $s_magang = $row['approved_pendamping'] == 1 ? '<span class="status-btn bg-green">Approved</span>' : '<span class="status-btn bg-red">Pending</span>';
                                    $s_ruang = $row['approved_petugas'] == 1 ? '<span class="status-btn bg-green">Approved</span>' : '<span class="status-btn bg-red">Pending</span>';
                                    
                                    $f_pendamping = ($row['approved_pendamping'] == 1 && !empty($row['feedback'])) ? $row['feedback'] : '-';
                                    $f_petugas = ($row['approved_petugas'] == 1 && !empty($row['feedback'])) ? $row['feedback'] : '-';

                                    echo "<tr>
                                        <td>".date('d M Y', strtotime($row['hari_tanggal']))."</td>
                                        <td>{$row['nama_pendamping_magang']}</td>
                                        <td>{$row['nama_pendamping_ruangan']}</td>
                                        <td>{$row['tempat_ruangan']}</td>
                                        <td style='min-width: 150px;'>{$row['uraian_kegiatan']}</td>
                                        <td>$s_magang</td>
                                        <td>$s_ruang</td>
                                        <td style='font-size: 12px; font-style: italic; color: #666;'>$f_pendamping</td>
                                        <td style='font-size: 12px; font-style: italic; color: #666;'>$f_petugas</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9' style='text-align:center; padding: 20px; color: #999;'>Belum ada riwayat kegiatan.</td></tr>";
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