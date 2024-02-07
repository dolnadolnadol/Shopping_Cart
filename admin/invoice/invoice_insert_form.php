<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ใบแจ้งหนี้</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .invoice {
      width: 80%;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ccc;
    }

    .invoice-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .invoice-header h1 {
      color: #333;
    }

    .invoice-details {
      margin-bottom: 20px;
    }

    .invoice-details p {
      margin: 5px 0;
    }

    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .invoice-table th, .invoice-table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    .invoice-total {
      text-align: right;
    }
    
    #overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }
    #overlay-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

  <div class="invoice">
    <div class="invoice-header">
      <h1>ใบแจ้งหนี้</h1>
    </div>

    <button onclick="toggleOverlay()">Click me</button>
        <div id="overlay">
        <div id="overlay-content">
            <p>This is the overlay content.</p>
            <button onclick="toggleOverlay()">Close</button>
        </div>
    </div>

    <?php
        $conn = mysqli_connect("localhost", "root", "", "shopping");
        $code = $_POST['id_customer'];
        $cur = "SELECT * FROM Customer WHERE CusID = '$code'";
        $msresults = mysqli_query($conn,$cur);
        if(mysqli_num_rows($msresults) > 0) {
            $row = mysqli_fetch_array($msresults);
            echo '<div class="invoice-details">
                        <p><strong>ลูกค้า:</strong>'. $row['CusName'] .'</p>
                        <p><strong>ที่อยู่:</strong>'. $row['Address'] .'</p>
                        <p><strong>วันที่:</strong>123456</p>
                </div>';
        }
    ?>
    <table class="invoice-table">
      <thead>
        <tr>
          <th>รายการ</th>
          <th>จำนวน</th>
          <th>ราคาต่อหน่วย</th>
          <th>รวม</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>สินค้า A</td>
          <td>2</td>
          <td>100.00</td>
          <td>200.00</td>
        </tr>
        <tr>
          <td>สินค้า B</td>
          <td>1</td>
          <td>50.00</td>
          <td>50.00</td>
        </tr>
      </tbody>
    </table>

    <div class="invoice-total">
      <p><strong>รวมทั้งสิ้น:</strong> 250.00</p>
    </div>
  </div>

<script>
  function toggleOverlay() {
    var overlay = document.getElementById('overlay');
    overlay.style.display = (overlay.style.display === 'none' || overlay.style.display === '') ? 'flex' : 'none';
  }
</script>

</body>
</html>
