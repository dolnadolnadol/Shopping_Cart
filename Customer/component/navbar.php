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
</style>

<nav>
    <ul>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?> href="index.php">Home</a></li>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'register.php') echo 'class="active"'; ?> href="register.php">Register</a></li>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'invoiceHistory.php') echo 'class="active"'; ?> href="invoiceHistory.php">Order History</a></li>
    </ul>

    <ul class="nav-right">
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'cart.php') echo 'class="active"'; ?> href="cart.php"><img class="cart-icon" src="cart.webp" alt="Cart"></a>
        </li>
        <li><a <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'class="active"'; ?> href="login.php">Login</a></li>
    </ul>
</nav>
