
<?php
    require('../header.php');

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();

    $leerlingenIntNr = [];
    $namenLeerlingen = [];
    $actie = "";

    if (!isset($_GET["users"]) || $_GET["users"] == "") {
        $toast->set("fa-exclamation-triangle", "Note", "", "U moet eerst een user selecteren", "warning");
        header("Location: userOverview.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actie"])) { 
        $actie = $_POST["actie"];
        $users = $_GET["users"];
        $leerlingenIntNr = explode(',', $users);
        handleAction($actie, $leerlingenIntNr);
    }

    function handleAction($actie, $leerlingenIntNr) {
        global $pdo, $toast;
        $namenLeerlingen = [];

        foreach ($leerlingenIntNr as $leerlingIntNr) {
            $query = "SELECT `naam`, `voornaam`, `klas` FROM `tblGebruiker` WHERE `internNr` = :internNr";
        
            try {
                $res = $pdo->prepare($query);
                $res->bindParam(':internNr', $leerlingIntNr, PDO::PARAM_INT);
                $res->execute();
                $namenLeerlingen[] = $res->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
        }

        foreach ($namenLeerlingen as $naamLeerling) {
            // Create username
            $klas = $naamLeerling["klas"];
            $voornaam = ucfirst(strtolower($naamLeerling["voornaam"]));
            $username = "0" . substr($klas, 0, 2) . strtolower(substr($klas, 2)) . $voornaam;

            // Create random password
            $randomNumber = mt_rand(1000, 9999);

            $query = "SELECT `commando` FROM `tblCommandos` WHERE `idPlatform` = 1 AND `type` = 'toevoegen'";
        
            try {
                $res = $pdo->prepare($query);
                $res->execute();
                $commando = $res->fetch(PDO::FETCH_ASSOC)['commando'];
                $commando = str_replace("username", $username, $commando);
                $commando = str_replace("password", $randomNumber, $commando);
                try {
                    $res = $pdo->prepare($commando);
                    $res->execute();
                }
                catch (PDOException $e) {
                    file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            } catch (PDOException $e) {
                file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            } catch (Exception $e) {
                file_put_contents("log.txt", date("Y-m-d H:i:s") . " || Command execution error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }

            // Insert into tblAccounts
            $query = "INSERT INTO `tblAccounts`(`internnrGebruiker`, `username`, `idPlatform`) VALUES (:nrGeb, :username, :idPla)";
            $values = [":nrGeb" => $leerlingIntNr, ":username" => $username, ":idPla" => 1];

            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $toast->set("fa-exclamation-triangle", "Gebruikers", "", "User '{$naamLeerling['naam']} {$voornaam}' toegevoegd", "success");
            } catch (PDOException $e) {
                $toast->set("fa-exclamation-triangle", "Error", "", "Gefaald om '{$naamLeerling['naam']} {$voornaam}' toe te voegen", "danger");
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
    <div class="col-sm-3"></div>
        <div class="col-sm-6 text-center">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?users=' . $_GET['users']; ?>">
                        <div class="button-container">
                            <button type="submit" name="actie" value="toevoegen" class="btn btn-success action-btn">
                                <i class="fas fa-square-check" data-bs-toggle="tooltip" data-bs-placement="top" title="Toeveogen user"></i>
                            </button>
                            <button type="submit" name="actie" value="verwijderen" class="btn btn-danger action-btn">
                                <i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijderen user"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <div class="col-sm-3"></div>
</div>

<!-- FOOTER -->
<?php 
    require('../footer1.php');
    require('../footer2.php');
?>
