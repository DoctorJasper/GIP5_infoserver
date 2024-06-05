<?php
require("../startphp.php");  
require("../pdo.php");

//de naam van je applicatie, deze moet gekend zijn in het dashboard
$oAuthAppName = "gip5_server";
//de url waarnaar je de gebruiker stuurt om in te loggen
$oAuthLoginUrl = "https://www.go-atheneumoudenaarde.be/dashboard_dev/oAuthLogin.php";
//de url om extra informatie te krijgen over een gebruiker
$oAuthGetUserInfoUrl = "https://www.go-atheneumoudenaarde.be/dashboard_dev/oAuthGetUserInfo.php";

if(!isset($_GET["code"])) {

    //stuur de gebruiker naar hier om in te loggen
    $loginUrl = $oAuthLoginUrl. "?app=" .$oAuthAppName;
    header("Location: $loginUrl");
    exit;
} else {
    $userToken = $_GET["code"];
    $dataJson = json_encode(array("app" => $oAuthAppName, "code" => $userToken));
    $options = array(
        'Content-Type: application/json',
        'Content-Length: '. strlen($dataJson)
    );
    $ch = curl_init($oAuthGetUserInfoUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $options);

    $result = curl_exec($ch);
    $result = json_decode($result, true);

    //query klaarzetten
    $query = "SELECT *
              FROM `tblGebruiker` 
              WHERE `internNr` = :internNr";

    //values voor de PDO
    $values = [":internNr" => $result["internalnr"]];
    
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    } catch (PDOException $e) {
        //error in de query
        echo "Query error<br>".$e;
        die();
    }
              
    //haal rij op uit resultaat
    $row = $res->fetch(PDO::FETCH_ASSOC);

    $_SESSION["internnummer"] = $result["internalnr"];
    $_SESSION["naam"] = $result["naam"];
    $_SESSION["voornaam"] = $result["voornaam"];
    $_SESSION["admin"] = $row["admin"];
    $_SESSION['CREATED'] = time();

    if (is_null($row)){
        header("Location: https://go-ao.smartschool.be");
        die();
    }

    if ($row["active"] == 1) {
        if ($row["admin"] == 0) {
            header("Location: ../GIP5/userpage.php");
            die();
        } else {
            header("Location: ../GIP5/adminpage.php");
            die();
        }
    }
}