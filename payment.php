<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
</head>
<body>
    <h1>Generate QR Code for UPI Payment</h1>
    
    <label for="amount">Enter Amount:</label>
    <input type="number" id="amount" placeholder="Enter amount" />
    <button onclick="generateQRCode()">Generate QR Code</button>
    
    <div id="qrcode"></div>

    <script>
        function generateQRCode() {
            // Replace 'abc@paytm' with your actual UPI ID
            const upiId = 'abc@paytm';

            const amountInput = document.getElementById('amount');
            const amount = amountInput.value;

            if (!amount || isNaN(amount) || amount <= 0) {
                alert('Please enter a valid amount.');
                return;
            }

            const qrCodeDiv = document.getElementById('qrcode');
            qrCodeDiv.innerHTML = '';

            const qrCode = new QRCode(qrCodeDiv, {
                text: `upi://${upiId}?amount=${amount}`,
                width: 128,
                height: 128,
            });
        }
    </script>
</body>
</html>
