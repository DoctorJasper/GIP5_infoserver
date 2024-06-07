<?php
    // Vereist het header.php-bestand voor de opmaak van de pagina
    require("../header.php");

    // Controleert of de gebruiker is ingelogd als admin, anders wordt deze omgeleid naar de indexpagina
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Vereist enkele bestanden en klassen
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    // Maakt een instantie van de Smartschool-klasse
    $ss = new Smartschool();
    // Haalt de lijst met klassen op
    $klasarray = $ss->ophalenKlassen();
    $showAlert = false;
    $leerlingen = [];
    $bestaandeLeerlingen = [];
    $exists = "";
    $id;

    //--- GET --------------------------------------------------------------------------------------------------------------------------------
    // Als het verzoeksmethode GET is en een klas is geselecteerd
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["klas"])) {
        $klas = $_GET["klas"];
        // Haalt de leerlingen op voor de geselecteerde klas
        $result = $ss->ophalenLeerlingen($klas);
        $resultArray = json_decode($result,true);
        $csvLeerlingen = [];
        
        // Maakt een CSV-bestand met de leerlingeninformatie
        foreach ($resultArray['account'] as $key => $row) {

            $naam[$key] = $row['naam'];
            $voornaam[$key] = $row['voornaam'];

            $csvLeerlingen[] = implode(",", [$row['internnummer'], $row['naam'], $row['voornaam'], $klas]);
        }
        file_put_contents('leerlingen.csv', implode(PHP_EOL, $csvLeerlingen));
        array_multisort($naam, SORT_ASC, $voornaam, SORT_ASC, $resultArray['account']);

        // Haalt bestaande leerlingen op voor de geselecteerde klas
        $query = 'SELECT internNr
                  FROM `tblGebruiker` 
                  WHERE `klas` = "' . $klas .'"';

        try {
            $res = $pdo->prepare($query);
            $res->execute();
            $bestaandeLeerlingen = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Behandel eventuele fouten
        }
    }

    //--- POST --------------------------------------------------------------------------------------------------------------------------------
    // Als het verzoeksmethode POST is en er leerlingen zijn geselecteerd
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leerlingen"])) {
        $lines = explode(PHP_EOL, file_get_contents('leerlingen.csv'));
        $intNummers = $_POST["leerlingen"];
        $klassen = [];
        $i = 0;

        // Splits de CSV-lijnen en haalt de leerlinginformatie op
        foreach($lines as $line) {
            $parts = explode(",", $line);
            $klassen[$parts[0]] = $parts;
        }

        // Voegt de geselecteerde leerlingen toe aan de database
        foreach($intNummers as $intNr) {
            $naam = $klassen[$intNr][1];
            $voornaam = $klassen[$intNr][2];
            $klas = $klassen[$intNr][3];
            $email = strtolower($voornaam .".". $naam . "@leerling.go-ao.be");
   
            // Voert een query uit om de leerling toe te voegen aan de database
            $query = "INSERT INTO `tblGebruiker`(`internNr`,`naam`,`voornaam`,`klas`,`email`)
                    VALUES (:NR, :naam, :voornaam, :klas, :email)";

            // Waardenarray voor PDO
            $values = [":NR" => $intNr, ":naam" => $naam, ":voornaam" => $voornaam, ":klas" => $klas,
            ":email" => $email];

            // Voert de query uit
            try {
                $res = $pdo->prepare($query);
                $res->execute($values);
                $toast->set("fa-exclamation-triangle", "Gebruikers","", "User '$naam $voornaam' toegevoegd","success");
            } catch (PDOException $e) 
            {   
                // Meldt een fout als de gebruiker niet kan worden toegevoegd
                $toast->set("fa-exclamation-triangle", "Error","", "Gefaald om '$naam $voornaam' toe te voegen","danger");
            }
        }
    } elseif($_SERVER["REQUEST_METHOD"] == "POST") {
        // Meldt een fout als er geen leerlingen zijn geselecteerd
        $toast->set("fa-exclamation-triangle", "Error","", "Selecteer een leerling","danger");
    }
    // Vereist het startHTML-bestand voor de opmaak van de pagina
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
    <div class="card-header bg-danger">
        <h1 class="text-white center">Users kiezen<h1>
    </div>
    <div class="card-body">
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
                                <th>Selecteer</th>
                                <th>Naam</th>
                                <th>Internnr</th>
                                <th>Status</th>
                                <th>Linux</th>
                                <th>MySql</th>
                                <th>Beheer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultArray['account'] as $key => $row) : ?>
                                <tr>
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

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <button type="button" value="<?php echo $row['internnummer'] ;?>" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#Accounts" onclick="myFunction(this.value)" <?php if($exists == "0") echo "disabled" ;?>>beheer</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </form>
                        </tbody>
                    </table>
                </div>
            </form>
        <?php endif; ?>
    </div>          
</div>
    
<!-- ACCOUNTS ------------------------------------------------------------------------------------------------------- -->
<!-- Een modaal venster voor het kiezen van een account -->
<div class="modal fade" id="Accounts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Titel van het modale venster -->
                <h5 class="modal-title" id="exampleModalLabel">Kies een account</h5>
                <!-- Knop om het modale venster te sluiten -->
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <!-- Link naar het Linux-account met een logo -->
                <a data-mdb-ripple-init id="link1" href="">
                    <img
                        src="<?php echo $path;?>/img/Linux_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="Linux"
                    />
                </a>
                
                <!-- Link naar het MySql-account met een logo -->
                <a data-mdb-ripple-init id="link2"  href="">
                    <img
                        src="<?php echo $path;?>/img/MySql_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="mySql"
                    />
                </a>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php require('../footer1.php') ;?>
<script>
    // JavaScript om de links van de accounts in te stellen
    let link1 = document.querySelector("#link1");
    let link2 = document.querySelector("#link2");

    // Functie om de link van het account in te stellen op basis van het nummer van de leerling
    function myFunction(nummer) {
        link1.href = "//<?php echo $path;?>GIP5/userLinux.php?id=" + nummer + "&klas=<?php echo $_GET["klas"] ;?>";
        link2.href = "//<?php echo $path;?>GIP5/userLinux.php?id=" + nummer + "&klas=<?php echo $_GET["klas"] ;?>";
    }
</script>
<?php require('../footer2.php') ;?>