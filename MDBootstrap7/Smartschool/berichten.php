<?php
require('../header.php');
require('../inc/config.php');
require('../classes/class.smartschool.php');
$message = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $naar = $_POST["name"];
    $onderwerp = $_POST["Eadress"];  
    $bericht = $_POST["Message"];    
    $van = 112013;
    $ss = new Smartschool();
    $result = $ss->bericht($van, $naar, $onderwerp, $bericht);
    
    if ($result) {
        $message = "bericht is goed verzonden";
    } else {
        $message = "Er ging iets mis met het versturen van het bericht.";
    }
}
require('../startHTML.php');
?>


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
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="name" name="name" class="form-control">
                        <label class="form-label" for="name">Name</label>
                        
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="Eadress" name="Eadress" class="form-control" />
                        <label class="form-label" for="Eadress">onderwerp</label>
                        <br><br>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <textarea class="form-control" id="Message" name="Message" rows="4"></textarea>
                        <label class="form-label" for="Message">Message</label>
                        <br><br>
                    </div>

                    <!-- Submit button -->
                    <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-4">Send</button>
                </form>
            </div>
            <div class="card-footer">
                <?php echo $message ?>
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
    // Custom scripts here
</script>

<?php
require('../footer2.php');
?>
