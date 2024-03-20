<!DOCTYPE html>
<?php 
    require('../header.php');

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

    require('../startHTML.php');
    require('../navbar.php');
    
    $showAlert = false;
    $post = false;

    //UPDATE USER
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        //Update query template
        $query = "SELECT * 
        FROM `tblKlassen` 
        WHERE `idKlas` = $id";

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute();    
            $row = $res->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) 
        {
            echo "Guery error.<br>".$e;
            die();
        }
    
    }
    else {
        $post = true;
        $idKlas = $_POST["id"];
        $klas = $_POST["klas"];
        if (strlen($klas) >= 2) {
            //Update query template
            $query = "UPDATE `tblKlassen`
                    SET `klas` = '$klas'
                    WHERE `idKlas` = '$idKlas'";

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
                        <input type="hidden" class="from-control" name="id" value="<?php if(!$post) echo $id ;?>">
                        <label for="Klas" class="form-label">klas</label>
                        <input type="text" class="form-control" id="Klas" name="klas" value="<?php if (!$post) echo $row['klas']; ?>" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Klas updaten</button>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</body>
</html>