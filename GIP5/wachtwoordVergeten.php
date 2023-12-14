<!DOCTYPE html>
<?php
require("startphp.php");

$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php");
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]); 

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
        $chars = "abcdefgthi...aABC Z0139";
        $code = "";
        for ($i=0; $i<5; $i++) {
            $randGetal = rand(max length van $chars);
            $code = $code + $chars[$randGetal];
        }
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
                    <button type="submit" class="btn btn-success">Stuur code</button>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</body>
</html>