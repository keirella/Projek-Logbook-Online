<?php 
    include 'config.php'; 
     
    if(isset($_SESSION['user_id'])) header("Location: index.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="centered-page">
    <div class="card-flag">
        <h1>Login Logbook</h1>
        <form method="POST">
            <input type="text" name="nim" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        
        <p style="margin-top: 20px; font-size: 14px; color: #666;">
            Belum punya akun? 
            <a href="register.php" style="color: #FAB12F; text-decoration: none; font-weight: bold;">Daftar di sini</a>
        </p>
    </div>

    <?php
    if(isset($_POST['login'])){
        $nim = $_POST['nim'];
        $nim_hash = hash('sha256', $nim);
        $password = $_POST['password'];

        $res = mysqli_query($conn, "SELECT * FROM users WHERE nim_hash='$nim_hash'");
        if($row = mysqli_fetch_assoc($res)){
            if(password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nama'] = decrypt_data($row['nama']);
                $_SESSION['nim'] = decrypt_data($row['nim_nip']);
                $_SESSION['asal'] = $row['asal'];
                $_SESSION['role'] = $row['role'];
                header("Location: index.php");
            } else { echo "<script>alert('Password Salah!');</script>"; }
        } else { echo "<script>alert('Akun tidak ditemukan!');</script>"; }
    }
    ?>
</body>
</html>
