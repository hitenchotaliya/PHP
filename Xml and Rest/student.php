<?php 

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);

$xml = new DOMDocument("1.0", "UTF-8");
$xml->formatOutput = true;

$root = $xml->createElement("students");
$xml->appendChild($root);

while ($row = $result->fetch_assoc()) {
    $student = $xml->createElement("student");
    $root->appendChild($student);

    $id = $xml->createElement("id", $row["id"]);
    $student->appendChild($id);

    $name = $xml->createElement("name", $row["name"]);
    $student->appendChild($name);

    $age = $xml->createElement("age", $row["age"]);
    $student->appendChild($age);

    $roll_number = $xml->createElement("roll_number", $row["roll_number"]);
    $student->appendChild($roll_number);
}

$xml->save("students.xml");

$conn->close();
?>