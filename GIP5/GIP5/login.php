<!DOCTYPE html>
<?php
require("startphp.php");

// Initialisatie van variabele om een alert weer te geven
$showAlert = false;

// Controleer of het een POST-verzoek is (wanneer het formulier is ingediend)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclusie van PDO-bestand voor databaseverbinding
    require("pdo.php");

    // Ontvang en trim de gebruikersnaam en het wachtwoord van het formulier
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    // Query voor het ophalen van gebruikersgegevens op basis van de gebruikersnaam
    $query = "SELECT `GUID`,`userName`,`userPassword`,`passwordReset`,`active`,`admin` 
              FROM `tblGebruiker` 
              WHERE `userName` = :userName";
    
    // Waarden voor de PDO-query
    $values = [":userName" => $username];
    
    try {
        // Voorbereiden en uitvoeren van de query
        $res = $pdo->prepare($query);
        $res->execute($values);
    } catch (PDOException $e) {
        // Toon foutmelding als er een fout optreedt bij het uitvoeren van de query
        echo "Query error<br>".$e;
        die();
    }
    
    // Haal rij op uit het resultaat
    $row = $res->fetch(PDO::FETCH_ASSOC);

    // Controleer of de gebruiker actief is
    if ($row["active"] == true) {
        // Controleer of de ingevoerde gebruikersnaam en wachtwoord overeenkomen met de database
        if ($username == $row["userName"] && password_verify($password, $row["userPassword"])) {
            // Sessievariabelen instellen voor ingelogde gebruiker
            $_SESSION["username"] = $username;
            $_SESSION['CREATED'] = time();
            $_SESSION['GUID'] = $row["GUID"];
  
            // Controleer of de "admin" sleutel bestaat in de $row array
            $_SESSION["admin"] = isset($row["admin"]) ? ($row["admin"] == 1 ? 1 : 0) : 0;
  
            // Doorsturen naar gebruikerspagina of beheerderspagina op basis van rechten
            if ($_SESSION["admin"] == 0) {
                header("Location: userpage.php?GUID=".$_SESSION["GUID"]);
                die();
            } else {
                header("Location: adminpage.php");
                die();
            }
        } else {
            // Gebruikersnaam en wachtwoord komen niet overeen
            $showAlert = true;
            $alertText = '<strong>FOUT!</strong> Gebruikersnaam en wachtwoord komen niet overeen';
        }
    } else {
        // Gebruiker is niet actief
        $showAlert = true;
        $alertText = '<strong>FOUT!</strong> Gebruikersnaam en wachtwoord komen niet overeen';
    }
}

require("header.php");
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-sm-4">
            <?php if ($showAlert) : ?>
                <!-- Toon een waarschuwing als er een fout is opgetreden -->
                <div class="alert alert-danger">
                    <?php echo $alertText; ?>
                </div>
            <?php endif; ?>
            <!-- Formulier voor het inloggen -->
            <div class="card">
                <div class="card-header">          
                    Log in
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <!-- Invoerveld voor gebruikersnaam -->
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="Password">Password:</label>
                            <!-- Invoerveld voor wachtwoord -->
                            <input type="password" class="form-control" id="Password" name="password" required>
                            <!-- Oogje om wachtwoord te tonen/verbergen -->
                            &nbsp;&nbsp;
                            <img src="Images/show.png" alt="eye" style="width: 20px;" id="oogje">
                        </div>
                        <!-- Link naar wachtwoord vergeten pagina -->
                        <a class="nav-link text-primary" href="wachtwoordVergeten.php">wachtwoord vergeten</a>
                        <br>
                        <!-- Knop om het formulier in te dienen en in te loggen -->
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript voor het tonen/verbergen van het wachtwoord -->
<script>
    let teller = 0;
    let oogje = document.querySelector("#oogje");
    oogje.addEventListener("click", wwToon);
    function wwToon() {
        teller++;
        if (teller % 2 == 1) {
            document.getElementById("Password").type = "text";
            this.src = "Images/hide.png";
        } else {
            document.getElementById("Password").type = "password";
            this.src = "Images/show.png";
        }
    }
</script>
</body>
</html>
