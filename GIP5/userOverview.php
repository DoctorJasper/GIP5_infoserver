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
    $klasarray = $ss->ophalenKlassen();
    $isChecked = 0;
    $teller = 0;
    $deleted = false;

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleted"])) {  
        //Update query template
        $query = "SELECT `idGeb`,`internNr`,`naam`,`voornaam`,`klas`,`email`,`active`,`admin` FROM `tblGebruiker` WHERE `active` = 0";
        $deleted = true;    
    } else {
        //Update query template
        $query = "SELECT `idGeb`,`internNr`,`naam`,`voornaam`,`klas`,`email`,`active`,`admin` FROM `tblGebruiker` WHERE `active` = 1";
        $deleted = false;
    }

    try{
        $res = $pdo->prepare($query);
        $res->execute();
    }catch(PDOException $e){
        //error in de query
        die();
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
<?php require('../navbar.php') ;?>
<br><br>
<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-<?php echo (!$deleted) ? "primary" : "danger";?> bg-gradient text-white">
            <h3 class="ml-5">Overzicht <?php if ($deleted) echo "verwijderde"; ?> gebruikers</h3>
        </div>    
        <div class="card-body">
       
            <!-- BUTTON: KIEZEN WELK ACCOUNT --> 
            <button type="button" class="btn btn-success" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#Accounts">accounts aanmaken</button>

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
            <table class="table table-hover table-striped">
                <tr>
                    <th class="fw-bold">Gebruikers</th>
                    <th class="fw-bold">Email</th>
                    <th class="fw-bold">Admin</th>
                    <th class="fw-bold">Update</th>
                </tr>
                <form  method="post" action="userActies.php">
                    <?php if ($res->rowCount() != 0) : ?>
                        <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                            <?php $teller++;?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input shadow-sm rounded float-end" type="checkbox" name="leerlingen[]" value="<?php echo $row['internNr']?>" id="<?php echo $teller;?>" onclick="myFunction(this.id)">&ensp;
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
            </table>
            <button type="submit" id="delete" name="btnDeleteUsers" class="btn btn-danger" style="display: none">verwijderen</button>
            <button type="submit" id="activeer" name="btnAcivateUsers" class="btn btn-success" style="display: none">activeren</button>
            </form>
        </div>
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
                <a data-mdb-ripple-init href="<?php echo $path;?>GIP5/userLinux.php">
                    <img
                        src="<?php echo $path;?>/img/Linux_logo.png"
                        class="img-fluid shadow p-2 rounded logos"
                        alt="Linux"
                    />
                </a>
                
                <a data-mdb-ripple-init href="<?php echo $path;?>GIP5/userMySql.php">
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

<?php require('../footer1.php');?>
<script>
    let button1 = document.querySelector("#delete");
    let button2 = document.querySelector("#activeer");

    function myFunction(nummer) {
        let idCheckbox = nummer;
        let checkbox = document.getElementById(idCheckbox);

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
    }
</script>
<?php require('../footer2.php');?>