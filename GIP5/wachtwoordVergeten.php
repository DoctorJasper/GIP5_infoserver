<!DOCTYPE html>
<?php
require("startphp.php");

$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php");
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]); 

    //create GUID
    $GUID = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

    //Update query template
    $query = "SELECT `idGeb`
              FROM `tblGebruiker` 
              WHERE `userName`= :UN AND `email` = :E";

    $values = [":UN" => $username, ":E" => $email];

    //Execute the query
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    } catch (PDOException $e) 
    {
        echo "Query error";
        $showError = true;
        $errorMessage = "Uw username of email kan niet gevonden worden.";
    }

    if (!$showError) {
        $rnNumber = password_hash(rand(0000, 9999));
    }
}
require("header.php");
?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-6">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="mb-3">
                        <label for="Username" class="form-label">Gebruikersnaam</label>
                        &nbsp;<span class="text-danger"><?php if ($showError) echo ""; ?></span>
                        <input type="text" class="form-control <?php if ($showError) echo 'border-danger';?>" id="Username" name="username" value="<?php if (isset($username)) echo $username;?>">
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email</label>
                        &nbsp;<span class="text-danger"><?php if ($showError) echo ""; ?></span>
                        <input type="email" class="form-control <?php if ($showError) echo 'border-danger';?>" id="Email" name="email" value="<?php if (isset($email)) echo $email;?>">
                        <p class="text-danger"><?php if($showError) echo $errorMessage;?></p>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">S</button>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</body>
</html>