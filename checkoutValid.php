<?php
    require_once("shared/utils/CartUtil.php");
    require_once("shared/DBManager.php");
    require_once("shared/OrderManager.php");
    require_once("shared/Product.php");
    require_once("shared/DQ_Dictionary.php");
    require_once("shared/utils/MessageUtil.php");
    require_once("shared/utils/AuthUtil.php");

    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }

    $db = new DBManager();
    $conn = $db->getConn();
    $cartUtil = new CartUtil();
    $orderMgr = new OrderManager($conn);
    $msgUtil = new MessageUtil();

    $deliveryMethod = $_POST["chosenDeliveryMethod"];

    $selectedCountry = $_POST["country"];
    $acceptedCountries = array("Australia");
    if (!in_array($selectedCountry, $acceptedCountries)) {
        $msgUtil->addMessage("order_fail", "Shipping address - Currently, we do not ship to the country that you selected");
        header("Location: checkout.php");
        exit();
    }

    $address = new DQ_Dictionary();
    $address->setVariable('street', $_POST['street']);
    $address->setVariable('city', $_POST['city']);
    $address->setVariable('state', $_POST['state']);
    $address->setVariable('postcode', $_POST['postcode']);

    //__createOrder returns order ID
    $orderID = $orderMgr->__createOrder($cartUtil->getTotalPrice(), $address->getValuesString(), $_POST['paymentMethod'], $deliveryMethod, $_SESSION['username']);

    if ($orderID == '0'){
        $msgUtil->addMessage("order_fail", "order - Something went wrong: Order has not been placed" + $orderID);
        header("Location: checkout.php");
    }

    //get orderlines from $_SESSION
    $orderLineArr = $cartUtil->getCart();
    //linecount used to ensure unique orderLineID in db
    $orderLineCount = 0;
    //orderlineError - will be false if there's no errors so far
    $orderLineError = false;
    foreach ($orderLineArr as $orderLine){
        echo '<br>';
        $orderlineID = $orderID . "_". $orderLineCount;
        $orderLineCount += 1;
        $product = $orderLine["product"];
        $options = $orderLine["options"];
        $customText = $orderLine["customText"];

        $p_summary = $cartUtil->getCustomText($customText) ."; " . $cartUtil->getSummary($options);
        $lineprice= $cartUtil->getUnitPrice($options);
        if (!$orderLineError){
            $orderLineError = $orderMgr->__createOrderLine($orderlineID, $orderLine['quantity'], $lineprice, $p_summary, $orderID, $product['productID']);
        } else {
            $msgUtil->addMessage("order_fail", "orderline - Something went wrong: Order has not been placed");
            header("Location: checkout.php");
        }
    }
    if ($orderID != '0' && !$orderLineError){
        echo 'here';
        $db->returnConn($conn);
        $cartUtil->clear();
        $msgUtil->addMessage("order_success", "Order has been placed! Thank you for shopping with Diary Queen");
        header("Location: index.php");
    }

?>