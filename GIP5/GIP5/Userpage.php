<!DOCTYPE html>

<?php
require("startphp.php");

// Controleert of de gebruiker is ingelogd en een beheerder is
if (!isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    // Als de gebruiker geen sessie heeft of geen beheerder is, wordt deze doorgestuurd naar de inlogpagina
    header("Location: login.php");
    exit;
} elseif (isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    // Als de gebruiker is ingelogd maar geen beheerder is, wordt deze doorgestuurd naar de 'Over ons'-pagina
    header("Location: About.php");
    exit();
}   

require("Header.php");
?>

<html lang="en">
<head>
    <!-- Meta-informatie over het document -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    
    <!-- Koppeling naar Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    
    
</head>

<body>

<!-- Navigatiebalk -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="/">Infoserver</a>
</nav>

<div class="container-fluid">
    <!-- Rij binnen de container -->
    <div class="row">
        <!-- Linker zijbalk (verborgen op medium en klein formaat schermen) -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-sticky">
                <!-- Ongeordende lijst met navigatielinks -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <!-- Link naar de 'Over ons'-pagina -->
                        <a class="nav-link" href="About.php"><i class="bi bi-info-circle">&nbsp; Over ons</i></a>
                    </li>
                    <li class="nav-item">
                        <!-- Link naar de contactpagina op een extern domein -->
                        <a class="nav-link" href="https://go-ao.smartschool.be/helpdesk#!tickets/list/4ca0ce7d-eeb0-4842-802e-8c5701705bcf" target="_blank"><i class="bi bi-telephone">&nbsp; Contact</i> </a>
                    </li>
                    <li class="nav-item">
                        <!-- Link naar de uitlogpagina -->
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right">&nbsp; logout</i></a>
                    </li>
                    <li class="nav-item">
                        <!-- Link naar de gebruikersoverzichtpagina -->
                        <a class="nav-link" href="userOverview.php"><i class="bi bi-database">&nbsp; Overview</i></a>
                    </li>
                    <li class="nav-item">
                        <!-- Link naar de klassenoverzichtpagina -->
                        <a class="nav-link" href="klasOverview.php"><i class="bi bi-people">&nbsp; Klassen</i></a>
                    </li>
                    <li class="nav-item">
                        <!-- Link naar de pagina voor het toevoegen van een nieuwe gebruiker -->
                        <a class="nav-link" href="usernew.php"><i class="bi bi-person-plus"> &nbsp; Nieuwe User</i></a>
                    </li>
                </ul>
            </div>
        </nav>

        
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <!-- Ruimte bovenaan de pagina -->
            <br>

            <!-- Hoofdkaartsectie -->
            <div class="card">
                <!-- Kaartheader -->
                <div class="card-header">
                    <!-- Titel van de kaart -->
                    <h1>Welkom ADMIN</h1>
                </div>

                
                <div class="col-md-4 mx-auto text-center">
                    <!-- Profielfoto van de beheerder -->
                    <img src="./Images/Pfp.jpg" class="img-fluid" alt="Sample Image">
                </div>

            
                <div class="card-body">
                  
                    <h5 class="card-title text-center">Dashboard Overview</h5>
                    
                    <p class="card-text text-center">Op deze pagina kan u de leerlingen beheren</p>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
