<?php
require('../header.php');

// Commented out to allow easy debugging
// if (!isset($_SESSION["firstname"])) {
//     header("Location: ../index.php");
//     exit;
// }

require('pdo.php');
require('../inc/config.php');
require('../classes/class.smartschool.php');

$ss = new Smartschool();
$klasarray = $ss->ophalenKlassen();

$row = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    
    $query = "SELECT * FROM `tblGebruiker` WHERE `internNr` = :intNr";

    $values = [":intNr" => $_GET["id"]];

    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
        $row = $res->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
    }

    $_SESSION["lastname"] = $row["naam"];
    $_SESSION["firstname"] = $row["voornaam"];
    $_SESSION["internalnr"] =  $row["internNr"];
    $_SESSION["email"] =  $row["email"];
    $_SESSION["admin"] =  $row["admin"];

    // Update query template
    $query = "SELECT g.naam, g.voornaam, a.username, p.platform
              FROM `tblAccounts` a, `tblGebruiker` g, `tblPlatform` p
              WHERE g.`internNr` = :intNr AND a.idPlatform = p.idPlt";

    $values = [":intNr" => $_SESSION["internalnr"]];

    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
        $row = $res->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error
        echo "Error: " . $e->getMessage();
    }
}

require('../startHTML.php');
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
</style>
<?php require('../navbarUser.php'); ?>

<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-primary text-white">
            <h3 class="ml-5">Userpage: <?php echo htmlspecialchars($row["voornaam"] . " " . $row["naam"]); ?></h3>
        </div>
        <div class="card-body">
            <div class="user-details">
                <h4>User Details</h4>
                <p><strong>Intern Number:</strong> <?php echo htmlspecialchars($internNr); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($row["voornaam"] . " " . $row["naam"]); ?></p>
                <p><strong>Platform:</strong> <?php echo htmlspecialchars($row["platform"]); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($row["username"]); ?></p>
            </div>

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
