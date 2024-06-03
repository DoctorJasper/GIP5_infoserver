<?php
    // Vereist het header.php-bestand voor de opmaak van de pagina
    require('../header.php');

    // Controleert of de gebruiker is ingelogd als admin, anders wordt deze omgeleid naar de indexpagina
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Vereist enkele bestanden en klassen
    require('pdo.php');
    require('pdoLocal.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    // Maakt een instantie van de Smartschool-klasse
    $ss = new Smartschool();

    // Initialiseren van variabelen
    $leerlingenIntNr = [];
    $namenLeerlingen = [];
    $actie = "";

    // Controleert of de gebruikersparameter is ingesteld en niet leeg is, anders wordt de gebruiker teruggeleid naar het gebruikersoverzicht
    if (!isset($_GET["users"]) || $_GET["users"] == "") {
        $toast->set("fa-exclamation-triangle", "Note", "", "U moet eerst een user selecteren", "warning");
        header("Location: userOverview.php");
        exit;
    }

    // Als het verzoeksmethode POST is en er een actie is ingesteld, wordt de actie afgehandeld
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actie"])) { 
        $actie = $_POST["actie"];
        $users = $_GET["users"];
        $leerlingenIntNr = explode(',', $users);
        handleAction($actie, $leerlingenIntNr);
    }

    // Functie om de acties uit te voeren op de gebruikers
    function handleAction($actie, $leerlingenIntNr) {
        global $pdo, $toast, $pdoLocal; // Haal de pdo, toast, ... op van de globale variabelen
        $namenLeerlingen = [];

        if ($actie == "toevoegen") {
            // Haalt de namen van de gebruikers op basis van hun interne nummers
            foreach ($leerlingenIntNr as $leerlingIntNr) {
                $query = "SELECT `naam`, `voornaam`, `klas` FROM `tblGebruiker` WHERE `internNr` = :internNr";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->bindParam(':internNr', $leerlingIntNr, PDO::PARAM_INT);
                    $res->execute();
                    $namenLeerlingen[] = $res->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            }

            // Voor elke gebruiker worden acties uitgevoerd
            foreach ($namenLeerlingen as $naamLeerling) {
                // Maakt een gebruikersnaam op basis van de klas en voornaam
                $klas = $naamLeerling["klas"];
                $voornaam = ucfirst(strtolower($naamLeerling["voornaam"]));
                $username = "0" . substr($klas, 0, 2) . strtolower(substr($klas, 2)) . $voornaam;

                // Genereert een willekeurig wachtwoord
                $randomNumber = mt_rand(1000, 9999);

                // Haalt het commando op voor het toevoegen van de gebruiker
                $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 2 AND `type` = 'toevoegen'";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->execute();
                    $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos'];
                    $commando = str_replace("username", $username, $commando);
                    $commando = str_replace("password", $randomNumber, $commando);
                    try {
                        // Voert het commando uit om de gebruiker toe te voegen
                        $res = $pdoLocal->prepare($commando);
                        $res->execute();
                    }
                    catch (PDOException $e) {
                        // Logt eventuele databasefouten
                        file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    }
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                } catch (Exception $e) {
                    // Logt eventuele commando-uitvoeringsfouten
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Command execution error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }

                // Voegt de gebruiker toe aan tblAccounts
                $query = "INSERT INTO `tblAccounts`(`internnrGebruiker`, `username`, `idPlatform`) VALUES (:nrGeb, :username, :idPla)";
                $values = [":nrGeb" => $leerlingIntNr, ":username" => $username, ":idPla" => 2];

                try {
                    $res = $pdo->prepare($query);
                    $res->execute($values);
                    $toast->set("fa-exclamation-triangle", "Gebruikers", "", "User '{$naamLeerling['naam']} {$voornaam}' toegevoegd", "success");
                } catch (PDOException $e) {
                    // Meldt een fout als de gebruiker niet kan worden toegevoegd
                    $toast->set("fa-exclamation-triangle", "Error", "", "Gefaald om '{$naamLeerling['naam']} {$voornaam}' toe te voegen", "danger");
                }
            }
        }
        if ($actie == "verwijderen") {
            // Haalt de namen van de gebruikers op basis van hun interne nummers
            foreach ($leerlingenIntNr as $leerlingIntNr) {
                $query = "SELECT `username` FROM `tblAccounts` WHERE `internnrGebruiker` = :internNr";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->bindParam('internNr', $leerlingIntNr);
                    $res->execute();
                    $namenLeerlingen[] = $res->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            }

            // Voor elke gebruiker worden acties uitgevoerd
            foreach ($namenLeerlingen as $naamLeerling) {
                // Haalt het commando op voor het toevoegen van de gebruiker
                $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 2 AND `type` = 'verwijderen'";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->execute();
                    $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos'];
                    $commando = str_replace("username", $username, $commando);
                    $commando = str_replace("password", $randomNumber, $commando);
                    try {
                        // Voert het commando uit om de gebruiker toe te voegen
                        $res = $pdoLocal->prepare($commando);
                        $res->execute();
                    }
                    catch (PDOException $e) {
                        // Logt eventuele databasefouten
                        file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    }
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                } catch (Exception $e) {
                    // Logt eventuele commando-uitvoeringsfouten
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Command execution error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }

                // Voegt de gebruiker toe aan tblAccounts
                $query = "INSERT INTO `tblAccounts`(`internnrGebruiker`, `username`, `idPlatform`) VALUES (:nrGeb, :username, :idPla)";
                $values = [":nrGeb" => $leerlingIntNr, ":username" => $username, ":idPla" => 2];

                try {
                    $res = $pdo->prepare($query);
                    $res->execute($values);
                    $toast->set("fa-exclamation-triangle", "Gebruikers", "", "User '{$naamLeerling['naam']} {$voornaam}' toegevoegd", "success");
                } catch (PDOException $e) {
                    // Meldt een fout als de gebruiker niet kan worden toegevoegd
                    $toast->set("fa-exclamation-triangle", "Error", "", "Gefaald om '{$naamLeerling['naam']} {$voornaam}' toe te voegen", "danger");
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
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php 
    require('../footer1.php');
    require('../footer2.php');
?>
