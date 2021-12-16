<?php 
    require_once("shared/utils/CartUtil.php");
    require_once("shared/utils/AuthUtil.php");
    require_once("shared/utils/TransitUtil.php");

    $tranUtil = new TransitUtil();

    // $customText = $tranUtil->popData("customMap");


    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }

    $cartUtil = new CartUtil();
    $orderLineArr = $cartUtil->getCart();

?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Shopping Cart</title>
        <?php require("shared/_imports.php") ?>
    </head>

<body class="bg-light">
    <?php require("shared/_navBar.php") ?>
    <div class="container">
	
        <div class="py-5 text-center">
            <h2>Confirm your choices</h2>
        </div>

            <div class="col-md-12 order-md-2 mb-6">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your shopping cart</span>
                </h4>
                
                <form method="post" action="shoppingCartBack.php">
                
                <ul class="list-group mb-6">
				    
                    <?php 
                        if ($orderLineArr== null){
                            echo 'Your cart is empty';
                        }
                        $cartPrice = 0;
                        foreach ($orderLineArr as $idInCart => $orderLine) {
                            $product = $orderLine["product"];
                            $options = $orderLine["options"];                            
                            $quantity = $orderLine["quantity"];
                            $customText = $orderLine["customText"];
                            $unitPrice = $cartUtil->getUnitPrice($options);
                            $totalPrice = $unitPrice * $quantity;
                            $cartPrice += $totalPrice;
                            echo <<<EOT
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                            
                                
                                <div class="col-sm-3">
                                <img src="static\img\DiaryQueenLogo.png" class="img-thumbnail" alt="Product Image">
                                </div>
                                <div class="col-md-8">
                                <div class="row ">
                                    <div class="col-md-8">
                                        <h6 class="my-0">{$product["productName"]}</h6>
                                        <hr class="mb-2 invisible">
                                        <small class="text-muted">{$cartUtil->getSummary($options)}</small>
                                        <hr class="mb-2 invisible">
                                        <small class="text-muted">{$cartUtil->getCustomText($customText)}</small>
                                    </div>
                                
                                <div class="col-md-3">
                                    <label for="amount" class="row col-form-label">quantity</label>
                                    <div class="row">
                                        <input name="$idInCart" value="$quantity" type="number" class="form-control " id="amount" placeholder="1" required="">
                                    </div>	
                                </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row">
                                
                                <div class="col-md-8">
                                <span class="text-muted">\${$unitPrice}/each</span>
                                </div>
                                
                                <div class="col-md-3">
                                <span class="text-muted">Total: \${$totalPrice}</span>
                                </div>
                                </div>
                                </div>
                            
                            </li>  
EOT;
                        }
                    ?>		
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (AUD)</span>
                        <strong><?php echo '$' . $cartPrice ?></strong>
                    </li>
					
                </ul>	

                <hr class="mb-4">
				
				<div class="row">
				
				    <div class="col-md-3">
				        <!-- <form method="post" action="checkout.php"> -->
                            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Checkout" name="submit">
                        <!-- </form>		 -->
                    </div>
					
                    <div class="col-md-4">
                        <input class="btn btn-primary btn-lg btn-block" type="submit" name="save" value="Update Shopping Cart">
                    </div>					
                	
                </div>
                
                </form>
            </div>
	</div>


</body>


</html>