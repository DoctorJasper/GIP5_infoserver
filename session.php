<!DOCTYPE html>
<?php
session_start();
?>
<html lang="nl_be">
    <head>
        <title>Session</title>
    </head>
    <body>
        <h1>Inhoud van $_SESSION</h1>
        <pre>
            <?php print_r($_SESSION); ?>
        </pre>
    </body>
</html>