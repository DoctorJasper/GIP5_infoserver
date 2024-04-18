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
    $bestaandeLeerlingen = [];
    $exists = "";
    $id;

    //--- GET --------------------------------------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["klas"])) {
        $klas = $_GET["klas"];
        $result = $ss->ophalenLeerlingen($klas);
        $resultArray = json_decode($result,true);
        $csvLeerlingen = [];
        
        foreach ($resultArray['account'] as $key => $row) {

            $naam[$key] = $row['naam'];
            $voornaam[$key] = $row['voornaam'];

            $csvLeerlingen[] = implode(",", [$row['internnummer'], $row['naam'], $row['voornaam'], $klas]);
        }
        file_put_contents('leerlingen.csv', implode(PHP_EOL, $csvLeerlingen));
        array_multisort($naam, SORT_ASC, $voornaam, SORT_ASC, $resultArray['account']);

        
        $query = 'SELECT internNr
                  FROM `tblGebruiker` 
                  WHERE `klas` = "' . $klas .'"';

        try {
            $res = $pdo->prepare($query);
            $res->execute();
            $bestaandeLeerlingen = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        }
    }

    //--- POST --------------------------------------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leerlingen"])) {
        $lines = explode(PHP_EOL, file_get_contents('leerlingen.csv'));
        $intNummers = $_POST["leerlingen"];
        $klassen = [];
        $i = 0;

        foreach($lines as $line) {
            $parts = explode(",", $line);
            $klassen[$parts[0]] = $parts;
        }

        foreach($intNummers as $intNr) {
            $naam = $klassen[$intNr][1];
            $voornaam = $klassen[$intNr][2];
            $klas = $klassen[$intNr][3];
            $email = strtolower($voornaam .".". $naam . "@leerling.go-ao.be");
   
            file_put_contents("log.txt","aanmaken van user -> $naam $voornaam - ".date("Y-m-d").PHP_EOL, FILE_APPEND);

            //Update query template
            $query = "INSERT INTO `tblGebruiker`(`internNr`,`naam`,`voornaam`,`klas`,`email`)
                    VALUES (:NR, :naam, :voornaam, :klas, :email)";

            //Values array for PDO
            $values = [":NR" => $intNr, ":naam" => $naam, ":voornaam" => $voornaam, ":klas" => $klas,
            ":email" => $email];

            //Execute the query
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $toast->set("fa-exclamation-triangle", "Gebruikers","", "User '$naam $voornaam' toegevoegd","success");
            } catch (PDOException $e) 
            {   
                $toast->set("fa-exclamation-triangle", "Error","", "Gefaald om '$naam $voornaam' toe te voegen","danger");
            }
        }
    } elseif($_SERVER["REQUEST_METHOD"] == "POST") {
        $toast->set("fa-exclamation-triangle", "Error","", "Selecteer een leerling","danger");
    }
    require('../startHTML.php');
?>
<style>
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
    .logos{
        height: 150px;
        width: 300px;
        margin: 25px;
    }
</style>
<?php require('../navbar.php'); ?>

<br><br>
<div class="card">
    <div class="card-header bg-primary bg-gradient">
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
                                <th>Linux</th>
                                <th>MySql</th>
                                <th>Beheer</th>
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
                                    <td>
                                        <?php foreach($bestaandeLeerlingen as $leerling) {
                                            if ($leerling['internNr'] == $row['internnummer']) {
                                                $exists = 1;
                                                break;
                                            } 
                                            else {
                                                $exists = 0;
                                            }
                                        }?>
                                        <input class="form-check-input checkbox" type="checkbox" name="leerlingen[]" value="<?php echo $row['internnummer']?>" <?php if($exists == "0") echo "checked" ;?> <?php if($exists == "1") echo "disabled" ;?>>
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <button type="button" value="<?php echo $row['internnummer'] ;?>" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#Accounts" onclick="myFunction(this.value)" <?php if($exists == "0") echo "disabled" ;?>>beheer</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        <?php endif; ?>
    </div>          
</div>
    
<!-- ACCOUNTS ------------------------------------------------------------------------------------------------------- -->
<div class="modal fade" id="Accounts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kies een account</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <a data-mdb-ripple-init id="link1" href="">
                    <img
                        src="<?php echo $path;?>/img/Linux_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="Linux"
                    />
                </a>
                
                <a data-mdb-ripple-init id="link2"  href="">
                    <img
                        src="<?php echo $path;?>/img/MySql_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="PhpMyAdmin"
                    />
                </a>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php require('../footer1.php') ;?>
<script>
    let link1 = document.querySelector("#link1");
    let link2 = document.querySelector("#link2");

    function myFunction(nummer) {
        link1.href = "//<?php echo $path;?>GIP5/userLinux.php?id=" + nummer + "&klas=<?php echo $_GET["klas"] ;?>";
        link2.href = "//<?php echo $path;?>GIP5/userLinux.php?id=" + nummer + "&klas=<?php echo $_GET["klas"] ;?>";
    }
</script>
<?php require('../footer2.php') ;?>