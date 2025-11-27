<?php
$host = "localhost";
$user = "root";        // default username
$pass = "";            // default password
$dbname = "info3135";  // database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
