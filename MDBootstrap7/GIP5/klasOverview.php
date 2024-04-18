<!DOCTYPE html>
<?php 
    require('../header.php');
// hieronder zet je PHP code
   
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

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
<div class ="card" id="card">
    <div class="card-header bg-primary bg-gradient">
        <h1 class="text-white center">Nieuwe Klassen<h1>
    </div>
    <div class="container mt-5">
        <div class="card-body">
            <div class="row">

                <!--OVERZICHT KLASSEN-->
                <div class="col-sm-6">
                    <div class ="card">
                        <div class="card-header bg-warning bg-gradient">
                            <h1 class="text-white center">Overzicht Klassen<h1>
                        </div>
                        <span class=float-end>
                            <?php if ($deleted): ?>
                                <a href="klasOverview.php"><i class="bi bi-person-heart fs-3 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde gebruikers"></i></a>
                            <?php else: ?>
                                <a href="klasNew.php"><i class="bi bi-person-plus-fill fs-3" data-bs-toggle="tooltip" data-bs-placement="top" title="nieuwe klas"></i></a>                
                                &nbsp;
                                <a href="klasOverview.php?deleted"><i class="bi bi-person-fill-slash fs-3 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="verwijderde klassen"></i></a>
                            <?php endif; ?>
                        </span>
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
                                            <?php if ($deleted) : ?>
                                                <a href="klasReactivate.php?klas=<?php echo $row["klas"] ;?>"><i id="Reactivate" class="bi bi-person-up text-success fs-5" data-bs-toggle="modal" data-bs-target="#ReactivateKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Heractiveer klas"></i></a>
                                            <?php else : ?>
                                                <a href="klasDelete.php?klas=<?php echo $row["klas"] ;?>"><i id="Delete" class="bi bi-trash text-danger fs-5" data-bs-toggle="modal" data-bs-target="#DeleteKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder klas"></i></a>
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
                <!--EIND OVERZICHT USERS-->                


                <!--KLAS SELECTEREN-->
                <div class="col-sm-6">
                    <div class ="card">
                        <div class="card-header bg-success bg-gradient">
                            <h1 class="text-white center">Selecteer Klas<h1>
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
    require('../footer1.php');
    require('../footer2.php');
?>