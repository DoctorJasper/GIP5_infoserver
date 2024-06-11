<?php    
    $date = new DateTime();
    $date->modify('+2 hours');
    $timestamp = $date->format('d-m-Y H:i:s');
?>