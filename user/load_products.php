<?php
require_once('../config.php');

if (isset($_GET['category_id']) && isset($_GET['page']) && isset($_GET['per_page'])) {
    $categoryId = $_GET['category_id'];
    $page = $_GET['page'];
    $perPage = $_GET['per_page'];

    $offset = ($page - 1) * $perPage;
    $sql = "SELECT p.name, pi.image_url, p.description, p.price
            FROM products AS p
            INNER JOIN product_images AS pi ON p.id = pi.product_id
            WHERE p.category_id = $categoryId
            LIMIT $perPage OFFSET $offset";

    $result = executeQuery($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            echo '<h2>' . $row['name'] . '</h2>';
            echo '<img src="../' . $row['image_url'] . '" alt="' . $row['name'] . '">';
            echo '<p>' . $row['description'] . '</p>';
            echo '<p>Price: $' . number_format($row['price'], 2) . '</p>';
            echo '</div>';
        }
    } else {
        echo 'No products found for this category.';
    }
}
?>
