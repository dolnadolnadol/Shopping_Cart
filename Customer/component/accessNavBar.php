<!-- navbar.php -->
<style>
    body {
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: rgba(255, 99, 71, 0.8); /* Light red color with transparency */
        padding: 10px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between; /* Added to align items to the left and right */

    }
    .nav-right {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 3%;
        position: relative; /* Added relative positioning */
    }

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    li {
        float: left;
        width: 150px;
        height: 50px;
    }

    li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: background-color 0.25s ease; /* Smooth transition effect */
        position: relative; /* Added for absolute positioning of the underline */
    }

    li a:hover {
        background-color: rgba(255, 99, 71, 1); /* Full red color on hover */
    }

    /* Style for the clicked link */
    li a.active {
        background-color: #d9534f; /* Bootstrap's danger color */
    }

    li a.active::after {
        content: ''; /* Create a pseudo-element for the underline */
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px; /* Underline thickness */
        background-color: white; /* Underline color */
    }

    body {
        margin-top: 50px; /* Adjust margin to avoid content being hidden under the fixed navbar */
    }

    .nav-right {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 3%;
    }

    .cart-icon {
        margin-top: -15%;
        justify-content: center;
        width: 50%; /* Adjust size as needed */
        height: 50%; /* Adjust size as needed */
    }

    .cart-icon-container {
        position: relative;
    }

    .badge {
        position: absolute;
        top: 0;
        right: 25%;
        background-color: #d9534f; /* Bootstrap's danger color */
        color: white;
        padding: 3px 8px;
        border-radius: 50%;
        font-size: 12px;
    }

    .cart-icon {
        width: 50%; /* Adjust size as needed */
        height: 50%; /* Adjust size as needed */
        margin-top: -15%; /* Adjust margin as needed */
    }
  
</style>

<nav>
    <ul>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?> href="index.php">Home</a></li>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == '../history.php') echo 'class="active"'; ?> href="./history.php">History</a></li>
    </ul>

    <ul class="nav-right">
        <?php
            $cx =  mysqli_connect("localhost", "root", "", "shopping");
            $uid = $_SESSION['id_username'];
            $cur = "SELECT * FROM cart WHERE CusID = '$uid'";
            $msresults = mysqli_query($cx, $cur);

            if (mysqli_num_rows($msresults) > 0) {
                echo "<li class='cart-icon-container'><a " . (basename($_SERVER['PHP_SELF']) == 'cart.php' ? "class='active'" : "") . " href='cart.php'><img class='cart-icon' src='./image/cart.webp' alt='Cart'></a><span class='badge badge-warning' id='lblCartCount'>" . mysqli_num_rows($msresults) . "</span></li>";

            } else {
                echo "<li class='cart-icon-container'><a " . (basename($_SERVER['PHP_SELF']) == 'cart.php' ? "class='active'" : "") . " href='cart.php'><img class='cart-icon' src='./image/cart.webp' alt='Cart'></a></li>";
            }
        ?>

        
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'profile.php') echo 'class="active"'; ?> href="profile.php">Profile</a>
        </li>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'logoutProcess.php') echo 'class="active"'; ?> href="logoutProcess.php">Logout</a>
        </li>
        
    </ul>
</nav>
