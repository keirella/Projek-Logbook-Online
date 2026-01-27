<?php 
    session_start();
    include 'config.php';

    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    if($_SESSION['role'] === 'pemagang') {
        header("Location: home_pemagang.php");
    } 
    else if($_SESSION['role'] === 'pendamping' || $_SESSION['role'] === 'petugas') {
        header("Location: approve_logbook.php");
    } 
    else {
        echo "Role tidak dikenali.";
    }
    exit;
?>