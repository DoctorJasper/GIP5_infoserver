<?php
    //softdelete a user
    session_start();

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['internNr'])) {
        $internNr = $_POST['internNr'];
        require("pdo.php");
        //Update query template
        $query = "UPDATE `tblGebruiker`
        SET `active` = 1
        WHERE `internNr` = :intNr";

        $values = [":intNr" => $internNr];

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);    
            header("Location: userOverview.php");
        } catch (PDOException $e) 
        {
            echo "Guery error.<br>".$e;
            die();
        }
    }
?>