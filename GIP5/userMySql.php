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
    require('datetime.php');

    // Maakt een instantie van de Smartschool-klasse
    $ss = new Smartschool();

    // Initialiseren van variabelen
    $leerlingenIntNr = [];
    $namenLeerlingen = [];
    $actie = "";
    
    $tabel = []; // Initialiseer een array voor de table

    // Controleert of de gebruikersparameter is ingesteld en niet leeg is, anders wordt de gebruiker teruggeleid naar het gebruikersoverzicht
    if (!isset($_GET["users"]) || $_GET["users"] == "") {
        $toast->set("fa-exclamation-triangle", "Note", "", "U moet eerst een user selecteren", "warning");
        header("Location: userOverview.php");
        exit;
    }
    else {    
        $users = $_GET["users"];
        $leerlingenIntNr = explode(',', $users);
    
        $query = "SELECT `voornaam`, `naam`, `klas` FROM `tblGebruiker` WHERE `internNr` IN($users)";
    
        // Uitvoeren van de query
        try {
            $res = $pdo->prepare($query);
            $res->execute();        
            $gebruikers = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) 
        {   
            // Log eventuele databasefouten en geef een foutmelding weer
            file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
        }
    }

    // Als het verzoeksmethode POST is en er een actie is ingesteld, wordt de actie afgehandeld
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actie"])) { 
        $actie = $_POST["actie"];
        handleAction($actie, $leerlingenIntNr);
    }

    // Functie om de acties uit te voeren op de gebruikers
    function handleAction($actie, $leerlingenIntNr) {
        global $pdo, $toast, $pdoLocal, $tabel, $timestamp; // Haal de pdo, toast, ... op van de globale variabelen
        $namenLeerlingen = [];

        if ($actie == "toevoegen") {
            // Haalt de namen van de gebruikers op basis van hun interne nummers
            foreach ($leerlingenIntNr as $leerlingIntNr) {
                try {
                    $query = "SELECT internnrGebruiker FROM `tblAccounts` WHERE idPlatform = 2 AND internnrGebruiker = :NR";
                    $values = [":NR" => $leerlingIntNr];
                
                    $res = $pdo->prepare($query);
                    $res->execute($values);
                    $row = $res->fetch(PDO::FETCH_ASSOC);
                }
                catch (PDOException $e) {
                    // Log eventuele databasefouten en geef een foutmelding weer
                    file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
                }

                
                if ($row["internnrGebruiker"] == null) {
                    $query = "SELECT `naam`, `voornaam`, `klas` FROM `tblGebruiker` WHERE `internNr` = :internNr";
                    $values = [":internNr" => $leerlingIntNr];
                
                    try {
                        $res = $pdo->prepare($query);
                        $res->execute($values);
                        $namenLeerlingen[$leerlingIntNr] = $res->fetch(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        // Logt eventuele databasefouten
                        file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    }
                }     
                else {       
                    array_push($tabel, array("User met internNr '$leerlingIntNr' bestaat al", "warning"));         
                } 
            }

            // Voor elke gebruiker worden acties uitgevoerd
            foreach ($namenLeerlingen as $leerlingIntNr => $naamLeerling) {
                // Maakt een gebruikersnaam op basis van de klas en voornaam
                $klas = $naamLeerling["klas"];
                $voornaam = ucfirst(strtolower($naamLeerling["voornaam"]));
                //$voornaam = str_replace("-", "", $voornaam);
                $username = "0" . substr($klas, 0, 2) . strtolower(substr($klas, 2)) . $voornaam;

                // Genereert een willekeurig wachtwoord
                $randomNumber = mt_rand(1000, 9999);

                // Haalt het commando op voor het toevoegen van de gebruiker
                $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 2 AND `type` = 'toevoegen'";
                $query2 = "SELECT username FROM `tblAccounts` WHERE idPlatform = 2";
            
                try {
                    while(true) {
                        $res = $pdo->prepare($query2); // Bereid de query voor
                        $res->execute(); // Voer de query uit                        
                        $row = $res->fetchAll(PDO::FETCH_ASSOC);

                        $usernames = array_column($row, 'username'); //array van usernames
                        
                        if (in_array($username, $usernames)) {
                            $teller++;
                            $username = $username . substr($naamLeerling["naam"], 0, $teller);
                        }
                        else {
                            break;
                        }
                    }

                    $res = $pdo->prepare($query);
                    $res->execute();
                    $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos'];
                    $commando = str_replace("gebruikersnaam", $username, $commando);
                    $commando = str_replace("password", $randomNumber, $commando);
                    try {
                        // Voert het commando uit om de gebruiker toe te voegen
                        $res = $pdoLocal->prepare($commando);
                        $res->execute();
                        array_push($tabel, array("MySql user $username toegevoegd", "success"));

                        //file_put_contents('pw.txt', "$username:$randomNummer" . PHP_EOL, FILE_APPEND);
                    }
                    catch (PDOException $e) {
                        // Logt eventuele databasefouten
                        file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                        array_push($tabel, array("Gefaal om mySql user $username toe te voegen", "danger"));
                    }
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                } catch (Exception $e) {
                    // Logt eventuele commando-uitvoeringsfouten
                    file_put_contents("log.txt", $timestamp . " || Command execution error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }

                // Voegt de gebruiker toe aan tblAccounts
                $query = "INSERT INTO `tblAccounts`(`internnrGebruiker`, `username`, `idPlatform`) VALUES (:nrGeb, :username, :idPla)";
                $values = [":nrGeb" => $leerlingIntNr, ":username" => $username, ":idPla" => 2];

                try {
                    $res = $pdo->prepare($query);
                    $res->execute($values);
                    array_push($tabel, array("Database user $username toegevoegd", "success"));
                } catch (PDOException $e) {
                    // Meldt een fout als de gebruiker niet kan worden toegevoegd
                    array_push($tabel, array("Gefaald om database user $username toe te voegen", "danger"));
                }
            }
        }
        if ($actie == "verwijderen") {
            // Haalt de namen van de gebruikers op basis van hun interne nummers
            foreach ($leerlingenIntNr as $leerlingIntNr) {
                $query = "SELECT `username` FROM `tblAccounts` WHERE `internnrGebruiker` = :internNr AND idPlatform = 2";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->bindParam('internNr', $leerlingIntNr);
                    $res->execute();
                    $namenLeerlingen[] = $res->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            }

            // Voor elke gebruiker worden acties uitgevoerd
            foreach ($namenLeerlingen as $naamLeerling) {
                $username = $naamLeerling["username"];
                
                if ($username != "") 
                {
                    // Haalt het commando op voor het toevoegen van de gebruiker
                    $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 2 AND `type` = 'verwijderen'";
                
                    try {
                        $res = $pdo->prepare($query);
                        $res->execute();
                        $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos'];
                        $commando = str_replace("gebruikersnaam", $username, $commando);
                        try {
                            // Voert het commando uit om de gebruiker toe te voegen
                            $res = $pdoLocal->prepare($commando);
                            $res->execute();
                            array_push($tabel, array("MySql user $username verwijderd", "success"));
                        }
                        catch (PDOException $e) {
                            // Logt eventuele databasefouten
                            file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                            array_push($tabel, array("Gefaald om mySql user $username te verwijderen", "danger"));
                        }
                    } catch (PDOException $e) {
                        // Logt eventuele databasefouten
                        file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    }

                    // Voegt de gebruiker toe aan tblAccounts
                    $query = "DELETE FROM `tblAccounts` WHERE `username` = :username";

                    $values = [":username" => $username];

                    try {
                        $res = $pdo->prepare($query);
                        $res->execute($values);
                        array_push($tabel, array("Database user $username verwijderd", "success"));
                    } catch (PDOException $e) {
                        // Meldt een fout als de gebruiker niet kan worden toegevoegd
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
    .terug {
        margin-left: 75px;
    }
    #scrollable-table {
        height: 250px;
        overflow-y: auto;
        margin-bottom: 20px;
    }
    body {
        overflow: hidden;
    }
</style>

<?php require('../navbar.php'); ?>


<br><br>
<div class="card">
    <div class="card-header bg-primary text-white text-center">
        <h3 class="ml-5">Beheer MySql accounts</h3>
    </div>
    <div class="card-body">
        <a href="userOverview.php"><button class="btn btn-primary terug">terug</button></a>
        <div class="row center">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="ml-5">Users</h3>
                    </div>
                    <div class="card-body">  
                        <div id="scrollable-table">                      
                            <?php foreach ($gebruikers as $gebruiker) : ?>
                                <div class="d-flex align-items-center mb-3">
                                    <p class="fw-bold"><?php echo $gebruiker["voornaam"] . " " . $gebruiker["naam"];?></p>
                                    <p class="ms-auto"><?php echo $gebruiker["klas"];?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 text-center">
                <div class="card">
                    <div class="card-header bg-light">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?users=' . $_GET['users']; ?>">
                            <button type="submit" name="actie" value="toevoegen" class="btn btn-success action-btn">
                                toevoegen
                                <i class="fas fa-square-check" data-bs-toggle="tooltip" data-bs-placement="top" title="Toevoegen user"></i>
                            </button>
                            <button type="submit" name="actie" value="verwijderen" class="btn btn-danger action-btn">
                                verwijderen
                                <i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijderen user"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">    
                        <div id="scrollable-table">   
                            <?php foreach ($tabel as $line) : ?>
                                <span class="badge bg-<?php echo $line[1] ;?>"><h3><?php echo $line[0] ;?></h3></span>
                                <p></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php 
    require('../footer1.php');
    require('../footer2.php');
?>
