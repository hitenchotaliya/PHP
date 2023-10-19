<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$sql = "SELECT id, name, description FROM categories";
$result = $conn->query($sql);

$categories = [];

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
    <title>Manage Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
        }

        .button {
            background-color: #4CAF50;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Manage Categories</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($categories as $category) { ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo $category['name']; ?></td>
                <td><?php echo $category['description']; ?></td>
                <td>
                    <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="button">Update</a>
                    <a href="delete_category.php?id=<?php echo $category['id']; ?>" class="button">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div style="margin-top: 20px;">
        <a href="add_category.php" class="button">Add New Category</a>
    </div>

    <div style="margin-top: 20px;">
        <a href="index.php" class="button">Back</a>
    </div>

    <form method="post" action="../logout.php">
        <input type="submit" value="Logout" style="margin-top: 20px;">
    </form>
</body>
</html>
