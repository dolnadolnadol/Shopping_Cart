<!-- navbar.php -->
<style>
    body {
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #FCFCFC ; /* Light red color with transparency */
        padding: 10px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        display: flex;
        border : 0;
        border-bottom: 1px;
        border-style: solid;
        justify-content: space-between; /* Added to align items to the left and right */

    }
    .nav-right {
        /* background-color: red; */
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 3%;
        position: relative; /* Added relative positioning */
        margin-right: 7%;
    }

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    li {
        float: left;
        /* width: 100px; */
        height: 50px;
    }
    li a {
        display: block;
        color: black;
        border-color: none;
        border-radius: 50px;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: background-color 0.25s ease; /* Smooth transition effect */
        position: relative; /* Added for absolute positioning of the underline */
    }
    li a.active {
        /* background-color: #D676A0; Bootstrap's danger color */
        /* font-weight: bold; */
        /* color:blue; */
    }
    li a:hover {
        background-color: #F2F1F1;
    }

    /* Style for the clicked link */

    li a.active::after {
        content: ''; /* Create a pseudo-element for the underline */
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px; /* Underline thickness */
    }

    body {
        margin-top: 50px; /* Adjust margin to avoid content being hidden under the fixed navbar */
    }

    .nav-left {
        display: flex;
        /* background-color:blue; */
        align-items: center;
        margin-left: 7%;
    }

    /* .nav-right {
        background-color: red;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-right: 7%;
    } */

    .cart-icon {
        justify-content: center;
        align-items: center;
        width: 30px;
    }

    .cart-icon-container {
        /* background-color:blue; */
        position: relative;
    }

    .badge {
        position: absolute;
        top: 0;
        right: 10%;
        background-color: red; /* Bootstrap's danger color */
        color: white;
        padding: 3px 8px;
        border-radius: 50%;
        font-size: 12px;
    }
    
    .divSearch{
        margin-right:2rem;
        align-items: center;
    }
    #searchBar {
        display: flex;
        padding: 7px;
        box-sizing: border-box;
        border-color:gray;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100px;
        
        background-image: url('./image/search_icon.png');
        
        transition: width 0.4s ease-in-out;
    }
    #searchBar:focus{
        width: 99%;
        border: 1px solid;
        outline:none;
        left: 20px;
    }
</style>

<nav>
    <div class="nav-left">
        <ul>
            <li><a <?php echo isActive('index.php'); ?> style="width:120px;" href="index.php">Home</a></li>
            <?php $cartIconClass = (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
                <li><a <?php echo isActive('../history.php'); ?> style="width:120px;" href="./history.php">History</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <ul class="nav-right">
        <li style="display:flex; align-items:center;">
            <!-- <div class="divSearch">
                <input id="searchBar" type="text" placeholder="Search...">
            </div> -->
        <li class='cart-icon-container'>
        <a <?php echo $cartIconClass; ?> style="padding:10px; width:40px;" href='cart.php'>
                <img class='cart-icon' src='./image/shopping-cart.png' alt='Cart'>
                <?php if(isset($_SESSION['cart'])) : ?>
                <?php $cartIconCount = (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? count($_SESSION['cart']) : 0; ?>

                <?php elseif(isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) :?>
                    <?php
                        // echo 'TEST123456';
                        $cx =  mysqli_connect("localhost", "root", "", "shopping");
                        $uid = (isset($_SESSION['id_username'])) ? $_SESSION['id_username'] : '';
                        $cur = "SELECT * FROM cart WHERE CusID = '$uid'";
                        $msresults = mysqli_query($cx, $cur);

                        $cartIconCount = (mysqli_num_rows($msresults) > 0) ? mysqli_num_rows($msresults) : 0;
                        mysqli_close($cx);
                    ?>
                <?php endif; ?>
                <?php if (!empty($cartIconCount)) : ?>
                    <span class='badge' id='lblCartCount'><?php echo $cartIconCount; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) && $_SESSION['status'] === true) : ?>
            <li><a <?php echo isActive('profile.php'); ?> style="width:120px;" href="profile.php">Profile</a></li>
            <li><a <?php echo isActive('logoutProcess.php'); ?> style="width:120px; color:red;" href="logoutProcess.php">Logout</a></li>

        <?php else : ?>
            <li><a <?php echo isActive('login.php'); ?> style="width:120px;" href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
<!-- <script>
    function updateTable(filterKeyword) {
            var tableRows = document.querySelectorAll('.user-row');

            tableRows.forEach(function (row) {
                var containsKeyword = false;

                // Loop through all columns (td elements) in the current row
                row.querySelectorAll('td').forEach(function (cell, index) {
                    var cellText = cell.innerText.toLowerCase();

                    // Check if the cell contains the filter keyword (string comparison)
                    if (cellText.includes(filterKeyword.toLowerCase())) {
                        containsKeyword = true;
                        return; // Break out of the loop if the keyword is found in any cell
                    }

                    // Check if the cell contains the filter keyword as a number
                    var cellNumber = parseFloat(cellText);
                    var filterNumber = parseFloat(filterKeyword);

                    if (!isNaN(cellNumber) && !isNaN(filterNumber) && cellNumber === filterNumber) {
                        containsKeyword = true;
                        return; // Break out of the loop if the numeric values match
                    }
                });

                // Display or hide the row based on the keyword presence
                if (containsKeyword) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        $('#filter').on('input', function() {
            var filterKeyword = $(this).val();
            updateTable(filterKeyword);
        });
</script> -->
<?php
    // Function to check and set 'active' class
    function isActive($page)
    {
        return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
    }
?>

