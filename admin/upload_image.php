<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../images/"; 
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $image_url = "images/" . basename($_FILES['image']['name']); 

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $insertSql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
            $stmt = $conn->prepare($insertSql);

            if ($stmt) {
                $stmt->bind_param("is", $product_id, $image_url);

                if ($stmt->execute()) {
                    header("Location: products.php");
                    exit();
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
            } else {
                $error_message = "Error preparing the SQL statement: " . $conn->error;
            }
        } else {
            $error_message = "Error uploading the image.";
        }
    } else {
        $error_message = "No image file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Upload Image</h1>

    <form method="post" enctype="multipart/form-data">
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" accept="image/*">
        <input type="submit" value="Upload Image">
    </form>

    <a href="products.php">Back to Product List</a>
</body>
</html>
