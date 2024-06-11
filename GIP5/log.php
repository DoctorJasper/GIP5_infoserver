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
    .pagination-container {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 20px;
    }
    .rows-per-page {
        margin-right: 10px;
    }
    .pagination-info {
        margin: 0 10px;
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
<div class="container">
    <div class="pagination-container">
        <div class="rows-per-page">
            <label for="lineFilter">Rows per page:</label>
            <select id="lineFilter" class="form-select d-inline w-auto" onchange="updateLineFilter()">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50" <?php echo $linesToShow == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $linesToShow == 100 ? 'selected' : ''; ?>>100</option>
                <option value="200" <?php echo $linesToShow == 200 ? 'selected' : ''; ?>>200</option>
            </select>
        </div>
        <div class="pagination-info">
            <span id="rowsInfo">1 - <?php echo $linesToShow; ?> of <?php echo count($lines); ?></span>
        </div>
        <div class="pagination-controls">
            <button class="btn btn-link" onclick="prevPage()">&#10094;</button>
            <button class="btn btn-link" onclick="nextPage()">&#10095;</button>
        </div>
    </div>
</div>

<?php require('../footer1.php'); ?>
<script>
    function updateLineFilter() {
        const filterValue = document.getElementById('lineFilter').value;
        window.location.href = "?lines=" + filterValue;
    }

    function prevPage() {
        console.log('Previous page');
        // Logic to go to the previous page
        updatePaginationInfo();
    }

    function nextPage() {
        console.log('Next page');
        // Logic to go to the next page
        updatePaginationInfo();
    }

    function updatePaginationInfo() {
        const filterValue = document.getElementById('lineFilter').value;
        const rowsInfo = document.getElementById('rowsInfo');
        // Logic to update the rows info text based on current page and filter value
        rowsInfo.innerText = `1 - ${filterValue} of <?php echo count($lines); ?>`; // Example update
    }
</script>
<?php require('../footer2.php'); ?>
