<?php
include('connect.php');

$sql = "INSERT INTO orders (customer_name, customer_email, shipping_address, order_details, subtotal, tax, total)
        VALUES ('Roxas', 'Roxas@customer.com', '12 street', 'Stratocaster Electric Guitar (x1)', 799.99, 56.00, 855.99);";

$sql .= "INSERT INTO orders (customer_name, customer_email, shipping_address, order_details, subtotal, tax, total)
         VALUES ('Sora', 'sora@customer.com', '45 Destiny Ave', 'Dreadnought Acoustic Guitar (x1)', 329.00, 23.03, 352.03);";

if (mysqli_multi_query($conn, $sql)) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
