<?php 
    include 'config.php';
    if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'pendamping' && $_SESSION['role'] !== 'petugas')) { 
        header("Location: login.php"); 
        exit; 
    }

    $status_filter = isset($_GET['status']) ? $_GET['status'] : 'belum';
    $col_status = ($_SESSION['role'] == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';
    $val_status = ($status_filter == 'sudah') ? 1 : 0;

    $asal_filter = isset($_GET['asal']) ? mysqli_real_escape_string($conn, $_GET['asal']) : '';

    $stats_query = "SELECT 
        (SELECT COUNT(id) FROM users WHERE role = 'pemagang') as total_aktif,
        (SELECT COUNT(id) FROM logbooks WHERE $col_status = 0) as total_belum,
        (SELECT COUNT(id) FROM logbooks WHERE $col_status = 1) as total_sudah";
    $stats_res = mysqli_query($conn, $stats_query);
    $stats = mysqli_fetch_assoc($stats_res);

    $list_asal_query = "SELECT DISTINCT asal FROM users WHERE role = 'pemagang' ORDER BY asal ASC";
    $list_asal_res = mysqli_query($conn, $list_asal_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel Persetujuan | Balai Yanpus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard_style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">👤</div>
                <h3 style="margin:0; font-size:16px;"><?php echo $_SESSION['nama']; ?></h3>
                <p style="margin:5px 0 0; font-size:11px; color:#999;"><?php echo strtoupper($_SESSION['role']); ?></p>
            </div>
            <nav class="nav-menu">
                <div class="menu-label">Menu Utama</div>
                <a href="home_pendamping.php" class="nav-item">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
                <a href="approve_logbook.php" class="nav-item active">
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
                <h2 style="margin:0;">Panel Persetujuan</h2>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>

            <div class="content-body">
                <div class="grid-stats">
                    <div class="stat-card">
                        <h4>Belum Approved</h4>
                        <p style="color: #e67e22;"><?php echo $stats['total_belum']; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Sudah Approved</h4>
                        <p style="color: #27ae60;"><?php echo $stats['total_sudah']; ?></p>
                    </div>
                </div>

                <div class="grid-buttons">
                    <a href="?status=belum" class="btn-status <?php echo $status_filter == 'belum' ? 'active-btn' : 'inactive-btn'; ?>">
                        BELUM APPROVED
                    </a>
                    <a href="?status=sudah" class="btn-status <?php echo $status_filter == 'sudah' ? 'active-btn' : 'inactive-btn'; ?>">
                        SUDAH APPROVED
                    </a>
                </div>

                <div class="table-container">
                    <div class="filter-header">
                        <h3 style="margin:0; font-size: 16px;">Daftar Mahasiswa</h3>
                        <form method="GET">
                            <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                            <select name="asal" class="filter-input" onchange="this.form.submit()">
                                <option value="">Semua Instansi</option>
                                <?php 
                                while($a = mysqli_fetch_assoc($list_asal_res)) {
                                    $selected = ($asal_filter == $a['asal']) ? 'selected' : '';
                                    echo "<option value='{$a['asal']}' $selected>{$a['asal']}</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>NAMA MAHASISWA</th>
                                <th>NIS / NIM</th>
                                <th>INSTANSI</th>
                                <th style="text-align: center;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT DISTINCT users.id, users.nama, users.nim_nip, users.asal 
                                    FROM users 
                                    JOIN logbooks ON users.id = logbooks.user_id 
                                    WHERE logbooks.$col_status = $val_status AND users.role = 'pemagang'";
                            
                            if($asal_filter != '') {
                                $sql .= " AND users.asal = '$asal_filter'";
                            }

                            $res = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($res) > 0) {
                                while($row = mysqli_fetch_assoc($res)){
                                    echo "<tr>
                                        <td><strong>".decrypt_data($row['nama'])."</strong></td>
                                        <td>".decrypt_data($row['nim_nip'])."</td>
                                        <td>{$row['asal']}</td>
                                        <td style='text-align: center;'>
                                            <a href='review_logbook.php?user_id={$row['id']}' class='btn-review'>REVIEW</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align:center; padding: 20px; color: #999;'>Tidak ada data mahasiswa.</td></tr>";
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