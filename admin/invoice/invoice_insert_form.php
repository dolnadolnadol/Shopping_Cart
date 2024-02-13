<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Form</title>

    <!-- Include necessary libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #cce0ff;
            /* Ocean-sky blue background color */
        }

        .invoice-form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            /* White background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Box shadow for a subtle lift */
        }

        .form-block {
            border: 1px solid #b3ccff;
            /* Light ocean-sky blue border color */
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #edf5ff;
            /* Light ocean-sky blue background color */
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #3366cc;
            /* Dark ocean-sky blue text color for labels */
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #99c2ff;
            /* Light ocean-sky blue border color for inputs and selects */
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            /* Darker ocean-sky blue button color */
            color: #fff;
            /* White text color for the button */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            /* Slightly darker color on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>

    <div class="invoice-form">
        <h2 style="color: #007bff;">Create Invoice</h2>

        <!-- Invoice Information Section -->
        <div class="form-block">
            <h3 style="color: #007bff;">Invoice Information</h3>
            <div class="form-group">
                <label for="numID">NumID:</label>
                <input type="text" id="numID" name="numID" readonly>
            </div>

            <div class="form-group">
                <label for="invID">InvID:</label>
                <input type="text" id="invID" name="invID" readonly>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Add Products Section -->
        <div class="form-block">
            <h3 style="color: #007bff;">Add Products</h3>

            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="pricePerUnit">Price Per Unit:</label>
                <input type="text" id="pricePerUnit" name="pricePerUnit" required>
            </div>

            <button type="button" id="addProductBtn">Add Product</button>
        </div>

        <!-- Product Table -->
        <h3 style="color: #007bff;">Product Information</h3>
        <table id="productTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price Per Unit</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Price:</strong></td>
                    <td id="totalProductPrice">0</td>
                </tr>
            </tfoot>
        </table>

        <h3 style="color: #007bff;">Customer Information</h3>
        <div class="form-block">
            <div class="form-group">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" required>
            </div>

            <div class="form-group">
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" required>
            </div>
        </div>
        <button type="submit">Submit</button>
    </div>

    <script>
        $(document).ready(function() {
            // Add Product Button Click Event
            $('#addProductBtn').click(function() {
                addProduct();
            });

            function addProduct() {
                // Get values from inputs
                var productName = $('#productName').val();
                var quantity = $('#quantity').val();
                var pricePerUnit = $('#pricePerUnit').val();

                // Calculate total price
                var totalPrice = quantity * pricePerUnit;

                // Create table row
                var row = '<tr>' +
                    '<td>' + productName + '</td>' +
                    '<td>' + quantity + '</td>' +
                    '<td>' + pricePerUnit + '</td>' +
                    '<td>' + totalPrice + '</td>' +
                    '</tr>';

                // Append row to the table
                $('#productTable tbody').append(row);

                // Update total price
                updateTotalPrice();

                // Clear input fields
                $('#productName').val('');
                $('#quantity').val('');
                $('#pricePerUnit').val('');
                $('#totalPrice').val('');
            }

            function updateTotalPrice() {
                // Calculate total product price
                var totalProductPrice = 0;
                $('#productTable tbody tr').each(function() {
                    var totalPriceCell = $(this).find('td:last-child').text();
                    totalProductPrice += parseFloat(totalPriceCell);
                });

                // Update total price in the footer
                $('#totalProductPrice').text(totalProductPrice.toFixed(2));
            }
        });
    </script>

</body>

</html>