<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$categories = [];
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $updateSql = "UPDATE products SET category_id = ?, name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);

    if ($stmt) {
        $stmt->bind_param("isssi", $category_id, $name, $description, $price, $product_id);

        if ($stmt->execute()) {
            header("Location: products.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "Error preparing the SQL statement: " . $conn->error;
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT p.id, p.name, p.description, p.price, p.category_id, c.name AS category_name FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    } else {
        header("Location: products.php");
        exit();
    }
} else {
    header("Location: products.php");
    exit();
}

$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
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

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
    <h1>Update Product</h1>

    <form method="post">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $product['category_id']) echo "selected"; ?>><?php echo $category['name']; ?></option>
            <?php } ?>
        </select>

        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required value="<?php echo $product['name']; ?>">

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo $product['description']; ?></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required step="0.01" value="<?php echo $product['price']; ?>">

        <input type="submit" value="Update Product">
    </form>

    <a href="products.php">Back to Product List</a>
</body>
</html>
