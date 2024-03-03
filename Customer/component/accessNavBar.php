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

    .nav-left {
        display: flex;
        align-items: center;
        margin-left: 2%;
    }

    .nav-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
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
    <div class="nav-left">
        <ul>
            <li><a <?php echo isActive('index.php'); ?> href="index.php">Home</a></li>
            <?php $cartIconClass = (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
                <li><a <?php echo isActive('../history.php'); ?> href="./history.php">History</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <ul class="nav-right">
        <li class='cart-icon-container'>
        <a <?php echo $cartIconClass; ?> href='cart.php'>
                <img class='cart-icon' src='./image/cart.webp' alt='Cart'>
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

                    <span class='badge badge-warning' id='lblCartCount'><?php echo $cartIconCount; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) && $_SESSION['status'] === true) : ?>
            <li><a <?php echo isActive('profile.php'); ?> href="profile.php">Profile</a></li>
            <li><a <?php echo isActive('logoutProcess.php'); ?> href="logoutProcess.php">Logout</a></li>

        <?php else : ?>
            <li><a <?php echo isActive('login.php'); ?> href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
<?php
    // Function to check and set 'active' class
    function isActive($page)
    {
        return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
    }
?>

