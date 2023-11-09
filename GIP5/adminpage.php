<!DOCTYPE html>
<?php
require("startphp.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

require("header.php");
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          Welcome
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <img src="./Images/Pfp.jpg" class="img-fluid" alt="Sample Image">
            </div>
            <div class="col-md-8">
              <h1>Welkom ADMIN</h1>
              <p>Op deze pagina kunt u de klassen en leerlingen beheren</p>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="Klassen" data-bs-toggle="dropdown" aria-expanded="false">
          Selecteer
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="#">6 INFO</a></li>
          <li><a class="dropdown-item" href="#">5 APDA</a></li>
          <li><a class="dropdown-item" href="#">smt smt</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
</body>
</html>