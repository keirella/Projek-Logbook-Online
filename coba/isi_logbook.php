<?php include 'config.php'; if(!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Isi Logbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card-flag">
        <h1>Form Logbook</h1>
        <form method="POST">
            <input type="text" name="p_magang" placeholder="Nama Pendamping Magang" required>
            <input type="text" name="p_ruangan" placeholder="Nama Pendamping Ruangan" required>
            <input type="date" name="tanggal" required>
            <input type="text" name="ruangan" placeholder="Tempat Ruangan" required>
            <textarea name="kegiatan" placeholder="Uraian Kegiatan" rows="5" required></textarea>
            <button type="submit" name="submit_log">Submit Logbook</button>
        </form>
        <a href="index.php" class="btn">⬅ Kembali</a>
    </div>

    <?php
    if(isset($_POST['submit_log'])){
        $uid = $_SESSION['user_id'];
        $pm = $_POST['p_magang'];
        $pr = $_POST['p_ruangan'];
        $tgl = $_POST['tanggal'];
        $rng = $_POST['ruangan'];
        $kgt = $_POST['kegiatan'];

        mysqli_query($conn, "INSERT INTO logbooks (user_id, hari_tanggal, nama_pendamping_magang, nama_pendamping_ruangan, tempat_ruangan, uraian_kegiatan) 
                             VALUES ('$uid', '$tgl', '$pm', '$pr', '$rng', '$kgt')");
        echo "<script>alert('Logbook Berhasil Disimpan!'); window.location='index.php';</script>";
    }
    ?>
</body>
</html>