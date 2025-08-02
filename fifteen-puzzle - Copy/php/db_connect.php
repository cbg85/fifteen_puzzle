<?php
$host = 'localhost';         // or 127.0.0.1
$dbname = 'yalinyoh1';
$username = 'yalinyoh1';
$password = 'yalinyoh1';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
