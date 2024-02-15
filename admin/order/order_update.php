<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>

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
        <form action="order_save_update.php" method="post">
            <h2 style="color: #007bff;">Update Order</h2>
    
            <!-- Invoice Information Section -->
            <div class="form-block">
                <h3 style="color: #007bff;">Order Information</h3>
                <div class="form-group">
                    <label for="RecID">RecID:</label>
                <?php     

                    $cx =  mysqli_connect("localhost", "root", "", "shopping");
                    $RecID = $_POST['id_order'];
                    echo "<input type='text' id='RecID' name='RecID' value='$RecID' readonly>
                    </div>";
                    $cur = "SELECT Status FROM receive WHERE RecID = '$RecID'";
                    $msresults = mysqli_query($cx, $cur);
                    $row = mysqli_fetch_array($msresults);
                    $status = $row['Status'];


                ?>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <?php
                            $statusCompare = ['Pending', 'Inprogress', 'Delivered' , 'Canceled'];
                            foreach ($statusCompare as $value) {
                                echo "<option value='$value'".($value == $status ? ' selected' : '').">$value</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            
            <h3 style="color: #007bff;">Customer Information</h3>
            <div class="form-block">
                <div class="form-group">
                    <label for="customerName">Customer Name:</label>
                    <select id="customerName" name="customerName" required>
                        <?php
                            // Your PHP code to fetch products from the database
                            $result = mysqli_query($cx, "SELECT * FROM Customer");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['CusID']}'>{$row['CusName']}</option>";
                            }
                        ?>
                    </select>
                             
                </div>
                <!-- <div class="form-group">
                    <label for="dueDate">Due Date:</label>
                    <input type="date" id="dueDate" name="dueDate" required>
                </div> -->
            </div>

            <!-- Add Products Section -->
            <div class="form-block">
                <!-- <h3 style="color: #007bff;">Add Products</h3> -->
                <!-- <div class="form-group">
                <label for="productName">Product Name:</label>
                <select id="productName" name="productName[]">
                        <?php
                        // Your PHP code to fetch products from the database
                        $result = mysqli_query($cx, "SELECT *
                                                    FROM Product
                                                    WHERE ProID NOT IN (SELECT DISTINCT ProID FROM receive_detail WHERE RecID = '$RecID')");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option data-product-id='{$row['ProID']}' data-price='{$row['PricePerUnit']}' value='{$row['ProID']}'>{$row['ProName']}</option>";
                            }
                        } else {
                            echo "Error: " . mysqli_error($cx);
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity[]" >
                </div>
                <button type="button" id="addProductBtn">Add Product</button>
            </div> -->
    

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
                <tbody>
                    <?php
                        $showTotal = 0.0;
                        $showVat = 0.0;
                        $result = mysqli_query($cx, " SELECT receive_detail.* , Product.*,
                            receive_detail.Qty * Product.PricePerUnit AS TotalPrice
                            FROM receive_detail 
                            INNER JOIN Product ON receive_detail.ProID = Product.ProID WHERE receive_detail.RecID = '$RecID'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<input type='hidden' name='ProID[]' value={$row['ProID']}";
                            echo "<tr>
                                    <td>{$row['ProName']}</td>
                                    <td><input type='text' name='Qty[]' value='{$row['Qty']}' class='quantity-input'></td>
                                    <td>{$row['PricePerUnit']}</td>
                                    <td class='total-price'>{$row['TotalPrice']}</td>
                                  </tr>
                                  ";
                            $showTotal += $row['TotalPrice'];
                        }        
                        $showVat +=  $showTotal * 0.07;  
                        $showTotal += $showVat;
                        
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>VAT 7%:</strong></td>
                        <td id="totalVatPriceInput"><?php echo $showVat; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total Price:</strong></td>
                        <td id="totalProductPrice"><?php echo $showTotal; ?></td>
                    </tr>
                </tfoot>
            </table>

            <?php
                echo "<input type='hidden' id='selectedProductsInput' name='selectedProducts' value='$showVat'>
                <input type='hidden' id='totalProductPriceInput' name='totalProductPrice' value='$showTotal'>
                <input type='submit' value='Submit'>";
            ?>
        </form>
    </div>

    <script>
    // Initialize an array to store selected product names

        $(document).ready(function() {

        // Event listener for quantity input changes
        $('.quantity-input').on('input', function() {
                updateTotalPrice($(this).closest('tr'));
            });

            // Function to update total price based on quantity
            function updateTotalPrice(row) {
                var quantity = parseInt(row.find('.quantity-input').val());
                var pricePerUnit = parseFloat(row.find('td:eq(2)').text());
                var totalPrice = quantity * pricePerUnit;

                // Update the total price cell in the same row
                row.find('.total-price').text(totalPrice.toFixed(2));

                // Call a function to update the grand total if needed
                updateGrandTotal();
            }

            // Function to update the grand total
            function updateGrandTotal() {
                var grandTotal = 0;
                var vatPrice = 0;

                // Iterate through each row and add up the total prices
                $('.total-price').each(function() {
                    grandTotal += parseFloat($(this).text());
                    vatPrice += grandTotal*0.07;
                });

                $('#totalVatPriceInput').text(vatPrice.toFixed(2));

                $('#totalProductPrice').text((grandTotal + vatPrice).toFixed(2));

                // Update hidden input value
                $('#totalProductPriceInput').val((grandTotal + vatPrice).toFixed(2));
            }
    });
       
    </script>

</body>

</html>