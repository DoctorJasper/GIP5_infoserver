<?php
require('../header.php');
// hieronder zet je PHP code
require('../inc/config.php');

require('../classes/class.smartschool.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $naar = $_POST["interalnr"];
    $onderwerp = $_POST["OnderwerpID"];
    $bericht = $_POST["boodschapID"];
    $van = 112014;

    $ss = new smartschool();
    $result = $ss->bericht($van, $naar, $onderwerp, $bericht);
    if ($result){
        $message = "Bericht is goed verzonden";
    }else{
        $message = "Bericht is niet verzonden";
    }
}

require('../startHTML.php');
?>
<!-- hier linken extra styling en css -->
<style>

</style>
<?php
require('../navbar.php');
?>
<div class="container-fluid mt-5" id="top">
<div class="d-flex justify-content-center align-items-center">
    <div class="card mt-5">
        
        <div class="card-header bg-donkerrood text-white text-center font-weight-bold">
        <a href="#" target="_blank"><span class="float-end"><i class="fas fa-print fa-2x"></i></a></span>
        <h3><i class="fas fa-clipboard-list"></i>&nbsp;Titel</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <p>Naar wie wil je een bericht sturen?</p>
                    <!-- Name input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="interalnr" name="interalnr" class="form-control" />
                        <label class="form-label" for="interalnrID">Interaknr</label>
                    </div>

                    <!-- text input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="Onderwerp" name="OnderwerpID" class="form-control" />
                        <label class="form-label" for="OnderwerpID">Onderwerp</label>
                    </div>

                    <!-- Message input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <textarea class="form-control" id="boodschap"  name="boodschapID"  rows="4"></textarea>
                        <label class="form-label" for="boodschapID">Boodschap</label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4">VERSTUUR</button>
                </form>
        </div>
        <div class="card-footer">
            <?php echo $message; ?>
        </div>
        
    </div>
</div>
</div>
<br>
<?php
require('../footer1.php');
?>
<!-- Custom scripts -->
<script type="text/javascript">

</script>
<?php
require('../footer2.php');
?>