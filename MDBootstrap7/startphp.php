<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'nl_BE');
$jaartal = date('Y');

//opstarten van een sessie
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 5000)) {
    // last request was more than 30 minutes ago
    header('logout.php');
}

$_SESSION['LAST_ACTIVITY'] = time();
