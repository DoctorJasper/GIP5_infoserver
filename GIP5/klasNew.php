<?php
    // Inclusief het header-bestand
    require("../header.php");

    // Variabele om aan te geven of er een melding moet worden weergegeven
    $showAlert = false;
    
    // Controleer of de gebruiker een admin is. Zo niet, stuur door naar de index pagina.
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Controleer of de request methode POST is
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Inclusief het pdo-bestand om de databaseverbinding te maken
        require("pdo.php");
        
        // Haal de waarde van het 'klas' veld op uit het formulier
        $klas = $_POST["klas"];
        
        // Controleer of de lengte van de klasnaam minimaal 2 tekens is
        if (strlen($klas) >= 2) {
            // Query template om een nieuwe klas toe te voegen aan de database
            $query = "INSERT INTO `tblKlassen`(`klas`) VALUES ('$klas')";

            // Voer de query uit
            try {
                $res = $pdo->prepare($query);
                $res->execute();
                
                // Als de klas succesvol is toegevoegd, stuur door naar de overzichtspagina
                header("Location: klasOverview.php");
                exit;
            } catch (PDOException $e) {
                // Bij een fout, stel een melding in en log de fout
                $toast->set("fa-exclamation-triangle", "Error", "", "Aanmaken van klas mislukt", "danger");
                file_put_contents("log.txt", date("Y-m-d H:i:s")." || Aanmaken van klas mislukt".PHP_EOL, FILE_APPEND);
                header("Location: klasOverview.php");
                exit;
            }
        }
    } else {
        // Als de request methode geen POST is, stel een melding in en log de fout
        $toast->set("fa-exclamation-triangle", "Error", "", "Aanmaken van klas mislukt", "danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Aanmaken van klas mislukt".PHP_EOL, FILE_APPEND);
        header("Location: klasOverview.php");
        exit;
    }
?>