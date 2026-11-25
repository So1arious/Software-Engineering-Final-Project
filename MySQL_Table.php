<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "e-commerce";

	// Create connection

	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection

	if ($conn->connect_error) {
		die("Connection failed: ". $conn->connect_error);
	}
	
	// sql to create table (For project)

	$sql = "CREATE TABLE orders (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		customer_name VARCHAR(50) NOT NULL,
		customer_email VARCHAR(50) NOT NULL,
		shipping_address TEXT NOT NULL,
    	order_details TEXT NOT NULL,
		subtotal DECIMAL(10,2) NOT NULL,
		tax DECIMAL(10,2) NOT NULL,
		total DECIMAL(10,2) NOT NULL,
		order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP

	)";	

	if ($conn->query($sql) === TRUE) {
		echo "Table myStudents created successfully";
	} else {
		echo "Error creating table: ". $conn->error;
	}
	$conn->close();
?>
