<html lang="nl-be">
<head>
  <title>Infoserver</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
      body{
        background-color : White;
      }
      .btn-primary {
        background-color: #ffffff; 
        border-color: #8E0037;
      }
      .btn-primary:hover {
        background-color: #8E0037; 
        border-color: #8E0037;
      }
      .navbar {
        background: linear-gradient(to right, #000000, #8E0037); 
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
        border-style: solid;
          border-width: 2px 2px 2px 2px;
      border-color: black;
      border-radius: 0.7rem;
      }
      .card-header {
        background: linear-gradient(to right, #000000, #8E0037);
        color: #ffffff;
      }
      .card-body {
        color: #000000;
      }
      #ReturnButton{
        
        background-color: #ffffff; 
        border-color: #8E0037;
      }
      #ReturnButton:hover {
        background-color: #8E0037; 
        border-color: #8E0037;
      }
      #SideCardKlas{
        
      }
      .select{
   
/* <select> styles */

  /* Reset */
  appearance: none;
  border: 0;
  outline: 0;
  font: inherit;
  /* Personalize */
  width: 20rem;
  padding: 1rem 4rem 1rem 1rem;
  background: var(--arrow-icon) no-repeat right 0.8em center / 1.4em,
    linear-gradient(to left, var(--arrow-bg) 3em, var(--select-bg) 3em);
  color: #8E0037;
  border-radius: 0.25em;
  box-shadow: 0 0 1em 0 rgba(0, 0, 0, 0.2);
  cursor: pointer;

}

      /*.tbody{
        
        background-color: #000000; 
        color: #8E0037;
        border-style: solid;
          border-width: 2px 2px 2px 2px;
      border-color: black;
      border-radius: 0.7rem;
      
      }*/
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