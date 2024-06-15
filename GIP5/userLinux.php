<?php
require('../header.php'); // Vereist de header.php bestand

// Controleer of de gebruiker een admin is, anders stuur hem terug naar de indexpagina
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
    header("Location: ../index.php");
    exit;   
}

require('pdo.php'); // Vereist het pdo.php bestand
require('../inc/config.php'); // Vereist het config.php bestand
require('../classes/class.smartschool.php'); // Vereist de Smartschool klasse
require('datetime.php');

$ss = new Smartschool(); // Maak een nieuw object van de Smartschool klasse aan

$leerlingenIntNr = []; // Initialiseer een array voor leerlingen interne nummers
$namenLeerlingen = []; // Initialiseer een array voor leerlingennamen
$actie = ""; // Initialiseer de actie variabele

$tabel = []; // Initialiseer een array voor de table

// Controleer of er gebruikers zijn geselecteerd, zo niet, geef een opmerking en stuur de gebruiker terug naar het overzicht
if (!isset($_GET["users"]) || $_GET["users"] == "") {
    $toast->set("fa-exclamation-triangle", "Opmerking", "", "U moet eerst een gebruiker selecteren", "warning");
    header("Location: userOverview.php");
    exit;
}

// Controleer of er een POST-verzoek is gedaan en of de actie is ingesteld
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actie"])) { 
    $actie = $_POST["actie"];
    $users = $_GET["users"];
    $leerlingenIntNr = explode(',', $users);
    handleAction($actie, $leerlingenIntNr, $ss); // Roep de handleAction functie aan
}

