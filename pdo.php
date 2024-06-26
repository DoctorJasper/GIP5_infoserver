<?php
    /*if (!isset($_SESSION["internalnr"]) || $_SESSION["internalnr"] != 1) {
        header("Location: ../index.php");
        exit;   
    }*/
    /* Host name of the MySQL server. */
    $host = 'kyan.go-ao.be';
    /* MySQL account username. */
    $user = '06InfoKyan';
    /* MySQL account password. */
    $passwd = 'ky@nideStud10';
    /* The default schema you want to use. */
    $dbname = 'GIP5_server';
    /* The PDO object. */
    $pdo = NULL;
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
        $pdo = new PDO($dsn, $user, $passwd, $options);
    }
    catch (PDOException $e)
    {
        $toast->set("fa-exclamation-triangle", "Error","", "Database connection failed","danger");
        file_put_contents("log.txt", date("Y-m-d H:i:s")." || Database connection failed".PHP_EOL, FILE_APPEND);
        header("Location: ../index.php");
        exit;
    }
?>