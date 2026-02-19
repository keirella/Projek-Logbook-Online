<?php 
    include 'config.php';
    if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'pendamping' && $_SESSION['role'] !== 'petugas')) { 
        header("Location: login.php"); exit; 
    }

    $role = $_SESSION['role'];
    $filter_val = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';
    $filter_col = ($role == 'pendamping') ? 'asal' : 'ruangan';

    if(isset($_GET['approve_id'])) {
        $id_to_approve = $_GET['approve_id'];
        $col_target = ($role == 'pendamping') ? 'approved_pendamping' : 'approved_petugas';
        $update = mysqli_query($conn, "UPDATE logbooks SET $col_target = 1 WHERE user_id = '$id_to_approve' AND $col_target = 0");
        
        echo "<script>alert('Kehadiran berhasil di-approve!'); window.location.href='panel_kehadiran.php';</script>";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel Kehadiran | Balai Yanpus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard_style.css">
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
        </aside>

        <main class="main-content">
            <nav class="navbar"><h2>Monitoring Kehadiran</h2></nav>
            <div class="content-body">
                <div class="table-container">
                    <div class="filter-header">
                        <h3>Daftar Kehadiran</h3>
                        <form method="GET">
                            <select name="filter" class="filter-input" onchange="this.form.submit()">
                                <option value=""><?php echo ($role == 'pendamping') ? 'Semua Instansi' : 'Semua Ruangan'; ?></option>
                                <?php 
                                $opt_query = ($role == 'pendamping') ? "SELECT DISTINCT asal as val FROM users WHERE role='pemagang'" : "SELECT DISTINCT ruangan as val FROM users WHERE role='pemagang'";
                                $opt_res = mysqli_query($conn, $opt_query);
                                while($opt = mysqli_fetch_assoc($opt_res)) {
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
                            $sql = "SELECT id, nama, asal, ruangan FROM users WHERE role = 'pemagang'";
                            if($filter_val != '') $sql .= " AND $filter_col = '$filter_val'";
                            
                            $res = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>
                                    <td><strong>".decrypt_data($row['nama'])."</strong></td>
                                    <td>".($role == 'pendamping' ? $row['asal'] : $row['ruangan'])."</td>
                                    <td>
                                        <a href='review_logbook.php?user_id={$row['id']}' class='btn-pending'>Detail</a>
                                        <a href='#' onclick=\"if(confirm('Apakah Anda yakin ingin menyetujui kehadiran mahasiswa ini?')) { window.location.href='panel_kehadiran.php?approve_id={$row['id']}'; }\" class='btn-approve'>Approve Now</a>
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