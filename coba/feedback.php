<?php 
    include 'config.php';
    if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'pendamping' && $_SESSION['role'] !== 'petugas')) { 
        header("Location: login.php"); 
        exit; 
    }

    $logbook_id = $_GET['logbook_id'];
    
    $query = "SELECT logbooks.*, users.nama as nama_pemagang FROM logbooks 
              JOIN users ON logbooks.user_id = users.id 
              WHERE logbooks.id = '$logbook_id'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);

    if(isset($_POST['do_approve'])){
        $nama_pemeriksa = mysqli_real_escape_string($conn, $_POST['nama_pemeriksa']);
        $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
        $target_user_id = $data['user_id']; 
        
        if($_SESSION['role'] == 'pendamping') {
            $sql = "UPDATE logbooks SET 
                    approved_pendamping = 1, 
                    nama_pendamping_magang = '$nama_pemeriksa', 
                    feedback_pendamping = '$feedback' 
                    WHERE id = '$logbook_id'";
        } else {
            $sql = "UPDATE logbooks SET 
                    approved_petugas = 1, 
                    nama_pendamping_ruangan = '$nama_pemeriksa', 
                    feedback_petugas = '$feedback' 
                    WHERE id = '$logbook_id'";
        }
        
        if(mysqli_query($conn, $sql)){
            echo "<script type='text/javascript'>
                    alert('Logbook Berhasil di-Approved!'); 
                    window.location.replace('review_logbook.php?user_id=" . $target_user_id . "');
                  </script>";
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback Logbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="profile-img">🛡️</div>
                <h3><?php echo $_SESSION['nama']; ?></h3>
                <p style="font-size: 12px; color: #666;">Pemberi Feedback (<?php echo ucfirst($_SESSION['role']); ?>)</p>
            </div>
            <div style="margin-top: auto; text-align: center;">
                <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus">
            </div>
        </aside>

        <main class="main-content">
            <nav class="navbar">
                <h2 style="color: #333; margin: 0;">Feedback & Approval</h2>
                <a href="review_logbook.php?user_id=<?php echo $data['user_id']; ?>" class="btn btn-dash" style="width:auto; padding: 8px 15px;">Kembali</a>
            </nav>

            <div class="content-body">
                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;">
                    <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fce4ec; padding-bottom: 10px;">Detail Logbook Magang</h3>
                    <table style="margin-top: 15px; width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Anak Magang</th>
                                <th>Tanggal Isi</th>
                                <th>Ruangan</th>
                                <th>Uraian Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo decrypt_data($data['nama_pemagang']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($data['hari_tanggal'])); ?></td>
                                <td><?php echo $data['tempat_ruangan']; ?></td>
                                <td><?php echo $data['uraian_kegiatan']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui logbook ini dan menyimpan feedback?');">
                        <div style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom: 5px; font-weight: bold; color: #555;">Nama Pemeriksa:</label>
                            <input type="text" name="nama_pemeriksa" value="<?php echo $_SESSION['nama']; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display:block; margin-bottom: 5px; font-weight: bold; color: #555;">Tanggal Feedback:</label>
                            <input type="text" value="<?php echo date('d-m-Y'); ?>" readonly style="background: #f9f9f9; cursor: not-allowed; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display:block; margin-bottom: 5px; font-weight: bold; color: #555;">Feedback / Catatan (sebagai <?php echo $_SESSION['role']; ?>):</label>
                            <textarea name="feedback" required placeholder="Tuliskan feedback Anda di sini..." style="width: 100%; min-height: 100px; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-family: inherit;"></textarea>
                        </div>

                        <button type="submit" name="do_approve" class="btn" style="background-color: #FAB12F; color: white; border: none; font-weight: bold; cursor: pointer; width: 100%; padding: 12px; border-radius: 10px;">Approve & Simpan Feedback</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>