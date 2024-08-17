<?php
require "config.php";

// Initialize alert variables
$alertMessage = "";
$alertType = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data from the form
    $productName = $_POST["pname"];
    $productPrice = $_POST["price"];

    // Validate form data (you can add more validation as needed)
    if (empty($productName) || empty($productPrice)) {
        $alertType = "danger";
        $alertMessage = "Please fill in all fields.";
    } else {
        // Use prepared statements to insert data into the database
        $sql = "INSERT INTO products (pname, price) VALUES (?, ?)";

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sd", $productName, $productPrice);

            // Execute the statement
            if ($stmt->execute()) {
                // Product added successfully
                $alertType = "success";
                $alertMessage = "Product added successfully!";
            } else {
                // Error in inserting data
                $alertType = "danger";
                $alertMessage = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Error in preparing the statement
            $alertType = "danger";
            $alertMessage = "Error in preparing statement: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product Page</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style2.css">
    <!-- Include custom CSS for styling -->
</head>
<body>
<div class="row">
    <?php include 'header.php' ?>
    <div class="col-0"></div>
    <div class='container col-4' style="margin-top:10%;">
        <div class="card">
            <div class="card-header">
                <h1>Add Product Page</h1>
            </div>
            <div class="card-body">
                <?php if (!empty($alertMessage)) : ?>
                    <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $alertMessage; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form method="post" action="add_product.php">
                    <div class="form-group">
                        <label for="product-name">Product Name</label>
                        <input type="text" class="form-control" id="product-name" name="pname" placeholder="Enter product name" required>
                    </div>
                    <div class="form-group">
                        <label for="product-price">Product Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="color:white; font-size:17px;  background-color: rgba(108, 91, 91, 0.539);">&#x20B9;</span>
                            </div>
                            <input type="number" class="form-control" id="product-price" name="price" placeholder="Enter product price" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Include Bootstrap and jQuery JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
