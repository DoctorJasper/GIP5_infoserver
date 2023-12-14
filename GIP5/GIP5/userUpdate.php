<?php
// Start de PHP-sessie (vereist voor het gebruik van $_SESSION)
session_start();

// Controleer of de gebruiker een beheerder is
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    // Als de gebruiker geen beheerder is, stuur ze dan naar de inlogpagina
    header("Location: login.php");  
    exit;
}

require("pdo.php");

// Initialisatie van variabelen
$showAlert = false;
$TextAlert = "";
$post = false;

// Als het verzoeksmethode niet POST is, haal gegevens op voor weergave in het formulier
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $id = $_GET['id'];

    // Selecteer query sjabloon om gebruikersgegevens op te halen
    $query = "SELECT `idGeb`,`userName`,`naam`,`voornaam`,`email`,`admin` 
    FROM `tblGebruiker` 
    WHERE `idGeb` = $id";

    // Voer de query uit
    try {
        $res = $pdo->prepare($query);
        $res->execute();    
        $row = $res->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Query error.<br>".$e;
        die();
    }
}
else {
    // Verwerk het POST-verzoek na het indienen van het formulier
    $post = true;
    $idGeb = $_POST["idGeb"];
    $username = trim($_POST["username"]);
    $naam = trim($_POST["naam"]); 
    $voornaam = trim($_POST["voornaam"]);
    $email = trim($_POST["email"]);
    $admin = isset($_POST["admin"]) ? 1 : 0;

    // Controleer of de naam- en voornaamvelden minimaal 2 tekens lang zijn
    if (strlen($naam) >= 2 || strlen($voornaam) >= 2) {
        // Update query sjabloon om gebruikersgegevens bij te werken
        $query = "UPDATE `tblGebruiker`
                SET `userName` = '$username', `naam` = '$naam',`voornaam` = '$voornaam',`email` = '$email',`admin` = '$admin'
                WHERE `idGeb` = '$idGeb'";

        // Voer de query uit
        try {
            $res2 = $pdo->prepare($query);
            $res2->execute();
            
            // Stuur de gebruiker door naar de gebruikersoverzichtspagina na succesvolle update
            header("Location: userOverview.php");
            exit;
        } catch (PDOException $e) {
            echo "Query error.<br>".$e;
            die();
        }
    } else {
        // Toon een foutmelding als de ingevoerde informatie te kort is
        $TextAlert = "<strong> FOUT! </strong> de ingegeven informatie is te kort of mogelijks fout.";
        $showAlert = true;
    }
}

require("header.php");
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <!-- Terugknop naar het gebruikersoverzicht -->
            <a class="btn btn-outline-primary" role="button" href="userOverview.php">Terug</a>
            
            <!-- Toon een foutmelding als showAlert waar is -->
            <?php if ($showAlert) : ?>
                <div class="alert alert-danger float-end">
                    <?php echo $TextAlert; ?>
                </div>
            <?php endif; ?>

            <!-- Formulier voor het bijwerken van gebruikersgegevens -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="mb-3">
                    <!-- Verborgen invoerveld voor de gebruikers-ID -->
                    <input type="hidden" class="from-control" name="idGeb" value="<?php if(!$post) echo $id ;?>">
                    <label for="Username" class="form-label">Gebruikersnaam</label>
                    <input type="text" class="form-control" id="Username" name="username" value="<?php if (!$post) echo $row['userName']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="Naam" class="form-label">Naam</label>
                    <input type="text" class="form-control" id="Naam" name="naam" value="<?php if (!$post) echo $row['naam']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="Voornaam" class="form-label">Voornaam</label>
                    <input type="text" class="form-control" id="Voornaam" name="voornaam" value="<?php if (!$post) echo $row['voornaam']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="Email" name="email" value="<?php if (!$post) echo $row['email']; ?>" required>
                </div>
                <div class="form-check form-switch">
                    <!-- Schakelaar voor het toewijzen van beheerdersrechten -->
                    <input class="form-check-input" name="admin" type="checkbox" role="switch" id="flexSwitchCheckDefault" <?php if (!$post) { if(isset($row["admin"]) && $row["admin"] == 1) echo "checked"; };?>>
                    <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                </div>
                <br>
                <!-- Knop voor het indienen van het formulier -->
                <button type="submit" class="btn btn-success">Gebruiker updaten</button>
            </form>
        </div>
        
    </div>
</div>
</body>
</html>
