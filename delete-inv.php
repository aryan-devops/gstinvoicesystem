<?php
require("config.php");

// Select Invoice Details From Database
$sql = "SELECT * FROM invoice";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Invoices</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<div class="row">
    <?php include 'header.php' ?>
    <div class="col-0"></div>
    <div class='container col-8'>
        <div class="alert alert-success" id="deleteSuccess" style="display: none;">
            Invoice deleted successfully!
        </div>
        <h1>Available Invoices</h1>
        <table class="table table-bordered" style="color:#fff;">
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Invoice Date</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $row["INVOICE_NO"] . "</td>";
                        echo "<td>" . $row["CNAME"] . "</td>";
                        echo "<td>" . date("d-m-Y", strtotime($row["INVOICE_DATE"])) . "</td>";
                        echo "<td>₹" . $row["GRAND_TOTAL"] . "</td>";
                        echo '<td>
                        <a href="#" class="btn btn-danger delete-invoice" data-invoice="' . $row["SID"] . '"><i class="fa-solid fa-trash"></i></a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No invoices available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
 </div>
    <script src="https://kit.fontawesome.com/b21c5464c0.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-invoice").click(function(e) {
                e.preventDefault();
                var invoiceId = $(this).attr("data-invoice");
                if (confirm("Are you sure you want to delete this invoice?")) {
                    window.location.href = "delete.php?id=" + invoiceId;
                }
            });
        });
    </script>
</body>
</html>
