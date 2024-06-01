<?php
    require('../header.php');

    // Controleer of de gebruiker een admin is
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php"); // Redirect naar de homepage als de gebruiker geen admin is
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php'); 

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen(); 

    require('../startHTML.php');
    require('../navbar.php');

    $showAlert = false; // Variabele om waarschuwingen te tonen
    $post = false; // Variabele om te controleren of het een POST-verzoek is

    // Controleer het verzoekstype
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $id = $_GET['id']; // Haal het gebruikers-ID uit de URL
        
        // Template voor de SELECT query
        $query = "SELECT `idGeb`,`internNr`,`naam`,`voornaam`,`email`,`admin` 
                  FROM `tblGebruiker` 
                  WHERE `idGeb` = $id";

        // Voer de query uit
        try {
            $res = $pdo->prepare($query);
            $res->execute();    
            $row = $res->fetch(PDO::FETCH_ASSOC); // Haal de resultaten op als een associatieve array
        } catch (PDOException $e) {
            // Log de fout en toon een foutmelding
            $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
            header("Location: ../index.php");
            exit;
        }
    } else {
        // Als het een POST-verzoek is
        $post = true;
        $idGeb = $_POST["idGeb"]; // Haal gegevens uit het POST-verzoek
        $internNr = trim($_POST["internNr"]);
        $naam = trim($_POST["naam"]); 
        $voornaam = trim($_POST["voornaam"]);
        $email = trim($_POST["email"]);
        $admin = isset($_POST["admin"]) ? 1 : 0;

        // Valideer de gegevens
        if (strlen($naam) >= 2 || strlen($voornaam) >= 2) {
            // Template voor de UPDATE query
            $query = "UPDATE `tblGebruiker`
                      SET `internNr` = '$internNr', `naam` = '$naam',`voornaam` = '$voornaam',`email` = '$email',`admin` = '$admin'
                      WHERE `idGeb` = '$idGeb'";

            // Voer de query uit
            try {
                $res2 = $pdo->prepare($query);
                $res2->execute();
                header("Location: userOverview.php"); // Redirect naar het overzicht
                exit;
            } catch (PDOException $e) {
                // Log de fout en toon een foutmelding
                $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
                file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
                header("Location: ../index.php");
                exit;
            }
        } else {
            // Toon een foutmelding als de gegevens niet geldig zijn
            $TextAlert = "<strong> FOUT! </strong> de ingegeven informatie is te kort of mogelijks fout.";
            $showAlert = true;
        }
    }
?>
<br><br>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <a class="btn btn-outline-primary" role="button" href="userOverview.php">Terug</a>
            <?php if ($showAlert) : ?>
                <div class="alert alert-danger float-end">
                    <?php echo $TextAlert; ?>
                </div>
            <?php endif; ?>
            <p><br></p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="mb-3">
                    <input type="hidden" class="from-control" name="idGeb" value="<?php if(!$post) echo $id ;?>">
                    <label for="InternNr" class="form-label">Intern nummer</label>
                    <input type="text" class="form-control" id="InternNr" name="internNr" value="<?php if (!$post) echo $row['internNr']; ?>" required>
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
                    <input class="form-check-input" name="admin" type="checkbox" role="switch" id="flexSwitchCheckDefault" <?php if (!$post) { if(isset($row["admin"]) && $row["admin"] == 1) echo "checked"; };?>>
                    <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                </div>
                <br>
                <button type="submit" class="btn btn-success">Gebruiker updaten</button>
            </form>
        </div>
        <div class="col-sm-6">
        </div>
    </div>
</div>
</body>
</html>