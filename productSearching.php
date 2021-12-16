<?php
    require("shared/DBManager.php");
    require("shared/ProductManager.php");
    require("shared/OrderManager.php");
    require("shared/Product.php");
    require("shared/Order.php");
    require_once("shared/utils/TransitUtil.php");
    $databaseManager = new DBManager();
    $conn = $databaseManager->getConn();
    $productManager = new ProductManager($conn);
    $orderManager = new OrderManager($conn);
    $tranUtil = new TransitUtil();

    $productName = $_POST['productSearch'];
    $productNames = [];
    $searchResult = $productManager->__searchProducts($productName);
    $productFound = sizeof($searchResult);
    foreach ($searchResult as $product) {
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
            <h3>Search for<?php echo " '", $productName, "'"?></h3>
            <h6>Showing result for <?php echo $productFound, " "?>products</h6>
            <div class="row mb-3 text-center">
		        <?php
                foreach ($searchResult as $product) {
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

               if($productFound < 1){
                   echo <<<EOT
                   <h5 style="color:red"> Sorry. Your search for '$productName' did not match any items.</h5>
EOT;
               }
		        ?>        

            </div>
			
        </div> 
	
	</body>
	
</html>