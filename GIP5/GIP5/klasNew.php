<!DOCTYPE html>
<?php
// PHP-code om te controleren of de gebruiker is ingelogd als beheerder
require("startphp.php");

$showAlert = false;

// Als de gebruiker geen beheerder is, wordt deze doorverwezen naar de inlogpagina
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    header("Location: login.php");
    exit;
}

// Verwerken van het POST-verzoek wanneer het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php"); // Databaseverbinding
    $klas = $_POST["klas"];

    // Controleer of de klasnaam minstens 2 tekens bevat
    if (strlen($klas) >= 2) {
        // SQL-query sjabloon voor het toevoegen van een nieuwe klas
        $query = "INSERT INTO `tblKlassen`(`klas`) VALUES ('$klas')";

        // Uitvoeren van de query
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            header("Location: klasOverview.php"); // Na succesvolle uitvoering doorverwijzen naar het overzicht
            exit;
        } catch (PDOException $e) {
            echo "Queryfout.<br>".$e;
            die();
        }
    } else {
        $TextAlert = "<strong> FOUT! </strong> de klas moet minstens 2 tekens bevatten.";
        $showAlert = true;
    }
}

require("header.php");
?>
<!-- HTML-gedeelte van de pagina -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <!-- Terugknop en foutmelding (indien nodig) -->
            <a class="btn btn-outline-primary" role="button" href="klasOverview.php">Terug</a>
            <?php if ($showAlert) : ?>
                <div class="alert alert-danger float-end">
                    <?php echo $TextAlert; ?>
                </div>
            <?php endif; ?>
            <br><br>
            <!-- Formulier voor het toevoegen van een nieuwe klas -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="mb-3">
                    <label for="Klas" class="form-label">Gebruikersnaam</label>
                    <input type="text" class="form-control" id="Klas" name="klas" required>
                </div>
                <br>
                <button type="submit" class="btn btn-success">Klas aanmaken</button>
            </form>
        </div>
        
    </div>
</div>
</body>
</html>
