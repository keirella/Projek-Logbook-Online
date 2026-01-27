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
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card-flag">
        <h1>Halo, <?php echo $_SESSION['nama']; ?></h1>
        <p><b>NIS/NIM/NIP :</b> <?php echo $_SESSION['nim']; ?></p>
        <p><b>Asal :</b> <?php echo $_SESSION['asal']; ?></p>
        <hr>
        <a href="isi_logbook.php" class="btn">📝 Isi Logbook</a>
        <a href="riwayat.php" class="btn">🕒 Riwayat Logbook</a>
        <a href="logout.php" style="color:red; display:block; margin-top:20px;">Logout</a>
    </div>
</body>
</html>