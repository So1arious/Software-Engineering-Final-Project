<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "e-commerce"; 

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed:" .mysqli_connect_error());
}

$sql = "INSERT INTO orders (id, customer_name, customer_email, shipping_address, order_details, subtotal, tax, total, order_date)
  VALUES (123456,'Roxas'.'Roxas@customer.com','12 street', 'Guitar (x1)', 299.99, 30.00, 329.99)";
  
if (mysqli_query($conn, $sql)) {
echo "New Purchase created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
?>
