<?php
require "config.php"
?>
<!DOCTYPE html>
<html>

<head>
    <title>Billing System Yesha Enterprises</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <link rel='stylesheet' href='https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css'>
    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js"
        integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
</head>

<body>
    <?php include 'header.php' ?>
    <div class='container pt-5'>
        <h1 class='text-center text-primary'>Yesha Enterprises Invoice System</h1>
        <hr>

        <?php
        require "config.php";

        // Check if the form is submitted
        if (isset($_POST["submit"])) {
            $invoice_no = mysqli_real_escape_string($conn, $_POST["invoice_no"]);

            // Check if the invoice number already exists
            $check_query = "SELECT * FROM invoice WHERE INVOICE_NO = '$invoice_no'";
            $result = $conn->query($check_query);

            if ($result->num_rows > 0) {
                echo "<div class='alert alert-danger'>Invoice number already exists. Please use a different invoice number.
             <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
            </div>";
            } else {
                $invoice_no = mysqli_real_escape_string($conn, $_POST["invoice_no"]);
                $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
                $cname = mysqli_real_escape_string($conn, $_POST["cname"]);
                $caddress = mysqli_real_escape_string($conn, $_POST["caddress"]);
                $ccity = mysqli_real_escape_string($conn, $_POST["ccity"]);
                $gstin = mysqli_real_escape_string($conn, $_POST["gstin"]);
                $grand_total = mysqli_real_escape_string($conn, $_POST["grand_total"]);

                // Insert invoice details into the database
                $insert_invoice_query = "INSERT INTO invoice (INVOICE_NO, INVOICE_DATE, CNAME, CAADDRESS, CCITY, gstIn, GRAND_TOTAL) VALUES ('$invoice_no', '$invoice_date', '$cname', '$caddress', '$ccity', '$gstin', '$grand_total')";

                if ($conn->query($insert_invoice_query)) {
                    $sid = $conn->insert_id;
                
                    $sql2 = "INSERT INTO invoice_products (SID, PNAME, PRICE, QTY, GSTPRICE, TOTAL) VALUES ";
                    $rows = [];
                    $gstPriceValues = $_POST["gstprice"]; // Retrieve the GSTPRICE values
                    $totalValues = $_POST["total"]; // Retrieve the GSTPRICE values
                
                    // Iterate through the product details sent from JavaScript
                    for ($i = 0; $i < count($_POST["pname"]); $i++) {
                        $pname = mysqli_real_escape_string($conn, $_POST["pname"][$i]);
                        $price = mysqli_real_escape_string($conn, $_POST["price"][$i]);
                        $qty = mysqli_real_escape_string($conn, $_POST["qty"][$i]);
                        $gstin_checked = isset($_POST["gstin"][$i]) ? 1 : 0;
                        $gstprice = mysqli_real_escape_string($conn, $gstPriceValues[$i]); // Retrieve the GSTPRICE value
                        $total = mysqli_real_escape_string($conn, $totalValues[$i]); // Retrieve the total value
                
                        $rows[] = "('$sid', '$pname', '$price', '$qty', '$gstprice', '$total')";
                    }
                    $sql2 .= implode(",", $rows);
                
                    if ($conn->query($sql2)) {
                        echo "<div class='alert alert-success'>Invoice Added. <a href='print.php?id={$sid}' target='_BLANK'>Click here to Print Invoice</a>
                             <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
                            </div>";
                    } else {
                        echo "<div class='alert alert-danger'>Invoice Products Added Failed.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Invoice Added Failed.</div>";
                }
                
            }
        }
        ?>


        <form method='post' action='index.php' autocomplete='off'>
            <div class='row'>
                <div class='col-md-4'>
                    <h5 class='text-success'>Invoice Details</h5>
                    <div class='form-group'>
                        <label>Invoice No</label>
                        <input type='text' name='invoice_no' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>Invoice Date</label>
                        <input type='text' name='invoice_date' id='date' required class='form-control'>
                    </div>
                </div>
                <div class='col-md-8'>
                    <h5 class='text-success'>Customer Details</h5>
                    <div class='form-group'>
                        <label>Name</label>
                        <input type='text' name='cname' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>Address</label>
                        <input type='text' name='caddress' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>City</label>
                        <input type='text' name='ccity' required class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>GSTIN</label>
                        <input type='text' name='gstin' required class='form-control'
                            onkeyup="this.value = this.value.toUpperCase();">
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-12'>
                    <h5 class='text-success'>Product Details</h5>
                    <table class='table table-bordered'>
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
                        <tbody id='product_tbody'>
                            <tr>
                                <td><input type='text' required name='pname[]' class='form-control'></td>
                                <td><input type='text' required name='price[]' class='form-control price'></td>
                                <td><input type='text' required name='qty[]' class='form-control qty'></td>
                                <td><input type='checkbox' class='form-check-input gstin-checkbox'> </td>
                                <td><input type='checkbox' class='form-check-input gstin-checkbox-less'> </td>
                                <td><input type='text' required name='gstprice[]' class='form-control gstprice'></td>
                                <td><input type='text' required name='total[]' class='form-control total'></td>
                                <td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'></td>
                            </tr>
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
                    <input type='submit' name='submit' value='Save Invoice' class='btn btn-success float-right'>
                </div>
            </div>
        </form>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Select Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Available Products:</label>
                            <div id="productList">
                                <!-- Product checkboxes will be dynamically added here -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addSelectedProducts">Add Selected</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function () {
            $('#duplicateAlert').alert('close');
        }, 5000);

        $(document).ready(function () {
            // Define a JavaScript array to store selected product details
            var selectedProducts = [];

            // Function to load available products into the modal
            function loadAvailableProducts() {
                $.ajax({
                    url: 'get_product.php', // Replace with the actual URL to retrieve products from the database
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Clear the product list
                        $('#productList').empty();

                        // Iterate through the products and create checkboxes
                        data.forEach(function (product) {
                            var checkbox = $('<div class="form-check"><input type="checkbox" class="form-check-input" value="' + product.PNAME + ':' + product.PRICE + '"><label class="form-check-label">' + product.PNAME + ' (₹' + product.PRICE + ')</label></div>');
                            $('#productList').append(checkbox);
                        });
                    },
                    error: function () {
                        alert('Error loading products.');
                    }
                });
            }

            // Open the product modal when the button is clicked
            $('#openProductModal').click(function () {
                loadAvailableProducts();
                $('#productModal').modal('show');
            });

            // Add selected products to the main form when the "Add Selected" button is clicked
            $('#addSelectedProducts').click(function () {
                // Clear any existing selections
                selectedProducts = [];

                // Iterate through selected checkboxes
                $('.form-check-input:checked').each(function () {
                    var productDetails = $(this).val().split(':');
                    var productName = productDetails[0];
                    var productPrice = parseFloat(productDetails[1]);

                    // Add selected product to the array
                    selectedProducts.push({
                        name: productName,
                        price: productPrice
                    });
                });

                // Update the pname and price fields in the main form
                updateMainFormFields();
                $('#productModal').modal('hide');
            });

            // Function to update the pname, price, gstin, gstprice, and total fields in the main form
            function updateMainFormFields() {
                // Clear the existing rows in the table
                $('#product_tbody').empty();
                // Iterate through selected products and add them to the main form
                selectedProducts.forEach(function (product) {
                    var newRow = '<tr>' +
                        '<td><input type="text" required name="pname[]" class="form-control" value="' + product.name + '"></td>' +
                        '<td><input type="text" required name="price[]" class="form-control price" value="' + product.price + '"></td>' +
                        '<td><input type="text" required name="qty[]" class="form-control qty"></td>' +
                        '<td><input type="checkbox" class="form-check-input gstin-checkbox"></td>' +
                        '<td><input type="checkbox" class="form-check-input gstin-checkbox-less"></td>' +
                        '<td><input type="text" required name="gstprice[]" class="form-control gstprice"></td>' +
                        '<td><input type="text" required name="total[]" class="form-control total"></td>' +
                        '<td><input type="button" value="x" class="btn btn-danger btn-sm btn-row-remove"></td>' +
                        '</tr>';

                    $('#product_tbody').append(newRow);
                });

                // Recalculate the grand total
                grand_total();
            }
            // Initialize datepicker
            $("#date").datepicker({
                dateFormat: "dd-mm-yy"
            });

            // Add row to the product table
            $("#btn-add-row").click(function () {
                var row = "<tr>";
                row += "<td><input type='text' required name='pname[]' class='form-control'></td>";
                row += "<td><input type='text' required name='price[]' class='form-control price'></td>";
                row += "<td><input type='text' required name='qty[]' class='form-control qty'></td>";
                row += "<td><input type='checkbox' name='gstin[]' class='form-check-input gstin-checkbox'></td>";
                row += "<td><input type='checkbox' class='form-check-input gstin-checkbox-less'></td>";
                row += "<td><input type='text' required name='gstprice[]' class='form-control gstprice'></td>";
                row += "<td><input type='text' required name='total[]' class='form-control total'></td>";
                row += "<td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'></td>";
                row += "</tr>";

                $("#product_tbody").append(row);
            });
            // Remove row from the product table
            $("body").on("click", ".btn-row-remove", function () {
                if (confirm("Are You Sure?")) {
                    $(this).closest("tr").remove();
                    grand_total();
                }
            });
            $("body").on("keyup", ".price, .qty", function () {
                var price = parseFloat($(this).closest("tr").find(".price").val()) || 0;
                var qty = parseFloat($(this).closest("tr").find(".qty").val()) || 0;
                var gstinChecked = $(this).closest("tr").find(".gstin-checkbox").prop("checked");

                // Calculate Total without GST (excluding GST)
                var totalWithoutGST = price - (price * (100 / (100 + (gstinChecked ? 18 : 0))));

                // Set the calculated values
                $(this).closest("tr").find(".total").val((price * qty - totalWithoutGST).toFixed(2));
                $(this).closest("tr").find(".gstprice").val((totalWithoutGST * qty).toFixed(2));
                grand_total();
            });
            $("body").on("change", ".gstin-checkbox", function () {
                var isChecked = $(this).prop("checked");
                var price = parseFloat($(this).closest("tr").find(".price").val()) || 0;
                var qty = parseFloat($(this).closest("tr").find(".qty").val()) || 0;

                // Calculate Total without GST (excluding GST) based on the checkbox state
                var gstRate = isChecked ? 18 : 0;
                var totalWithoutGST = price - (price * (100 / (100 + gstRate)));

                // Set the calculated values
                $(this).closest("tr").find(".total").val((price * qty + totalWithoutGST).toFixed(2));
                $(this).closest("tr").find(".gstprice").val((totalWithoutGST * qty).toFixed(2));
                grand_total();
            });
            $("body").on("change", ".gstin-checkbox-less", function () {
                var isChecked = $(this).prop("checked");
                var price = parseFloat($(this).closest("tr").find(".price").val()) || 0;
                var qty = parseFloat($(this).closest("tr").find(".qty").val()) || 0;

                // Calculate Total without GST (excluding GST) based on the checkbox state
                var gstRate = isChecked ? 18 : 0;
                var totalWithoutGST = price - (price * (100 / (100 + gstRate)));
                // Set the calculated values
                $(this).closest("tr").find(".gstprice").val((totalWithoutGST * qty).toFixed(2));
                grand_total();
            });
            function grand_total() {
                var tot = 0;
                $(".total").each(function () {
                    tot += Number($(this).val());
                });
                $("#grand_total").val(tot.toFixed(2));
            }
        });
    </script>
</body>

</html>
