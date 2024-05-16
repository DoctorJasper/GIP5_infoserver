<?php
    require('../header.php');
// hieronder zet je PHP code

if ($_SERVER['REQUEST_METHOD'] == "GET"){
    require('../inc/config.php');
    require('../classes/class.smartschool.php');

    $ss = new Smartschool();

    $klas = $_GET['klas'];

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
</head>
<body>
    <div class="text-center">
        <h3><i class="fas fa-clipboard-list"></i>&nbsp;Klaslijst van <?php echo $klas; ?></h3>
    </div>
        <table class="table">
            <thead>
                <tr>
                <th>Name</th>
                <th>Internnr</th>
                <th>Status</th>
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
                            
                </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
      
    </div>
</div>
        <br>
        <!-- Custom scripts -->
        <script type="text/javascript">
            window.print();
        </script>
</body>
</html>
       