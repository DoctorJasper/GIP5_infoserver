<?php
    require('../header.php');
// hieronder zet je PHP code
   
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['klas'])) {
        $klas = $_GET['klas'];
        require("pdo.php");
        //Update query template
        $query = "UPDATE `tblKlassen`
        SET `active` = 0
        WHERE `klas` = :ID";

        $values = [":ID" => $klas];

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);    
            $row = $res->fetch(PDO::FETCH_ASSOC);

            $toast->set("fa-exclamation-triangle", "Meldig","", "Klas '". $row["klas"]."' verwijderd","success");
            file_put_contents("log.txt","Klas '". $row["klas"]."' verwijderd".date("Y-m-d").PHP_EOL, FILE_APPEND);
            header("Location: klasOverview.php");
            exit;
        } catch (PDOException $e) 
        {
            $toast->set("fa-exclamation-triangle", "Meldig","", "Gefaald om klas '". $row["klas"]."' te verwijderen","danger");
            file_put_contents("log.txt","Gefaald om klas '". $row["klas"]."' te verwijderen".date("Y-m-d").PHP_EOL, FILE_APPEND);
            header("Location: klasOverview.php");
            exit;
        }
    }
    
    require('../startHTML.php');
    require('../navbar.php');

    require('../footer1.php');
    require('../footer2.php');
?>