<?php
    // define the table name
$table_name = 'students';

// check the request method
$request_method = $_SERVER['REQUEST_METHOD'];

// include the database connection file
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my";

// create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

switch($request_method) {
    case 'GET':
        // call the getStudents function
        getStudents($table_name);
        break;
    case 'POST':
        // call the insertStudent function
        insertStudent($table_name);
        break;
    case 'PUT':
        // call the updateStudent function
        updateStudent($table_name);
        break;
    case 'DELETE':
        // call the deleteStudent function
        deleteStudent($table_name);
        break;
    default:
        // invalid request method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

// function to get all students
function getStudents($table_name) {
    global $conn;
    $query = "SELECT * FROM " . $table_name;
    $result = mysqli_query($conn, $query);
    $students = array();
    while($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($students);
}

// function to insert a new student
function insertStudent($table_name) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $query = "INSERT INTO " . $table_name . " (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if(mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'message' => 'Student added successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Error adding student.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// function to update a student
function updateStudent($table_name) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $query = "UPDATE " . $table_name . " SET name = '$name', email = '$email', phone = '$phone' WHERE id = $id";
    if(mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'message' => 'Student updated successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Error updating student.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// function to delete a student
function deleteStudent($table_name) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $query = "DELETE FROM " . $table_name . " WHERE id = $id";
    if(mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'message' => 'Student deleted successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'message' => 'Error deleting student.'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
   


?>