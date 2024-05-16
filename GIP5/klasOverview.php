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

    if ($_SERVER["REQUEST_METHOD"] == "GET") {  
        //Update query template
        $query = "SELECT * FROM `tblKlassen`";
    }

    try{
        $res = $pdo->prepare($query);
        $res->execute();
    }catch(PDOException $e){
        $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
        header("Location: ../index.php");
        exit;
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
        <h1 class="text-white center">Mijn Klassen<h1>
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
                                            <a href="klasDelete.php?klas=<?php echo $row["klas"] ;?>"><i id="Delete" class="bi bi-trash text-danger fs-5" data-bs-toggle="modal" data-bs-target="#DeleteKlas" data-bs-toggle="tooltip" data-bs-placement="top" title="Verwijder klas"></i></a>
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