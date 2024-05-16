<?php
    require('../header.php');
   
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');


    require('../startHTML.php');
?>
    <p>Homo</p>
<?php
    require('../footer1.php');
    require('../footer2.php');
?>