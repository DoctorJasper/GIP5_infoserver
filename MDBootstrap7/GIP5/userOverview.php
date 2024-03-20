<!DOCTYPE html>
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

    require('../startHTML.php');
    require('../navbar.php');
    
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
    ?>
    <style>
        #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        border: 1px solid #000000;
        width: 100%;
        }

        #customers tr:nth-child(even){background-color: #8E0037;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #8E0037;
        color: white;
        }
    </style>
    <br><br>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-12">
                <a class="btn btn-outline-danger" id="ReturnButton" role="button" href="../index.php">Terug</a>
                <br><br>
                <span class=float-end>
                    <?php if ($deleted): ?>
                        <a href="userOverview.php"><i class="bi bi-person-heart fs-3 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                    <?php else: ?>
                        <a href="userNew.php"><i class="fas fa-user-plus fs-3 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="nieuwe gebruiker"></i></a>                
                        &nbsp;
                        <a href="userOverview.php?deleted"><i class="fas fa-user-xmark fs-3 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                    <?php endif; ?>
                </span>
                <h3>Overzicht <?php if ($deleted) echo "verwijderde"; ?> gebruikers</h3>
                <table class="table table-hover table-striped">
                    
                <table class="table table-hover table-striped" id="customers">
                <tr>
                        <th class="fw-bold">Gebruikers</th>
                        <th class="fw-bold">Email</th>
                        <th class="fw-bold">Admin</th>
                        <th class="fw-bold">Acties</th>
                    </tr>
                    <?php if ($res->rowCount() != 0) : ?>
                        <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input shadow-sm rounded float-end" type="checkbox" name="leerlingen[]" value="<?php echo $row['internNr']?>" id="flexCheckDefault">&ensp;
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
                                    <?php if ($deleted) : ?>
                                        <i id="Reactivate" class="bi bi-person-up text-success fs-5" onclick='showModalReactivate("<?php echo $row["voornaam"]; ?>","<?php echo $row["internNr"]; ?>")' data-bs-toggle="modal" data-bs-target="#Activate" data-bs-toggle="tooltip" data-bs-placement="top" title="Heractiveer gebruiker"></i>                                       
                                    <?php else : ?>
                                        <a href="userUpdate.php?id=<?php echo $row['idGeb']; ?>"><i class="fas fa-pen-to-square text-warning fs-5"  data-bs-toggle="tooltip" data-bs-placement="top" title="Wijzig gebruiker"></i></a>
                                        <i id="Delete" class="fas fa-square-xmark text-danger fs-5" onclick='showModalDelete("<?php echo $row["voornaam"]; ?>","<?php echo $row["internNr"]; ?>")' data-bs-toggle="modal" data-bs-target="#DeleteUser" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder gebruiker"></i>                                     
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="6">Geen gegevens gevonden</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>       

<!-- Modal -->
<div class="modal fade" id="DeleteUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Verwijder gebruiker</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                Ben je zeker dat je gebruiker '<span id="userDEL"></span>' wil verwijderen? 
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <button type="button" value="" id="knopVerwijder" class="btn btn-danger"
                onclick="deactivateUser(this.value)">Verwijder</button>
            </div>
        </div>
    </div>
</div>


<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Modal activate -->
<div class="modal fade" id="Activate">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Heractiveer gebruiker</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                Ben je zeker dat je gebruiker '<span id="userREA"></span>' wil heractiveren? 
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <button type="button" value="" id="knopHeractiveren" class="btn btn-success"
                onclick="reactivateUser(this.value)">Heractiveren</button>
            </div>
        </div>
    </div>
</div>


<form action="userDelete.php" method="post" style="display:none" id="userDeleteForm">
    <input type="hidden" id="userId" name="internNr">
</form>
<form action="userReactivate.php" method="post" style="display:none" id="userReactivateRorm">
    <input type="hidden" id="userId2" name="internNr">
</form>


<script>
    //DEACTIVEREN
    function showModalDelete(voornaam, internNr) {
        console.log(voornaam);
        document.getElementById("userDEL").innerHTML = voornaam;
        document.getElementById("knopVerwijder").value = internNr;
        
    }

    function deactivateUser(id) {
        let idInput = document.querySelector("#userId");
        idInput.value = id;
        let form = document.querySelector("#userDeleteForm");
        form.submit();
    }

    //----------------------------------------------------------------------------------------------------------------

    //HERACTIVEREN
    function showModalReactivate(voornaam, internNr) {
        document.getElementById("userREA").innerHTML = voornaam;
        document.getElementById("knopHeractiveren").value = internNr;
    }

    function reactivateUser(id) {
        let idInput = document.querySelector("#userId2");
        idInput.value = id;
        let form = document.querySelector("#userReactivateRorm");
        form.submit();
    }
</script>