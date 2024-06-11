<?php
    // Inclusief het header-bestand
    require('../header.php');

    // Controleer of de gebruiker een admin is. Zo niet, stuur door naar de indexpagina.
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Inclusief het PDO-bestand
    require('pdo.php');

    // Als de knop "Verwijder gebruikers" is ingedrukt
    if(isset($_POST["btnDeleteUsers"])) {
        // Ontvang de geselecteerde gebruikers-id's
        $idLeerlingen = $_POST["leerlingen"];
        // Formatteer de id's als een string gescheiden door komma's
        $idSorted = implode(", ", $idLeerlingen);

        // Query om de geselecteerde gebruikers te deactiveren
        $query = "UPDATE `tblGebruiker` SET `active` = 0 WHERE `internNr` IN($idSorted)";

        // Uitvoeren van de query
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            // Succesmelding
            $toast->set("fa-exclamation-triangle", "Gebruikers","", "Gebruikers met internnummer '$idSorted' verwijderd","success");
            // Loggen van de actie
            file_put_contents("log.txt", $timestamp." || Gebruikers met internnummer '$idSorted' verwijderd".PHP_EOL, FILE_APPEND);
        } catch (PDOException $e) 
        {   
            // Foutmelding als de query mislukt
            $toast->set("fa-exclamation-triangle", "Error","", "Gefaald om gebruikers met internnummer '$idSorted' te verwijderen","danger");
            // Loggen van de fout
            file_put_contents("log.txt", $timestamp." || Verwijderen van gebruikers met internnummer '$idSorted' mislukt".PHP_EOL, FILE_APPEND);
        }
    }
    // Als de knop "Activeer gebruikers" is ingedrukt
    elseif(isset($_POST["btnAcivateUsers"])) {
        // Ontvang de geselecteerde gebruikers-id's
        $idLeerlingen = $_POST["leerlingen"];
        // Formatteer de id's als een string gescheiden door komma's
        $idSorted = implode(", ", $idLeerlingen);

        // Query om de geselecteerde gebruikers te activeren
        $query = "UPDATE `tblGebruiker` SET `active` = 1 WHERE `internNr` IN($idSorted)";

        // Uitvoeren van de query
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            // Succesmelding
            $toast->set("fa-exclamation-triangle", "Gebruikers","", "Gebruikers met internnummer '$idSorted' geactiveerd","success");
            // Loggen van de actie
            file_put_contents("log.txt", $timestamp." || Gebruikers met internnummer '$idSorted' geactiveerd".PHP_EOL, FILE_APPEND);
        } catch (PDOException $e) 
        {   
            // Foutmelding als de query mislukt
            $toast->set("fa-exclamation-triangle", "Error","", "Gefaald om gebruikers met internnummer '$idSorted' te activeren","danger");
            // Loggen van de fout
            file_put_contents("log.txt", $timestamp." || Activeren van gebruikers met internnummer '$idSorted' mislukt".PHP_EOL, FILE_APPEND);
        }
    }

    // Na het uitvoeren van de acties, stuur door naar de gebruikersoverzichtspagina
    header("Location: userOverview.php");
    exit;
?>