<!DOCTYPE html>
<?php
require("startphp.php");

$showAlert = false;

if (!isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    header("Location: login.php");
    exit;
} elseif (isset($_SESSION["username"]) && $_SESSION["admin"] != 1) {
    header("Location: About.php");
    exit();
}

$dir = "JSON/";
$files = scandir($dir);
$klasFiles = [];
$leerlingen = [];
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        $parts = explode(".", $file);
        $klasFiles[] = $parts[0];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["klas"])) {
    $klas = $_GET["klas"];
    $content = file_get_contents("JSON/".$klas.".json");
    $data = json_decode($content, true);

    foreach ($data as $record) {
        $leerling = [
            "naam" => $record["naam"],
            "voornaam" => $record["voornaam"],
            "internnummer" => $record["internnummer"]
        ];
        $leerlingen[] = $leerling;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php");
    $username = trim($_POST["username"]);
    $naam = trim($_POST["naam"]);
    $voornaam = trim($_POST["voornaam"]);
    $email = trim($_POST["email"]);
    $admin = isset($_POST["admin"]) ? 1 : 0;
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

    if (strlen($naam) >= 2 || strlen($voornaam) >= 2) {
        //create GUID
        $GUID = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        //Update query template
        $query = "INSERT INTO `tblGebruiker`(`GUID`,`userName`,`naam`,`voornaam`,`email`,`userPassword`,`admin`)
                VALUES (:ID, :userName, :naam, :voornaam, :email, :userPassword, :adm)";

        //Values array for PDO
        $values = [":ID" => $GUID, ":userName" => $username, ":naam" => $naam, ":voornaam" => $voornaam,
                ":email" => $email, ":userPassword" => $password, ":adm" => $admin];

        //Execute the query
        try {
            $res = $pdo->prepare($query);
            $res->execute($values);
            header("Location: adminpage.php");
            exit;
        } catch (PDOException $e) 
        {
            $TextAlert = "<strong> FOUT! </strong> de ingegeven informatie is te kort, mogelijks fout of al in gebruik.";
            $showAlert = true;
        }
    } else {
        $TextAlert = "<strong> FOUT! </strong> de ingegeven informatie is te kort of mogelijks fout.";
        $showAlert = true;
    }
}
require("header.php");
?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-4">
                <a class="btn btn-outline-primary" role="button" href="userOverview.php">Terug</a>
                <?php if ($showAlert) : ?>
                    <div class="alert alert-danger float-end">
                        <?php echo $TextAlert; ?>
                    </div>
                <?php endif; ?>
                <br><br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="mb-3">
                        <label for="Username" class="form-label">Gebruikersnaam</label>
                        <input type="text" class="form-control" id="Username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="Naam" class="form-label">Naam</label>
                        <input type="text" class="form-control" id="Naam" name="naam" required>
                    </div>
                    <div class="mb-3">
                        <label for="Voornaam" class="form-label">Voornaam</label>
                        <input type="text" class="form-control" id="Voornaam" name="voornaam" required>
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="Email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="Password" class="form-label">Wachtwoord</label>
                        <input type="password" class="form-control" id="Password" name="password" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" />
                        <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Gebruiker aanmaken</button>
                </form>
            </div>
            <div class="col-sm-1">   
            </div>    
            <div class="col-sm-7">
                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <select name="klas" class="form-select" aria-label="Default select example">
                        <option value="" selected disabled>Selecteer klas</option>
                        <?php foreach ($klasFiles as $klas) : ?>
                            <option value="<?php echo $klas; ?>"><?php echo $klas; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
                <?php foreach ($leerlingen as $leerling) : ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0 mt-0 fw-bold"><?php echo $leerling["naam"];?></p>
                            <p class="mb-0 mt-0"><?php echo $leerling["voornaam"];?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>