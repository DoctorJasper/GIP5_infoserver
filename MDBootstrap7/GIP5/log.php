<?php 
    require('../header.php');
   
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }

    $lines = explode(PHP_EOL, file_get_contents('log.txt'));
    
    require('../startHTML.php');
?>
<style>
    .card {
        margin-left: 75px;
        margin-right: 75px; 
        margin-top: 40px;
    }
</style>
<?php require('../navbar.php') ;?>
<br><br>
<div class="card" id="card">
    <div class="col-sm-12">
        <div class="card-header bg-success bg-gradient text-white">
            <h3 class="ml-5">Log file</h3>
        </div>    
        <div class="card-body">
            <?php foreach($lines as $line) : ?>
                <h5><?php echo $line ?></h5>
            <?php endforeach; ?>
        </div>
    </div>
</div>  
<?php require('../footer1.php');?>
<?php require('../footer2.php');?>