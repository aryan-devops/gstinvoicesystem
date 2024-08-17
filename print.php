<?php
require("fpdf/fpdf.php");
require("word.php");
require("config.php");

function createPDF($info, $products_info, $contains22) {
    class PDF extends FPDF
    {
        private $info;
        private $contains22;
        private $isLastPage = false;

        function __construct($info, $contains22)
        {
            parent::__construct();
            $this->info = $info;
            $this->contains22 = $contains22;
        }

        function Header()
        {
            // Logo and company information
            // $this->Image('image.png', 10, 10, 30);
            $this->SetFont('Arial', 'B', 17);
            $this->Cell(-1);
            $this->Cell(0, 12, "Yesha Enterprises", 0, 1, 'L');
            // Address and contact information
            $this->SetFont('Arial', '', 10);
            $this->Cell(-1);
            $this->Cell(0, 8, "71 Govind Sarang Vyavasiyak Parisar, New Rajendra Nagar,Raipur,Chhatisghar ZIP - 492001.", 0, 1, 'L');
            $this->Cell(-1);
            $this->Cell(0, 8, "PH : 9770403382, 9977228924, Email Id: yeshaenterprises01@gmail.com", 0, 1, 'L');
            $this->Cell(-1);
            $this->Cell(0, 8, "GSTIN: 22GEIPP1287J1Z6 , State: 22-Chhattisgarh", 0, 1, 'L');
        }

        function Footer()
        {
            // Check if this is the last page
            if ($this->isLastPage) {
                // Bank information
                $this->SetY(-39);
                $this->SetFont('Arial','B', 12,'L');
                $this->Cell(0, 7, "Bank Details : " , 0, 1, 'L');
                $info = $this->info;
                $this->SetFont('Arial','', 11,'L');
                $this->Cell(0, 7, "A/C Name: " . $info["name"], 0, 1, 'L');
                $this->Cell(0, 7, "Bank Name: " . $info["bank_name"], 0, 1, 'L');
                $this->Cell(0, 7, "Account No: " . $info["bank_account"], 0, 1, 'L');
                $this->Cell(0, 7, "IFSC Code: " . $info["ifsc_code"], 0, 1, 'L');

                $this->SetY(-35);
                $this->SetFont('Arial','B', 12,'R');
                $this->Cell(0, 7, "For : Yesha Enterprises  " , 0, 1, 'R');
                $info = $this->info;
                $this->SetFont('Arial','', 11,'L');
                $this->Cell(0, 7, " " , 0, 11, 'R');
                $this->Cell(0, 7, " " , 0, 11, 'R');
                $this->Cell(0, 7, "Authorised Signatory", 0,1, 'R');
            }
        }

        function body($products_info)
        {
            $this->Ln(3); // Move down to create space below the title
            $this->SetLineWidth(0.1); // Adjust line thickness if needed
            $this->SetDrawColor(20, 31, 31); // Set line color (black)
            $this->Line(0, $this->GetY(), 250, $this->GetY()); // Draw a line
            $this->Ln(1); // Move down after drawing the line
            // Invoice title
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 8, "INVOICE", 0, 1, 'C');
            //line 
            $this->Ln(1); // Move down to create space below the title
            $this->SetLineWidth(0.1); // Adjust line thickness if needed
            $this->SetDrawColor(20, 31, 31); // Set line color (black)
            $this->Line(0, $this->GetY(), 250, $this->GetY()); // Draw a line
            $this->Ln(1); // Move down after drawing the line
            // Bill to
            $this->Ln(5);
            $info = $this->info;
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 7, "Bill To: ", 0, 1);
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 7, $info["customer"], 0, 1);
            $this->Cell(0, 7, $info["address"], 0, 1);
            $this->Cell(0, 7, $info["city"], 0, 1);
            $this->Cell(0, 7, "GSTIN: " . $info["gstin"], 0, 1);

            // Invoice details
            $this->SetY(86);
            $this->SetX(150);
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(50, 1, "Invoice No:" . $info["invoice_no"], 0, 0, 'R');
            $this->SetX(150);
            $this->Cell(50, 20, "Invoice Date:".  $info["invoice_date"], 0, 0, 'R');
           // Table Header
            $this->SetY(105);
            $this->SetFont('Arial', 'B', 11);
            $this->SetFillColor(128, 128, 128); // Set the background color for the header row
            $this->SetTextColor(255); // Set text color to white

            // Apply Bootstrap table class
            $this->Cell(10, 10, "S.N.", 1, 0, 'C', true, '', 1, 'align="center"'); // 'true' sets the cell background to the fill color
            $this->Cell(50, 10, "DESCRIPTION", 1, 0, 'C', true, '', 1, 'align="center"');
            $this->Cell(30, 10, "PRICE (Rs.)", 1, 0, 'C', true, '', 1, 'align="center"');
            $this->Cell(20, 10, "QTY", 1, 0, 'C', true, '', 1, 'align="center"');
            $this->Cell(40, 10, "GSTPRICE (Rs.)", 1, 0, 'C', true, '', 1, 'align="center"');
            $this->Cell(40, 10, "TOTAL (Rs.)", 1, 1, 'C', true, '', 1, 'align="center"');

            // Reset cell fill color and text color for subsequent rows
            $this->SetFillColor(255); // Reset the cell background color
            $this->SetTextColor(0); // Reset text color to black

            $this->SetFont('Arial', '', 10);
            $sn = 1;
            foreach ($products_info as $row) {
                $this->Cell(10, 10, $sn++, 1, 0, 'C');;
                $this->Cell(50, 10, $row["name"], 1);
                $this->Cell(30, 10, $row["price"], 1, 0, 'C');
                $this->Cell(20, 10, $row["qty"], 1, 0, 'C');
                $this->Cell(40, 10, $row["gstprice"], 1, 0, 'C');
                $this->Cell(40, 10, $row["total"], 1, 1, 'C');
            }

            // Fill empty rows if necessary
            $remainingRows = 5 - count($products_info);
            for ($i = 0; $i < $remainingRows; $i++) {
                $this->Cell(10, 10, '', 1);
                $this->Cell(50, 10, '', 1);
                $this->Cell(30, 10, '', 1);
                $this->Cell(20, 10, '', 1);
                $this->Cell(40, 10, '', 1);
                $this->Cell(40, 10, '', 1, 1);
            }
            $this->isLastPage = true;
                 // GST Calculation
                 $totalGST = 0;
                 foreach ($products_info as $row) {
                     $totalGST += $row["gstprice"];
                 }
                $cgst = 0;
                $sgst = 0;
                $igst = 0;

                if ($this->contains22) {
                    $cgst = $totalGST / 2;
                    $sgst = $totalGST / 2;
                } else {
                    $igst = $totalGST;
                }
                
                // GST Printing
                $this->SetFont('Arial', '', 12);
                if ($this->contains22) {
                    $this->Cell(150, 10, "CGST", 1, 0, 'R');
                    $this->Cell(40, 10, "Rs. " . number_format($cgst, 2), 1, 1, 'R');
                    
                    $this->Cell(150, 10, "SGST", 1, 0, 'R');
                    $this->Cell(40, 10, "Rs. " . number_format($sgst, 2), 1, 1, 'R');
                } else {
                    $this->Cell(150, 10, "IGST", 1, 0, 'R');
                    $this->Cell(40, 10, "Rs. " . number_format($igst, 2), 1, 1, 'R');
                }
                // Total
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(150, 10, "TOTAL", 1, 0, 'R');
                $this->Cell(40, 10, "Rs. " . $info["total_amt"], 1, 1, 'R');

                // Amount in words
                $this->Ln(10);
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 7, "Amount in Words: ", 0, 1);
                $this->SetFont('Arial', '', 12);
                $this->Cell(0, 7, $info["words"], 0, 1);
                $this->isLastPage = true;
        }
    }

    $pdf = new PDF($info, $contains22);
    $pdf->AddPage();
    $pdf->body($products_info);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Output();
}

