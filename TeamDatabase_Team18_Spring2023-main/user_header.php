<?php
    session_start();
    //echo pre('111111111111111111111111');
    //echo pre($_SESSION);
    //echo pre($_SESSION);
    //echo pre($_SESSION['ID']);
    if(!isset($_SESSION['ID']) && !isset($_SESSION['userType'])){
        header('location:login.php');
    }
?>
