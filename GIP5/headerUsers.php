<!DOCTYPE html>
<html lang="nl-be">
<head>
  <title>Infoserver</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f2e6ff;  
      overflow: hidden;
    }
    .header {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000; /* Ensure the header is above other elements */
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
      background: linear-gradient(to right, #000000, #1a3b9e);
      color: #ffffff;
    }
    .card-body {
      color: #000000;
    }
    .sidebar {
            background-color: #05072b;
            color: #05072b;
            height: 100vh;
            padding-top: 20px;
    }
    .sidebar a {
            color: #ecf0f1;
    }
    .sidebar a:hover {
            color: #ecf0f1;
    }
    
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="header">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
    <a class="navbar-brand">INFOSERVER</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        
        <br>
      </div>
    </div>
  </nav>
</div>


</body>
</html>