<?php
    session_start();
    require("shared/utils/AuthUtil.php");
    require("shared/utils/CartUtil.php");
    $authUtil = new AuthUtil();
    $authUtil->logoutUser();
    $cartUtil = new CartUtil();
    $cartUtil->clear();

    // redirect to home page
    header("Location: index.php");
?>