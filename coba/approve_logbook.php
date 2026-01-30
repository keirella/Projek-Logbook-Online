<?php 
    include 'config.php';
    if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'pendamping' && $_SESSION['role'] !== 'petugas')) { 
        header("Location: login.php"); 
        exit; 
    }
    $status_filter = isset($_GET['status']) ? $_GET['status'] : 'belum';
    $col_status = ($_SESSION['role'] == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';
    $val_status = ($status_filter == 'sudah') ? 1 : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">🛡️</div>
                <h3><?php echo $_SESSION['nama']; ?></h3>
                <p style="font-size: 12px; color: #666;">Ruangan: <?php echo $_SESSION['asal']; ?></p>
            </div>
            <div style="margin-top: auto; text-align: center;">
                <img src="image/logo_yanpus.png" alt="Logo Yanpus" class="logo-yanpus">
                <p style="font-size: 10px;">Balai Yanpus</p>
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Panel Persetujuan</h2>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>

            <div class="content-body">
                <div class="grid-menu" style="margin-bottom: 30px;">
                    <a href="?status=belum" class="btn btn-dash <?php echo $status_filter == 'belum' ? '' : 'bg-red'; ?>" style="margin:0;">
                        <?php echo $status_filter == 'belum' ? '● ' : ''; ?>BELUM APPROVED
                    </a>
                    <a href="?status=sudah" class="btn btn-dash <?php echo $status_filter == 'sudah' ? '' : 'bg-green'; ?>" style="margin:0;">
                        <?php echo $status_filter == 'sudah' ? '● ' : ''; ?>SUDAH APPROVED
                    </a>
                </div>

                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fce4ec; padding-bottom: 10px;">
                        DAFTAR MAGANG <?php echo strtoupper($status_filter); ?> APPROVED
                    </h3>
                    <table>
                        <thead>
                            <tr>
                                <th>NAMA ANAK MAGANG</th>
                                <th>NIS / NIM / NIP</th>
                                <th>ASAL</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT DISTINCT users.id, users.nama, users.nim_nip, users.asal 
                                      FROM users 
                                      JOIN logbooks ON users.id = logbooks.user_id 
                                      WHERE logbooks.$col_status = $val_status AND users.role = 'pemagang'";
                            $res = mysqli_query($conn, $query);
                            if(mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_assoc($res)){
                                    echo "<tr>
                                        <td>".decrypt_data($row['nama'])."</td>
                                        <td>".decrypt_data($row['nim_nip'])."</td>
                                        <td>{$row['asal']}</td>
                                        <td>
                                            <a href='review_logbook.php?user_id={$row['id']}' class='status-btn bg-green' style='text-decoration:none; display:inline-block;'>
                                                REVIEW
                                            </a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align:center; padding: 20px; color: #999;'>Tidak ada data anak magang.</td></tr>";
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
