<?php
    include 'config.php';
    if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemagang') {
        header("Location: login.php");
        exit; 
    }
?>

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
        $user_id = $_SESSION['user_id'];
        $nama_pendamping_magang = $_POST['p_magang'];
        $nama_pendamping_ruangan = $_POST['p_ruangan'];
        $hari_tanggal = $_POST['tanggal'];
        $tempat_ruangan = $_POST['ruangan'];
        $uraian_kegiatan = $_POST['kegiatan'];

        mysqli_query($conn, 
        "INSERT INTO logbooks (
        user_id, 
        hari_tanggal, 
        nama_pendamping_magang, 
        nama_pendamping_ruangan, 
        tempat_ruangan, 
        uraian_kegiatan
        ) 

        VALUES (
        '$user_id', 
        '$hari_tanggal', 
        '$nama_pendamping_magang', 
        '$nama_pendamping_ruangan', 
        '$tempat_ruangan', 
        '$uraian_kegiatan'
        )");
        echo "<script>alert('Logbook Berhasil Disimpan!'); window.location='index.php';</script>";
    }
    ?>
</body>
</html>