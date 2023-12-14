<?php
    // Start de PHP-sessie (vereist voor het gebruik van $_SESSION)
    session_start();

    // Controleer of de gebruiker is ingelogd als beheerder
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
        // Als de gebruiker geen beheerder is, stuur ze dan naar de inlogpagina
        header("Location: login.php");  
        exit;
    }

    // Controleer of het verzoeksmethode POST is en of het GUID-veld is ingesteld
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['GUID'])) {
        // Haal het GUID op uit het POST-verzoek
        $GUID = $_POST['GUID'];

        require("pdo.php");

        // Update query sjabloon
        $query = "UPDATE `tblGebruiker`
        SET `active` = 1
        WHERE `GUID` = :ID";

        // Array met bindende waarden voor de query
        $values = [":ID" => $GUID];

        // Voer de query uit
        try {
            // Bereid de query voor
            $res = $pdo->prepare($query);
            // Voer de query uit met de opgegeven waarden
            $res->execute($values);    
            
            // Stuur de gebruiker door naar de gebruikersoverzichtspagina na succesvolle update
            header("Location: userOverview.php");
        } catch (PDOException $e) {
            // Vang een PDOException op en geef een foutmelding weer als er een fout optreedt
            echo "Query error.<br>".$e;
            die();
        }
    }
?>
