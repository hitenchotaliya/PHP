<?php
require_once('../config.php');

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = $userId";
$result = executeQuery($sql);

if ($result && $result->num_rows === 1) {
    $adminData = $result->fetch_assoc();
    $adminName = $adminData['username'];
} else {
    $adminName = "Admin";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .dashboard {
            background-color: #f4f4f4;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .menu {
            margin-top: 20px;
        }

        .menu a {
            display: block;
            padding: 10px 0;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .menu a:hover {
            background-color: #ddd;
        }

        .logout-button {
            text-align: center;
            margin-top: 20px;
        }

        .logout-button button {
            background-color: #ff3333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo $adminName; ?></h1>
    </div>

    <div class="container">
        <div class="dashboard">
            <h2>Admin Dashboard</h2>

            <div class="menu">
                <a href="category.php">Manage Categories</a>
                <a href="products.php">Manage Products</a>
            </div>

            <div class="logout-button">
                <form method="post" action="../logout.php">
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
