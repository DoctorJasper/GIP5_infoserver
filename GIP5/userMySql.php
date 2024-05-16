<?php
    require("../header.php");

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');

    $query = "CREATE USER 'username'@'%' IDENTIFIED BY 'password';GRANT USAGE ON *.* TO 'username'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;CREATE DATABASE IF NOT EXISTS `username`;GRANT ALL PRIVILEGES ON `username`.* TO 'username'@'%';";
    $query = str_replace("username","06INFOKyan",$query);
    $query = str_replace("password","pw123",$query);
    
    try {
        // Prepare and execute the update query
        $res = $pdo->prepare($query);
        $res->execute();
    
        header("Location: klasOverview.php");
        exit;  
    } catch (PDOException $e) {
        header("Location: klasOverview.php");
        exit;
    }
?>