<?php
    require('../header.php');
// hieronder zet je PHP code

    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();
    $klasarray = $ss->ophalenKlassen();

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
            <form method="post" action="klaslijst2.php">
                <select name="klas" data-mdb-select-init data-mdb-filter="true" onchange="this.form.submit()"> 
                    <option disabled selected>Kies een klas</option>
                        <?php 
                            foreach ($klasarray as $klas) {
                                echo "<option value='" . $klas['code'] . "'>" . $klas['code'] . "</option>";
                            }
                        ?>
                </select>
            </form>
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