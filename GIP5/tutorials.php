<?php
    require('../header.php');

    // Commented out to allow easy debugging
    if (!isset($_SESSION["naam"])) {
        header("Location: ../index.php");
         exit;
    }
    
    require('../inc/config.php'); // Vereist het config.php bestand
    require('../classes/class.smartschool.php'); // Vereist de Smartschool klasse

    $ss = new Smartschool(); // Maak een nieuw object van de Smartschool klasse aan

    require('pdo.php');
    require('../startHTML.php');
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    #card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 75px;
    }
    .pagecard {
        padding: 25px;
    }
</style>
<?php 
if ($_SESSION["admin"] == 0) {
    require('../navbarUser.php'); 
}
else {
    require('../navbar.php');
}

?>

<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-body">
            <div class="tutorial-selection">
                <label for="tutorialSelect">Select a tutorial:</label>
                <select id="tutorialSelect" class="form-select" onchange="showTutorial(this)">
                    <option value="" data-thumbnail="" selected disabled>Select a tutorial</option>
                    <option value="https://www.youtube.com/watch?v=KJotmmDJWAg" data-thumbnail="link_to_thumbnail_1.jpg">Tutorial 1 - Change Your Password</option>
                    <option value="https://www.youtube.com/watch?v=KJztmmDJWAg" data-thumbnail="link_to_thumbnail_2.jpg">Tutorial 2 - CSS Basics</option>
                </select>
            </div>

            <div class="tutorial-info" id="tutorialInfo">
                <a id="thumbnailLink" href="#" target="_blank">
                    <img id="tutorialThumbnail" src="" alt="Tutorial Thumbnail">
                </a>
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

        document.getElementById("thumbnailLink").href = tutorialLink;
        document.getElementById("tutorialThumbnail").src = thumbnail;
        document.getElementById("tutorialTitle").textContent = tutorialTitle;
        document.getElementById("tutorialLink").innerHTML = '<a href="' + tutorialLink + '" target="_blank">View Tutorial</a>';
    }
</script>
<?php require('../footer2.php'); ?>
