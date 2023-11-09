<!DOCTYPE html>
<html lang="nl-be">
<head>
  <title>CNU - Welcome</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f2e6ff;
    }
    .btn-primary {
      background: linear-gradient(to right, #000000, #1a3b9e);
      border: none;
    }
    .btn-primary:hover {
      background: linear-gradient(to right, #000000, #1a3b9e);
    }
    .navbar {
      background: linear-gradient(to right, #000000, #1a3b9e);
    }
    .navbar-dark .navbar-nav .nav-link {
      color: #ffffff;
    }
    .navbar-dark .navbar-toggler-icon {
      filter: invert(1) brightness(100);
    }
    .card {
      background-color: #ffffff;
      color: #000000;
      margin-top: 20px;
    }
    .card-header {
      background: linear-gradient(to right, #000000, #1a3b9e);
      color: #ffffff;
    }
    .card-body {
      color: #000000;
    }
    .dropdown-menu {
      background: #545259;
    }
    .dropdown-item {
      color: #ffffff;
    }
    .dropdown-item:hover {
      background-color: #100647;
      color: white; 
    }
    
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
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