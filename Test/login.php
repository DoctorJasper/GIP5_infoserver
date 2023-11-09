<!DOCTYPE html>
<?php
    require("startphp.php");

    if (isset($_SESSION['username'])) {
        //user is reeds aangemeld
        header("Location: beveiligd.php");
        exit;
    }
?>

<?php require("../header.php"); ?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-sm-4">
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