// Functie om de actie te verwerken
function handleAction($actie, $leerlingenIntNr, $ss) {
    global $pdo, $toast, $tabel, $timestamp; // Haal de pdo, toast, ... op van de globale variabelen
    $namenLeerlingen = []; // Initialiseer een array voor leerlingennamen

    // Als de actie 'toevoegen' is
    if ($actie == "toevoegen") {
        // Loop door elk leerlingen interne nummer
        foreach ($leerlingenIntNr as $leerlingIntNr) {
            // Bereid een query voor om de naam, voornaam en klas van de leerling op te halen
            $query = "SELECT `naam`, `voornaam`, `klas` FROM `tblGebruiker` WHERE `internNr` = :internNr";
        
            try {
                $res = $pdo->prepare($query); // Bereid de query voor
                $res->bindParam(':internNr', $leerlingIntNr, PDO::PARAM_INT); // Bind de parameter
                $res->execute(); // Voer de query uit
                $namenLeerlingen[$leerlingIntNr] = $res->fetch(PDO::FETCH_ASSOC); // Haal de resultaten op en voeg deze toe aan de array
            } catch (PDOException $e) {
                file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND); // Log eventuele databasefouten
            }
        }

        // Loop door elke leerling en voer acties uit
        foreach ($namenLeerlingen as $leerlingIntNr => $naamLeerling) {
            // Maak gebruikersnaam aan (kleine letters)
            $username = strtolower($naamLeerling["voornaam"]);

            // Genereer een willekeurig wachtwoord
            $password = mt_rand(1000, 9999);

            // Haal de commando's op om gebruikers toe te voegen en wachtwoorden te wijzigen
            $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 1 AND `type` = 'check'";
            $query1 = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 1 AND `type` = 'toevoegen'";
            $query2 = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 1 AND `type` = 'password'";
        
            try {
                $res = $pdo->prepare($query); // Bereid de query voor
                $res->execute(); // Voer de query uit
                $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos']; // Haal het commando op
                $commando = str_replace("gebruikersnaam", $username, $commando); // Vervang placeholders met de gebruikersnaam

                $output = shell_exec($commando);
                
                if ($output != null) die();
            
                $res = $pdo->prepare($query1); // Bereid de query voor
                $res->execute(); // Voer de query uit
                $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos']; // Haal het commando op
                $commando = str_replace("gebruikersnaam", $username, $commando); // Vervang placeholders met de gebruikersnaam
                $commando = str_replace("wachtwoord", $password, $commando); // Vervang placeholders met het wachtwoord

                // Voer het commando uit en log het
                file_put_contents("log.txt", $timestamp . " || Command to execute: " . $commando . PHP_EOL, FILE_APPEND);
                exec($commando);

                // Sla het wachtwoord op in een tekstbestand
                file_put_contents("pw.txt",$username.":".$password);

                // Haal het commando op om het wachtwoord te wijzigen en voer het uit
                $res = $pdo->prepare($query2); // Bereid de query voor
                $res->execute(); // Voer de query uit
                $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos']; // Haal het commando op
                $commando = str_replace("gebruikersnaam", $username, $commando); // Vervang placeholders met de gebruikersnaam
                file_put_contents("log.txt", $timestamp . " || Command to execute: " . $commando . PHP_EOL, FILE_APPEND); // Log het commando
                exec($commando); // Voer het commando uit

                // Geef een succesmelding weer
                array_push($tabel, array("Linux gebruiker $username toegevoegd", "success"));

            } catch (PDOException $e) {
                // Log eventuele databasefouten en geef een foutmelding weer
                file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                array_push($tabel, array("Gefaald om Linux gebruiker $username aan te maken", "danger"));
            }
            
            // Insert into tblAccounts
            $query = "INSERT INTO `tblAccounts`(`internnrGebruiker`, `username`, `idPlatform`) VALUES (:nrGeb, :username, :idPla)";
            $values = [":nrGeb" => $leerlingIntNr, ":username" => $username, ":idPla" => 1];

            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                
                var_dump($leerlingIntNr);
                array_push($tabel, array("Database user $username toegevoegd", "success"));
            } catch (PDOException $e) {
                array_push($tabel, array("Gefaald om database user $username toe te voegen", "danger"));
            }

            // mail versturen
            /*$bericht = "<html><body>";
            $bericht .= "<p>Beste,</p>";
            $bericht .= "<p></p>";
            $bericht .= "<p>Dit is uw huidige linux wachtwoord: <strong>" . htmlspecialchars($password) . "</strong></p>";
            $bericht .= "<p><a href='http://83.217.67.87/gip/GIP5/userpage.php?id=$leerlingIntNr'>Klik hier om uw wachtwoord te veranderen</a>.</p>";
            $bericht .= "<p></p>";
            $bericht .= "<p>mvg</p>";
            $bericht .= "</body></html>";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // Assuming $ss->bericht function supports headers
            $result = $ss->bericht("115759", $leerlingIntNr, "Linux Code", $bericht, $headers);
            if ($result) {
                $message = "Bericht is goed verzonden.";
            } else {
                $message = "Bericht is niet verzonden.";
            }*/
        }
    }

    if ($actie == "verwijderen") {
         // Voor elke gebruiker de gebruikersnaam ophalen
        foreach ($leerlingenIntNr as $leerlingIntNr) {
            $query = "SELECT `username` FROM `tblAccounts` WHERE `internnrGebruiker` = :internNr AND idPlatform = 1";
        
            try {
                $res = $pdo->prepare($query);
                $res->bindParam('internNr', $leerlingIntNr);
                $res->execute();
                $namenLeerlingen[] = $res->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
        }
        // Voor elke gebruiker de gebruikersnaam controleren en actie uitvoeren indien deze bestaat
        foreach ($namenLeerlingen as $naamLeerling) {
            $username = $naamLeerling["username"];

            if ($username != "") 
            {
                $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 1 AND `type` = 'verwijderen'";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->execute();
                    $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos'];
                    $commando = str_replace("gebruikersnaam", $username, $commando);

                    file_put_contents("log.txt", $timestamp . " || Command to execute: " . $commando . PHP_EOL, FILE_APPEND);
                    
                    exec($commando);
                    array_push($tabel, array("Linux user $username verwijderd", "success"));
                } catch (PDOException $e) {
                       // Foutloggen bij databasequeryfouten
                    file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    array_push($tabel, array("Gefaald om linux user $username te verwijderen", "danger"));
                }

              // Verwijderen van gebruiker uit de database
                $query = "DELETE FROM `tblAccounts` WHERE `username` = '$username'";

                try {
                    $res = $pdo->prepare($query);
                    $res->execute();
                    array_push($tabel, array("Database user $username verwijderd", "success"));
                } catch (PDOException $e) {
                    array_push($tabel, array("Gefaald om database user $username verwijderd", "danger"));
                } 
            }
            else {
                array_push($tabel, array("De user kan niet gevonden worden, omdat deze niet in de database staat", "danger"));
            }
        }
    }
}
?>

<?php require('../startHTML.php'); ?>

<style>
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
    .button-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .action-btn {
        width: 120px;
        height: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
    }
    .action-btn i {
        font-size: 48px;
    }
    .btn-success {
        background-color: #28a745;
        border: none;
        color: white;
    }
    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: white;
    }
</style>

<?php require('../navbar.php'); ?>

<br><br>
<div class="row center">
    <div class="col-sm-12 text-center">
        <div class="card">
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?users=' . $_GET['users']; ?>">
                    <div class="button-container">
                        <button type="submit" name="actie" value="toevoegen" class="btn btn-success action-btn">
                            <i class="fas fa-square-check" data-bs-toggle="tooltip" data-bs-placement="top" title="Toevoegen user"></i>
                        </button>
                        <button type="submit" name="actie" value="verwijderen" class="btn btn-danger action-btn">
                            <i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijderen user"></i>
                        </button>
                    </div>
                </form>
                <br><br>
                <?php foreach ($tabel as $line) : ?>
                    <span class="badge bg-<?php echo $line[1] ;?>"><h3><?php echo $line[0] ;?></h3></span>
                    <p></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php 
    require('../footer1.php');
    require('../footer2.php');
?>
