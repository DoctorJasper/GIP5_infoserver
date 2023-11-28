<?php
    //softdelete a user
    session_start();

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
        header("Location: login.php");  
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idKlas'])) {
        $idKlas = $_POST['idKlas'];
        require("pdo.php");
        //Update query template
        $query = "UPDATE `tblKlassen`
        SET `active` = 0
        WHERE `idKlas` = :ID";

        $values = [":ID" => $idKlas];

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);    
            header("Location: klasOverview.php");
        } catch (PDOException $e) 
        {
            echo "Guery error.<br>".$e;
            die();
        }
    }
?>