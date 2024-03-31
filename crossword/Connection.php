<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crossword_db";

$port = 3306; // Replace with the actual port number
$conn = new mysqli($servername, $username, $password, $dbname, $port);





if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

global $mysqli; // Make $mysqli global
?>