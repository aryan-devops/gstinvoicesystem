<?php
require("config.php");

// Check if the 'id' query parameter is set
if (isset($_GET["id"])) {
    $invoiceId = $_GET["id"];

    // Fetch the invoice details based on the invoiceId
    $sql = "SELECT * FROM invoice WHERE SID = $invoiceId";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $invoiceNo = $row["INVOICE_NO"];
        $invoiceDate = $row["INVOICE_DATE"];
        $customerName = $row["CNAME"];
        $customerCity = $row["CCITY"];
        $customergstin = $row["gstIn"];
        $customerAddress = $row["CAADDRESS"];
        // Populate other fields similarly
    } else {
        // Handle the case where no invoice with the provided ID is found
        echo "Invoice not found.";
        exit();
    }
} else {
    // Handle the case where 'id' is not provided or invalid
    echo "Invalid invoice ID.";
    exit();
}

// Fetch product information
$product_info = array();
$sql = "SELECT * FROM invoice_products WHERE SID='$invoiceId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_info[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <!-- Include Bootstrap CSS and other necessary styles/scripts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<div class="container">
    <h1>Edit Invoice</h1>
    <form method="post" action="update_invoice.php">
        <div class="row">
            <div class="col-md-4">
                <h5 class="text-success">Invoice Details</h5>
                <div class="form-group">
                    <label for="invoice-no">Invoice No</label>
                    <input type="text" class="form-control" id="invoice-no" name="invoice_no" value="<?php echo $invoiceNo; ?>" required>
                </div>
                <div class="form-group">
                    <label for="invoice-date">Invoice Date</label>
                    <input type="text" class="form-control" id="invoice-date" name="invoice_date" value="<?php echo $invoiceDate; ?>" required>
                </div>
            </div>
            <div class="col-md-8">
                <h5 class="text-success">Customer Details</h5>
                <div class="form-group">
                    <label for="customer-name">Customer Name</label>
                    <input type="text" class="form-control" id="customer-name" name="cname" value="<?php echo $customerName; ?>" required>
                </div>
                <div class="form-group">
                    <label for="customer-address">Customer Address</label>
                    <input type="text" class="form-control" id="customer-address" name="caddress" value="<?php echo $customerAddress; ?>" required>
                </div>
                <div class="form-group">
                    <label for="customer-city">Customer City</label>
                    <input type="text" class="form-control" id="customer-city" name="ccity" value="<?php echo $customerCity; ?>" required>
                </div>
                <div class="form-group">
                    <label for="customer-gstin">GSTIN</label>
                    <input type="text" class="form-control" id="customer-gstin" name="gstin" value="<?php echo $customergstin; ?>" required>
                </div>
            </div>
        </div>

        <!-- Product Information Section -->
        <div class="row">
    <div class="col-md-12">
        <h5 class="text-success">Product Information</h5>
        <table class="table table-bordered" style="color:white;" id="product_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>GSTNOTADDED</th>
                    <th>GSTADDED</th>
                    <th>GSTPRICE</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="product_tbody">
                <?php
                foreach ($product_info as $product) {
                    echo "<tr>";
                    echo "<td><input type='text' required name='pname[]' class='form-control' value='" . $product["PNAME"] . "'></td>";
                    echo "<td><input type='text' required name='price[]' class='form-control price' value='" . $product["PRICE"] . "'></td>";
                    echo "<td><input type='text' required name='qty[]' class='form-control qty' value='" . $product["QTY"] . "'></td>";
                    echo "<td><input type='checkbox' name='gstin[]' style='margin-left: 20px;' class='form-check-input gstin-checkbox' " . (isset($product["GSTIN"]) && $product["GSTIN"] == 1 ? "checked" : "") . "></td>";
                    echo "<td><input type='checkbox' style='margin-left: 20px;' class='form-check-input gstin-checkbox-less'></td>";
                    echo "<td><input type='text' required name='gstprice[]' class='form-control gstprice' value='" . $product["GSTPRICE"] . "'></td>";
                    echo "<td><input type='text' required name='total[]' class='form-control total' value='" . $product["TOTAL"] . "'></td>";
                    echo "<td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                            <tr>
                                <td><button type='button' class='btn btn-primary btn-sm' id='openProductModal'>Select
                                        Product</button></td>
                                <td><input type='button' value='+ Add Row' class='btn btn-primary btn-sm'
                                        id='btn-add-row'></td>
                                <td colspan='3' class='text-right'>Total</td>
                                <td><input type='text' name='grand_total' id='grand_total' class='form-control'
                                        required></td>
                            </tr>
                        </tfoot>
        </table>

        <button type="button" class="btn btn-success" id="btn-add-row">Add Row</button>
        <input type="hidden" name="invoice_id" value="<?php echo $invoiceId; ?>">
        <button type="submit" class="btn btn-primary">Update Invoice</button>
        <br>
        <br>
        <label for="grand_total">Grand Total:</label>
        <input type="text" id="grand_total" name="grand_total" class="form-control" readonly>
    </div>
</div>
    </form>
</div>
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel"><b>Select Products</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div>
                            <label style="color:black; font-size: 25px;"><b>Available Products:</b></label>
                            <div style="color:black;" id="productList">
                                <!-- Product checkboxes will be dynamically added here -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn" id="addSelectedProducts">Add Selected</button>
                </div>
            </div>
        </div>
    </div>
<script src="https://kit.fontawesome.com/b21c5464c0.js" crossorigin="anonymous"></script>

    <!-- Include Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
