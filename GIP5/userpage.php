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

    $post = false;
    $platform = "";
    $username = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["newPasswd"])) {
        if (isset($_POST["newPasswd"])) {
            var_dump($_POST);
            die();
            $newPasswd = $_POST["newPasswd"];
            $platform = $_POST["platform"];
            $username = $_POST["username"];
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
        position: relative;
        padding: 20px;
        background-color: #333;
    }

    .video-list {
        display: flex;
        margin: 0;
        padding: 0;
        list-style: none;
        animation: scrollVideos 20s linear infinite;
    }

    .video-item {
        flex: 0 0 auto;
        margin-right: 10px;
    }

    video {
        width: 300px;
        height: 200px;
        border: 2px solid #fff;
        border-radius: 5px;
    }

    /* Keyframes for auto-scrolling effect */
    @keyframes scrollVideos {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-300px * 3)); /* Adjust this value based on the number of videos */
        }
    }
</style>
<?php 
if ($_SESSION["admin"] == 0) {
    require('../navbarUser.php'); 
}
else {
    require('../navbar.php');
}

?>

<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-primary text-white">
            <h3 class="ml-5">Userpage: <?php echo $row[0]["voornaam"] . " " . $row[0]["naam"]; ?></h3>
        </div>
        <div class="card-body">
            <p><strong>Intern Number:</strong> <?php echo $row[0]["internNr"]; ?></p>
            <div class="col-sm-6">
                <div class="video-container">
                    <ul class="video-list">
                        <li class="video-item">
                            <video src="video1.mp4" controls></video>
                        </li>
                        <li class="video-item">
                            <video src="video2.mp4" controls></video>
                        </li>
                        <li class="video-item">
                            <video src="video3.mp4" controls></video>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6">
                <?php if (!$post) : ;?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="card pagecard">
                            <span class="badge bg-warning text-dark"><h3><?php echo $row[0]["platform"]; ?></h3></span><br>
                            <div class="d-flex align-items-center mb-3">
                                <h3><strong>Username:</strong> <?php echo $row[0]["username"]; ?></h3>
                                <button type="submit" class="btn btn-primary float-end">edit wachtwoord</button>
                            </div>
                            <input type="hidden" name="platform" value="<?php echo $row[0]["platform"]; ?>">
                            <input type="hidden" name="username" value="<?php echo $row[0]["username"]; ?>">
                        </div>
                    </form>
                    
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="card pagecard">
                            <span class="badge bg-success text-dark"><h3><?php echo $row[1]["platform"]; ?></h3></span><br>
                            <div class="d-flex align-items-center mb-3">
                                <h3><strong>Username:</strong> <?php echo $row[1]["username"]; ?></h3>
                                <button type="submit" class="btn btn-primary float-end">edit wachtwoord</button>
                            </div>
                            <input type="hidden" name="platform" value="<?php echo $row[1]["platform"]; ?>">
                            <input type="hidden" name="username" value="<?php echo $row[1]["username"]; ?>">
                        </div>
                    </form>
                <?php else : ;?>
                    <a href="userpage.php"><button class="btn btn-danger float-end">annuleer</button></a>
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
