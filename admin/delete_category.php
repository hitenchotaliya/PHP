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
$sql = "SELECT name FROM categories WHERE id = $category_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: category.php");
    exit();
}

$category = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        
        $deleteSql = "DELETE FROM categories WHERE id = $category_id";
        if ($conn->query($deleteSql) === TRUE) {
            header("Location: category.php");
            exit();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        header("Location: category.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .confirmation {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .confirmation p {
            margin: 10px 0;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Delete Category</h1>

    <div class="confirmation">
        <p>Are you sure you want to delete the category "<?php echo $category['name']; ?>"?</p>
        <form method="post" action="delete_category.php?id=<?php echo $category_id; ?>">
            <input type="submit" name="confirm" value="yes" class="button">
            <input type="submit" name="confirm" value="no" class="button">
        </form>
    </div>

    <?php if (isset($error_message)) { ?>
        <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
    <?php } ?>

    <a href="category.php">Back to Category List</a>
</body>
</html>
