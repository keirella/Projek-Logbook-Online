<?php 
    include 'config.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="centered-page">
    <div class="card-flag">
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="image/Logo.png" alt="Logo Yanpus" class="logo-yanpus">
        </div>
        <h1>Update Password</h1>
        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">Masukkan Username dan Password Baru Anda</p>
        
        <form method="POST">
            <input type="text" name="nim" placeholder="Masukkan Username (NIM/NIP)" required>
            <input type="password" name="new_password" placeholder="Password Baru" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
            
            <button type="submit" name="update_pass">Update Password Sekarang</button>
            <a href="login.php" class="btn" style="background-color: #F3C623 !important; margin-top: 10px;">Batal / Kembali</a>
        </form>
    </div>

    <?php
    if(isset($_POST['update_pass'])){
        $nim = $_POST['nim'];
        $nim_hash = hash('sha256', $nim);
        $new_pass = $_POST['new_password'];
        $conf_pass = $_POST['confirm_password'];

        if($new_pass !== $conf_pass){
            echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
        } else {
            $check = mysqli_query($conn, "SELECT id FROM users WHERE nim_hash='$nim_hash'");
            if(mysqli_num_rows($check) > 0){
                $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                
                // update ke database
                $sql = "UPDATE users SET password = '$hashed_new_pass' WHERE nim_hash = '$nim_hash'";
                if(mysqli_query($conn, $sql)){
                    echo "<script>
                            alert('Password berhasil diperbarui! Silakan login kembali.');
                            window.location.href='login.php';
                          </script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('Username tidak ditemukan! Pastikan NIM/NIP benar.');</script>";
            }
        }
    }
    ?>
</body>
</html>