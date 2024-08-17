<?php
require("config.php");

// Check if the invoice ID is provided in the URL
if (isset($_GET["id"])) {
    $invoiceId = $_GET["id"];
    
    // Perform the delete operation
    $sql = "DELETE FROM invoice WHERE SID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoiceId);
    
    if ($stmt->execute()) {
        // Deletion was successful
        header("Location: delete-inv.php?success=1"); // Redirect to invoices.php with success parameter
        exit();
    } else {
        // Deletion failed
        header("Location: delete-inv.php?error=1"); // Redirect to invoices.php with error parameter
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Invoice ID is not provided in the URL
    header("Location: delete-inv.php"); // Redirect to invoices.php if no ID is provided
    exit();
}
?>
