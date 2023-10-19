<?php
$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$database = "meesho"; 


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function executeQuery($sql) {
    global $conn;
    $result = $conn->query($sql);
    return $result;
}

?>