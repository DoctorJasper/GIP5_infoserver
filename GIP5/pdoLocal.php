<?php
    /*if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1) {
        header("Location: ../index.php");
        exit;   
    }*/
    
    /* Host name of the MySQL server. */
    $host = '83.217.67.87';
    /* MySQL account username. */
    $user = 'root';
    /* MySQL account password. */
    $passwd = 'gip_server';
    /* The default schema you want to use. */
    $dbname = 'gip';
    /* The PDO object. */
    $pdoLocal = NULL;
    /* Connection string, or "data source name". */
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname .';charset=utf8';
    // Set options
    $options = array(
        PDO::ATTR_PERSISTENT    => true,
        PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8",
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    );
    /* Connection inside a try/catch block. */
    try
    {  
        /* PDO object creation. */
        $pdoLocal = new PDO($dsn, $user, $passwd, $options);
    }
    catch (PDOException $e)
    {
        $toast->set("fa-exclamation-triangle", "Error","", "Database query error","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database query error".PHP_EOL, FILE_APPEND);
        header("Location: ../index.php");
        exit;
    }
?>.