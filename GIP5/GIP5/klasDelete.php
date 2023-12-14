<?php
    // Soft delete van een gebruiker
    session_start();

    // Controleren of de gebruiker een beheerder is, anders doorsturen naar de inlogpagina
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
        header("Location: login.php");  
        exit;
    }

    // Controleren of het een POST-verzoek is en of de 'idKlas' is ingesteld
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idKlas'])) {
        $idKlas = $_POST['idKlas'];
        require("pdo.php"); // Verbinding maken met de database

        // Query-sjabloon voor het updaten van de 'active' status naar 0 (soft delete)
        $query = "UPDATE `tblKlassen`
                  SET `active` = 0
                  WHERE `idKlas` = :ID";

        $values = [":ID" => $idKlas]; // array voor het uitvoeren van de query

        // Uitvoeren van de query
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);    
            header("Location: klasOverview.php"); // Terugsturen naar het overzicht na het succesvol uitvoeren van de update
        } catch (PDOException $e) {
            echo "Queryfout.<br>".$e;
            die();
        }
    }
?>
