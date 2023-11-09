<!DOCTYPE html>
<html lang="nl-be">
<?php require("header.php"); ?>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">INFOSERVER</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="Homepage.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="About.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://go-ao.smartschool.be/helpdesk#!tickets/list/4ca0ce7d-eeb0-4842-802e-8c5701705bcf">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Index.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

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