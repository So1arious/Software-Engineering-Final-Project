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
<!DOCTYPE html>
<html>
<head>
    <title>Solfage Music Shop</title>
    <link rel="stylesheet" href="../Frontend/shop_style.css">
</head>
<body>

<div class="nav">
    <a href="shop.php">Shop</a>
    <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>
    <a href="admin.php">Admin Login</a>
</div>

<h1>Music Instrument Shop</h1>

<!-- Category Filter Form using $_GET -->
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Filter by Category:
    <select name="category">
        <option value="">All Instruments</option>
        <option value="Clarinet">Clarinet</option>
        <option value="Drums">Drums</option>
        <option value="Flutes">Flutes</option>
        <option value="Guitars">Guitars</option>
        <option value="Keyboards">Keyboards</option>
        <option value="Trumpts">Trumpts</option>
        <option value="Trombone">Trombone</option>
        <option value="Saxaphone">Saxaphone</option>
        <option value="Violin">Violin</option>
    </select>
    <input type="submit" value="Filter">
</form>
<br>

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



