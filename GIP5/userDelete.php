<?php
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
        header("Location: login.php");  
        exit;
    }
    
    //softdelete a user
    session_start();

    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['GUID'])) {
        $GUID = $_POST['GUID'];
        require("pdo.php");
        //Update query template
        $query = "UPDATE `tblGebruiker`
        SET `active` = 0
        WHERE `GUID` = :ID";

        $values = [":ID" => $GUID];

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);    
            echo "ok";
        } catch (PDOException $e) 
        {
            echo "Guery error.<br>".$e;
            die();
        }
        header("Location: userOverview.php");
    }
?>