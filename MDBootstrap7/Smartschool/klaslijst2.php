<?php
    require('../header.php');
// hieronder zet je PHP code

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();

    $klas = $_POST['klas'];

    $result = $ss->ophalenLeerlingen($klas);
    //omzetten van json naar associatieve array
    $resultArray = json_decode($result,true);//als je geen true doen, dan zet hij ze om naar objecten
    //sorteren van de array
    foreach ($resultArray['account'] as $key => $row) {
        $naam[$key] = $row['naam'];
        $voornaam[$key] = $row['voornaam'];
    }
    array_multisort($naam, SORT_ASC, $voornaam, SORT_ASC, $resultArray['account']);
   
}
else{
    header("Location: klaslijsten.php");
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
        <a href="klaslijst_print.php?klas=<?php echo $klas; ?>" target="_blank">
            <span class="float-end"><i class="fas fa-print fa-2x"></i></span>   
       </a>
        <h3><i class="fas fa-clipboard-list"></i>&nbsp;Klaslijst van <?php echo $klas; ?></h3>
        </div>
        <div class="card-body">
                <table class="table align-middle mb-0 bg-white">
            <thead class="bg-light">
                <tr>
                <th>Name</th>
                <th>Internnr</th>
                <th>Status</th>
                <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($resultArray['account'] as $key => $row) : ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <?php $foto = $ss->ophalenfoto($row['internnummer']); ?>
                        <img
                        src="data:image/png;base64,<?php echo $foto; ?>" 
                        class="rounded-circle" 
                        height="100px" 
                        width="100px"
                        />
                                    <div class="ms-3">
                                        <p class="fw-bold mb-1"><?php echo $row['naam']; ?></p>
                                        <p class="text-muted mb-0"><?php echo $row['voornaam']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td> 
                                <p class="fw-normal mb-1"><?php echo $row['internnummer']; ?></p>
                            </td>
                            <td>
                                <span class="badge badge-success rounded-pill d-inline">
                                    <?php echo $row['@attributes']['status']; ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-link btn-sm btn-rounded">Edit</button>
                                <a href="BerichtnaarUser.php" target="_blank"></span>
                                <button type="button" class="btn btn-link btn-sm btn-rounded">Verstuur bericht</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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