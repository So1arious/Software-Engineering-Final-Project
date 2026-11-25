<?php
session_start();
include('connect.php');

// Filter by category using $_GET (Lecture: PHP SQL Data Filtering)
if (isset($_GET['category']) && $_GET['category'] != "") {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $sql = "SELECT * FROM products WHERE category='$category'";
} else {
    $sql = "SELECT * FROM products";
}

$result = mysqli_query($conn, $sql);
?>

<?php
// Display products using mysqli_num_rows and mysqli_fetch_assoc
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        if (!empty($row['image_url'])) {
            echo "<img src='" . $row['image_url'] . "' alt='Instrument'>";
        }
        echo "<h3>" . $row["name"] . "</h3>";
        echo "<p>Category: " . $row["category"] . "</p>";
        echo "<p>" . $row["description"] . "</p>";
        echo "<p><strong>Price: $" . $row["price"] . "</strong></p>";
        echo "<p>In Stock: " . $row["stock_quantity"] . "</p>";

        if ($row["stock_quantity"] > 0) {
            echo "<form method='POST' action='cart.php'>";
            echo "<input type='hidden' name='product_id' value='" . $row["id"] . "'>";
            echo "<input type='submit' name='add' value='Add to Cart'>";
            echo "</form>";
        } else {
            echo "<p style='color:red;'>Out of Stock</p>";
        }
        echo "</div>";
    }
} else {
    echo "0 results";
}
mysqli_close($conn);
?>

</body>
</html>
