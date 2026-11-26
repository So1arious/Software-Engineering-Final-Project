<?php
session_start();
include('connect.php');

// Hardcoded Admin Credentials (Lecture: Login Page Example)
$admin_user = "admin";
$admin_pass = "music123";

// Handle Logout (Lecture: Destroying a Session)
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location: admin.php");
    exit();
}

// Handle Login
$login_error = "";
if (isset($_POST['login'])) {
    if ($_POST['username'] == $admin_user && $_POST['password'] == $admin_pass) {
        $_SESSION['loggedin'] = true;
    } else {
        $login_error = "Username or password is incorrect";
    }
}

// Handle Add Instrument (Lecture: INSERT INTO)
$msg = "";
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock_quantity'];
        $image_url = $_POST['image_url'];

        $sql = "INSERT INTO products (name, category, description, price, stock_quantity, image_url)
                VALUES ('$name', '$category', '$description', $price, $stock, '$image_url')";

        if ($conn->query($sql) === TRUE) {
            $msg = "New instrument added successfully!";
        } else {
            $msg = "Error: " . $conn->error;
        }
    }

    // Handle Delete Instrument (Lecture: DELETE FROM ... WHERE)
    if (isset($_GET['delete'])) {
        $del_id = $_GET['delete'];
        $sql = "DELETE FROM products WHERE id=$del_id";
        if ($conn->query($sql) === TRUE) {
            $msg = "Instrument deleted successfully!";
        } else {
            $msg = "Error deleting record: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Music Shop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>

<a href="http://localhost/SE-Final-Project/Backend/shop.php">&larr; Back to Shop</a>

<?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false): ?>

    <h2>Admin Login</h2>
    <?php if ($login_error != "") echo "<p style='color:red;'>$login_error</p>"; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username:<br>
        <input type="text" name="username" required><br><br>
        Password:<br>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>

<?php else: ?>

    <h1>Admin Inventory Management</h1>
    <p><a href="admin.php?logout=true">Logout</a></p>
    <?php if ($msg != "") echo "<p style='color:green; font-weight:bold;'>$msg</p>"; ?>

    <h3>Add New Instrument</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Name: <input type="text" name="name" required><br><br>
        Category:
        <select name="category">
            <option value="">All Instruments</option>
            <option value="Clarinet">Clarinet</option>
            <option value="Drums">Drums</option>
            <option value="Flutes">Flutes</option>
            <option value="Guitars">Guitars</option>
            <option value="Keyboards">Keyboards</option>
            <option value="Trumpts">Trumpts</option>
            <option value="Trombone">Trombone</option>
            <option value="Saxaphone">Saxophone</option>
            <option value="Violin">Violin</option>
        </select><br><br>
        Description:<br>
        <textarea name="description" required></textarea><br><br>
        Price: <input type="number" step="0.01" name="price" required><br><br>
        Stock Quantity: <input type="number" name="stock_quantity" required><br><br>
        Image URL (optional): <input type="text" name="image_url"><br><br>
        <input type="submit" name="add_product" value="Add Instrument">
    </form>

    <h3>Current Inventory</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["category"] . "</td>";
                echo "<td>$" . $row["price"] . "</td>";
                echo "<td>" . $row["stock_quantity"] . "</td>";
                echo "<td><a href='admin.php?delete=" . $row["id"] . "'>Delete</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

<?php endif; $conn->close(); ?>

</body>
</html>
