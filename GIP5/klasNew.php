<!DOCTYPE html>
<?php
require("startphp.php");

$showAlert = false;

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
    header("Location: login.php");
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
            echo "Guery error.<br>".$e;
            die();
        }
    } else {
        $TextAlert = "<strong> FOUT! </strong> de klas moet minstens 2 tekens bevatten.";
        $showAlert = true;
    }
}
require("header.php");
?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <a class="btn btn-outline-primary" role="button" href="klasOverview.php">Terug</a>
                <?php if ($showAlert) : ?>
                    <div class="alert alert-danger float-end">
                        <?php echo $TextAlert; ?>
                    </div>
                <?php endif; ?>
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