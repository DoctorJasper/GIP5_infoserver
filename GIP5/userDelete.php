<?php
require("startphp.php");

if (!isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    header("Location: login.php");
    exit;
} elseif (isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    header("Location: About.php");
    exit();
}

require("pdo.php");

//SET USER TO UNACTIVE
$id = $_GET['id'];

//Update query template
$query = "UPDATE `tblGebruiker`
SET `active` = '0'
WHERE `idGeb` = '$id'";

//Execute the query
try {
    $res = $pdo->prepare($query);
    $res->execute();    
    $row = $res->fetch(PDO::FETCH_ASSOC);
    header("Location: userOverview.php");
    exit();
} catch (PDOException $e) 
{
    echo "Guery error.<br>".$e;
    die();
}