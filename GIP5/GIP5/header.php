<html lang="nl-be">

<head>
  <!-- Titel van de pagina -->
  <title>Infoserver</title>

  <!-- Metatags voor karaktercodering en viewportconfiguratie -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Koppeling naar Bootstrap CSS en Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


  <style>
    body {
      background-color: #d3d3db; 
    }

    .btn-primary {
      background-color: #1a3b9e; 
      border-color: #1a3b9e;
    }

    .btn-primary:hover {
      background-color: #993399;
      border-color: #993399; 
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
    }

    .card-header {
      background: linear-gradient(to right, #000000, #1a3b9e); /* Achtergrondkleur van kaartkop met verloop */
      color: #ffffff; /* Tekstkleur van kaartkop */
    }

    .card-body {
      color: #000000; /* Tekstkleur van kaartinhoud */
    }
  </style>

  <!-- Koppeling naar Bootstrap JS-bibliotheek -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

  <!-- Navigatiebalk met Bootstrap-styling -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <!-- Merknaam in de navigatiebalk -->
      <a class="navbar-brand" href="#">INFOSERVER</a>
      <!-- Toggler voor responsieve weergave op kleinere schermen -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Navigatielinks -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <!-- Navigatielinks naar verschillende pagina's -->
          <li class="nav-item">
            <a class="nav-link" href="About.php">About</a>
          </li>
          
          
          <!-- Toont login-link als de gebruiker niet is ingelogd, anders toont logout-link -->
          <?php if (!isset($_SESSION["username"])): ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">login</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
