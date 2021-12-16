<?php 
  require("shared/_imports.php"); 
  require("shared/_navBar.php"); 
  require("shared/DBManager.php");
  require("shared/OrderManager.php");
  require_once("shared/utils/AuthUtil.php");
  require("shared/Order.php");
  $authUtil = new AuthUtil();
  
  if (!(new AuthUtil())->isLoggedIn()) {
    header("Location: login.php");
  }

  $databaseManager = new DBManager();
  $conn = $databaseManager->getConn();
  $orderManager = new OrderManager($conn);
  $username = $authUtil->getUsername();
  $orderList = $orderManager->__retireveAllOrder($username);
?>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Order Records</title>
        <script>
          function getOrderID(elm){
            var orderID = $(elm).closest("tr").find("td:first-child").text();
            sessionStorage.setItem("orderid", orderID);
          }
        </script>
    </head>

<body class="bg-light">

    <div class="container">
	    <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your order records</span>
        </h4>
	    <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            
            <tr>
              <th>Order ID</th>
              <th>Total Price</th>
              <th>Order Time</th>
              <th>Order Status</th>
              <th>Shipping Address</th>
			        <th>Shipping Date</th>
			        <th>Feedback</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($orderList as $order){
              if($order->getFeedback()!=null){
                echo <<<EOT
                <tr>
                <td>{$order->getOrderID()}</td>
                <td>{$order->getTotalPrice()}</td>
                <td>{$order->getPrettyOrderTime()}</td>
                <td>{$order->getOrderStatus()}</td>
                <td>{$order->getShippingAddress()}</td>
                <td>{$order->getShippedDate()}</td>
                <td>{$order->getFeedback()}</td>
                </tr>
EOT;
              }else{
                echo <<<EOT
                <tr>
                <td>{$order->getOrderID()}</td>
                <td>{$order->getTotalPrice()}</td>
                <td>{$order->getPrettyOrderTime()}</td>
                <td>{$order->getOrderStatus()}</td>
                <td>{$order->getShippingAddress()}</td>
                <td>{$order->getShippedDate()}</td>
                <td><form action="addFeedback.php"><button class="btn btn-primary btn-block" type="submit" onclick="getOrderID(this);" />Add Feedback</button></form></td>
                </tr>
EOT;
              }
            }
            ?>
          </tbody>
        </table>
      </div>
	
	</div>


</body>


</html>