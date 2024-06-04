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
    $internNr = $_GET["id"];

    // Update query template
    $query = "SELECT g.naam, g.voornaam, a.username, p.platform
              FROM `tblAccounts` a, `tblGebruiker` g, `tblPlatform` p
              WHERE g.`internNr` = :intNr AND a.idPlatform = p.idPlt";

    $values = [":intNr" => $internNr];

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
        background: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        padding: 15px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .card-body {
        padding: 20px;
    }

    .form-select {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 20px;
    }

    .tutorial-info {
        text-align: center;
    }

    .tutorial-info img {
        max-width: 300px;
        margin-bottom: 20px;
    }

    .tutorial-info h3 {
        margin-bottom: 10px;
    }

    .tutorial-info a {
        color: #007bff;
        text-decoration: none;
    }

    .tutorial-info a:hover {
        text-decoration: underline;
    }

    .user-details {
        margin-bottom: 20px;
    }

    .user-details h4 {
        margin-bottom: 10px;
    }

    .user-details p {
        margin: 5px 0;
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
