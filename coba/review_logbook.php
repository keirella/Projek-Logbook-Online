<?php
include 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$target_user_id = $_GET['user_id'];
$col_to_update = ($_SESSION['role'] == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Review Logbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">🛡️</div>
                <h3><?php echo $_SESSION['nama']; ?></h3>
                <p style="font-size: 12px; color: #666;">Mode Review</p>
            </div>
            <div style="margin-top: auto; text-align: center;">
                <img src="image/logo_yanpus.png" alt="Logo Yanpus" class="logo-yanpus">
                <p style="font-size: 10px;">Balai Yanpus</p>
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Detail Review Logbook</h2>
                <a href="approve_logbook.php" class="btn btn-dash" style="width: auto; padding: 8px 15px;">Kembali</a>
            </nav>

            <div class="content-body">
                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>HARI TANGGAL</th>
                                <th>RUANGAN</th>
                                <th>URAIAN</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM logbooks WHERE user_id='$target_user_id' ORDER BY hari_tanggal DESC");
                            while($row = mysqli_fetch_assoc($res)){
                                $approved = ($row[$col_to_update] == 1);
                                echo "<tr>
                                    <td>".date('d-m-Y', strtotime($row['hari_tanggal']))."</td>
                                    <td>{$row['tempat_ruangan']}</td>
                                    <td>{$row['uraian_kegiatan']}</td>
                                    <td>";
                                if($approved){
                                    echo "<span style='color: #2ecc71; font-weight:bold;'>✓ Approved</span>";
                                } else {
                                    echo "<a href='feedback.php?logbook_id={$row['id']}' class='status-btn' style='background-color:#FAB12F; color:white; text-decoration:none; display:inline-block;'>Approve Now</a>";
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