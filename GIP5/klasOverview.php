<!DOCTYPE html>
<?php 
    require('startphp.php');

    if (!isset($_SESSION["admin"]) && $_SESSION["admin"] == 0) {
        header("Location: login.php");
        exit;
    }
    
    require('pdo.php');

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleted"])) {  
        //Update query template
        $query = "SELECT * FROM `tblKlassen` WHERE `active` = 0";
        $deleted = true;
    } else {
        //Update query template
        $query = "SELECT * FROM `tblKlassen` WHERE `active` = 1";
        $deleted = false;
    }

    try{
        $res = $pdo->prepare($query);
        $res->execute();
    }catch(PDOException $e){
        //error in de query
        echo 'Query error';
        die();
    }

    require('header.php');
    
    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <a class="btn btn-outline-primary" role="button" href="adminpage.php">Terug</a>
                <br><br>
                <span class=float-end>
                    <?php if ($deleted): ?>
                        <a href="klasOverview.php"><i class="bi bi-person-heart fs-3 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                    <?php else: ?>
                        <a href="klasNew.php"><i class="bi bi-person-plus-fill fs-3" data-bs-toggle="tooltip" data-bs-placement="top" title="nieuwe klas"></i></a>                
                        &nbsp;
                        <a href="klasOverview.php?deleted"><i class="bi bi-person-fill-slash fs-3 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde klassen"></i></a>
                    <?php endif; ?>
                </span>
                <h3>Overzicht <?php if ($deleted) echo "verwijderde"; ?> klassen</h3>
                <table class="table table-hover table-striped">
                    <tr>
                        <th>klas</th>
                        <th>acties</th>
                    </tr>
                    <?php if ($res->rowCount() != 0) : ?>
                        <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td><?php echo$row["Klas"]; ?> </td>
                                <td>
                                    <?php if ($deleted) : ?>
                                        <i id="Reactivate" class="bi bi-person-up text-success fs-5" onclick='showModalReactivate("<?php echo $row["Klas"]; ?>","<?php echo $row["idKlas"]; ?>")' data-bs-toggle="modal" data-bs-target="#ReactivateKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Heractiveer klas"></i>
                                    <?php else : ?>
                                        <a href="klasUpdate.php?id=<?php echo $row['idKlas']; ?>"><i class="bi bi-pencil-square text-warning  fs-5"  data-bs-toggle="tooltip" data-bs-placement="top" title="Wijzig gebruiker"></i></a>
                                        <i id="Delete" class="bi bi-x-square text-danger  fs-5" onclick='showModalDelete("<?php echo $row["Klas"]; ?>","<?php echo $row["idKlas"]; ?>")' data-bs-toggle="modal" data-bs-target="#DeleteKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder klas"></i>
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
<div class="modal fade" id="DeleteKlas">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Verwijder klas</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                Ben je zeker dat je klas '<span id="klasDEL"></span>' wil verwijderen? 
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <button type="button" value="" id="knopVerwijder" class="btn btn-danger"
                onclick="deactivateKlas(this.value)">Verwijder</button>
            </div>
        </div>
    </div>
</div>


<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Modal activate -->
<div class="modal fade" id="ReactivateKlas">
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


<form action="klasDelete.php" method="post" style="display:none" id="klasDeleteForm">
    <input type="hidden" id="klasId" name="idKlas">
</form>
<form action="klasReactivate.php" method="post" style="display:none" id="klasReactivateForm">
    <input type="hidden" id="klasId2" name="idKlas">
</form>


<script>
    //DEACTIVEREN
    function showModalDelete(klas, idKlas) {
        document.getElementById("klasDEL").innerHTML = klas;
        document.getElementById("knopVerwijder").value = idKlas;
    }

    function deactivateKlas(id) {
        let idInput = document.querySelector("#klasId");
        idInput.value = id;
        let form = document.querySelector("#klasDeleteForm");
        form.submit();
    }

    //----------------------------------------------------------------------------------------------------------------

    //HERACTIVEREN
    function showModalReactivate(klas, idKlas) {
        document.getElementById("userREA").innerHTML = klas;
        document.getElementById("knopHeractiveren").value = idKlas;
    }

    function reactivateUser(id) {
        let idInput = document.querySelector("#klasId2");
        idInput.value = id;
        let form = document.querySelector("#klasReactivateForm");
        form.submit();
    }
</script>
