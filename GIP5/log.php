<?php
// Inclusief het header-bestand
require('../header.php');

// Controleer of de gebruiker een admin is. Zo niet, stuur door naar de indexpagina.
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
    header("Location: ../index.php");
    exit;   
}

// Inclusief het configuratiebestand en de Smartschool klasse
require('../inc/config.php');
require('../classes/class.smartschool.php');

// Maak een nieuw Smartschool object aan
$ss = new Smartschool();

// Lees de inhoud van het logbestand en splits het in regels
$lines = explode(PHP_EOL, file_get_contents('log.txt')); 

// Keer de volgorde van de regels om, zodat de meest recente bovenaan staat
$lines = array_reverse($lines);

// Bepaal het aantal regels om weer te geven (standaard 50)
$linesToShow = isset($_GET['lines']) ? (int)$_GET['lines'] : 50;

// Inclusief het startHTML-bestand
require('../startHTML.php');
?>
<style>
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
    #scrollable-table {
        height: 450px;
        overflow-y: auto;
        margin-bottom: 5px;
    }
    .filter-container {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }
    body {
        overflow: hidden;
    }
</style>
<?php require('../navbar.php'); ?>
<br><br>
<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-success bg-gradient text-white">
            <h3 class="ml-5">Logbestand</h3>
        </div>
        <div class="card-body">
            <!-- Weergeven van elk logregel -->
            <div id="scrollable-table">
                <?php foreach(array_slice($lines, 0, $linesToShow) as $line) : ?>
                    <h5><?php echo $line ?></h5>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>  

 <!-- Filter Dropdown -->
 <div class="filter-container d-flex align-items-center">
    <label for="lineFilter" class="form-label me-2">Toon regels: </label>
    <select id="lineFilter" class="form-select" onchange="updateLineFilter()">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="50" selected>50</option>
        <option value="100">100</option>
        <option value="200">200</option>
    </select>
</div>

<?php require('../footer1.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
    <script>
        function updateLineFilter() {
            const lines = document.getElementById('lineFilter').value;
            window.location.href = '?lines=' + lines;
        }

        // Preserve the selected option on page reload
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const lines = urlParams.get('lines') || '50';
            document.getElementById('lineFilter').value = lines;
        });
    </script>
<?php require('../footer2.php'); ?>

