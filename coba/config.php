<?php
    session_start();
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "logbook_magang";

    $conn = mysqli_connect($host, $user, $pass, $db);

    if(!$conn) {
        die("Koneksi gagal".mysqli_connect_error());
    }

    // ntar key nya ganti terserah bingung
    define('ENCRYPTION_KEY', 'p@ssw0rd_rahasia_123_magang');
    define('ENCRYPTION_IV', '1234567890123456');

    function encrypt_data($data) {
        return openssl_encrypt($data, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
    }

    function decrypt_data($data) {
        return openssl_decrypt($data, 'AES-256-CBC', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
    }
?>