<?php
    include 'config.php';
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemagang') {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Presensi - Balai Yanpus</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .presensi-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .card-flag {
            max-width: 800px !important; 
            width: 100%;
        }
        
        .form-group {
            width: 100%;
            text-align: left;
            margin-bottom: 5px;
        }
        
        .form-group label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            margin-left: 5px;
        }

        .row-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            width: 100%;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 35px;
            width: 100%;
            margin-top: 20px;
        }

        .btn-batal {
            background-color: #ccc !important;
            color: white !important;
            margin-top: 0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-batal:hover {
            background-color: #bbb !important;
        }

        button[type="submit"] {
            margin-top: 0 !important;
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
                <h2 style="color: #333; margin: 0;">Presensi Harian</h2>
                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="logout-link">Logout</a>
            </nav>

            <div class="presensi-container">
                <div class="card-flag">
                    <h1>Form Presensi</h1>
                    <form method="POST">
                        
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="<?php echo $_SESSION['nama']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>NIM/NIS/NIP</label>
                            <input type="text" name="nim" value="<?php echo $_SESSION['nim']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Asal Instansi</label>
                            <input type="text" name="asal" value="<?php echo $_SESSION['asal']; ?>" readonly>
                        </div>

                        <div class="row-inputs">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Status Kehadiran</label>
                                <select name="status" required>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Ijin">Ijin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tempat Ruangan</label>
                            <input type="text" name="ruangan" placeholder="Contoh: Ruang Koleksi Umum" required>
                        </div>

                        <div class="row-inputs">
                            <div class="form-group">
                                <label>Jam Masuk Pagi</label>
                                <input type="time" name="jam_masuk_pagi">
                            </div>
                            <div class="form-group">
                                <label>Jam Masuk Siang</label>
                                <input type="time" name="jam_masuk_siang">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jam Pulang</label>
                            <input type="time" name="jam_pulang">
                        </div>

                        <div class="button-group">
                            <a href="home_pemagang.php" class="btn btn-batal">Batal</a>
                            <button type="submit" name="submit">Kirim Presensi</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php
        if(isset($_POST['submit'])){
            $user_id = $_SESSION['user_id'];
            $nama = $_POST['nama'];
            $nim = $_POST['nim'];
            $asal = $_POST['asal'];
            $tanggal = $_POST['tanggal'];
            $status = $_POST['status'];
            $ruangan = $_POST['ruangan'];
            $jam_masuk_pagi = $_POST['jam_masuk_pagi'];
            $jam_masuk_siang = $_POST['jam_masuk_siang'];
            $jam_pulang = $_POST['jam_pulang'];

            mysqli_query($conn, "INSERT INTO kehadiran (user_id, nama, nim, asal, tanggal, status, ruangan, jam_masuk_pagi, jam_masuk_siang, jam_pulang) 
            VALUES ('$user_id', '$nama', '$nim', '$asal', '$tanggal', '$status', '$ruangan', '$jam_masuk_pagi', '$jam_masuk_siang', '$jam_pulang')");
            echo "<script>alert('Presensi Berhasil Disimpan!'); window.location='presensi.php';</script>";
        }
    ?>
</body>
</html>