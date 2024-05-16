<!DOCTYPE html>
<?php
    require("../header.php");

    $showAlert = false;
    
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require("pdo.php");
        $klas = $_POST["klas"];
        if (strlen($klas) >= 2) {
            //Update query template
            $query = "INSERT INTO `tblKlassen`(`klas`) VALUES ('$klas')";

            //Execute the query
            try {
                $res = $pdo->prepare($query);
                $res->execute();
                header("Location: klasOverview.php");
                exit;
            } catch (PDOException $e) 
            {
                $toast->set("fa-exclamation-triangle", "Error","", "Aanmaken van klas mislukt","danger");
                file_put_contents("log.txt", date("Y-m-d H:i:s")." || aanmaken van klas mislukt".PHP_EOL, FILE_APPEND);
                header("Location: klasOverview.php");
                exit;
            }
        }
    } else {
        $toast->set("fa-exclamation-triangle", "Error","", "Aanmaken van klas mislukt","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || aanmaken van klas mislukt".PHP_EOL, FILE_APPEND);
        header("Location: klasOverview.php");
        exit;
    }
?>
</body>
</html>