<?php
    require('../header.php');

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');

    
    if(isset($_POST["btnDeleteUsers"])) {
        $idLeerlingen = $_POST["leerlingen"];
        $idSorted = implode(", ", $idLeerlingen);

        $query = "UPDATE `tblGebruiker` SET `active` = 0 WHERE `internNr` IN($idSorted)";

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            $toast->set("fa-exclamation-triangle", "Gebruikers","", "Users met internnummer '$idSorted' verwijderd","success");
            file_put_contents("log.txt","verwijderd users met internnummer '$idSorted' - ".date("Y-m-d").PHP_EOL, FILE_APPEND);
        } catch (PDOException $e) 
        {   
            $toast->set("fa-exclamation-triangle", "Error","", "Gefaald om users met internnummer '$idSorted' te verwijderen","danger");
            file_put_contents("log.txt","verwijderen van users met internnummer '$idSorted' mislukt - ".date("Y-m-d").PHP_EOL, FILE_APPEND);
        }
    }
    elseif(isset($_POST["btnAcivateUsers"])) {
        $idLeerlingen = $_POST["leerlingen"];
        $idSorted = implode(", ", $idLeerlingen);

        $query = "UPDATE `tblGebruiker` SET `active` = 1 WHERE `internNr` IN($idSorted)";

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            $toast->set("fa-exclamation-triangle", "Gebruikers","", "Users met internnummer '$idSorted' verwijderd","success");
            file_put_contents("log.txt","verwijderd users met internnummer '$idSorted' - ".date("Y-m-d").PHP_EOL, FILE_APPEND);
        } catch (PDOException $e) 
        {   
            $toast->set("fa-exclamation-triangle", "Error","", "Gefaald om users met internnummer '$idSorted' te verwijderen","danger");
            file_put_contents("log.txt","verwijderen van users met internnummer '$idSorted' mislukt - ".date("Y-m-d").PHP_EOL, FILE_APPEND);
        }
    }

    header("Location: userOverview.php");
    exit;
?>