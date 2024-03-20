<?php
require('../header.php');
// hieronder zet je PHP code


$toonfoto = false;
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    require('../inc/config.php');
    require('../classes/class.smartschool.php');
    $internalnr = $_POST["internalnr"];
 
    $ss = new Smartschool();
    $foto = $ss->ophalenfoto($internalnr);
    $toonfoto = true;
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
                <p>Toon Foto</p>
                    <!-- Name input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="interalnr" name="internalnr" class="form-control" />
                        <label class="form-label" for="interalnrID">Interaknr</label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4">TOON FOTO</button>
                </form>
        </div>
        <?php if ($toonfoto) : ?>
            <div class="card-footer">
                <img src="data:image/png;base64,<?php echo $foto; ?>" class="rounded-circle" height="100px" width="100px">
            </div>
        <?php endif; ?>
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