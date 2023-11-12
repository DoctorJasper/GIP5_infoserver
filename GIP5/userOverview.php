<!DOCTYPE html>
<?php 
    require('startphp.php');

    if (!isset($_SESSION['username']) && $_SESSION["admin"] == 0) {
        //user is reeds aangemeld
        header("Location: login.php");
        exit;
    }
    require('pdo.php');

    $query = "SELECT `idGeb`,`GUID`,`userName`,`naam`,`voornaam`,`active`, `admin`, `email`
    FROM `tblGebruiker` 
    WHERE `active`= 1";//variabele (:username)

    try{
        $res = $pdo->prepare($query);
        $res->execute();
    }catch(PDOException $e){
        //error in de query
        echo 'Query error';
        die();
    }

    require('header.php');
    
    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-12">
                <a class="btn btn-outline-primary" role="button" href="adminpage.php">Terug</a>
                <p></p>
                <h3>Overzicht gerbuikers</h3>
                <table class="table table-hover table-striped">
            
               
                    <tr>
                        <th>Gebruikersnaam</th>
                        <th>Naam</th>
                        <th>Voornaam</th>
                        <th>email</th>
                        <th>admin</th>
                        <th>acties</th>
                    </tr>
                    <?php while($row = $res->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?php echo$row["userName"]; ?> </td>
                            <td><?php echo$row["naam"]; ?> </td>
                            <td><?php echo$row["voornaam"]; ?> </td>
                            <td><?php echo$row["email"]; ?> </td>
                            <td><?php echo$row["admin"] ? '<i class="bi-check-square-fill text-success"></i>':
                                                            '<i class="bi bi-square"></i>';?> </td>
                            <td>
                                <a href="userUpdate.php?id=<?php echo $row['idGeb']; ?>"><i class="bi bi-pencil-square text-warning"></i></a>
                                <i class="bi bi-x-square text-danger"></i>
                                <i class="bi bi-arrow-clockwise text-info"></i>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

    </div>
</div>       

