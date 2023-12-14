<?php
// Inclusie van startphp.php voor configuratie en controle op sessie en rechten
require("startphp.php");

// Controle op sessie en initialisatie van 'intro_played' in de sessie
if (!isset($_SESSION["intro_played"])) {
    $_SESSION["intro_played"] = false;
}

$showIntro = false;

// Controleert of 'intro_played' niet waar is
if (!$_SESSION["intro_played"]) {
    $showIntro = true;
    $_SESSION["intro_played"] = true;
}

// Inclusie van de header voor de adminpagina
require("HeaderAdminpage.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>

    <!-- CSS introductiepagina -->
    <?php if ($showIntro): ?>
        <style>
            body {
                margin: 0;
                overflow: hidden;
                background-color: #f2e6ff;
            }

            #intro {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #000000;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Arial', sans-serif;
                font-size: 100px;
                z-index: 1010;
            }

            .sliding-bar {
                position: absolute;
                width: 0;
                height: 200px;
                background: linear-gradient(to right, #000000, #1a3b9e, #000000);
                text-shadow: 20px;
                animation: slideBar 1s ease-in-out forwards;
            }

            .white-light {
                position: relative;
                width: 100%;
                height: 200px;
                background-color: white;
                opacity: 0;
                animation: fadeIn 4s ease-in-out forwards;
            }

            @keyframes slideBar {
                0% {
                    width: 0;
                }
                100% {
                    width: 100%;
                }
            }

            @keyframes fadeIn {
                0% {
                    opacity: 0;
                }
                100% {
                    opacity: 1;
                }
            }

            .intro-text {
                position: absolute;
                opacity: 0;
                animation: fadeIn 0.75s ease-in-out forwards;
            }

            #main {
                display: none;
            }
        </style>
    <?php endif; ?>
</head>

<body>
    <!-- Toont de introductiepagina als $showIntro waar is -->
    <?php if ($showIntro): ?>
        <div id="intro">
            <div class="white-light"></div>
            <div class="sliding-bar"></div>
            <div class="intro-text">InfoServer</div>
        </div>
    <?php endif; ?>

    <!-- Navigatiebalk -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Infoserver</a>
    </nav>

    <!-- Hoofdcontainer -->
    <div class="container-fluid">
        <div class="row">
            <!-- Zijbalk met navigatie-opties -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="navbar-nav ms-auto">
                        <!-- Navigatielinks naar verschillende pagina's -->
                        <li class="nav-item">
                            <a class="nav-link" href="About.php"><i class="bi bi-info-circle">&nbsp; Over ons</i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right">&nbsp; logout</i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="userOverview.php"><i class="bi bi-database">&nbsp; Overview</i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="klasOverview.php"><i class="bi bi-people">&nbsp; Klassen</i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="usernew.php"><i class="bi bi-person-plus"> &nbsp; Nieuwe User</i></a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Hoofdgedeelte van de pagina met dynamische inhoud -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <br>
                <!-- Card met welkomstbericht voor de admin -->
                <div class="card">
                    <div class="card-header">
                        <h1>Welkom ADMIN</h1>
                    </div>
                    <!-- Afbeelding en korte beschrijving in het card-body gedeelte -->
                    <div class="col-md-4 mx-auto text-center">
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

    <script>
        // Verbergt de introductiepagina na een vertraging
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var introContainer = document.querySelector('#intro');
                introContainer.style.display = 'none';
            }, 1000);
        });
    </script>
</body>

</html>
