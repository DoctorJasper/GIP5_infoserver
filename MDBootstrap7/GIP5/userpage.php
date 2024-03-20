<!DOCTYPE html>
<?php

//if (!isset($_SESSION["naam"]) /*|| $_SESSION["admin"] == 1*/) {
//    header("Location: login.php");
//    exit;
//}

//require('pdo.php');
    require('../header.php');
// hieronder zet je PHP code

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

    require('../startHTML.php');
    require('../navbar.php');
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style> 
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    select {
        width: 300px;
        padding: 10px;
        font-size: 16px;
    }
    option {
        padding: 10px;
    }
    .tutorialThumbnail{
        max-width: 300px;
        max-height: 300px;
    }
</style>
</head>
<body>
<br><br><br>
<div class="card">
    <div class="card-header bg-lichtgroen">
    <h1 class="text-center text-white">Welkom <?php echo $_SESSION['firstname']; ?></h1>
    </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <br>
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>Uw user gegevens</h4>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
</html>
<?php
    require('../footer1.php');
    ?>
    <!-- Custom scripts -->
    <script type="text/javascript">

    </script>
    <?php
    require('../footer2.php');
?>  