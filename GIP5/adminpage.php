<?php
    require('../header.php');
// hieronder zet je PHP code

    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();
    $command = "";

    $query = "SELECT commandos FROM `tblCommandos` c, `tblPlatform` p WHERE c.`idPlatform`=p.`idPlt`";

    try{
        $res = $pdo->prepare($query);
        $res->execute();
        $row = $res->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        //error in de query
        var_dump($e);
        die();
    }

//-- GET ------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["comm"])) {
        $command = $_GET["comm"];
    }

//-- POST ------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["linux"])) {
        $text = $_POST["linux"];
        $delay = 0.1;
        // Update the command in the database
        $query = 'UPDATE `tblCommandos` SET `commandos`="'. $text . '" WHERE idPlatform=1';

        try {
            // Prepare and execute the update query
            $res = $pdo->prepare($query);
            $res->execute();
            // Refresh the page after a delay
            header("Refresh: $delay");
        } catch (PDOException $e) {
            // Handle query error
            var_dump($e);
            die();
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["phpMyAdmin"])) {
        $text = $_POST["phpMyAdmin"];
        $delay = 0.1;
        // Update the command in the database
        $query = 'UPDATE `tblCommandos` SET `commandos`="'. $text . '" WHERE idPlatform=2';

        try {
            // Prepare and execute the update query
            $res = $pdo->prepare($query);
            $res->execute();
            // Refresh the page after a delay
            header("Refresh: $delay");
        } catch (PDOException $e) {
            // Handle query error
            var_dump($e);
            die();
        }
    }

    require('../startHTML.php');
?>
<style>
    .card{
        margin-left: 75px;
        margin-right: 75px;
        margin-top:40px;
    }
    body {
        background-color: #f2f2f2;
    }
</style>
<?php require('../navbar.php') ;?>
<br><br>
<div class="card">
    <div class="card-header  bg-donkerrood bg-gradient">
        <h1 class="text-white text-center">Welkom <?php// echo $_SESSION["firstname"] ;?> ADMIN</h1>
    </div>
    <div class="container-fluid">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header bg-success bg-gradient">
                            <h4 class="text-white">Linux</h4>
                        </div>
                        <div class="card-body"> 
                            <?php if ($command != "linux") : ?>
                                <a href="adminpage.php?comm=linux"><button type="button" class="btn btn-primary">Edit</button></a>
                                <p></p>
                                <div class="md-form amber-textarea active-amber-textarea-2">
                                    <textarea id="text1" class="md-textarea form-control" rows="6" disabled><?php echo $row[0]["commandos"] ;?></textarea>
                                </div>
                            <?php elseif ($command == "linux") : ?>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <button type="submit" class="btn btn-success">Toepassen</button>
                                        <p></p>
                                        <div class="md-form amber-textarea active-amber-textarea-2">
                                            <textarea id="text1" class="bg-dark br-gradient text-white md-textarea form-control" name="linux" rows="6"><?php echo $row[0]["commandos"] ;?></textarea>
                                        </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header bg-warning bg-gradient">
                            <h4 class="text-white">phpMyAdmin</h4>
                        </div>
                        <div class="card-body"> 
                            <?php if ($command != "phpMyAdmin") : ?>
                                <a href="adminpage.php?comm=phpMyAdmin"><button type="button" class="btn btn-primary">Edit</button></a>
                                <p></p>
                                <div class="md-form amber-textarea active-amber-textarea-2">
                                    <textarea id="text1" class="md-textarea form-control" rows="6" disabled><?php echo $row[1]["commandos"] ;?></textarea>
                                </div>
                            <?php elseif ($command == "phpMyAdmin") : ?>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <button type="submit" class="btn btn-success">Toepassen</button>
                                        <p></p>
                                        <div class="md-form amber-textarea active-amber-textarea-2">
                                            <textarea id="text1" class="bg-dark br-gradient text-white md-textarea form-control" name="phpMyAdmin" rows="70"><?php echo $row[1]["commandos"] ;?></textarea>
                                        </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>
        </div>
    </div>
</div>
<?php
        require('../footer1.php');
    ?>
    <!-- Custom scripts -->
    <script type="text/javascript">

    </script>
    <?php
    require('../footer2.php');
?>