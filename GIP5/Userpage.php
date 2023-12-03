<!DOCTYPE html>
<?php
require("startphp.php");

if (!isset($_SESSION["username"]) || $_SESSION["admin"] == 1) {
    header("Location: login.php");
    exit;
} 
    
require('pdo.php');

//Update query template
$query = "SELECT a.`idAcc`, a.`guidGebruiker`, a.`idPlatform`, a.`AanmaakDatum`, p.`platform`
          FROM `tblAccounts` a, `tblGebruiker` g, `tblPlatform` p
          WHERE g.`GUID` =  :ID AND a.`idPlatform` = p.`idPlt`";

$values = [":ID" => $_GET["GUID"]];

try{
    $res = $pdo->prepare($query);
    $res->execute($values);
} catch(PDOException $e){
    //error in de query
    echo 'Query error';
    die();
}

require("headerUsers.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Infoserver</a>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-sticky">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="About.php"  target="_blank"><i class="bi bi-info-circle">&nbsp; Over ons</i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://go-ao.smartschool.be/helpdesk#!tickets/list/4ca0ce7d-eeb0-4842-802e-8c5701705bcf" target="_blank"><i class="bi bi-telephone">&nbsp; Contact</i> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right">&nbsp; logout</i></a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <br>
            <div class="card">
                <div class="card-header">
                    <h1>Welkom USER</h1>
                </div>
                <div class="card-body">
                    <h4>Uw user gegevens</h4>
                    <?php if ($res->rowCount() != 0) : ?>
                        <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                            <br>
                            <div class="card">
                                <div class="card-bosy bg-light ml-3 mt-1">
                                    <h3><?php echo $row["platform"];?></h3>
                                </div>
                                <div class="card-bosy ml-3 mt-1">
                                    <p><?php echo $row["AanmaakDatum"];?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="6">Geen gegevens gevonden</td></tr>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>