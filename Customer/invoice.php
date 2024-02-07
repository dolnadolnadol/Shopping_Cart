<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #ef476f;
        }

        .product {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        .customer-details {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice</h1>
        <div class="customer-details">
            <p>Customer Name: John Doe</p>
            <p>Email: john@example.com</p>
            <p>Address: 123 Main Street, City</p>
        </div>
        <div class="product">
            <span>Product 1</span>
            <span>$10.00</span>
        </div>

        <div class="product">
            <span>Product 2</span>
            <span>$20.00</span>
        </div>

        <div class="total">
            Total: $30.00
        </div>

    </div>
</body>
</html>
