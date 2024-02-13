<?php
$host = "localhost";
$dbusername = "daniel";
$dbpassword = "Viaeunaur14^";
$dbname = "theperfectgift";

$conn = mysqli_connect($host, $dbusername, $dbpassword, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
