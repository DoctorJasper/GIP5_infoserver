<?php 
    // Vereiste bestanden en controle op administratorsessie
    require('../header.php');
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Vereiste bestanden en initialisatie van variabelen
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');
    
    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();
    $isChecked = 0;
    $teller = 0;
    $deleted = false;
    $selectedUsers = [];

    // Bepaal de query op basis van de GET-parameter "deleted"
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleted"])) {  
        $query = "SELECT `idGeb`,`internNr`,`naam`,`voornaam`,`klas`,`email`,`active`,`admin` FROM `tblGebruiker` WHERE `active` = 0";
        $deleted = true;    
    } else {
        $query = "SELECT `idGeb`,`internNr`,`naam`,`voornaam`,`klas`,`email`,`active`,`admin` FROM `tblGebruiker` WHERE `active` = 1";
        $deleted = false;
    }

    // Uitvoeren van de query
    try{
        $res = $pdo->prepare($query);
        $res->execute();
    }catch(PDOException $e){
        // Foutafhandeling bij databasequeryfouten
        $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
        header("Location: ../index.php");
        exit;
    }

    $query = "SELECT g.`internNr`, p.`platform`, a.username FROM `tblGebruiker` g, `tblAccounts` a, `tblPlatform` p WHERE g.`active` = 1 AND g.`internNr`= a.`internnrGebruiker` AND a.`idPlatform` = p.`idPlt`";

    // Uitvoeren van de query
    try{
        $res2 = $pdo->prepare($query);
        $res2->execute();
        $row2 = $res2->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        // Foutafhandeling bij databasequeryfouten
        $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
    }
    // Vereiste HTML-bestanden en start van HTML-structuur
    require('../startHTML.php');
?>
<style>
    /* CSS-styling */
    #card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
    .logos{
        height: 150px;
        width: 300px;
        margin: 25px;
    }
    #scrollable-table {
        height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
    }
