<?php
    // include('./component/session.php');
    require('fpdf186/fpdf.php');

    class PDF extends FPDF
    {
        function header()
        {
            $this->SetFont('Arial', 'B', 30);
            $this->Cell(0, 10, 'Invoice', 0, 1, 'C');
        }


        function chapterTitle($num, $label)
        {
            // $this->SetFont('Arial', 'B', 12);
            // $this->Cell(0, 10, 'Invoice #: ' . $num, 0, 1, 'l'); // Change '1' to '0' here
            // $this->Cell(0, 10, 'Date: ' . $label, 0, 1, 'l');
        }

        function addInvoiceDetails($invoiceNumber, $invoiceDate)
        {
            $this->chapterTitle($invoiceNumber, $invoiceDate);
        }

        function addDetails($customerName, $customerTel)
        {
            // $this->SetFont('Arial', 'B', 12);
            // $this->Cell(0, 10, 'Customer Details', 0, 1, 'L');
            $this->SetFont('Arial', '', 12);
            $this->SetFillColor(255, 255, 255);
            $this->Cell(30, 7, 'Date', 0, 0, 'L');
            $this->Cell(30, 7, '22-3-5', 0, 0, 'L');
            
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(60, 7, 'Delivery location', 'L', 0, 'L');
            
            $this->Cell(60, 7, 'Address for receipt', 'L', 0, 'L');

            $this->ln();//-----------------------------------------------------

            $this->SetFont('Arial', '', 12);
            $this->Cell(30, 7, 'Payment Type', 0, 0, 'L');
            $this->Cell(30, 7, 'BBL', 0, 0, 'L');
            
            $this->Cell(30, 7, 'Name', 'L', 0, 'L');
            $this->SetFont('THSarabunNew', 'B', 17);
            $this->Cell(30, 7, iconv('UTF-8', 'cp874',$customerName), 'R', 0, 'L');
            $this->SetFont('Arial', '', 12);
            $adressRecriept = 'Bangkok , ladkrabang';
            $maxWidth = 60;
            $lineHeight = 7;
            $nbLines = ceil($this->GetStringWidth($adressRecriept) / $maxWidth);
            $this->MultiCell($maxWidth, 7, $adressRecriept, 'L', 0, 'L');
            $this->SetY($this->GetY() - ($lineHeight * $nbLines));

             $this->ln();//-----------------------------------------------------

            $this->SetFont('Arial', '', 12);
            $this->Cell(30, 7, 'Tel', 0, 0, 'L');
            $this->Cell(30, 7, '0985522182', 0, 0, 'L');
            
            $this->Cell(30, 7, 'Address', 'L', 0, 'L');
            $adressDelivery = 'Saraburi , muaklek';
            $maxWidth = 30;
            $nbLines = ceil($this->GetStringWidth($adressDelivery) / $maxWidth);
            $this->MultiCell($maxWidth, 7, $adressDelivery, 'R', 0, 'L');
            $this->SetY($this->GetY() - ($lineHeight * $nbLines));
            
             $this->ln();//-----------------------------------------------------

            $this->SetFont('Arial', '', 12);
            $this->Cell(30, 7, 'Name', 0, 0, 'L');
            $this->Cell(30, 7, '22-3-5', 0, 0, 'L');
            
            $this->Cell(30, 7, '', 'L', 0, 'L');
            $this->Cell(30, 7, '', 'R', 0, 'L');
            
             $this->ln();//-----------------------------------------------------

            $this->SetFont('Arial', '', 12);
            $this->Cell(30, 7, 'Adress', 0, 0, 'L');
            $adressPayer = 'Saraburi,muang 18180';
            $nbLines = ceil($this->GetStringWidth($adressPayer) / $maxWidth);
            $this->MultiCell($maxWidth, 7, $adressPayer, 'R', 0, 'L');
            
            // $this->SetFont('THSarabunNew', 'B', 17);
            // $this->Cell(0, 10, 'Name: ' . iconv('UTF-8', 'cp874',$customerName), 0, 1, 'L');
            // $this->Cell(60, 10, iconv('UTF-8', 'cp874',$customerName), 1);
             $this->ln();//-----------------------------------------------------
        }

        function addInvoiceTable($invoiceItems)
        {

            $this->SetFont('Arial', 'B', 12);

            // Set the background color for the header cells
            $this->SetFillColor(76, 175, 80);
            $this->Cell(60, 10, 'Product', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Quantity', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Unit Price', 1, 0, 'C', true);
            $this->Cell(50, 10, 'Total', 1, 1, 'C', true); // Move to the next line and add true
            
            // Reset the background color to default (white) for the following cells
            $this->SetFillColor(255, 255, 255);
        
            $this->SetFont('Arial', '', 12);
            foreach ($invoiceItems as $item) {
                $this->SetFont('THSarabunNew', 'B', 17);
                $this->Cell(60, 10, iconv('UTF-8', 'cp874',$item['product']), 1);
                $this->Cell(40, 10, $item['quantity'], 1);
                $this->Cell(40, 10, $item['unitPrice'] , 1);
                $this->Cell(50, 10, $item['total'] , 1);
                $this->Ln();
            }
        }

        function addInvoiceTotal($invoiceSubTotal , $invoiceVat ,$invoiceTotal)
        {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(140, 10, 'SubTotal', 0 , 0 , 'R');
            $this->Cell(50, 10, number_format($invoiceSubTotal, 2), 1);
            $this->Ln();

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(140, 10, 'VAT', 0 , 0 , 'R');
            $this->Cell(50, 10, number_format($invoiceVat, 2), 1);
            $this->Ln();

            $this->SetFont('Arial', 'B', 12);
            $this->Cell(140, 10, 'Total', 0 , 0 , 'R');
            $this->Cell(50, 10, number_format($invoiceTotal, 2), 1);
        }
    }

    // Sample data
    // if(isset($_POST['id_invoice']) && isset($_POST['id_customer'])) {

        $customerId = 81;
        $invoiceId = 'INV022';


        $cx =  mysqli_connect("localhost", "root", "", "shopping");
        $invoiceQuery = mysqli_query($cx, "SELECT * FROM Customer WHERE CusID = '$customerId'");
        $row_customer = mysqli_fetch_array($invoiceQuery);
        $customerName = $row_customer['CusFName'] . $row_customer['CusLName'];
        $customerTel = $row_customer['Tel'];

        $invoiceSubTotal = 0;
        $invoiceNumber = "";
        $invoiceDate = "";
        $invoiceStatus = "";
        $invoiceItems = array();

        $invoiceQuery = mysqli_query($cx, "SELECT invoice.* , invoice_detail.*, product.* , (product.PricePerUnit * invoice_detail.Qty ) AS SubTotal
        FROM invoice
        INNER JOIN invoice_detail ON invoice.InvID = invoice_detail.InvID
        INNER JOIN Product ON invoice_detail.ProID = Product.ProID
        WHERE invoice.CusID = '$customerId' AND invoice_detail.InvID = '$invoiceId'");

        while ($row = mysqli_fetch_array($invoiceQuery)) {            
            $invoiceItems[] = array(
                'product' => $row['ProName'],
                'quantity' => $row['Qty'],
                'unitPrice' => $row['PricePerUnit'],
                'total' => $row['SubTotal'],
            );
            $invoiceSubTotal += $row['SubTotal'];
            $invoiceNumber = $row['InvID'];
            $invoiceDate = $row['Period'];
            $invoiceStatus = $row['Status'];
        }
      
        $invoiceVat = $invoiceSubTotal * 0.07;
        $invoiceTotal = $invoiceSubTotal + $invoiceVat;
        mysqli_close($cx);

        // Create PDF
        $pdf = new PDF();
        $pdf->AddFont('THSarabunNew','','THSarabunNew.php');
        $pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
        $pdf->AddPage();
        $pdf->addInvoiceDetails($invoiceNumber, $invoiceDate);
        $pdf->addDetails($customerName, $customerTel);
        $pdf->addInvoiceTable($invoiceItems);
        $pdf->addInvoiceTotal($invoiceSubTotal , $invoiceVat ,$invoiceTotal);

        // Output PDF
        $pdf->Output();
    // }
?>
