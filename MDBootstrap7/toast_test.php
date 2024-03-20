<?php
require('header.php');

$toast->set("fa-info-circle", "Klassenraad","", "Tabel is leeggemaakt","success");
$toast->set("fa-exclamation-triangle", "Klassenraad","test", "Tabel is leeggemaakt","danger");
$toast->set("fa-check", "Klassenraad","test", "Tabel is leeggemaakt","warning");
header("Location: index.php");