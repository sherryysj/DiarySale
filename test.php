<?php

require("shared/DBManager.php");
require("shared/OrderManager.php");

$db = new DBManager();
$conn = $db->getConn();

$oMgr = new OrderManager($conn);

$_SESSION["username"] = "testLFC";

// echo $oMgr->__createOrder(14.55, "address", "card");

if (substr_compare("testing this", "test", 0, 4) == 0){
    echo "ye";
}


$paramName = "customText_parameter";
$customTag = "customText_";

echo substr_compare($paramName, $customTag, 0, strlen($customTag));


?>