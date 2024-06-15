<?php
    require('../header.php');

    // Commented out to allow easy debugging
    // if (!isset($_SESSION["firstname"])) {
    //     header("Location: ../index.php");
    //     exit;
    // }
    
    require('../inc/config.php'); // Vereist het config.php bestand
    require('../classes/class.smartschool.php'); // Vereist de Smartschool klasse

    $ss = new Smartschool(); // Maak een nieuw object van de Smartschool klasse aan

    require('pdo.php');
    require('pdoLocal.php');

    $post = false;
    $platform = "";
    $username = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["newPasswd"])) {
            $newPasswd = $_POST["newPasswd"];
            $platform = $_POST["platform"];
            $username = $_POST["username"];

            if ($platform == "MySql") {
                $query = "SELECT `commandos` FROM `tblCommandos` WHERE `idPlatform` = 2 AND `type` = 'update'";
            
                try {
                    $res = $pdo->prepare($query);
                    $res->execute();
                    $commando = $res->fetch(PDO::FETCH_ASSOC)['commandos'];
                    $commando = str_replace("gebruikersnaam", $username, $commando);
                    $commando = str_replace("password", $newPasswd, $commando);
                    try {
                        // Voert het commando uit om de gebruiker toe te voegen
                        $res = $pdoLocal->prepare($commando);
                        $res->execute();
                        $toast->set("fa-exclamation-triangle", "Note","", "Password is geüpdatet","Success");
                    }
                    catch (PDOException $e) {
                        // Logt eventuele databasefouten
                        file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                        array_push($tabel, array("Gefaal om mySql user $username toe te voegen", "danger"));
                    }
                } catch (PDOException $e) {
                    // Logt eventuele databasefouten
                    file_put_contents("log.txt", $timestamp . " || Database query error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                } catch (Exception $e) {
                    // Logt eventuele commando-uitvoeringsfouten
                    file_put_contents("log.txt", $timestamp . " || Command execution error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            }
        }
        else {
            $post = true;
            $platform = $_POST["platform"];
            $username = $_POST["username"];
        }
    }
    // Update query template
    $query = "SELECT g.naam, g.voornaam, a.username, p.platform, g.internNr
            FROM tblGebruiker g
            JOIN tblAccounts a ON g.internNr = a.internnrGebruiker
            JOIN tblPlatform p ON a.idPlatform = p.idPlt
            WHERE g.internNr = :intNr";

    $values = [":intNr" => $_SESSION["internalnr"]];

    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
        $row = $res->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
    }

    require('../startHTML.php');
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    #card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 75px;
    }
    .pagecard {
        padding: 25px;
    }

    .video-container {
        width: 100%;
        overflow: hidden;
    }

    .video-list {
        display: flex;
        margin: 0;
        padding: 0;
        list-style: none;
        animation: scrollVideos 10s linear infinite;
    }

    .video-item {
        flex: 0 0 auto;
        margin-right: 10px;
    }

    video {
        width: 600px;
        height: 400px;
        border: 2px solid #fff;
        border-radius: 5px;
    }

    /* Keyframes for auto-scrolling effect */
    @keyframes scrollVideos {
        0% {
            transform: translateX(0);
        }
        15% {
            transform: translateX(0);
        }
        85% {
            transform: translateX(calc(-400px * 3)); /* Adjust this value based on the number of videos */
        }
        100% {
            transform: translateX(calc(-400px * 3)); /* Adjust this value based on the number of videos */
        }
    }
</style>

<?php require ($_SESSION["admin"] == 0) ? '../navbarUser.php' : '../navbar.php';?>

<div class="card" id="card">
    <div class="card-header bg-primary text-white">
        <h3 class="ml-5">Userpage: <?php echo $_SESSION["voornaam"] . " " . $_SESSION["naam"]; ?></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="video-container">
                    <ul class="video-list">
                        <li class="video-item">
                            <video src="tutorials/Eminem - Lose Yourself [HD].mp4" controls></video>
                            <h4>Tutorial 1</h4>
                        </li>
                        <li class="video-item">
                            <video src="video2.mp4" controls></video>
                            <h4>Tutorial 2</h4>
                        </li>
                        <li class="video-item">
                            <video src="video3.mp4" controls></video>
                            <h4>Tutorial 3</h4>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6">
                <?php if (!$post) : ;?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="card pagecard">
                            <span class="badge bg-warning text-dark"><h3>Linux</h3></span><br>
                            <div class="d-flex align-items-center mb-3">
                                <h3>
                                    <strong>Username:</strong> <?php echo isset($row[0]["username"]) ? $row[0]["username"] : "nog geen account"; ?>
                                </h3>
                                <button type="submit" class="btn btn-primary ms-auto" <?php echo isset($row[0]["username"]) ? "": "disabled";?>>edit wachtwoord</button>
                            </div>
                            <input type="hidden" name="platform" value="<?php echo $row[0]["platform"]; ?>">
                            <input type="hidden" name="username" value="<?php echo $row[0]["username"]; ?>">
                        </div>
                    </form>
                    <br>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="card pagecard">
                            <span class="badge bg-success text-dark"><h3>MySQL</h3></span><br>
                            <div class="d-flex align-items-center mb-3">
                                <h3>
                                    <strong>Username:</strong> <?php echo isset($row[1]["username"]) ? $row[1]["username"] : "nog geen account"; ?>
                                </h3>
                                <button type="submit" class="btn btn-primary ms-auto" <?php echo isset($row[1]["username"]) ? "": "disabled";?>>edit wachtwoord</button>
                            </div>
                            <input type="hidden" name="platform" value="<?php echo $row[1]["platform"]; ?>">
                            <input type="hidden" name="username" value="<?php echo $row[1]["username"]; ?>">
                        </div>
                    </form>
                <?php else : ;?>
                    <div class="card pagecard">                    
                        <a href="userpage.php"><button class="btn btn-danger float-end">annuleren</button></a>
                        <h3>Wachtwoord <?php echo $platform ;?> aanpassen</h3>
                        <br>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" name="newPasswd" class="form-control" id="myPasswd">
                                <input type="checkbox" class="form-check-input" onclick="myFunction()">Show Password
                                <input type="hidden" name="platform" value="<?php echo $platform; ?>">
                                <input type="hidden" name="username" value="<?php echo $username; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                <?php endif;?>                
            </div>
        </div>
    </div>
</div>

<?php require('../footer1.php'); ?>
<script src="scripts.js"></script>
<script>
    function myFunction() {
        var x = document.getElementById("myPasswd");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
<?php require('../footer2.php'); ?>
