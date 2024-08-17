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
    <style>
        #qrModalBody {
            text-align: center;
        }

        #payment-qr-img {
            max-width: 100%;
            height: auto;
        }

        .share-button {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="row">
        <?php include 'header.php' ?>
        <div class="col-0"></div>
        <div class='container col-8'>
            <div class="alert alert-success" id="deleteSuccess" style="display: none;">
                Invoice deleted successfully!
            </div>
            <h1 style="color:#fff;">Available Invoices</h1>
            <table class="table table-bordered" style="color:#fff;">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Invoice Date</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                        <th>Payment QR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["INVOICE_NO"] . "</td>";
                            echo "<td>" . $row["CNAME"] . "</td>";
                            echo "<td>" . date("d-m-Y", strtotime($row["INVOICE_DATE"])) . "</td>";
                            echo "<td>₹" . $row["GRAND_TOTAL"] . "</td>";
                            // Hidden input for total amount
                            echo '<td id="total-amount-' . $row["SID"] . '" style="display: none;">' . $row["GRAND_TOTAL"] . '</td>';
                            echo '<td><a href="print.php?id=' . $row["SID"] . '" class="btn btn-success"><i class="fa-regular fa-eye"></i></a></td>';
                            echo '<td><button class="btn btn-primary generate-qr" data-toggle="modal" data-target="#qrModal" data-invoice="' . $row["SID"] . '">Generate QR <img width="28" height="28" src="https://img.icons8.com/fluency-systems-regular/48/qr-code--v2.png" alt="qr-code--v2"/></button></td>';
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

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">Payment QR Code </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="qrModalBody">
                    <img id="payment-qr-img" src="" alt="Payment QR Code">
                    <div class="mt-3">
                        <button class="btn btn-success share-button" onclick="shareViaWhatsApp()">Share via WhatsApp <img width="28" height="28" src="https://img.icons8.com/color/48/whatsapp--v1.png" alt="whatsapp--v1"/></button>
                        <button class="btn btn-danger share-button" onclick="shareViaGmail()">Share via Gmail<img width="28" height="28" src="https://img.icons8.com/fluency/48/mail--v1.png" alt="mail--v1"/></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/b21c5464c0.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include qrious library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".generate-qr").click(function (e) {
                e.preventDefault();
                var invoiceId = $(this).data("invoice");
                // You need to fetch the total amount from the respective row
                var totalAmount = parseFloat($("#total-amount-" + invoiceId).text().replace('₹', ''));
                console.log(totalAmount);
                // Create a QRious instance
                var qr = new QRious({
                    element: document.getElementById('payment-qr-img'),
                    value: `upi://pay?pa=9977228924@kotak811&mc=INR&tid=1234567890123456&tr=1234567890&tn=Payment%20Request&am=${totalAmount}&cu=INR`,
                    size: 200
                });

                // Show the modal
                $('#qrModal').modal('show');
            });
        });

        function shareViaWhatsApp() {
            // Implement the logic to share the QR code via WhatsApp
            // Get the QR code image URL
            var qrCodeImageUrl = $('#payment-qr-img').attr('src');

            // Encode the message
            var message = encodeURIComponent('Check out my payment QR code: ' + qrCodeImageUrl);

            // Open WhatsApp with pre-filled message
            window.open('https://wa.me/9977228924/?text=' + message);

        }

        function shareViaGmail() {
            // Implement the logic to share the QR code via Gmail
            alert('Sharing via Gmail');
        }
    </script>
</body>

</html>
