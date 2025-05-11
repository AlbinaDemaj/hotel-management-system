<?php

$host = 'localhost';
$db_user = 'root';
$db_pass = ''; 
$db_name = 'hotel_management';
$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Gabim gjatë lidhjes: " . mysqli_connect_error());
}
