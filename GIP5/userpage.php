<!DOCTYPE html>
<?php
    require('../header.php');

    if (!isset($_SESSION["firstname"])) {
        header("Location: ../index.php");
        exit;
    }

// hieronder zet je PHP code
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();


    /*if (!isset($_SESSION["admin"]) || $_SESSION["admin"] == 0) {
        header("Location: ../impersonate.php");
        die();
    }
//Update query template
/*$query = "SELECT a.`idAcc`, a.`internnrGebruiker`, a.`idPlatform`, a.`AanmaakDatum`, p.`platform`
          FROM `tblAccounts` a, `tblGebruiker` g, `tblPlatform` p
          WHERE g.`internNr` =  :intNr AND a.`idPlatform` = p.`idPlt`";

//$values = [":intNr" => $_GET["NR"]];

try{
    $res = $pdo->prepare($query);
    $res->execute($values);
} catch(PDOException $e){
    //error in de query
    echo 'Query error';
    die();
}
*/

require('../startHTML.php');
?>
<style> 
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    select {
        width: 300px;
        padding: 10px;
        font-size: 16px;
    }
    option {
        padding: 10px;
    }
    .tutorialThumbnail {
        max-width: 300px;
        max-height: 300px;
    }
</style>
<?php require('../navbar.php') ;?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-sticky">
                <ul class="navbar-nav ms-auto">
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <br>
            <div class="card">
                <div class="card-header">
                    <h1>Welkom <?php echo $_SESSION['firstname']; ?></h1>
                </div>
                <div class="card-body">
                    <h4>Uw user gegevens</h4>
                    
                </div>
            </div>
        </main>
    </div>
</div>
<body>

<select onchange="showTutorial(this)">
    <option value="">Selecteer een tutorial</option>    
    <option value="https://www.youtube.com/watch?v=KJotmmDJWAg" >Tutorial 1 - Verander je wachtwoord</option>
    <option value="https://www.youtube.com/watch?v=KJztmmDJWAg" >Tutorial 2 - CSS Basics</option>
    <!-- Add more tutorial options as needed -->
</select>

<div id="tutorialInfo">
    <div id="thumbnailContainer">
        <a id="thumbnailLink"  target="_blank">
            <img id="tutorialThumbnail" class="thumbnail" src="" alt="Tutorial Thumbnail">
        </a>
    </div>
    <h3 id="tutorialTitle"></h3>
    <p id="tutorialLink"></p>
</div>

<?php require('../footer1.php'); ?>
<script>
    function showTutorial(select) {
        var selectedIndex = select.selectedIndex;
        var selectedOption = select.options[selectedIndex];
        var thumbnail = selectedOption.getAttribute("data-thumbnail");
        var tutorialTitle = selectedOption.textContent;
        var tutorialLink = selectedOption.value;

        document.getElementById("tutorialLink").innerHTML = '<a href="' + tutorialLink + '" target="_blank">View Tutorial</a>';
    }
</script>
<?php require('../footer2.php');?>  