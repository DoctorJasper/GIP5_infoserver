<?php
    require('pdo.php');

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["klas"])) {
        $klas = $_GET["klas"];
        // Update the "klas" in the database
        $query = "INSERT INTO `tblKlassen`(`klas`) VALUES ('" . $klas . "')";

        try{
            $res = $pdo->prepare($query);
            $res->execute();
            header("Location: klasOverview.php");
            exit;
        }catch(PDOException $e){
            //error in de query
            echo 'Query errors';
            die();
        }
    } else {
        header("Location: ../index.php");
        die();
    }
?>