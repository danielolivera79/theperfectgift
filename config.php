<?php
$host = "localhost";
$dbusername = "";
$dbpassword = "";
$dbname = "theperfectgift";

$conn = mysqli_connect($host, $dbusername, $dbpassword, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
