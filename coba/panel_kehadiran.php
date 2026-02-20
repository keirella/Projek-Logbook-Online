<?php 
    include 'config.php';
    if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'pendamping' && $_SESSION['role'] !== 'petugas')) { 
        header("Location: login.php"); exit; 
    }

    $role = $_SESSION['role'];
    $filter_val = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

    if(isset($_GET['approve_id'])) {
        $id_to_approve = mysqli_real_escape_string($conn, $_GET['approve_id']);
        $col_target = ($role == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';
        
        // Menggunakan update pada ID spesifik kehadiran agar data langsung berubah tepat sasaran
        $update = mysqli_query($conn, "UPDATE kehadiran SET $col_target = 1 WHERE id = '$id_to_approve'");
        
        // Redirect kembali ke halaman asal (atau review_kehadiran) agar data terbaru di-fetch ulang oleh browser
        $redirect = isset($_GET['user_id']) ? "review_kehadiran.php?user_id=".$_GET['user_id'] : "panel_kehadiran.php";
        echo "<script>alert('Kehadiran berhasil di-approve!'); window.location.href='$redirect';</script>";
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel Kehadiran | Balai Yanpus</title>
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
                <a href="home_pendamping.php" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
                <a href="approve_logbook.php" class="nav-item"><span class="nav-icon">✅</span> Panel Persetujuan</a>
                <a href="panel_kehadiran.php" class="nav-item active"><span class="nav-icon">📅</span> Panel Kehadiran</a>
            </nav>
            <div style="margin-top: auto; text-align: center; padding-bottom: 20px;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus" style="width: 150px; ">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2>Monitoring Kehadiran</h2>
                <a href="logout.php" class="logout-link">Logout</a>
            </nav>
            <div class="content-body">
                <div class="table-container">
                    <div class="filter-header">
                        <h3>Daftar Kehadiran</h3>
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
                                <th>NAMA MAHASISWA</th>
                                <th><?php echo ($role == 'pendamping') ? 'INSTANSI' : 'RUANGAN'; ?></th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                                        u.id, 
                                        u.nama, 
                                        u.asal, 
                                        (SELECT k.ruangan FROM kehadiran k WHERE k.user_id = u.id ORDER BY k.tanggal DESC LIMIT 1) as ruangan_aktif
                                    FROM users u 
                                    WHERE u.role = 'pemagang'";
                            
                            if($filter_val != '') {
                                if($role == 'pendamping') {
                                    $sql .= " AND u.asal = '$filter_val'";
                                } else {
                                    $sql .= " HAVING ruangan_aktif = '$filter_val'";
                                }
                            }
                            
                            $res = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($res)) {
                                $lokasi = ($role == 'pendamping') ? $row['asal'] : ($row['ruangan_aktif'] ?? '-');
                                echo "<tr>
                                    <td><strong>".decrypt_data($row['nama'])."</strong></td>
                                    <td>".$lokasi."</td>
                                    <td>
                                        <a href='review_kehadiran.php?user_id={$row['id']}' class='btn-pending'>Detail</a>
                                    </td>
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