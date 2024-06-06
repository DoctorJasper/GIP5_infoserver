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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $post = true;
        $platform = $_POST["platform"];
        $username = $_POST["username"];
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
    }
    #card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 75px;
    }
    .pagecard {
        padding: 25px;
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
            <?php if (!$post) : ;?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="card pagecard">
                        <span class="badge bg-warning text-dark"><h3  name="platform"><?php echo $row[0]["platform"]; ?></h3></span><br>
                        <h3 name="username"><strong>Username:</strong> <?php echo $row[0]["username"]; ?></h3>
                        <button type="submit" class="btn btn-primary float-end d-inline">edit wachtwoord</button>
                    </div>
                </form>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="card pagecard">
                        <span class="badge bg-success text-dark"><h3 name="platform"><?php echo $row[1]["platform"]; ?></h3></span><br>
                        <h3 name="username"><strong>Username:</strong> <?php echo $row[1]["username"]; ?></h3>
                        <button type="submit" class="btn btn-primary float-end d-inline">edit wachtwoord</button>
                    </div>
                </form>
            <?php else : ;?>
                <form>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="myPasswd">
                        <input type="checkbox" onclick="myFunction()">Show Password
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php endif;?>
        </div>
    </div>
</div>

<?php require('../footer1.php'); ?>
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
