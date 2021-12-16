<?php
require_once("shared/_imports.php");
require_once("shared/_navBar.php");
require_once("shared/DBManager.php");
require_once("shared/OrderManager.php");
require_once("shared/utils/AuthUtil.php");
require_once("shared/Order.php");
require_once("shared/utils/MessageUtil.php");
require_once("shared/utils/AuthUtil.php");

if (!(new AuthUtil())->isLoggedIn()) {
    header("Location: login.php");
}

$databaseManager = new DBManager();
$conn = $databaseManager->getConn();
$orderManager = new OrderManager($conn);
$msgUtil = new MessageUtil();

foreach ($_POST as $p_name => $p_value) {
    $$p_name = $p_value ? $p_value : "";
    echo $p_name, $p_value;
}

$success = $orderManager->__addFeedback($postOrderID, $Feedback);
if ($success) {
    $msgUtil->displayAlertAndJump("Feedback added!", "orderRecord.php");
} else {
    $msgUtil->displayAlertAndJump("Failed to add feedback, try again!", "orderRecord.php");
}
