<html lang="nl-be">
<head>
  <title>Infoserver</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background-color:#ffffff;
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
      background: linear-gradient(to right, #000000,#010052,#1a3b9e); 
    }
    .navbar-dark .navbar-nav .nav-link {
      color: #ffffff;
    }
    .navbar-dark .navbar-toggler-icon {
      filter: invert(1) brightness(100);
    }
    .card {
      background-color: #cfcfcf; 
      color: #000000;
    }
    .card-header {
      background: linear-gradient(to right, #000000, #1a3b9e);
      color: #ffffff;
    }
    .card-body {
      color: #000000;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand">INFOSERVER</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="About.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://go-ao.smartschool.be/helpdesk#!tickets/list/4ca0ce7d-eeb0-4842-802e-8c5701705bcf" target="_blank">Contact</a>
        </li>
        <?php if (!isset($_SESSION["username"])): ?>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>