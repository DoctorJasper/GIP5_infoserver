<!DOCTYPE html>
<?php
require("startphp.php");

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php");
    $klas = $_POST["klas"];

    //Update query template
    $query = "INSERT INTO `tblKlassen`(`Klas`) VALUES ('$klas')";

    //Execute the query
    try {
        $res = $pdo->prepare($query);
        $res->execute();
        header("Location: klasOverview.php");
        exit;
    } catch (PDOException $e) 
    {
        echo "Guery error.<br>".$e;
        die();
    }
}
require("header.php");
?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <a class="btn btn-outline-primary" role="button" href="adminpage.php">Terug</a>
                <br><br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="mb-3">
                        <label for="Klas" class="form-label">Gebruikersnaam</label>
                        <input type="text" class="form-control" id="Klas" name="klas" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Klas aanmaken</button>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</body>
</html>