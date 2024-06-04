<?php
    require('../header.php');

    /*if (!isset($_SESSION["firstname "])) {
        header("Location: ../index.php");
        exit;
    }*/

// hieronder zet je PHP code
    require('pdo.php');
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

    
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {  
    $internNr = $_GET["id"];

    //Update query template
    $query = "SELECT g.internNr, g.naam, g.voornaam, g.klas, a.username, a.idPlatform, p.platform
    FROM `tblAccounts` a, `tblGebruiker` g, `tblPlatform` p
    WHERE g.`internNr`= :intNr AND g.internNr=a.internnrGebruiker AND a.idPlatform=p.idPlt AND a.idPlatform = 2";

    $values = [":intNr" => $internNr];

    try{
        $res = $pdo->prepare($query);
        $res->execute($values);
        $row = $res->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
        //error in de query
    }
}

require('../startHTML.php');
?>
<style> 
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
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
<?php require('../navbarUser.php') ;?>

<br><br>
<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-primary br-text-white">
            <h3 class="ml-5">Userpage <?php echo $row["voornaam"].$row["naam"]; ?></h3>
        </div>    
        <div class="card-body">
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
        </div>
    </div>
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