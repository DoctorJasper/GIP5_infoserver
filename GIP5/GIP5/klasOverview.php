<!DOCTYPE html>
<?php 
    // Start de PHP-sessie en voeg de sessie-startpagina toe
    require('startphp.php');

    // Controleer of de gebruiker niet is ingelogd of geen beheerder is, stuur anders naar de inlogpagina
    if (!isset($_SESSION["admin"]) && $_SESSION["admin"] == 0) {
        header("Location: login.php");
        exit;
    }
    
    // Inclusie van PDO-bestand voor databaseverbinding
    require('pdo.php');

    // Bepaal de status van klassen op basis van queryparameters
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleted"])) {  
        $query = "SELECT * FROM `tblKlassen` WHERE `active` = 0";
        $deleted = true;  // Gebruikt om onderscheid te maken tussen actieve en verwijderde klassen
    } else {
        $query = "SELECT * FROM `tblKlassen` WHERE `active` = 1";
        $deleted = false;
    }

    try {
        // Voer de query uit om klassen op te halen op basis van de bovenstaande status
        $res = $pdo->prepare($query);
        $res->execute();
    } catch (PDOException $e) {
        // Foutafhandeling als er een fout optreedt bij het uitvoeren van de query
        echo 'Query error';
        die();
    }

    // Inclusie van de header voor de HTML-pagina
    require('header.php');
    
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-6">
            <!-- Terugknop naar het admin-dashboard -->
            <a class="btn btn-outline-primary" role="button" href="adminpage.php">Terug</a>
            <br><br>
            <span class=float-end>
                <?php if ($deleted): ?>
                    <!-- Link naar verwijderde gebruikers -->
                    <a href="klasOverview.php"><i class="bi bi-person-heart fs-3 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                <?php else: ?>
                    <!-- Links naar nieuwe klas en verwijderde klassen -->
                    <a href="klasNew.php"><i class="bi bi-person-plus-fill fs-3" data-bs-toggle="tooltip" data-bs-placement="top" title="nieuwe klas"></i></a>                
                    &nbsp;
                    <a href="klasOverview.php?deleted"><i class="bi bi-person-fill-slash fs-3 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde klassen"></i></a>
                <?php endif; ?>
            </span>
            <h3>Overzicht <?php if ($deleted) echo "verwijderde"; ?> klassen</h3>
            <!-- Tabel met klassen -->
            <table class="table table-hover table-striped">
                <tr>
                    <th>klas</th>
                    <th>acties</th>
                </tr>
                <?php if ($res->rowCount() != 0) : ?>
                    <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <!-- Naam van de klas -->
                            <td><?php echo$row["klas"]; ?> </td>
                            <!-- Acties (Wijzigen, Verwijderen of Heractiveren) -->
                            <td>
                                <?php if ($deleted) : ?>
                                    <!-- Heractiveer-knop -->
                                    <i id="Reactivate" class="bi bi-person-up text-success fs-5" onclick='showModalReactivate("<?php echo $row["klas"]; ?>","<?php echo $row["idKlas"]; ?>")' data-bs-toggle="modal" data-bs-target="#ReactivateKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Heractiveer klas"></i>
                                <?php else : ?>
                                    <!-- Wijzig-knop -->
                                    <a href="klasUpdate.php?id=<?php echo $row['idKlas']; ?>"><i class="bi bi-pencil-square text-warning  fs-5"  data-bs-toggle="tooltip" data-bs-placement="top" title="Wijzig gebruiker"></i></a>
                                    <!-- Verwijder-knop -->
                                    <i id="Delete" class="bi bi-x-square text-danger  fs-5" onclick='showModalDelete("<?php echo $row["klas"]; ?>","<?php echo $row["idKlas"]; ?>")' data-bs-toggle="modal" data-bs-target="#DeleteKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder klas"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <!-- Melding als er geen gegevens zijn gevonden -->
                    <tr><td colspan="6">Geen gegevens gevonden</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

</div>
</div>       

<!-- Modal voor het verwijderen van een klas -->
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
                <!-- Annuleer-knop -->
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <!-- Verwijderen-knop -->
                <button type="button" value="" id="knopVerwijder" class="btn btn-danger" onclick="deactivateKlas(this.value)">Verwijder</button>
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Modal voor het heractiveren van een klas -->
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
                <!-- Annuleer-knop -->
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <!-- Heractiveren-knop -->
                <button type="button" value="" id="knopHeractiveren" class="btn btn-success" onclick="reactivateUser(this.value)">Heractiveren</button>
            </div>
        </div>
    </div>
</div>

<!-- Verborgen formulier voor het verwijderen van een klas -->
<form action="klasDelete.php" method="post" style="display:none" id="klasDeleteForm">
    <input type="hidden" id="klasId" name="idKlas">
</form>

<!-- Verborgen formulier voor het heractiveren van een klas -->
<form action="klasReactivate.php" method="post" style="display:none" id="klasReactivateForm">
    <input type="hidden" id="klasId2" name="idKlas">
</form>

<!-- JavaScript-functies voor het beheren van de modals -->
<script>
    // FUNCTIES VOOR HET VERWIJDEREN
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

    // FUNCTIES VOOR HET HERACTIVEREN
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


