<!DOCTYPE html>
<?php
    require("../header.php");

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();
    $showAlert = false;
    $leerlingen = [];
    $exists = "";

    require('../startHTML.php');
    require('../navbar.php');

    //--- GET --------------------------------------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["klas"])) {
        $klas = $_GET["klas"];
        $result = $ss->ophalenLeerlingen($klas);
        $resultArray = json_decode($result,true);
        $csvLeerlingen = [];
        
        foreach ($resultArray['account'] as $key => $row) {

            $naam[$key] = $row['naam'];
            $voornaam[$key] = $row['voornaam'];

            $csvLeerlingen[] = implode(",", [$row['internnummer'], $row['naam'], $row['voornaam']]);
        }
        file_put_contents('leerlingen.csv', implode(PHP_EOL, $csvLeerlingen));
        array_multisort($naam, SORT_ASC, $voornaam, SORT_ASC, $resultArray['account']);
    }

    //--- POST --------------------------------------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leerlingen"])) {
        $lines = explode(PHP_EOL, file_get_contents('leerlingen.csv'));
        $intNummers = $_POST["leerlingen"];
        $klas = [];
        $i = 0;

        foreach($lines as $line) {
            $parts = explode(",", $line);
            $klas[$parts[0]] = $parts;
        }

        foreach($intNummers as $intNr) {
            file_put_contents("log.txt","aanmaken van user $intNr".PHP_EOL, FILE_APPEND);
            //var_dump($klas[$intNr]);
            $naam = $klas[$intNr][1];
            $voornaam = $klas[$intNr][2];
            $email = strtolower($voornaam .".". $naam . "@leerling.go-ao.be");

            //Update query template
            $query = "INSERT INTO `tblGebruiker`(`internNr`,`naam`,`voornaam`,`email`)
                    VALUES (:NR, :naam, :voornaam, :email)";

            //Values array for PDO
            $values = [":NR" => $intNr, ":naam" => $naam, ":voornaam" => $voornaam,
            ":email" => $email];

            //Execute the query
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $toast->set("fa-exclamation-triangle", "Gebruikers","", "User '$naam $voornaam' toegevoegd","success");
            } catch (PDOException $e) 
            {   
                $toast->set("fa-exclamation-triangle", "Error","", "Gefaald op '$naam $voornaam' toe te voegen","danger");
            }
        }
    }
    elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        $internNr = trim($_POST["internNr"]);
        $naam = trim($_POST["naam"]);
        $voornaam = trim($_POST["voornaam"]);
        $email = trim($_POST["email"]);
        $admin = isset($_POST["admin"]) ? 1 : 0;

        if (strlen($naam) >= 2 || strlen($voornaam) >= 2) {
            $query = "INSERT INTO `tblGebruiker`(`internNr`,`naam`,`voornaam`,`email`,`admin`)
                    VALUES (:intNr, :naam, :voornaam, :email, :adm)";

            $values = [":intNr" => $GUID, ":userName" => $username, ":naam" => $naam, ":voornaam" => $voornaam,
                    ":email" => $email, ":adm" => $admin];

            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
            } catch (PDOException $e) 
            {
                $TextAlert = "<strong> FOUT! </strong> de ingegeven informatie is te kort, mogelijks fout of al in gebruik.";
                $showAlert = true;
            }
        } else {
            $TextAlert = "<strong> FOUT! </strong> de ingegeven informatie is te kort of mogelijks fout.";
            $showAlert = true;
        }
    }
