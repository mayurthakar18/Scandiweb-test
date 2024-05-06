<?php
$host = "localhost";
$username = "mayurthakar";
$password = "mayurthakar";
$dbname = "scandiwebtest";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

