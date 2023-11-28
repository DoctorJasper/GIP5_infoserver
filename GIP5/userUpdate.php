<!DOCTYPE html>
<?php
require("startphp.php");

if (!isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    header("Location: login.php");  
    exit;
} elseif (isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    header("Location: About.php");
    exit();
}

require("pdo.php");
$post = false;

//UPDATE USER
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $id = $_GET['id'];
    //Update query template
    $query = "SELECT `idGeb`,`userName`,`naam`,`voornaam`,`email`,`admin` 
    FROM `tblGebruiker` 
    WHERE `idGeb` = $id";

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
    var_dump($_POST);
    $post = true;
    $idGeb = $_POST["idGeb"];
    $username = trim($_POST["username"]);
    $naam = trim($_POST["naam"]); 
    $voornaam = trim($_POST["voornaam"]);
    $email = trim($_POST["email"]);
    $admin = isset($_POST["admin"]) ? 1 : 0;

    //Update query template
    $query = "UPDATE `tblGebruiker`
              SET `userName` = '$username', `naam` = '$naam',`voornaam` = '$voornaam',`email` = '$email',`admin` = '$admin'
              WHERE `idGeb` = '$idGeb'";

    //Execute the query
    try {
        $res2 = $pdo->prepare($query);
        $res2->execute();
        header("Location: userOverview.php");
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
                <a class="btn btn-outline-primary" role="button" href="userOverview.php">Terug</a>
                <p><br></p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="mb-3">
                        <input type="hidden" class="from-control" name="idGeb" value="<?php if(!$post) echo $id ;?>">
                        <label for="Username" class="form-label">Gebruikersnaam</label>
                        <input type="text" class="form-control" id="Username" name="username" value="<?php if (!$post) echo $row['userName']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Naam" class="form-label">Naam</label>
                        <input type="text" class="form-control" id="Naam" name="naam" value="<?php if (!$post) echo $row['naam']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Voornaam" class="form-label">Voornaam</label>
                        <input type="text" class="form-control" id="Voornaam" name="voornaam" value="<?php if (!$post) echo $row['voornaam']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="Email" name="email" value="<?php if (!$post) echo $row['email']; ?>" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="admin" type="checkbox" role="switch" id="flexSwitchCheckDefault" <?php if (!$post) { if(isset($row["admin"]) && $row["admin"] == 1) echo "checked"; };?>>
                        <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Gebruiker updaten</button>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</body>
</html>