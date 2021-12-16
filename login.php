<?php
    require_once("shared/utils/MessageUtil.php");
    require_once("shared/SystemManager.php");
    
    $msgUtil = new MessageUtil();
    $sysMgr = new SystemManager();
    $roleName = $sysMgr->getRole() == "customer" ? "Customer" : "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $roleName ?> Login</title>
    <?php require("shared/_imports.php") ?>
</head>
<body>
    <header>
    <?php 
        require('shared/_navBar.php');
    ?>
    </header>
    <div class="container">        
        <h3 class="my-4">
            <?php echo $roleName ?> Login
        </h3>
        <?php
            if ($sysMgr->getRole() == "customer") {
                $msgUtil->flashHtmlSuccessIfAny("accountCreated");
            }
            $formAction = "loginValid.php"; 
            require("shared/_loginForm.php");
            if ($sysMgr->getRole() == "customer") {
                echo <<<EOT
                    <div class="text text-default my-3 row col-4">
                        <div class="col-8">Don't have an account?</div> 
                        <a class="col btn btn-info btn-sm" href="customerSignUp.php">Register</a>
                    </div>
EOT;
            }
        ?>
    </div>
</body>
</html>