?>
<br>
<br><br>
<div class="card">
            <div class="card-header  bg-donkerrood">
                <h1 class="text-white center">Nieuwe User<h1>
            </div>
    <div class="container mt-5">
        
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <!--<div class="card">
                                <div class="card-header  bg-primary">
                                    <h1 class="text-white center">Maak user aan<h1>
                                </div>
                                    <div class="card-body">
                                        <a class="btn btn-outline-danger" role="button" href="../index.php">Terug</a>

                                            <?php// if ($showAlert) : ?>
                                                <div class="alert alert-danger float-end">
                                                    <?php //echo $TextAlert; ?>
                                                </div>
                                            <?php //endif; ?>
                                            
                                            <form method="post" action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                                <div class="mb-3">
                                                    <label for="internNr" class="form-label">Intern nummer</label>
                                                    <input type="text" class="form-control" id="internNr" name="internNr" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Naam" class="form-label">Naam</label>
                                                    <input type="text" class="form-control" id="Naam" name="naam" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Voornaam" class="form-label">Voornaam</label>
                                                    <input type="text" class="form-control" id="Voornaam" name="voornaam" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="Email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="Email" name="email" required>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"/>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                                                </div>
                                                <br>
                                                <button type="submit" class="btn btn-success">Gebruiker aanmaken</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          -->
                        </div>
                    </div>
                        <div class="col-sm-8">
                            <div class="card">
                                    <div class="card-header bg-info">
                                        <h1 class="text-white center">Users kiezen<h1>
                                    </div>
                                        <div class="card-body">
                                            <!-- SELECT KLAS -->
                                            <div class="col-sm-2">
                                                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                                    <select name="klas" class="form-select" data-mdb-select-init data-mdb-filter="true" onchange="this.form.submit()"> 
                                                        <option disabled selected>Kies een klas</option>
                                                        <?php foreach ($klasarray as $klas) : ?>
                                                            <?php echo "<option value='" . $klas['code'] . "'>" . $klas['code'] . "</option>"; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </form>
                                            </div>
                        

                            <!-- KLASLIJST -->
                            <?php if(isset($_GET["klas"])) : ?>
                                <br><br>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <h3 class="d-inline"><i class="fas fa-clipboard-list"></i>&nbsp;Klaslijst van <?php echo $_GET["klas"]; ?></h3>
                                    <button type="submit" class="btn btn-success float-end d-inline">Gebruikers aanmaken</button>
                                    <br><br>
                                    <div class="card-body">
                                        <table class="table align-middle mb-0 bg-white">
                                            <thead class="bg-light">
                                                <tr>
                                                <th>Name</th>
                                                <th>Internnr</th>
                                                <th>Status</th>
                                                <th>Selecteer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($resultArray['account'] as $key => $row) : ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php $foto = $ss->ophalenfoto($row['internnummer']); ?>
                                                        <img
                                                        src="data:image/png;base64,<?php echo $foto; ?>" 
                                                        class="rounded-circle" 
                                                        height="100px" 
                                                        width="100px"
                                                        />
                                                                    <div class="ms-3">
                                                                        <p class="fw-bold mb-1"><?php echo $row['naam']; ?></p>
                                                                        <p class="text-muted mb-0"><?php echo $row['voornaam']; ?></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td> 
                                                                <p class="fw-normal mb-1"><?php echo $row['internnummer']; ?></p>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-success rounded-pill d-inline">
                                                                    <?php echo $row['@attributes']['status']; ?>
                                                                </span>
                                                            </td>
                                                            <?php
                                                                $exists = "1";
                                                                $query = 'SELECT *
                                                                FROM `tblGebruiker` 
                                                                WHERE `naam` = "' . $row["naam"] . '"';

                                                                try {
                                                                    $res = $pdo->prepare($query);
                                                                    $res->execute();
                                                                    $row2 = $res->fetch(PDO::FETCH_ASSOC);
                                                                    /*$naam = strval($row2["naam"]);
                                                                    if ($row["naam"] == $naam) {
                                                                        $exists = "1";
                                                                    }*/
                                                                } catch (PDOException $e) {
                                                                    $exists = "0";
                                                                }
                                                            ?>
                                                            <td>
                                                                <input class="form-check-input" type="checkbox" name="leerlingen[]" value="<?php echo $row['internnummer']?>" id="flexCheckDefault" <?php if($exists == "0") echo "checked" ;?>>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>            
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
?>
<!-- Custom scripts -->
<script type="text/javascript">

</script>
<?php
require('../footer2.php');
?>