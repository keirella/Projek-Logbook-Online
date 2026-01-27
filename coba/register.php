<?php 
    include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card-flag">
        <h1>Buat Akun Magang</h1>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="nim" placeholder="NIS / NIM / NIP" required>
            <input type="text" name="asal" placeholder="Asal Instansi (Sekolah/Univ)" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Daftar Sekarang</button>
        </form>
        <a href="login.php" class="btn">Sudah punya akun? Login</a>
    </div>

    <?php
    if(isset($_POST['register'])){
        $nama = encrypt_data($_POST['nama']);
        $nim = encrypt_data($_POST['nim']);
        $nim_hash = hash('sha256', $_POST['nim']); // buat pencarian login
        $asal = $_POST['asal'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $cek = mysqli_query($conn, "SELECT * FROM users WHERE nim_hash='$nim_hash'");
        if(mysqli_num_rows($cek) > 0){
            echo "<script>alert('Akun sudah terdaftar!'); window.location='register.php';</script>";
        }

        $query = "INSERT INTO users (nama, nim_nip, nim_hash, asal, password, role) VALUES ('$nama', '$nim', '$nim_hash', '$asal', '$password', 'pemagang')";
        if(mysqli_query($conn, $query)){
            echo "<script>alert('Pendaftaran Berhasil!'); window.location='login.php';</script>";
        }
    }
    ?>
</body>
</html>