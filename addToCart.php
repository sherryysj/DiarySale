<?php
    require("shared/utils/AuthUtil.php");
    require("shared/utils/CartUtil.php");
    require("shared/Product.php");
    require("shared/Option.php");
    require("shared/utils/TransitUtil.php");
    require("shared/utils/MessageUtil.php");
    $authUtil = new AuthUtil();
    $cartUtil = new CartUtil();
    $tranUtil = new TransitUtil();
    $msgUtil = new MessageUtil();


    if ($authUtil->isLoggedIn()) {
        // assign by value
        $optionMap = $tranUtil->popData("optionMap");
        $customMap = $tranUtil->popData("customMap");
        $data = $_POST;

        foreach($data as $name => $value){
            echo $name . " : " . $value . "<br>";
        }

        $productId = $data["productId"];
        $productName = $data["productName"];
        $quantity = $data["quantity"];
        $product = new Product($productId, $productName);
        unset($data["productId"]);
        unset($data["productName"]);    
        unset($data["quantity"]);
        $paramOptionArr = [];
        $customText = [];
        foreach($data as $paramName => $optName) { 
            //check paramName for customText_ tag
            $customTag = "customText_";  
            //if the substr_compare returns zero, the string starts with the customTag
            if (substr_compare($paramName, $customTag, 0, strlen($customTag)) == 0){
                $customText[$paramName] = $optName;
            } else {
                $paramOptionArr[$paramName] = $optionMap[$optName];
            }            
        }

        $cartUtil->addItem($product->toArr(), $paramOptionArr, $quantity, $customText);
        // TODO: temporary: flash a success message
        $tranUtil->addData("productNames", [$productId => $productName]);
        //$cartUtil->updateCustomText($cartUtil->getMaxID(), $customText);
        header("Location: shoppingCart.php?productId=$productId");
    } else {
        $msgUtil->addMessage("loginError", "Please log in to make purchases");
        header("Location: login.php");
    }
?>