<?php
    include_once '../dbConfig.php'; 
?>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #FCFCFC ;
        padding: 10px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        display: flex;
        border : 0;
        border-bottom: 1px;
        border-style: solid;
        justify-content: space-between;

    }
    .nav-right {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 3%;
        position: relative;
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
        transition: background-color 0.25s ease;
        position: relative;
    }
    li a:hover {
        background-color: #F2F1F1;
    }


    li a.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
    }

    body {
        margin-top: 50px;
    }

    .nav-left {
        display: flex;
        align-items: center;
        margin-left: 7%;
    }

    .cart-icon {
        justify-content: center;
        align-items: center;
        width: 30px;
    }

    .cart-icon-container {
        position: relative;
    }

    .badge {
        position: absolute;
        top: 0;
        right: 10%;
        background-color: red;
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
        <li class='cart-icon-container'>
        <a <?php echo $cartIconClass; ?> style="padding:10px; width:40px;" href='cart.php'>
                <img class='cart-icon' src='./image/shopping-cart.png' alt='Cart'>
                <?php if(isset($_SESSION['cart'])) : ?>
                <?php $cartIconCount = (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? count($_SESSION['cart']) : 0; ?>

                <?php elseif(isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) :?>
                    <?php
                        $uid = (isset($_SESSION['id_username'])) ? $_SESSION['id_username'] : '';
                        $cur = "SELECT * FROM cart WHERE CusID = '$uid'";
                        $msresults = mysqli_query($conn, $cur);

                        $cartIconCount = (mysqli_num_rows($msresults) > 0) ? mysqli_num_rows($msresults) : 0;
                         
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
<?php
    function isActive($page)
    {
        return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
    }
?>

