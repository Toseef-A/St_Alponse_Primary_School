<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "st_alphonse_primary_school";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>