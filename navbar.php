    <style>
        .user-container {
            display: flex;
            align-items: center;
        }
        .user-name {
            font-size: 14px;
            margin-left: 10px;
            line-height: 30px;
        }
    </style>
</head>
<?php
//variabelen voor wie mag wat zien.
define('MR_admin', 1);
define('MR_directie', 2);
define('MR_LK', 4);
define('MR_fin', 8);
define('MR_LB', 16);
define('MR_OPV', 32);
define('MR_secr', 64);
define('MR_MVD', 128);
define('MR_roosteraars', 256);
define('MR_helpdesk', 512);
define('MR_bestelling', 1024);
define('MR_CLW_LK', 2048);
define('MR_CLW_OP', 4096);
define('VWG_LO', 8192);
define('Beheerders-WO', 16384);
define('MR_Ambtsbevoegdheden', 32768);
define('beheer_inschrijvingen', 65536);
?>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #e3f2fd;"
        data-mdb-theme="light">


        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-mdb-collapse-init
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->
                <a class="navbar-brand mt-2 mt-lg-0" href="<?php echo $path; ?>index.php">
                    <img src="https://www.go-atheneumoudenaarde.be/dashboard/img/go-ao/GO-AO_Logo_RGB_36x36.png"
                        alt="GO-AO logo" loading="lazy" />
                </a>

                <!-- Links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown position-static">
                        <a href="#" aria-expanded="false" id="navbarDropdownMenu" class="nav-link  dropdown-toggle"
                            data-mdb-dropdown-init role="button">
                            <i class="fa-solid fa-folder-open"></i> GIP menu
                        </a>

                        <div class="dropdown-menu w-100 mt-0" aria-labelledby="navbarDropdownMenu"
                            style="border-top-left-radius: 0; border-top-right-radius: 0;">
                            <div class="container">
                                <div class="row my-4">
                                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                                        <div class="list-group list-group-flush">
                                            <h6 class="mb-0 list-group-item text-uppercase font-weight-bold">
                                                Admin opties
                                            </h6>
                                            <a class="list-group-item list-group-item-action"
                                                href="<?php echo $path ?>GIP5/userOverview.php">
                                                <i class="fas fa-file-export ps-1 pe-3"></i>User overview
                                            </a>
                                            <a class="list-group-item list-group-item-action"
                                                href="<?php echo $path ?>GIP5/klasOverview.php">
                                                <i class="fas fa-users ps-1 pe-3"></i>Klassen
                                            </a>
                                            <a class="list-group-item list-group-item-action"
                                                href="<?php echo $path ?>GIP5/beheerCommandos.php">
                                                <i class="fas fa-wrench ps-1 pe-3"></i>Beheer commandos
                                            </a>
                                            <a class="list-group-item list-group-item-action"
                                                href="<?php echo $path ?>GIP5/log.php">
                                                <i class="fas fa-align-left ps-1 pe-3"></i>log file
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                                        <div class="list-group list-group-flush">
                                            <h6 class="mb-0 list-group-item text-uppercase font-weight-bold">
                                                User opties
                                            </h6>
                                            <a class="list-group-item list-group-item-action"
                                                href="<?php echo $path ?>GIP5/userpage.php">
                                                <i class="far fa-file ps-1 pe-3"></i>Userpage
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path ?>session.php">
                            <i class="fa-solid fa-list"></i> Session laten zien
                        </a>
                    </li>
                </ul>
            </div>


            <!-- Right element -->
            <div class="d-flex align-items-center">                
                <!-- Avatar -->  
                <?php 
                    $foto = $ss->ophalenfoto($_SESSION['internalnr']); 
                ?>
                <img
                    src="data:image/png;base64,<?php echo $foto; ?>" 
                    class="rounded-circle" 
                    height="30px" 
                    width="30px"
                    loading="lazy" 
                />
                <span class="user-name text-black">
                    <?php echo $_SESSION['voornaam'] . ' ' . $_SESSION['naam']; ?>
                </span>
            </div>
            <!-- Right element -->
        </div>
    </nav>
    <!-- Navbar -->