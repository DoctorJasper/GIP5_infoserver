<!DOCTYPE html>
<?php
require("startphp.php");

// Initialisatie van variabele om een alert weer te geven
$showAlert = false;

// Controleer op administratorrechten, anders doorsturen naar login-pagina
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    header("Location: login.php");  
    exit;
}

// Inclusie van PDO-bestand voor databaseverbinding
require("pdo.php");

// Initialisatie van variabele voor het controleren of er een formulier is ingediend
$post = false;

// UPDATE USER
// Controleer of het een GET-verzoek is om gegevens weer te geven
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    // Query om de gegevens van een klas op te halen
    $query = "SELECT * 
              FROM `tblKlassen` 
              WHERE `idKlas` = $id";

    // Voer de query uit
    try {
        $res = $pdo->prepare($query);
        $res->execute();    
        $row = $res->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) 
    {
        // Toon foutmelding als er een fout optreedt bij het uitvoeren van de query
        echo "Query error.<br>".$e;
        die();
    }
}
// Als het een POST-verzoek is, verwerk het formulier
else {
    $post = true;
    $idKlas = $_POST["id"];
    $klas = $_POST["klas"];
    // Controleer of de ingevoerde klasnaam minstens 2 tekens bevat
    if (strlen($klas) >= 2) {
        // Update query om de klasgegevens bij te werken
        $query = "UPDATE `tblKlassen`
                  SET `klas` = '$klas'
                  WHERE `idKlas` = '$idKlas'";

        // Voer de update-query uit
        try {
            $res = $pdo->prepare($query);
            $res->execute();
            // Doorsturen naar overzichtspagina na succesvolle update
            header("Location: klasOverview.php");
            exit;
        } catch (PDOException $e) 
        {
            // Toon foutmelding als er een fout optreedt bij het uitvoeren van de update-query
            echo "Query error.<br>".$e;
            die();
        }
    } else {
        // Toon foutmelding als de klasnaam minder dan 2 tekens bevat
        $TextAlert = "<strong> FOUT! </strong> de klas moet minstens 2 tekens bevatten.";
        $showAlert = true;
    }
} 


require("header.php");
?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <!-- Terugknop naar overzichtspagina -->
                <a class="btn btn-outline-primary" role="button" href="klasOverview.php">Terug</a>
                <?php if ($showAlert) : ?>
                    <!-- Toon een waarschuwing als er een fout is opgetreden -->
                    <div class="alert alert-danger float-end">
                        <?php echo $TextAlert; ?>
                    </div>
                <?php endif; ?>
                <br><br>
                <!-- Formulier voor het bijwerken van klasgegevens -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="mb-3">
                        <!-- Verborgen veld voor het opslaan van klas-ID -->
                        <input type="hidden" class="from-control" name="id" value="<?php if(!$post) echo $id ;?>">
                        <label for="Klas" class="form-label">klas</label>
                        <!-- Invoerveld voor het bijwerken van klasnaam -->
                        <input type="text" class="form-control" id="Klas" name="klas" value="<?php if (!$post) echo $row['klas']; ?>" required>
                    </div>
                    <br>
                    <!-- Knop om het formulier in te dienen en de gebruiker bij te werken -->
                    <button type="submit" class="btn btn-success">Gebruiker updaten</button>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</body>
</html>
