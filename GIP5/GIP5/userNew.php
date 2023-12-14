<!DOCTYPE html>
<?php
require("startphp.php");

// Initialisatie van variabele om een alert weer te geven
$showAlert = false;

// Controleer of de gebruiker is ingelogd als administrator
if (!isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    // Als niet ingelogd als administrator, doorsturen naar login-pagina
    header("Location: login.php");
    exit;
} elseif (isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    // Als wel ingelogd, maar niet als administrator, doorsturen naar About-pagina
    header("Location: About.php");
    exit();
}

// Controleer of het een POST-verzoek is (wanneer het formulier is ingediend)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclusie van PDO-bestand voor databaseverbinding
    require("pdo.php");
    
    // Ontvang en trim de ingevoerde gegevens van het formulier
    $username = trim($_POST["username"]);
    $naam = trim($_POST["naam"]);
    $voornaam = trim($_POST["voornaam"]);
    $email = trim($_POST["email"]);
    $admin = isset($_POST["admin"]) ? 1 : 0;
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

    // Controleer of de ingevoerde naam- en voornaamgegevens lang genoeg zijn
    if (strlen($naam) >= 2 || strlen($voornaam) >= 2) {
        // Genereer een GUID voor de nieuwe gebruiker
        $GUID = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        // Update query-template voor het toevoegen van een nieuwe gebruiker
        $query = "INSERT INTO `tblGebruiker`(`GUID`,`userName`,`naam`,`voornaam`,`email`,`userPassword`,`admin`)
                  VALUES (:ID, :userName, :naam, :voornaam, :email, :userPassword, :adm)";

        // Waarden array voor PDO
        $values = [":ID" => $GUID, ":userName" => $username, ":naam" => $naam, ":voornaam" => $voornaam,
                   ":email" => $email, ":userPassword" => $password, ":adm" => $admin];

        // Voer de query uit
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
            
            // Doorsturen naar beheerderspagina na succesvolle toevoeging van gebruiker
            header("Location: adminpage.php");
            exit;
        } catch (PDOException $e) 
        {
            // Toon foutmelding als er een fout optreedt bij het uitvoeren van de query
            echo "Query error.<br>".$e;
            die();
        }
    } else {
        // Toon foutmelding als de ingegeven informatie te kort of mogelijk fout is
        $TextAlert = "<strong> FOUT! </strong> De ingegeven informatie is te kort of mogelijk fout.";
        $showAlert = true;
    }
}

require("header.php");
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <!-- Terugknop naar gebruikersoverzichtspagina -->
            <a class="btn btn-outline-primary" role="button" href="userOverview.php">Terug</a>
            <?php if ($showAlert) : ?>
                <!-- Toon een waarschuwing als er een fout is opgetreden -->
                <div class="alert alert-danger float-end">
                    <?php echo $TextAlert; ?>
                </div>
            <?php endif; ?>
            <br><br>
            <!-- Formulier voor het toevoegen van een nieuwe gebruiker -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="mb-3">
                    <label for="Username" class="form-label">Gebruikersnaam</label>
                    <!-- Invoerveld voor gebruikersnaam -->
                    <input type="text" class="form-control" id="Username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="Naam" class="form-label">Naam</label>
                    <!-- Invoerveld voor naam -->
                    <input type="text" class="form-control" id="Naam" name="naam" required>
                </div>
                <div class="mb-3">
                    <label for="Voornaam" class="form-label">Voornaam</label>
                    <!-- Invoerveld voor voornaam -->
                    <input type="text" class="form-control" id="Voornaam" name="voornaam" required>
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label">Email</label>
                    <!-- Invoerveld voor email -->
                    <input type="email" class="form-control" id="Email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="Password" class="form-label">Wachtwoord</label>
                    <!-- Invoerveld voor wachtwoord -->
                    <input type="password" class="form-control" id="Password" name="password" required>
                </div>
                <div class="form-check form-switch">
                    <!-- Schakelaar voor het toekennen van beheerdersrechten -->
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="admin" />
                    <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                </div>
                <br>
                <!-- Knop om het formulier in te dienen en een nieuwe gebruiker toe te voegen -->
                <button type="submit" class="btn btn-success">Gebruiker aanmaken</button>
            </form>
        </div>
        <div class="col-sm-6">
        </div>
    </div>
</div>
</body>
</html>
