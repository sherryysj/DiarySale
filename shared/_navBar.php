<?php
if (!isset($_SESSION)) {
    session_start();
}
$username = NULL;
$role = NULL;
if (array_key_exists("username", $_SESSION))
    $username = $_SESSION["username"];
if (array_key_exists("role", $_SESSION))
    $role = $_SESSION['role'];
$isLoggedIn = $username != NULL;
?>

<nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light bg-white border-bottom box-shadow mb-3">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
            <ul class="navbar-nav">
                <?php
                if ($isLoggedIn)
                    echo <<<EOT
                        <li class="nav-item">
                            <span class="nav-link text-dark">Welcome, {$username}</span> 
                        </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="shoppingCart.php">Shopping Cart</a>
                        </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">My Account</a>
                            <div class="dropdown-menu" aria-labelledby="dropdown01">
                                <a class="dropdown-item" href="changePassword.php">Change Password</a>
                                <a class="dropdown-item" href="orderRecord.php">My Orders</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="logout.php">Log out</a>
                        </li>
EOT;
                else {
                    echo <<<EOT
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="login.php">Log in/Sign Up</a>
                        </li>
EOT;
                }

                ?>
            </ul>

            <ul class="navbar-nav flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="productBrowsing.php">Catalog</a>
                </li>
                <form method="post" action="productSearching.php" style="margin-left:5em;">
                    <div class="row">
                        <input type="search" class="form-control col-md-9" id="productSearch" name="productSearch" placeholder="Search for Products.." required>
                        <input class="btn btn-sm btn-light col-md-3" type="submit" value="Search"></div>

                </form>
            </ul>
        </div>
    </div>
</nav>