// Fetch data from your database
$id = $_GET["id"];

$sql = "SELECT * FROM invoice WHERE SID='$id'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();

    $obj = new IndianCurrency($row["GRAND_TOTAL"]);

    $info = [
        "customer" => $row["CNAME"],
        "address" => $row["CAADDRESS"],
        "city" => $row["CCITY"],
        "invoice_no" => $row["INVOICE_NO"],
        "gstin" => $row["gstIn"],
        "invoice_date" => date("d-m-Y", strtotime($row["INVOICE_DATE"])),
        "total_amt" => $row["GRAND_TOTAL"],
        "words" => $obj->get_words(),
        "bank_name" => "State Bank Of India",
        "bank_account" => "41093610478",
        "ifsc_code" => "SBIN0016156",
        "name" => "Yesha Enterprises",
    ];

    $products_info = [];

    $sql = "SELECT * FROM invoice_products WHERE SID='$id'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $products_info[] = [
                "name" => $row["PNAME"],
                "price" => $row["PRICE"],
                "qty" => $row["QTY"],
                "gstprice" => $row["GSTPRICE"],
                "total" => $row["TOTAL"],
            ];
        }
    }

    $contains22 = strpos($info["gstin"], "22") !== false;

    // Call the createPDF function with your real data
    createPDF($info, $products_info, $contains22);
} else {
    echo "Invoice not found";
}
?>