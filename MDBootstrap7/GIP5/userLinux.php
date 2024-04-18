<?php
    require('../header.php');
   
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
        $internNummer = $_GET["id"];

        $query = "SELECT * FROM `tblGebruiker` WHERE `internNr` IN($internNummer)";

        try{
            $res = $pdo->prepare($query);
            $res->execute();
            $row = $res->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            //error in de query
            die();
        }
    }



    // AANMAKEN USER ---------------------------------------------------------------------------
    $username = $row[0]["voornaam"];
    $password = "pw123";
    $cmd = 'sudo adduser '.$username.' --gecos ",,," -disabled-password';
    //$cmd = 'sudo whoami';
    //echo $cmd;
    
    $output=null;
    $retval=null;
    exec($cmd, $output, $retval);
    //var_dump($retval);
    //var_dump($output);
    if ($retval != 0) {
        $toast->set("fa-exclamation-triangle", "Meldig","", "Deze user bestaad reeds","warning");
        header('Location: userNew.php?klas='.$_GET["klas"]);
        exit;
    }
    
    //echo "Returned with status $retval and output:\n<br>";
    //print_r($output);
    
    file_put_contents("pwd.txt",$username.":".$password);
    
    $cmd = "sudo chpasswd < pwd.txt";
    exec($cmd,$output,$retval);
    if ($retval != 0) {
        $toast->set("fa-exclamation-triangle", "Error","", "Het paswoord kon niet gewijzigd worden","danger");
        header('Location: userNew.php?klas='.$_GET["klas"]);
        exit;
    }
    $toast->set("fa-exclamation-triangle", "Meldig","", "User '". $row["vooraam"]."' werd toegevoegd","success");
    file_put_contents("log.txt","User '". $row['vooraam']."' werd toegevoegd".date("Y-m-d").PHP_EOL, FILE_APPEND);
    header('Location: userNew.php?klas='.$_GET["klas"]);
    exit;
    
    // FOOTER
    require('../footer1.php');
    require('../footer2.php');
?>