<?php
session_start();
include('connect.php');

// Initialize cart array in $_SESSION
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['add'])) {
    $id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Process Checkout
$order_message = "";
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $shipping_address = $_POST['shipping_address'];
    $subtotal = $_POST['subtotal'];
    $tax = $_POST['tax'];
    $total = $_POST['total'];
    $order_details = $_POST['order_details'];

    // Insert order into database (Lecture: Add Data to a Database Table)
    $sql = "INSERT INTO orders (customer_name, customer_email, shipping_address, order_details, subtotal, tax, total)
            VALUES ('$customer_name', '$customer_email', '$shipping_address', '$order_details', $subtotal, $tax, $total)";

    if ($conn->query($sql) === TRUE) {
        // Deduct stock for each item purchased
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $update_sql = "UPDATE products SET stock_quantity = stock_quantity - $qty WHERE id = $pid";
            $conn->query($update_sql);
        }
        // Clear cart session
        unset($_SESSION['cart']);
        $order_message = "Order placed successfully! Thank you, $customer_name.";
    } else {
        $order_message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 60%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>

<a href="http://localhost/SE-Final-Project/Backend/shop.php">&larr; Back to Shop</a>
<h1>Your Shopping Cart</h1>

<?php if ($order_message != "") { echo "<p style='color:green; font-weight:bold;'>$order_message</p>"; } ?>

<?php
if (!empty($_SESSION['cart'])) {
    $subtotal = 0;
    $details_summary = "";

    echo "<table>";
    echo "<tr><th>Instrument</th><th>Price</th><th>Quantity</th><th>Line Total</th><th>Action</th></tr>";

    foreach ($_SESSION['cart'] as $id => $qty) {
        $sql = "SELECT * FROM products WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $line_total = $row['price'] * $qty;
            $subtotal += $line_total;
            $details_summary .= $row['name'] . " (x" . $qty . "), ";

            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>$" . $row['price'] . "</td>";
            echo "<td>" . $qty . "</td>";
            echo "<td>$" . number_format($line_total, 2) . "</td>";
            echo "<td><a href='cart.php?remove=$id'>Remove</a></td>";
            echo "</tr>";
        }
    }

    $tax = $subtotal * 0.07; // 7% sales tax
    $total = $subtotal + $tax;

    echo "</table>";
    echo "<p>Subtotal: $" . number_format($subtotal, 2) . "</p>";
    echo "<p>Tax (7%): $" . number_format($tax, 2) . "</p>";
    echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
?>

    <h2>Checkout</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Full Name:<br>
        <input type="text" name="customer_name" required><br><br>
        Email:<br>
        <input type="email" name="customer_email" required><br><br>
        Shipping Address:<br>
        <textarea name="shipping_address" required></textarea><br><br>

        <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
        <input type="hidden" name="tax" value="<?php echo $tax; ?>">
        <input type="hidden" name="total" value="<?php echo $total; ?>">
        <input type="hidden" name="order_details" value="<?php echo $details_summary; ?>">

        <input type="submit" name="checkout" value="Place Order">
    </form>

<?php
} else {
    echo "<p>Your cart is empty.</p>";
}
$conn->close();
?>

</body>
</html>
