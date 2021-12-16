<?php
    require_once("shared/utils/CartUtil.php");
    require_once("shared/utils/AuthUtil.php");

    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }


    if (isset($_POST["save"])) {
        updateCart($_POST);
        header("Location: shoppingCart.php");
        exit();
    } else if (isset($_POST["submit"])) {
        updateCart($_POST);
        header("Location: checkout.php");
        exit();
    }

    function updateCart($postData) {
        $cartUtil = new CartUtil();
        unset($postData['save']);
        foreach ($_POST as $id => $qt) {
            $cartUtil->setQuantity($id, $qt);
        }
    }
?>