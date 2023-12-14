<?php
session_start();

// Controleer op administratorrechten, anders doorsturen naar login-pagina
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    header("Location: login.php");  
    exit;
}

// Controleer of het een POST-verzoek is en of de GUID is ingesteld
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['GUID'])) {
    // Ontvang de GUID van het POST-verzoek
    $GUID = $_POST['GUID'];
    
    // Inclusie van PDO-bestand voor databaseverbinding
    require("pdo.php");
    
    // Update query-template voor het markeren van de gebruiker als inactief
    $query = "UPDATE `tblGebruiker`
              SET `active` = 0
              WHERE `GUID` = :ID";
    
    // Waarden voor de PDO-query
    $values = [":ID" => $GUID];

    // Voer de query uit
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);    
        
        // Doorsturen naar de gebruikersoverzichtspagina na succesvolle soft delete
        header("Location: userOverview.php");
    } catch (PDOException $e) 
    {
        // Toon foutmelding als er een fout optreedt bij het uitvoeren van de query
        echo "Query error.<br>".$e;
        die();
    }
}
?>
