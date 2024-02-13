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
    <form id="invoiceForm" method="post" action="order_save_update.php">
        <div class="invoice-form">
            <h2 style="color: #007bff;">Create Order</h2>
          
            <!-- Invoice Information Section -->
            <div class="form-block">
                <?php
                    $cx =  mysqli_connect('localhost', 'root', '', 'shopping');
                    $result = mysqli_query($cx, "SELECT MAX(RecID) AS rec_id FROM receive");
                    $row = mysqli_fetch_assoc($result);
                    $lastID = $row['rec_id'];
                    $numericPart = intval(substr($lastID, 6));
                    $newNumericPart = $numericPart + 1;
                    $RecID = 'rec_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
                
                    echo "<h3 style='color: #007bff;'>Invoice Information</h3>
                    <div class='form-group'>
                        <label for='RecID'>RecID:</label>
                        <input type='text' id='RecID' name='RecID' value='$RecID' readonly>
                    </div>";
                ?>

              

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Pickups">Pending</option>
                        <option value="Progress">Paid</option>
                        <option value="Delivered">Cancelled</option>
                    </select>
                </div>
            </div>

             <!-- Customer Information Section -->
            <h3 style="color: #007bff;">Customer Information</h3>
            <div class="form-block">
                <div class="form-group">
                    <label for="customerName">Customer Name:</label>
                    <select id='customerName' name='customerName' required>
                        <?php
                            $customerQuery = mysqli_query($cx, 'SELECT Customer.* FROM customer');
                            while ($row = mysqli_fetch_array($customerQuery)) {
                                echo "<option value='{$row["CusID"]}'>{$row["CusName"]}</option>";
                            }
                        ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="dueDate">Due Date:</label>
                    <input type="date" id="dueDate" name="dueDate" required>
                </div>
            </div>  

            <!-- Add Products Section -->
            <div class="form-block">
                <h3 style="color: #007bff;">Add Products</h3>
                <div class='form-group'>
                    <label for='productName'>Product Name:</label>
                    <select id='productName' name='id_product' required>
                        <?php
                            $productQuery = mysqli_query($cx, 'SELECT * FROM product');
                            while ($row = mysqli_fetch_array($productQuery)) {
                                echo "<option data-price='{$row["PricePerUnit"]}' value='{$row["ProID"]}'>{$row["ProName"]}</option>";
                            }
                            mysqli_close($cx);
                        ?>
                    </select>
                </div>

                <div class='form-group'>
                    <label for='quantity'>Quantity:</label>
                    <input type='text' id='quantity' name='quantity[]'>
                </div>

                <button type='button' id='addProductBtn'>Add Product</button>
            </div>

            <!-- Product Table -->
            <h3 style="color: #007bff;">Product Information</h3>
            <table id="selectedProductsTable">
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
            <input type="hidden" id="totalProductPriceInput" name="totalProductPrice" value="">
            <input type="hidden" id="selectedProductsInput" name="selectedProducts" value="">
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Add Product Button Click Event
            $('#addProductBtn').click(function() {
                addProduct();
            });

            function addProduct() {
                // Get values from inputs
                var productName = $('#productName option:selected').text();
                var proID = $('#productName').val();

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
                $('#selectedProductsTable tbody').append(row);

                // Update total price
                updateTotalPrice();

                // เก็บค่า ProID ใน hidden input
                updateSelectedProductsInput();

                

                // Set proID in the hidden input field
                $('#proIDInput').val(proID);

                // Clear input fields
                $('#quantity').val('');
            }

        function updateTotalPrice() {
            // Calculate total product price
            var totalProductPrice = 0;
            $('#productTable tbody tr').each(function () {
                var totalPriceCell = $(this).find('td:last-child').text();
                totalProductPrice += parseFloat(totalPriceCell);
            });

            // Update total price in the footer
            $('#totalProductPrice').text(totalProductPrice.toFixed(2));

            // Set totalProductPrice in the hidden input field
            $('#totalProductPriceInput').val(totalProductPrice.toFixed(2));
        }
        });

        function updateSelectedProductsInput() {
            var selectedProducts = [];
            $('#selectedProductsTable tbody tr').each(function() {
                var proIDCell = $(this).find('td:last-child').text();
                selectedProducts.push(proIDCell);
            });

            // อัปเดตค่า ProID ใน hidden input
            $('#selectedProductsInput').val(selectedProducts.join(','));
        }
    </script>

</body>
</html>