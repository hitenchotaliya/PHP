<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    $deleteSql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            header("Location: products.php");
            exit();
        }
    }
}

header("Location: products.php");
?>
