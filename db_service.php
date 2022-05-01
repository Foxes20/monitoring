<?php
$servername = "Localhost";
$username   = "service_dev_user";
$password   = "aY2dS7yU7y";
$dbname     = "service_dev";

$connect = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connect , "utf8");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}






 // mysqli_close($connect);