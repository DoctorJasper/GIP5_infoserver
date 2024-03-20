<?php
    // Include necessary files
    require('../header.php');

    // Check if the user is an admin, if not redirect to index.php
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    // Include PDO, configuration, and class files
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    // Instantiate Smartschool class
    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

    // Query to retrieve commands from database
    $query = "SELECT `commandos` 
    FROM `tblCommandos` c, `tblPlatform` p 
    WHERE c.`idPlatform`=p.`idPlt`";

    try {
        // Prepare and execute the query
        $res = $pdo->prepare($query);
        $res->execute();
        // Fetch the command
        $row = $res->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle query error
        var_dump($e);
        die();
    }

    // GET method: if command parameter is set in the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["comm"])) {
        $command = $_GET["comm"];
    }

    // POST method: if linux command is submitted
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

    // Include startHTML, navbar, and custom style
    require('../startHTML.php');
    require('../navbar.php');
?>
<style>
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
</style>

<br><br>

<!-- Main content container -->
<div class="card">
    <div class="card-header bg-donkerrood bg-gradient">
        <h1 class="text-white text-center">Welkom <?php // echo $_SESSION["firstname"] ;?> ADMIN</h1>
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
                            <?php if (!isset($_GET["comm"])) : ?>
                                <!-- Display command and edit button -->
                                <a href="adminpage.php?comm=linux"><button type="button" class="btn btn-primary">Edit</button></a>
                                <p></p>
                                <!-- Display command text area -->
                                <div class="md-form amber-textarea active-amber-textarea-2">
                                    <textarea id="text1" class="md-textarea form-control" rows="6" disabled>
                                        <?php echo $row["commandos"]; ?>
                                    </textarea>
                                </div>
                            <?php else : ?>
                                <!-- Display form to submit edited Linux command -->
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <button type="submit" class="btn btn-success">Toepassen</button>
                                    <p></p>
                                    <!-- Display text area for editing Linux command -->
                                    <div class="md-form amber-textarea active-amber-textarea-2">
                                        <textarea id="text1" class="bg-dark br-gradient text-white md-textarea form-control" name="linux" rows="70">
                                            <?php echo $row["commandos"]; ?>
                                        </textarea>
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
                            <!-- Placeholder button and text area for phpMyAdmin -->
                            <button type="submit" class="btn btn-primary">Edit</button>
                            <p></p>
                            <div class="md-form amber-textarea active-amber-textarea-2">
                                <textarea id="text2" class="md-textarea form-control" rows="6">
                                </textarea>
                            </div>
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
    // Custom scripts
</script>
<?php
    require('../footer2.php');
?>
``
