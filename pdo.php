<?php
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
        echo "Database connection OK";
    }
    catch (PDOException $e)
    {
    /* If there is an error, an exception is thrown. */
    echo 'Database connection failed.';
    var_dump($e);
    die();
    }
?>