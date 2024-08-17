<?php
// Database connection - Replace with your actual database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'invoice_db';

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve product data
$query = "SELECT PNAME, PRICE FROM products"; // Replace 'products' with your actual table name

$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

// Create an array to store product data
$productData = array();

// Fetch and format the data
while ($row = mysqli_fetch_assoc($result)) {
    $productData[] = $row;
}

// Close the database connection
mysqli_close($con);

// Return the product data as JSON
header('Content-Type: application/json');
echo json_encode($productData);
?>
