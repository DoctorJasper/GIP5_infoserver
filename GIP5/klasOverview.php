<?php 
    require('../header.php');

    // Controleer of de gebruiker een admin is. Zo niet, stuur door naar de index pagina.
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Inclusief benodigde bestanden voor databaseverbinding en andere configuraties
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');
    require('datetime.php');

    // Maak een nieuw Smartschool object aan om klassen op te halen
    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

    // Query voor het ophalen van alle klassen
    $query = "SELECT * FROM `tblKlassen`";

    // Voer de query uit
    try {
        $res = $pdo->prepare($query);
        $res->execute();
    } catch (PDOException $e) {
        // Bij een fout, stel een melding in, log de fout en stuur door naar de indexpagina
        $toast->set("fa-exclamation-triangle", "Error", "", "Database query error", "danger");
        file_put_contents("log.txt", $timestamp." || Database query error".PHP_EOL, FILE_APPEND);
        header("Location: ../index.php");
        exit;
    }

    // Inclusief het HTML start-bestand
    require('../startHTML.php');
?>
<style>
    #card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
</style>
<?php require('../navbar.php') ;?>
<br><br>
<br>
<div class="card" id="card">
    <div class="card-header bg-danger">
        <h1 class="text-white center">Overzicht klassen<h1>
    </div>
    <div class="container mt-5">
        <div class="card-body">
            <div class="row">

                <!-- OVERZICHT KLASSEN -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header bg-warning bg-gradient">
                            <h1 class="text-white center">Mijn klassen<h1>
                        </div>
                        <table class="table table-hover table-striped">
                            <tr>
                                <th>klas</th>
                                <th>acties</th>
                            </tr>
                            <?php if ($res->rowCount() != 0) : ?>
                                <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <tr>
                                        <td><a class="text-black" href="userNew.php?klas=<?php echo $row["klas"]; ?>"><?php echo$row["klas"]; ?></a></td>
                                        <td> 
                                            <!-- Verwijder-knop voor klassen -->
                                            <a href="klasDelete.php?klas=<?php echo $row["klas"] ;?>"><i id="Delete" class="bi bi-trash text-danger fs-5" data-bs-toggle="modal" data-bs-target="#DeleteKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder klas"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <!-- Melding voor geen gevonden gegevens -->
                                <tr><td colspan="6">Geen gegevens gevonden</td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <!-- EINDE OVERZICHT KLASSEN -->                


                <!-- KLAS SELECTEREN -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header bg-success bg-gradient">
                            <h1 class="text-white center">Kies een niewe klas<h1>
                        </div>
                        <div class="card-body" id="SideCardKlas">
                            <form method="post" action="klasNew.php">
                                <select name="klas" class="select" data-mdb-select-init data-mdb-filter="true" onchange="this.form.submit()"> 
                                    <option disabled selected>Kies een klas</option>
                                        <?php foreach ($klasarray as $klas) : ?>
                                            <?php echo "<option value='" . $klas['code'] . "'>" . $klas['code'] . "</option>"; ?>
                                            <div>
                                            <p class="mb-0 mt-0 fw-bold"><?php echo $row["naam"];?></p>
                                            <p class="mb-0 mt-0"><?php echo $row["voornaam"];?></p>
                                        </div> 
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<?php
    // Inclusief het footer-bestand
    require('../footer1.php');
    require('../footer2.php');
?>