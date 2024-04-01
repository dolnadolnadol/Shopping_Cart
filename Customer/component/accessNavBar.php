<?php
    include_once '../dbConfig.php';
?>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #fff;
        padding: 10px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-left ul,
    .nav-right ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .nav-left li,
    .nav-right li {
        margin-right: 20px;
    }

    .nav-left a,
    .nav-right a {
        display: block;
        color: #000;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .nav-left a:hover,
    .nav-right a:hover {
        background-color: #ddd;
    }

    /* .active {
        background-color: #eef;
    } */

    .cart-icon {
        width: 30px;
        margin-right: 5px;
    }

    .badge {
        background-color: red;
        color: #fff;
        padding: 3px 8px;
        border-radius: 50%;
        font-size: 12px;
        position: absolute;
        top: 0;
        right: 0;
    }
</style>

<nav>
    <div class="nav-left">
        <ul>
            <li><a <?php echo isActive('index.php'); ?> href="./">Home</a></li>
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
                <li><a <?php echo isActive('../history.php'); ?> href="./history.php">History</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="nav-right">
        <ul>
            <!-- <li>
                <?php echo $_SESSION['auth']; ?>
            </li> -->
            <li>
                <a href='../admin/' id="admin" style='display:none;'>
                    admin
                </a>
            </li>
            <li class="cart-icon-container">
                <a href="cart.php" style="display:none;" id="cart" class="cart-link">
                    <img class="cart-icon"  src="./image/shopping-cart.png" alt="Cart">
                </a>
                <?php if (isset($_SESSION['cart']) || (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true)) : ?>
                    <?php
                        $cartIconCount = (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? count($_SESSION['cart']) : 0;

                        if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) {
                            $uid = (isset($_SESSION['id_username'])) ? $_SESSION['id_username'] : '';
                            $cur = "SELECT * FROM cart WHERE CusID = '$uid'";
                            $msresults = mysqli_query($conn, $cur);
                            $cartIconCount = (mysqli_num_rows($msresults) > 0) ? mysqli_num_rows($msresults) : 0;
                        }
                    ?>
                    <?php if (!empty($cartIconCount)) : ?>
                        <span class="badge" id="lblCartCount"><?php echo $cartIconCount; ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) && $_SESSION['status'] === true) : ?>
                <li><a <?php echo isActive('profile.php'); ?> href="profile.php">Profile</a></li>
                <li><a <?php echo isActive('logoutProcess.php'); ?> style="color:red;" href="logoutProcess.php">Logout</a></li>
            <?php else : ?>
                <li><a <?php echo isActive('login.php'); ?> href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
    .cart-icon-container {
        position: relative;
    }

    .badge {
        background-color: red;
        color: #fff;
        padding: 3px 8px;
        border-radius: 50%;
        font-size: 12px;
        position: absolute;
        top: -5px;
        right: -5px;
    }

    .cart-link {
        position: relative;
        display: inline-block;
    }
</style>
<script>
    <?php if (isset($_SESSION['auth'])) : ?>
        document.getElementById("cart").style.display = "inline-block";
    <?php endif ?>
    <?php if (($_SESSION['auth'] == 'product-admin' || $_SESSION['auth'] == 'permissions-admin' || $_SESSION['auth'] == 'super-admin')) : ?>
        document.getElementById("admin").style.display = "inline-block";
        <?php endif ?>
</script>


<?php
    function isActive($page)
    {
        return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
    }
?>