</style>
<?php require('../navbar.php') ;?>
<br><br>
<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-<?php echo (!$deleted) ? "primary" : "danger";?>  text-white">
            <h3 class="ml-5">Overzicht <?php if ($deleted) echo "verwijderde"; ?> gebruikers</h3>
        </div>    
        <div class="card-body">
       
            <!-- BUTTON: KIEZEN WELK ACCOUNT --> 
            <button type="button" class="btn btn-success" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#Accounts">accounts aanmaken</button>

            <button type="submit" form="form1" id="delete" name="btnDeleteUsers" class="btn btn-danger" style="display: none">verwijderen</button>
            <button type="submit" form="form1" id="activeer" name="btnAcivateUsers" class="btn btn-success" style="display: none">activeren</button>

            <span class="float-end">
                <?php if ($deleted): ?>
                    <a href="userOverview.php"><i class="fas fa-user-group fs-3 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="actieve gebruikers"></i></a>  
                <?php else: ?>
                    <a href="userNew.php"><i class="fas fa-user-plus fs-3 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="nieuwe gebruiker"></i></a>                
                    &nbsp;
                    <a href="userOverview.php?deleted"><i class="fas fa-user-xmark fs-3 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>               
                <?php endif; ?>
            </span>
            <br><br>
            <div class="px-4">
                
            </div>
            <div id="scrollable-table">
                <table class="table table-hover table-striped">
                    <tr>
                        <th><input class="form-check-input shadow-sm rounded" type="checkbox" id="selectAll" onclick="selectAll(this.id)" title="Select all"/></th>
                        <th class="fw-bold">Gebruikers</th>
                        <th class="fw-bold">Email</th>
                        <th class="fw-bold">Accounts</th>
                        <th class="fw-bold">Admin</th>
                        <th class="fw-bold">Update</th>
                    </tr>
                    <form id="form1" method="post" action="userActies.php">
                        <?php if ($res->rowCount() != 0) : ?>
                            <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                                <?php $teller++;?>
                                <tr>
                                    <td>
                                        <input class="form-check-input shadow-sm rounded" type="checkbox" name="leerlingen[]" value="<?php echo $row['internNr']?>" id="<?php echo $teller;?>" onclick="myFunction(this.id)">&ensp;
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php $foto = $ss->ophalenfoto($row['internNr']); ?>
                                            <img
                                            src="data:image/png;base64,<?php echo $foto; ?>" 
                                            class="rounded-circle" 
                                            height="50px" 
                                            width="50px"
                                            />
                                            <div class="ms-3">
                                                <p class="fw-bold mb-1"><?php echo $row['naam']; ?></p>
                                                <p class="text-muted mb-0"><?php echo $row['voornaam']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo$row["email"]; ?> </td>
                                    <td>
                                        <?php
                                            $platforms = [];
                                            foreach ($row2 as $account) {
                                                if ($row["internNr"] == $account["internNr"]) {
                                                    $platforms[] = [
                                                        "platform" => $account["platform"],
                                                        "username" => $account["username"]
                                                    ];
                                                }
                                            }
                                            foreach ($platforms as $platform) {
                                                $badgeColor = "";
                                                switch ($platform["platform"]) {
                                                    case "Linux":
                                                        $badgeColor = "bg-warning text-dark";
                                                        break;
                                                    case "MySql":
                                                        $badgeColor = "bg-info text-dark";
                                                        break;
                                                    default:
                                                        $badgeColor = "bg-secondary";
                                                        break;
                                                }
                                                echo '<span class="badge ' . $badgeColor . '">' . $platform["platform"] . '</span>';
                                                echo '<span class="float-end font-monospace">' . $platform["username"] . '</span><br>';
                                            }
                                            if (empty($platforms)) {
                                                echo '<span class="badge bg-secondary">nog geen account</span>';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo$row["admin"] ? '<i class="fas fa-square-check text-success fs-5"></i>':
                                                                    '<i class="far fa-square fs-5"></i>';?> </td>
                                    <td>
                                        <a href="userUpdate.php?id=<?php echo $row['idGeb']; ?>"><i class="fas fa-pen-to-square text-warning fs-5"  data-bs-toggle="tooltip" data-bs-placement="top" title="Wijzig gebruiker"></i></a>    
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr><td colspan="6">Geen gegevens gevonden</td></tr>
                        <?php endif; ?>                        
                    </form>
                </table>
            </div>
        </div>
    </div>
</div>       

<!-- ACCOUNTS MODAL -->
<div class="modal fade" id="Accounts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
               
                <h5 class="modal-title" id="exampleModalLabel">Kies een account</h5>
                
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <!-- Link voor Linux-account -->
                <a data-mdb-ripple-init href="<?php echo $path."GIP5/userLinux.php?users=";?>" id="linux_link">
                    <img
                        src="<?php echo $path;?>/img/Linux_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="Linux"
                    />
                </a>
                
                <!-- Link voor MySQL-account -->
                <a data-mdb-ripple-init href="<?php echo $path."GIP5/userMySql.php?users=";?>" id="mysql_link">              
                    <img
                        src="<?php echo $path;?>/img/MySql_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="MySql"
                    />
                </a>
            </div>
        </div>
    </div>
</div>

<?php require('../footer1.php');?>

<script>
    // JavaScript voor functionaliteit binnen het modale venster
    let button1 = document.querySelector("#delete");
    let button2 = document.querySelector("#activeer");

    // Functie om knoppen weer te geven/verbergen en links bij te werken op basis van geselecteerde gebruikers
    function myFunction(nummer) {
       
        // Afhankelijk van de waarde van $deleted worden verschillende acties uitgevoerd
        let idCheckbox = nummer;
        let checkbox = document.getElementById(idCheckbox);

        if (getChecked().length > 0) {
            <?php if(!$deleted) : ?>
                button1.style.display = "block";
                console.log("ok, <?php echo $isChecked;?>");
            <?php else : ?>
                button2.style.display = "block";
                console.log("ok, <?php echo $isChecked;?>");
            <?php endif; ?>
        } else {
            button1.style.display = "none";
            console.log("niet ok, <?php echo $isChecked;?>");

            button2.style.display = "none";
            console.log("ok, <?php echo $isChecked;?>");
        }
        updateLinks();
    }

    // Functie om alle selectievakjes aan/uit te zetten
    function selectAll(id) {
        let checkboxes = document.getElementsByName('leerlingen[]');
        let source = document.querySelector('input[type="checkbox"]');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = source.checked;
        });

        let checkbox = document.getElementById(id)
        if(checkbox.checked == true) {
            <?php if(!$deleted) : ?>
                button1.style.display = "block";
                console.log("ok, <?php echo $isChecked;?>");
            <?php else : ?>
                button2.style.display = "block";
                console.log("ok, <?php echo $isChecked;?>");
            <?php endif; ?>
        } else {
            button1.style.display = "none";
            console.log("niet ok, <?php echo $isChecked;?>");

            button2.style.display = "none";
            console.log("ok, <?php echo $isChecked;?>");
        }
        updateLinks();
    }
    
    // Functie om geselecteerde gebruikers op te halen
    function getChecked() {
        let checkboxes = document.getElementsByName('leerlingen[]');
        let leerlingen = [];
        checkboxes.forEach(function(checkbox){
            if (checkbox.checked) {
                leerlingen.push(checkbox.value);
            }
        })
        console.log(leerlingen);
        return leerlingen;
    }

    // Functie om links bij te werken op basis van geselecteerde gebruikers
    function updateLinks() {
        let path = "<?php echo $path."GIP5/";?>";

        let linuxLink = document.querySelector("#linux_link");
        linuxLink.setAttribute("href",path+"userLinux.php?users="+getChecked().join(","))

        let mysqlLink = document.querySelector("#mysql_link");
        mysqlLink.setAttribute("href",path+"userMySql.php?users="+getChecked().join(","))
    }
</script>
<?php require('../footer2.php');?>