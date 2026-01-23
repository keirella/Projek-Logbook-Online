<?php include 'config.php'; if(!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Logbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card-flag" style="max-width: 900px;">
        <h1>Riwayat Kegiatan</h1>

        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
                <div style="overflow-x:auto;">
                    <table>
                        <tr>
                            <th>Hari/Tanggal</th>
                            <th>Pendamping Magang</th>
                            <th>Petugas Ruangan</th>
                            <th>Ruangan</th>
                            <th>Uraian</th>
                            <th>Aksi</th>
                            <th>Export Logbook</th>
                        </tr>
                        <?php
                        $uid = $_SESSION['user_id'];
                        $res = mysqli_query($conn, "SELECT * FROM logbooks WHERE user_id='$uid' ORDER BY hari_tanggal DESC");
                        while($row = mysqli_fetch_assoc($res)){
                            $s_magang = $row['status_magang'] == 1 ? '<button class="status-btn bg-green">Approved</button>' : '<button class="status-btn bg-red">Belum Approved</button>';
                            $s_ruang = $row['status_ruangan'] == 1 ? '<button class="status-btn bg-green">Approved</button>' : '<button class="status-btn bg-red">Belum Approved</button>';
                            
                            echo "<tr>
                                <td>{$row['hari_tanggal']}</td>
                                <td>{$row['nama_pendamping_magang']}</td>
                                <td>{$row['nama_pendamping_ruangan']}</td>
                                <td>{$row['tempat_ruangan']}</td>
                                <td>{$row['uraian_kegiatan']}</td>
                                <td>$s_magang</td>
                                <td>$s_ruang</td>
                            </tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <a href="index.php" class="btn">⬅ Kembali ke Halaman Utama</a>
    </div>
</body>
</html>