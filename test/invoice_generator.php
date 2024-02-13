<?php
// Replace these with your actual data
$invoiceNumber = 'INV123';
$invoiceDate = '2024-02-13';
$customerName = 'John Doe';
$customerEmail = 'john@example.com';

// Example invoice items
$invoiceItems = [
    ['product' => 'Product A', 'quantity' => 2, 'unitPrice' => 50, 'total' => 100],
    ['product' => 'Product B', 'quantity' => 1, 'unitPrice' => 75, 'total' => 75],
    // Add more items as needed
];

// Calculate total
$invoiceTotal = array_sum(array_column($invoiceItems, 'total'));

// Include the HTML template
include('invoice_template.html');
?>
