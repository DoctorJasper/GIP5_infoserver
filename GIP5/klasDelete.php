<?php
    // Inclusief het header-bestand
    require('../header.php');

    // Controleer of de gebruiker een admin is. Zo niet, stuur door naar de index pagina.
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Inclusief benodigde bestanden voor databaseverbinding en andere configuraties
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');
    require('datetime.php');

    // Controleer of de request methode GET is en of de 'klas' parameter is ingesteld
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['klas'])) {
        // Haal de waarde van de 'klas' parameter op
        $klas = $_GET['klas'];
        
        // Inclusief het pdo-bestand opnieuw om zeker te zijn van de verbinding
        require("pdo.php");
        
        // Query template om een klas te verwijderen
        $query = "DELETE FROM `tblKlassen` WHERE `klas` = :ID";

        // Waarden voor de query
        $values = [":ID" => $klas];

        // Voer de query uit
        try {
            // Bereid de query voor en voer deze uit met de gegeven waarden
            $res = $pdo->prepare($query);
            $res->execute($values);
            
            // Stel een melding in en log de verwijdering
            $toast->set("fa-exclamation-triangle", "Melding", "", "Klas '". $_GET["klas"]."' verwijderd", "success");
            file_put_contents("log.txt", $timestamp." || Klas '". $_GET["klas"]."' verwijderd".PHP_EOL, FILE_APPEND);
            
            // Stuur door naar de overzichtspagina
            header("Location: klasOverview.php");
            exit;
        } catch (PDOException $e) {
            // Bij een fout, stel een melding in en log de fout
            $toast->set("fa-exclamation-triangle", "Melding", "", "Gefaald om klas '". $_GET["klas"]."' te verwijderen", "danger");
            file_put_contents("log.txt", $timestamp." || Gefaald om klas '". $_GET["klas"]."' te verwijderen".PHP_EOL, FILE_APPEND);
            
            // Stuur door naar de overzichtspagina
            header("Location: klasOverview.php");
            exit;
        }
    }

    // Inclusief het HTML start-bestand
    require('../startHTML.php');
    
    // Inclusief de navigatiebalk
    require('../navbar.php');

    // Inclusief de footer-bestanden
    require('../footer1.php');
    require('../footer2.php');
?>
