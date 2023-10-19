<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: category.php");
    exit();
}

$category_id = $_GET['id'];
$sql = "SELECT name, description FROM categories WHERE id = $category_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: category.php");
    exit();
}

$category = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'];
    $newDescription = $_POST['description'];

    $updateSql = "UPDATE categories SET name = '$newName', description = '$newDescription' WHERE id = $category_id";
    
    if ($conn->query($updateSql) === TRUE) {
        header("Location: category.php");
        exit();
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
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
        textarea {
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
    <h1>Edit Category</h1>

    <form method="post" action="edit_category.php?id=<?php echo $category_id; ?>">
        <label for="name">Category Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $category['name']; ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo $category['description']; ?></textarea>

        <input type="submit" value="Update Category">
    </form>

    <?php if (isset($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <a href="category.php">Back to Category List</a>
</body>
</html>
