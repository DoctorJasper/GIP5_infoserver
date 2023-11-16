<!DOCTYPE html>
<?php
require("startphp.php");  

$showAlert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php");
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    //query klaarzetten
    $query = "SELECT `GUID`,`userName`,`userPassword`,`passwordReset`,`active`,`admin` 
              FROM `tblGebruiker` 
              WHERE `userName` = :userName";
    //values voor de PDO
    $values = [":userName" => $username];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    } catch (PDOException $e) {
        //error in de query
        echo "Query error<br>".$e;
        die();
    }
    //haal rij op uit resultaat
    $row = $res->fetch(PDO::FETCH_ASSOC);

    if ($row["active"] == true) {
        if ($username == $row["userName"] && password_verify($password, $row["userPassword"])) {
            $_SESSION["username"] = $username;
            $_SESSION['CREATED'] = time();
            $_SESSION['GUID'] = $row["GUID"];
            $_SESSION["admin"] = $row["admin"];
            if ($_SESSION["admin"] == 1) {
              header("Location: adminpage.php");
              exit;
            } else {
              header("Location: About.php");
              exit;
            }
        } else {
            //userID en ww komen niet overeen
            $TextAlert = '<strong>FOUT!</strong> Je wachtwoord of ID komen niet overeen';
        $showAlert = true;
        }
    } else {
        //geen active user
        $TextAlert = '<strong>FOUT!</strong> Er is geen actieve user gevonden';
        $showAlert = true;
    }
}
require("header.php");
?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-sm-4">
      <?php if ($showAlert) : ?>
                    <div class="alert alert-danger">
                        <?php echo $TextAlert; ?>
                    </div>
                <?php endif; ?>
        <div class="card">
          <div class="card-header">          
              Log in
          </div>
          <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <br>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>              
              </div>
              <a class="nav-link text-primary" href="../Index.php">wachtwoord vergeten</a>
              <br>
              <button type="submit" class="btn btn-primary">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  </body>
</html>