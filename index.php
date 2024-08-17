<?php
require("config.php");

// Fetch data for monthly invoices
$sqlMonthly = "SELECT DATE_FORMAT(INVOICE_DATE, '%Y-%m') AS month, COUNT(*) AS invoice_count, SUM(GRAND_TOTAL) AS total_sales FROM invoice GROUP BY month";
$resultMonthly = $conn->query($sqlMonthly);

// Fetch the highest billing amount and associated customer name
$sqlHighestBilling = "SELECT CNAME, INVOICE_DATE, MAX(GRAND_TOTAL) AS highest_billing FROM invoice GROUP BY CNAME, INVOICE_DATE ORDER BY highest_billing DESC LIMIT 1";
$resultHighestBilling = $conn->query($sqlHighestBilling);
$rowHighestBilling = $resultHighestBilling->fetch_assoc();
$highestBillingAmount = $rowHighestBilling['highest_billing'];
$customerName = $rowHighestBilling['CNAME'];
$billingdate = $rowHighestBilling['INVOICE_DATE'];

// Fetch and organize the data for Chart.js
$monthlyData = array();
$totalSalesData = array();

if ($resultMonthly->num_rows > 0) {
    while ($rowMonthly = $resultMonthly->fetch_assoc()) {
        $monthlyData[$rowMonthly['month']] = $rowMonthly['invoice_count'];
        $totalSalesData[$rowMonthly['month']] = $rowMonthly['total_sales'];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style2.css">
    <!-- Include custom CSS for styling -->
    <style>
        body {
            background-color: #f8f9fa; /* Set a background color */
            color: #343a40; /* Set font color to a dark color for better readability */
        }
        .chart-container {
            width: 30%; /* Adjust the width as needed */
            margin: 2%;
            color:#fff;
        }
        .chart-title{
            color:#fff;
            font-size:20px;
        }
        .billing-info {
            margin-left: 25%;
            width: 65%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="row">
        <?php include 'header.php' ?>
    </div>
    <div class="row">
        <div class='container chart-container' style="margin-left:25%;">
            <!-- Monthly Invoice Chart -->
            <h3 class="chart-title">Monthly Invoice Count</h3>
            <canvas id="monthlyInvoiceChart" class="chart"></canvas>
        </div>
        <div class='container chart-container'>
            <!-- Total Sales Chart -->
            <h3 class="chart-title">Total Sales Amount</h3>
            <canvas id="totalSalesChart" class="chart"></canvas>
        </div>
    </div>

    <!-- Display the highest billing amount -->
    <div class="container" style="margin-left:25%; width:65%;">
        <h4 class="chart-title">Highest Billing Amount: ₹<?php echo number_format($highestBillingAmount, 2); ?></h4>
        <h4 class="chart-title">Customer Name: <?php echo $customerName; ?></h4>
        <h4 class="chart-title">Billing Date: <?php echo $billingdate; ?></h4>
    </div>
    <!-- Include Bootstrap and jQuery JavaScript -->
    <script>
   // Monthly Invoice Chart
   var ctxMonthly = document.getElementById('monthlyInvoiceChart').getContext('2d');
   var monthlyChart = new Chart(ctxMonthly, {
      type: 'line',
      data: {
         labels: <?php echo json_encode(array_keys($monthlyData)); ?>,
         datasets: [{
            label: 'Invoice Count',
            data: <?php echo json_encode(array_values($monthlyData)); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
         }]
      }
   });

   // Total Sales Chart
   var ctxTotalSales = document.getElementById('totalSalesChart').getContext('2d');
   var totalSalesChart = new Chart(ctxTotalSales, {
      type: 'bar',
      data: {
         labels: <?php echo json_encode(array_keys($totalSalesData)); ?>,
         datasets: [{
            label: 'Total Sales',
            data: <?php echo json_encode(array_values($totalSalesData)); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            fontColor:'rgb(255, 255, 255)',
            borderWidth: 1
         }]
      }
   });
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
