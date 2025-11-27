<?php
 
 // Database confifentials
 $servername="localhost";
 $username= "root";
 $password= "";
 $database="golden_plate";

 // create connection to MySQL database
 $conn= new mysqli($servername, $username, $password, $database);

 //check if connection was successful
 if ($conn->connect_error) {
    die("Connection_failed". $conn->connect_error);
 }
 // set character set to UTF-8 to hadnle special character
 $conn->set_charset("utf8bm4");
?>