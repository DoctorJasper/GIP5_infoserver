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
        height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
    }
    body {
        overflow: hidden;
    }
</style>
<?php require('../navbar.php') ;?>
<br><br>
<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-success bg-gradient text-white">
            <h3 class="ml-5">Logbestand</h3>
        </div>    
        <div class="card-body">
            <!-- Weergeven van elk logregel -->
             <div id="scrollable-table">                
                <?php foreach($lines as $line) : ?>
                    <h5><?php echo $line ?></h5>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>  
<?php require('../footer1.php');?>
<?php require('../footer2.php');?>