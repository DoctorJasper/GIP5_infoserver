<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'nl_BE');
session_start();
session_unset();

include("path.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['A'])) {
        //Admin
        $_SESSION['foto'] = "https://mdbcdn.b-cdn.net/img/new/avatars/1.webp";
        $_SESSION["lastname"] = "Vanderkelen";
        $_SESSION["firstname"] = "Kyan";
        $_SESSION["internalnr"] = "115759";
        $_SESSION["email"] = "kyan.vanderkelen@leerling.go-ao.be";
        $_SESSION["admin"] = 1;
    } 
    else {
        //leerling
        $_SESSION['foto'] = "https://mdbcdn.b-cdn.net/img/new/avatars/4.webp";
        $_SESSION["lastname"] = "Janssens";
        $_SESSION["firstname"] = "Jan";
        $_SESSION["internalnr"] = "17302010339";
        $_SESSION["email"] = "jan.janssens@go-ao.be";
        $_SESSION["admin"] = 0;
        $_SESSION['klas'] = "6INFO";
    }
    header("Location: index.php");
    exit();
}
require('startHTML.php');
?>
</head>
<body>
<div class="container-fluid mt-5">
<div class="d-flex justify-content-center align-items-center">
    <div class="card mt-5">
        <div class="card-header bg-middelrood text-white text-center font-weight-bold">        
        <h3><i class="fa-solid fa-people-arrows"></i>&nbsp;GO-AO Dashboard<br>Impersonate</h3>
        </div>
        <div class="card-body text-center">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
                <button type="submit" class="btn btn-lg btn-primary" name="L">Leerling</button>
                <br><br>
                <button type="submit" class="btn btn-lg btn-danger" name="A">Admin</button>
            </form>
        </div>
    </div>
</div>
</div>
<br>
<?php
require('footer1.php');
require('footer2.php');
?>