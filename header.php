<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'nl_BE');
session_start();

include("path.php");

//indien nog niemand is aangemeld ga naar impersonte
if (!isset($_SESSION["internalnr"])) {
    header("Location: $path/GIP5/login.php");
    exit();
}

require_once 'classes/class.toastr.php';
$toast = new toast();

