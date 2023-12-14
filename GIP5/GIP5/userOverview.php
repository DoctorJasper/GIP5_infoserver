<!DOCTYPE html>
<?php 
    require('startphp.php');

    // Controleer of de gebruiker is ingelogd en administratorrechten heeft
    if (!isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
        header("Location: login.php");
        exit;
    } elseif (isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
        header("Location: About.php");
        exit();
    }
    
    require('pdo.php');

    // Controleer of het een GET-verzoek is en of "deleted" is ingesteld
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleted"])) {  
        // Update query-template voor het ophalen van verwijderde gebruikers
        $query = "SELECT `idGeb`,`GUID`,`userName`,`naam`,`voornaam`,`email`,`active`,`admin` FROM `tblGebruiker` WHERE `active` = 0";
        $deleted = true;
    } else {
        // Update query-template voor het ophalen van actieve gebruikers
        $query = "SELECT `idGeb`,`GUID`,`userName`,`naam`,`voornaam`,`email`,`active`,`admin` FROM `tblGebruiker` WHERE `active` = 1";
        $deleted = false;
    }

    try{
        // Voorbereiden en uitvoeren van de query
        $res = $pdo->prepare($query);
        $res->execute();
    } catch(PDOException $e){
        // Foutmelding bij queryfout
        echo 'Query error';
        die();
    }

    require('header.php');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-sm-12">
            <!-- Terugknop naar beheerderspagina -->
            <a class="btn btn-outline-primary" role="button" href="adminpage.php">Terug</a>
            <br><br>
            <span class="float-end">
                <?php if ($deleted): ?>
                    <!-- Link naar overzicht van actieve gebruikers -->
                    <a href="userOverview.php"><i class="bi bi-person-heart fs-3 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                <?php else: ?>
                    <!-- Links naar nieuwe gebruiker toevoegen, verwijderde gebruikers tonen en verwijderde gebruikers herstellen -->
                    <a href="userNew.php"><i class="bi bi-person-plus-fill fs-3" data-bs-toggle="tooltip" data-bs-placement="top" title="nieuwe gebruiker"></i></a>                
                    &nbsp;
                    <a href="userOverview.php?deleted"><i class="bi bi-person-fill-slash fs-3 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                <?php endif; ?>
            </span>
            <h3>Overzicht <?php if ($deleted) echo "verwijderde"; ?> gebruikers</h3>
            <table class="table table-hover table-striped">
                <tr>
                    <th>Gebruikersnaam</th>
                    <th>Naam</th>
                    <th>Voornaam</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Acties</th>
                </tr>
                <?php if ($res->rowCount() != 0) : ?>
                    <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?php echo$row["userName"]; ?> </td>
                            <td><?php echo$row["naam"]; ?> </td>
                            <td><?php echo$row["voornaam"]; ?> </td>
                            <td><?php echo$row["email"]; ?> </td>
                            <td><?php echo$row["admin"] ? '<i class="bi-check-square-fill text-success fs-5"></i>':
                                                            '<i class="bi bi-square fs-5"></i>';?> </td>
                            <td>
                                <?php if ($deleted) : ?>
                                    <!-- Knop voor het heractiveren van verwijderde gebruiker -->
                                    <i id="Reactivate" class="bi bi-person-up text-success fs-5" onclick='showModalReactivate("<?php echo $row["userName"]; ?>","<?php echo $row["GUID"]; ?>")' data-bs-toggle="modal" data-bs-target="#Activate" data-bs-toggle="tooltip" data-bs-placement="top" title="Heractiveer gebruiker"></i>
                                <?php else : ?>
                                    <!-- Link naar pagina voor het wijzigen van gebruiker -->
                                    <a href="userUpdate.php?id=<?php echo $row['idGeb']; ?>"><i class="bi bi-pencil-square text-warning fs-5"  data-bs-toggle="tooltip" data-bs-placement="top" title="Wijzig gebruiker"></i></a>
                                    <!-- Knop voor het verwijderen van gebruiker -->
                                    <i id="Delete" class="bi bi-x-square text-danger fs-5" onclick='showModalDelete("<?php echo $row["userName"]; ?>","<?php echo $row["GUID"]; ?>")' data-bs-toggle="modal" data-bs-target="#DeleteUser" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder gebruiker"></i>
                                    <!-- Knop voor het herstellen van gebruiker -->
                                    <i class="bi bi-arrow-clockwise text-info fs-5"></i>
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

<!-- Modal voor het verwijderen van gebruiker -->
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
                <!-- Annuleerknop voor het sluiten van het modal -->
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <!-- Knop voor het daadwerkelijk verwijderen van de gebruiker -->
                <button type="button" value="" id="knopVerwijder" class="btn btn-danger" onclick="deactivateUser(this.value)">Verwijder</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal voor het heractiveren van gebruiker -->
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
                <!-- Annuleerknop voor het sluiten van het modal -->
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Annuleer</button>
                <!-- Knop voor het daadwerkelijk heractiveren van de gebruiker -->
                <button type="button" value="" id="knopHeractiveren" class="btn btn-success" onclick="reactivateUser(this.value)">Heractiveren</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulier voor het verwijderen van gebruiker -->
<form action="userDelete.php" method="post" style="display:none" id="userDeleteForm">
    <input type="hidden" id="userId" name="GUID">
</form>

<!-- Formulier voor het heractiveren van gebruiker -->
<form action="userReactivate.php" method="post" style="display:none" id="userReactivateRorm">
    <input type="hidden" id="userId2" name="GUID">
</form>

<script>
    // Functie voor het tonen van het verwijderen modal
    function showModalDelete(username, guid) {
        document.getElementById("userDEL").innerHTML = username;
        document.getElementById("knopVerwijder").value = guid;
    }

    // Functie voor het verwijderen van gebruiker
    function deactivateUser(id) {
        let idInput = document.querySelector("#userId");
        idInput.value = id;
        let form = document.querySelector("#userDeleteForm");
        form.submit();
    }

    // Functie voor het tonen van het heractiveren modal
    function showModalReactivate(username, guid) {
        document.getElementById("userREA").innerHTML = username;
        document.getElementById("knopHeractiveren").value = guid;
    }

    // Functie voor het heractiveren van gebruiker
    function reactivateUser(id) {
        let idInput = document.querySelector("#userId2");
        idInput.value = id;
        let form = document.querySelector("#userReactivateRorm");
        form.submit();
    }
</script>
