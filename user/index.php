<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = $userId";
$result = executeQuery($sql);

if ($result && $result->num_rows === 1) {
    $userData = $result->fetch_assoc();
    $userNAme = $userData['username'];
} else {
    $userNAme = "User";
}


// if ($_SESSION['role'] === 'user') {
//     echo "Hello from user ";
// }
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            margin-top: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }

        .logout-button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <header>
        <h1>Welcome, <?php echo $userNAme; ?></h1>
    </header>
    <div class="container">
        <p>Categories:</p>
        <ul>
            <?php
            $sql = "SELECT * FROM categories";
            $result = executeQuery($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // echo '<li><a href="#">' . $row['name'] . '</a></li>';
                    echo '<li><a class="category-link" href="#" data-category-id="' . $row['id'] . '">' . $row['name'] . '</a></li>';
                }
            } else {
                echo '<li>No categories found</li>';
            }
            ?>
        </ul>
    
        <div class="product-container">
            <script>
                $(document).ready(function() {
                    var currentPage = 1;
                    var productsPerPage = 1;
                    function loadProducts(page) {
                        var categoryId = $(".category-link.active").data("category-id");
                        $.ajax({
                            url: "load_products.php",
                            type: "GET",
                            data: {
                                category_id: categoryId,
                                page: page,
                                per_page: productsPerPage
                            },
                            success: function(data) {
                                $(".product-container").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error("Error loading products:", error);
                            }
                        });
                    }
                    $(".category-link").click(function(e) {
                        e.preventDefault();
                        $(".category-link").removeClass("active");
                        $(this).addClass("active");
                        currentPage = 1;
                        loadProducts(currentPage);
                    });
                    $(".next-page").click(function() {
                        currentPage++;
                        loadProducts(currentPage);
                    });
                    $(".prev-page").click(function() {
                        if (currentPage > 1) {
                            currentPage--;
                            loadProducts(currentPage);
                        }
                    });
                });
            </script>
        </div>

        <div class="pagination">
            <button class="prev-page">Previous Page</button>
            <button class="next-page">Next Page</button>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
               
                $(".category-link").click(function(e) {
                    e.preventDefault();
                    var categoryId = $(this).data("category-id");
                    $.ajax({
                        url: "load_products.php",
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(data) {
                            $(".product-container").html(data);
                        }
                    });
                });
            });
        </script>

        <form method="post" action="../logout.php">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>
</body>

</html>