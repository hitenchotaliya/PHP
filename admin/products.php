<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT p.id, p.name, p.description, p.price, c.name AS category_name, i.image_url FROM products p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_images i ON p.id = i.product_id";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$imageSql = "SELECT id, image_url FROM product_images WHERE product_id = ?";
$imagesStmt = $conn->prepare($imageSql);

if ($imagesStmt) {
    $imagesStmt->bind_param("i", $product['id']);
    $imagesStmt->execute();
    $imagesResult = $imagesStmt->get_result();
    $images = [];

    while ($image = $imagesResult->fetch_assoc()) {
        $images[] = $image;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Products</title>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this product?");
        }


        var deleteLinks = document.querySelectorAll('.delete-link');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (!confirmDelete()) {
                    e.preventDefault();
                }
            });
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
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

        tr:hover {
            background-color: #e0e0e0;
        }

        a {
            text-decoration: none;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }

        .button:hover {
            background-color: #45a049;
        }

        .action-buttons {
            display: flex;
            justify-content: space-around;
        }

        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>

<body>
    <h1>Manage Products</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Images</th>
            <th>Actions</th>
        </tr>
        <?php
        $prevProductId = null;

        foreach ($products as $product) {
            if ($prevProductId !== $product['id']) {
                $prevProductId = $product['id'];

                echo '<tr>';
                echo '<td>' . $product['id'] . '</td>';
                echo '<td>' . $product['category_name'] . '</td>';
                echo '<td>' . $product['name'] . '</td>';
                echo '<td>' . $product['description'] . '</td>';
                echo '<td>$' . number_format($product['price'], 2) . '</td>';
                echo '<td class="image-container">';

                echo '<img class="product-image" src="../' . $product['image_url'] . '" alt="Product Image">';

                echo '<td class="action-buttons">';
                echo '<a href="edit_product.php?id=' . $product['id'] . '" class="button">Update</a>';
                echo '<a href="delete_product.php?id=' . $product['id'] . '" class="button delete-link">Delete</a>';

                echo '<a href="upload_image.php?id=' . $product['id'] . '" class="button">Upload Image</a>';

                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
    </table>



    <a href="add_product.php" class="button">Add New Product</a>
    <a href="index.php" class="button">Back</a>
</body>

</html>