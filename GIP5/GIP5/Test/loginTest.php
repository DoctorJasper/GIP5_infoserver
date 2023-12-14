<?php
require("startphp.php");  

$showAlert = false;
$showIntro = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("pdo.php");
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $query = "SELECT `GUID`,`userName`,`userPassword`,`passwordReset`,`active`,`admin` 
              FROM `tblGebruiker` 
              WHERE `userName` = :userName";
    $values = [":userName" => $username];
    try {
        $res = $pdo->prepare($query);
        $res->execute($values);
    } catch (PDOException $e) {
        echo "Query error<br>".$e;
        die();
    }
    $row = $res->fetch(PDO::FETCH_ASSOC);

    if ($row["active"] == true) {
        if ($username == $row["userName"] && password_verify($password, $row["userPassword"])) {
            $_SESSION["username"] = $username;
            $_SESSION['CREATED'] = time();
            $_SESSION['GUID'] = $row["GUID"];
            $_SESSION["admin"] = $row["admin"];
            $showIntro = true;
            if ($_SESSION["admin"] == 0) {
                header("Location: userpage.php?GUID=".$_SESSION["GUID"]);
                die();
            } else {
                header("Location: adminpage.php");
                die();
            }
        } else {
            $showAlert = true;
        }
    } else {
        $showAlert = true;
    }
}
require("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <?php if ($showIntro): ?>
        <style>
            body {
                margin: 0;
                overflow: hidden;
                background-color: #f2e6ff;
            }

            #intro {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #000000;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Arial', sans-serif;
                font-size: 100px;
                z-index: 1010;
            }

            .sliding-bar {
                position: absolute;
                width: 0;
                height: 200px;
                background: linear-gradient(to right, #000000, #1a3b9e, #000000);
                text-shadow: 20px;
                animation: slideBar 1s ease-in-out forwards;
            }

            .white-light {
                position: relative;
                width: 100%;
                height: 200px;
                background-color: white;
                opacity: 0;
                animation: fadeIn 4s ease-in-out forwards;
            }

            @keyframes slideBar {
                0% {
                    width: 0;
                }
                100% {
                    width: 100%;
                }
            }

            @keyframes fadeIn {
                0% {
                    opacity: 0;
                }
                100% {
                    opacity: 1;
                }
            }

            .intro-text {
                position: absolute;
                opacity: 0;
                animation: fadeIn 0.75s ease-in-out forwards;
            }
            #main { display:none;}
        </style>
    <?php endif; ?>
</head>
<body>
    <?php if ($showIntro): ?>
        <div id="intro">
            <div class="white-light"></div>
            <div class="sliding-bar"></div>
            <div class="intro-text">InfoServer</div>  
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">          
                        Log in
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="Password">Password:</label>
                                <input type="password" class="form-control" id="Password" name="password" required>   
                                &nbsp;&nbsp;
                                <img src="Images/show.png" alt="eye" style="width: 20px;" id="oogje">           
                            </div>
                            <a class="nav-link text-primary" href="wachtwoordVergeten.php">wachtwoord vergeten</a>
                            <br>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
         let teller = 0;
    let oogje = document.querySelector("#oogje");
    let introContainer = document.querySelector('#intro');

    oogje.addEventListener("click", wwToon);
    function wwToon() {
        teller++;
        if (teller % 2 == 1) {
            document.getElementById("Password").type = "text";
            this.src = "Images/hide.png";
        } else {
            document.getElementById("Password").type = "password";
            this.src = "Images/show.png";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            setTimeout(function() {
                introContainer.style.display = 'none';
                window.location.href = 'adminpage.php';
            }, 2000);
        });
    });
    </script>
</body>
</html>
