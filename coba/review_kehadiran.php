<?php
include 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$target_user_id = $_GET['user_id'];
$col_to_update = ($_SESSION['role'] == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Review Kehadiran</title>
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
<body> <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">👤</div>
                <h3 style="margin:0; font-size:16px;"><?php echo $_SESSION['nama']; ?></h3>
                <p style="margin:5px 0 0; font-size:11px; color:#999;"><?php echo strtoupper($_SESSION['role']); ?></p>
            </div>
            <nav class="nav-menu">
                <div class="menu-label">Menu Utama</div>
                <a href="home_pendamping.php" class="nav-item active">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
                <a href="approve_logbook.php" class="nav-item">
                    <span class="nav-icon">✅</span> Panel Persetujuan
                </a>
                <a href="panel_kehadiran.php" class="nav-item">
                    <span class="nav-icon">📅</span> Panel Kehadiran
                </a>
            </nav>
            <div style="margin-top: auto; text-align: center; padding-bottom: 20px;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus" style="width: 150px; height: 40px;">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Detail Review Kehadiran</h2>
                <a href="panel_kehadiran.php" class="btn btn-dash-dash" style="width: auto; padding: 8px 15px;">Kembali</a>
            </nav>

            <div class="content-body">
                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>HARI TANGGAL</th>
                                <th>STATUS</th>
                                <th>RUANGAN</th>
                                <th>JAM MASUK PAGI</th>
                                <th>JAM MASUK SIANG</th>
                                <th>JAM PULANG</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM kehadiran WHERE user_id='$target_user_id' ORDER BY tanggal DESC");
                            while($row = mysqli_fetch_assoc($res)){
                                $approved = ($row[$col_to_update] == 1);
                                echo "<tr>
                                    <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
                                    <td>{$row['status']}</td>
                                    <td>{$row['ruangan']}</td>
                                    <td>{$row['jam_masuk_pagi']}</td>
                                    <td>{$row['jam_masuk_siang']}</td>
                                    <td>{$row['jam_pulang']}</td>
                                    <td>";
                                if($approved){
                                    echo "<span style='color: #2ecc71; font-weight:bold;'>✓ Approved</span>";
                                } else {
                                    echo "<a href='#' onclick=\"if(confirm('Apakah Anda yakin ingin menyetujui kehadiran mahasiswa ini?')) { window.location.href='panel_kehadiran.php?approve_id={$row['id']}'; }\" class='btn-approve'>Approve Now</a>";
                                }
                                echo "</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>