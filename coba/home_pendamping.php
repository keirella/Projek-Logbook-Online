<?php 
    include 'config.php';
    if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'pendamping' && $_SESSION['role'] !== 'petugas')) { 
        header("Location: login.php"); 
        exit; 
    }

    $role = $_SESSION['role'];
    $col_status = ($role == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';

    $stats_query = "SELECT 
        (SELECT COUNT(id) FROM users WHERE role = 'pemagang') as total_aktif,
        (SELECT COUNT(id) FROM logbooks WHERE $col_status = 0) as total_belum,
        (SELECT COUNT(id) FROM logbooks WHERE $col_status = 1) as total_sudah";
    $stats_res = mysqli_query($conn, $stats_query);
    $stats = mysqli_fetch_assoc($stats_res);

    $filter_val = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Balai Yanpus</title>
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
                <h3><?php echo $_SESSION['nama']; ?></h3>
                <p><?php echo strtoupper($role); ?></p>
            </div>
            <nav class="nav-menu">
                <div class="menu-label">Menu Utama</div>
                <a href="home_pendamping.php" class="nav-item active"><span class="nav-icon">🏠</span> Dashboard</a>
                <a href="approve_logbook.php" class="nav-item"><span class="nav-icon">✅</span> Panel Persetujuan</a>
                <a href="panel_kehadiran.php" class="nav-item"><span class="nav-icon">📅</span> Panel Kehadiran</a>
            </nav>
            <div style="margin-top: auto; text-align: center; padding-bottom: 20px;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus" style="width: 150px; ">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2>Dashboard Monitoring</h2>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>

            <div class="content-body">
                <div class="welcome-box">
                    <h2>Selamat Datang, <?php echo $_SESSION['nama']; ?>! 👋</h2>
                    <p>Monitoring mahasiswa berdasarkan <strong><?php echo ($role == 'pendamping') ? 'Instansi' : 'Ruangan'; ?></strong>.</p>
                </div>

                <div class="grid-stats">
                    <div class="stat-card"><h4>Belum Approved</h4><p style="color:#e67e22"><?php echo $stats['total_belum']; ?></p></div>
                    <div class="stat-card"><h4>Sudah Approved</h4><p style="color:#27ae60"><?php echo $stats['total_sudah']; ?></p></div>
                </div>

                <div class="quick-actions">
                    <a href="approve_logbook.php?status=belum" class="action-card">Review Logbook Baru</a>
                    <a href="panel_kehadiran.php" class="action-card" style="background: #4CAF50;">Cek Kehadiran Hari Ini</a>
                </div>

                <div class="table-container">
                    <div class="filter-header">
                        <h3>Daftar Mahasiswa Aktif</h3>
                        <form method="GET">
                            <select name="filter" class="filter-input" onchange="this.form.submit()">
                                <option value=""><?php echo ($role == 'pendamping') ? 'Semua Instansi' : 'Semua Ruangan'; ?></option>
                                <?php 
                                $opt_query = ($role == 'pendamping') ? "SELECT DISTINCT asal as val FROM users WHERE role='pemagang'" : "SELECT DISTINCT ruangan as val FROM kehadiran";
                                $opt_res = mysqli_query($conn, $opt_query);
                                while($opt = mysqli_fetch_assoc($opt_res)) {
                                    if(!$opt['val']) continue;
                                    $sel = ($filter_val == $opt['val']) ? 'selected' : '';
                                    echo "<option value='{$opt['val']}' $sel>{$opt['val']}</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>NAMA</th>
                                <th>NIM/NIP</th>
                                <th>INSTANSI</th>
                                <th>RUANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT 
                                     users.id,
                                     users.nama,
                                     users.nim_nip,
                                     users.asal,
                                     (SELECT ruangan FROM kehadiran WHERE user_id = users.id ORDER BY tanggal DESC LIMIT 1) as ruangan 
                                     FROM users 
                                     WHERE users.role = 'pemagang'";
                            
                            if($filter_val != '') {
                                if($role == 'pendamping') {
                                    $sql .= " AND users.asal = '$filter_val'";
                                } else {
                                    $sql .= " HAVING ruangan = '$filter_val'";
                                }
                            } 

                            $res = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>
                                    <td><strong>".decrypt_data($row['nama'])."</strong></td>
                                    <td>".decrypt_data($row['nim_nip'])."</td>
                                    <td>{$row['asal']}</td>
                                    <td>".($row['ruangan'] ?? '-')."</td>
                                </tr>";
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