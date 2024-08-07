<?php
session_start();
include('connect.php')

if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
    }

?>