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
    $delay = 0.1;

    $query = "SELECT commandos FROM `tblCommandos` c, `tblPlatform` p WHERE c.`idPlatform`=p.`idPlt`";

    try{
        $res = $pdo->prepare($query);
        $res->execute();
        $row = $res->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
        header("Location: ../index.php");
        exit;
    }

//-- GET ------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["comm"])) {
        $command = $_GET["comm"];
    }

//-- POST ------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["linux"])) {
        $text = $_POST["linux"];
        
        // Update the command in the database
        $query = "UPDATE `tblCommandos` SET `commandos`= '". $text . "' WHERE idComm = 1";

        try {
            // Prepare and execute the update query
            $res = $pdo->prepare($query);
            $res->execute();

            $toast->set("fa-exclamation-triangle", "Melding","", "Command veld van 'Linux AddUser' bewerkt","success");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Command veld van 'Linux AddUser' bewerkt".PHP_EOL, FILE_APPEND);
        } catch (PDOException $e) {
            $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error: ".$e->getMessage().PHP_EOL, FILE_APPEND);
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["linux2"])) {
        $text = $_POST["linux2"];
        // Update the command in the database
        $query = "UPDATE `tblCommandos` SET `commandos`= '". $text . "' WHERE idComm = 3";

        try {
            // Prepare and execute the update query
            $res = $pdo->prepare($query);
            $res->execute();

            $toast->set("fa-exclamation-triangle", "Melding","", "Command veld van 'Linux DeleteUser' bewerkt","success");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Command veld van 'Linux DeleteUser' bewerkt".PHP_EOL, FILE_APPEND);
            // Refresh the page after a delay
            header("Refresh: $delay");
        } catch (PDOException $e) {
            $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error: ".$e->getMessage().PHP_EOL, FILE_APPEND);
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["MySql"])) {
        $text = $_POST["MySql"];
        // Update the command in the database
        $query = 'UPDATE `tblCommandos` SET `commandos`="'. $text . '" WHERE idPlatform=2';

        try {
            // Prepare and execute the update query
            $res = $pdo->prepare($query);
            $res->execute();

            $toast->set("fa-exclamation-triangle", "Melding","", "Command veld van 'MySql' bewerkt","success");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Command veld van 'MySql' bewerkt".PHP_EOL, FILE_APPEND);
            // Refresh the page after a delay
            header("Refresh: $delay");
        } catch (PDOException $e) {
            $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
            file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
            header("Location: adminpage.php");
            exit;
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
    <div class="card-header bg-primary bg-gradient">
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
                                            <textarea id="text1" class="bg-dark br-gradient text-white md-textarea form-control" name="linux" rows="35"><?php echo $row[0]["commandos"] ;?></textarea>
                                        </div>
                                </form>
                            <?php endif; ?>
                            <br><br>
                            <?php if ($command != "linux2") : ?>
                                <a href="adminpage.php?comm=linux2"><button type="button" class="btn btn-primary">Edit</button></a>
                                <p></p>
                                <div class="md-form amber-textarea active-amber-textarea-2">
                                    <textarea id="text1" class="md-textarea form-control" rows="6" disabled><?php echo $row[2]["commandos"] ;?></textarea>
                                </div>
                            <?php elseif ($command == "linux2") : ?>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <button type="submit" class="btn btn-success">Toepassen</button>
                                        <p></p>
                                        <div class="md-form amber-textarea active-amber-textarea-2">
                                            <textarea id="text1" class="bg-dark br-gradient text-white md-textarea form-control" name="linux2" rows="30"><?php echo $row[0]["commandos"] ;?></textarea>
                                        </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header bg-warning bg-gradient">
                            <h4 class="text-white">MySql</h4>
                        </div>
                        <div class="card-body"> 
                            <?php if ($command != "MySql") : ?>
                                <a href="adminpage.php?comm=MySql"><button type="button" class="btn btn-primary">Edit</button></a>
                                <p></p>
                                <div class="md-form amber-textarea active-amber-textarea-2">
                                    <textarea id="text1" class="md-textarea form-control" rows="6" disabled><?php echo $row[1]["commandos"] ;?></textarea>
                                </div>
                            <?php elseif ($command == "MySql") : ?>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <button type="submit" class="btn btn-success">Toepassen</button>
                                        <p></p>
                                        <div class="md-form amber-textarea active-amber-textarea-2">
                                            <textarea id="text1" class="bg-dark br-gradient text-white md-textarea form-control" name="MySql" rows="70"><?php echo $row[1]["commandos"] ;?></textarea>
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