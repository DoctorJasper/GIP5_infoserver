<?php
// Start de sessie
session_start();

// Controleer op administratorrechten
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    header("Location: login.php");
    exit;
}

// Controleer of het een POST-verzoek is en of 'idKlas' is ingesteld
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idKlas'])) {
    $idKlas = $_POST['idKlas'];

    // Inclusie van de databaseverbinding
    require("pdo.php");

    // Update query template
    $query = "UPDATE `tblKlassen`
              SET `active` = 1
              WHERE `idKlas` = :ID";

    $values = [":ID" => $idKlas];

    // Voer de query uit
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
        
        // Doorverwijzen naar klasOverview.php na succesvolle update
        header("Location: klasOverview.php");
    } catch (PDOException $e) {
        // Foutafhandeling in geval van een queryfout
        echo "Query error.<br>" . $e;
        die();
    }
}
?>
