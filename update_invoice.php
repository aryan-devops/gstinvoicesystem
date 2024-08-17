<?php
require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $invoiceId = mysqli_real_escape_string($conn, $_POST["invoice_id"]);
    $invoiceNo = mysqli_real_escape_string($conn, $_POST["invoice_no"]);
    $invoiceDate = mysqli_real_escape_string($conn, $_POST["invoice_date"]);
    $customerName = mysqli_real_escape_string($conn, $_POST["cname"]);
    $customerAddress = mysqli_real_escape_string($conn, $_POST["caddress"]);
    $customerCity = mysqli_real_escape_string($conn, $_POST["ccity"]);
    $customerGSTIN = mysqli_real_escape_string($conn, $_POST["gstin"]);
    $grandTotal = mysqli_real_escape_string($conn, $_POST["grand_total"]);

    $updateInvoiceQuery = "UPDATE invoice SET INVOICE_NO = '$invoiceNo', INVOICE_DATE = '$invoiceDate', CNAME = '$customerName', CAADDRESS = '$customerAddress', CCITY = '$customerCity', GSTIN = '$customerGSTIN', GRAND_TOTAL = '$grandTotal' WHERE SID = $invoiceId";

    if ($conn->query($updateInvoiceQuery)) {
        $updatedProductIds = $_POST["product_id"];
        $updatedProductNames = $_POST["pname"];
        $updatedPrices = $_POST["price"];
        $updatedQuantities = $_POST["qty"];
        $updatedGSTPrices = $_POST["gstprice"];
        $updatedTotals = $_POST["total"];

        for ($i = 0; $i < count($updatedProductIds); $i++) {
            $productId = mysqli_real_escape_string($conn, $updatedProductIds[$i]);
            $productName = mysqli_real_escape_string($conn, $updatedProductNames[$i]);
            $price = mysqli_real_escape_string($conn, $updatedPrices[$i]);
            $quantity = mysqli_real_escape_string($conn, $updatedQuantities[$i]);
            $gstPrice = mysqli_real_escape_string($conn, $updatedGSTPrices[$i]);
            $total = mysqli_real_escape_string($conn, $updatedTotals[$i]);

            $updateProductQuery = "UPDATE invoice_products SET PNAME = '$productName', PRICE = '$price', QTY = '$quantity', GSTPRICE = '$gstPrice', TOTAL = '$total' WHERE ID = $productId";

            if (!$conn->query($updateProductQuery)) {
                echo "Error updating product details.";
                exit();
            }
        }
        header("Location: edit.php?id=" . $invoiceId . "&success=1");
        exit();
    } else {
        echo "Error updating invoice details.";
        exit();
    }
}
?>
