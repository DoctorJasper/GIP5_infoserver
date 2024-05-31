<?php 
    require('header.php');
// PAGINA IS NOG NIET AF
    // Check if the user is an admin
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: index.php");
        exit;   
    }

    require('pdo.php');
    require('inc/config.php');
    require('classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();
    $command = "";

    $query = "SELECT commandos FROM `tblCommandos` c, `tblPlatform` p WHERE c.`idPlatform`=p.`idPlt`";

    try {
        $res = $pdo->prepare($query);
        $res->execute();
        $row = $res->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle database query error
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
        header("Location: index.php");
        exit;
    }

    require('startHTML.php');
?>
<style>
    .card {
        margin-left: 75px;
        margin-right: 75px;
        margin-top: 40px;
    }
    body {
        background-color: #f2f2f2;
    }
    .carousel-item iframe {
        width: 100%;
        height: 500px;
    }
    .carousel-control-prev, .carousel-control-next {
        width: 60px;
        height: 60px;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        filter: invert(100%);
        width: 30px;
        height: 30px;
    }
</style>
<?php require('navbarUser.php'); ?>
<br><br>
<div class="container">
    <div card></div>
    <div id="videoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white text-center">
                        Tutorial 1: How to Use Smartschool
                    </div>
                    <div class="card-body text-center">
                        <iframe src="https://www.youtube.com/embed/VIDEO_ID1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white text-center">
                        Tutorial 2: Advanced Features
                    </div>
                    <div class="card-body text-center">
                        <iframe src="https://www.youtube.com/embed/VIDEO_ID2" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white text-center">
                        Tutorial 3: Troubleshooting
                    </div>
                    <div class="card-body text-center">
                        <iframe src="https://www.youtube.com/embed/VIDEO_ID3" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<?php
    require('footer1.php');
    require('footer2.php');
?>