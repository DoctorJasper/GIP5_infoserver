<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'nl_BE');
session_start();

$path = "//gip/MDBootstrap7/";

//indien nog niemand is aangemeld ga naar impersonte
if (!isset($_SESSION["internalnr"])) {
    header("Location: impersonate.php");
    exit();
}

require_once 'classes/class.toastr.php';
$toast = new toast();

