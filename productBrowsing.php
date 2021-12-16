<?php
    require("shared/DBManager.php");
    require("shared/ProductManager.php");
    require("shared/OrderManager.php");
    require("shared/Product.php");
    require("shared/Order.php");
    require("shared/OrderLine.php");
    require_once("shared/utils/TransitUtil.php");
    $databaseManager = new DBManager();
    $conn = $databaseManager->getConn();
    $productManager = new ProductManager($conn);
    $orderManager = new OrderManager($conn);
    $tranUtil = new TransitUtil();

    $productList = $productManager->__retriveAllProducts();
    $orderFeedbackList = $orderManager->__retrieveAllOrderFeedback();
    // add to session to pass to customisation page
    $productNames = [];
    foreach ($productList as $product) {
        $productNames[$product->getProductID()] = $product->getProductName();
    }
    $tranUtil->addData("productNames", $productNames);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Product Browsing</title>
        <?php require("shared/_imports.php") ?>
    </head>
    <body>
        <?php require("shared/_navBar.php") ?>

        <div class="container">
	
            <div class="row mb-3 text-center">
		        <?php
                foreach ($productList as $product) {
                    echo <<<EOT
                    <div class="col-auto mb-3">
			        <div class="card mb-4 shadow-sm" style="min-width: 15rem; max-width: 15rem;">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">{$product->getProductName()}</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mt-3 mb-4">
                            <img src="static\img\DiaryQueenLogo.png" class="img-thumbnail" alt="Product Image">
                            </ul>
                            <a href="productCustomising.php?productId={$product->getProductID()}" class="btn btn-lg btn-block btn-outline-primary">Customize</a>
					    </div>
                    </div>
                    </div>
EOT;
               }
		        ?>
	        
	
            </div>
		    <hr class="mb-4">
			<div>
			    <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Diary Queen Customer Feedback</span>
                </h4>
				
				<div class="col-md-8">
                    <?php
                    foreach ($orderFeedbackList as $orderFeedback) {
                        echo <<<EOT
                        <div class="container">
                            <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                                <div class="col p-4 d-flex flex-column position-static">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex bd-highlight">
                                                <div class="p-2 bd-highlight">Customer: <strong>{$orderFeedback->getUsername()}</strong></div>
                                                <i class="ml-auto p-2 bd-highlight">Order Date: {$orderFeedback->getPrettyOrderTime()}</i>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text mb-auto">{$orderFeedback->getFeedback()}</p>
                                        </div>
                                        <div class="card-footer">
EOT;
                                        $orderID = $orderFeedback->getOrderID();

                                        foreach($orderManager->__retrieveOrderLines($orderID) as $orderLine){
                                            echo "<small><strong>". $orderLine->getQuantity() . "x </strong>\"" . $orderLine->getProductSummary() . "\"</small> <br>";
                                        }
                                        echo <<< EOT
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
EOT;
                    }
                    ?>
                </div>
			</div>
        </div> 
	
	</body>
	
</html>