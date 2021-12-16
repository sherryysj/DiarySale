<!DOCTYPE html>

<?php
    // OOP needed

    require_once("shared/DBManager.php");
    require_once("shared/ProductManager.php");
    require_once("shared/Parameter.php");
    require_once("shared/Option.php");
    require_once("shared/utils/TransitUtil.php");

    $tranUtil = new TransitUtil();
    $productId = $_GET["productId"];
    $namesArr = $tranUtil->popData("productNames");
    if (!isset($namesArr[$productId])) {
        header("Location: productBrowsing.php");
        exit();
    }
    $productName = $namesArr[$productId];

    $databaseManager = new DBManager();
    $conn = $databaseManager->getConn();
    $productManager = new ProductManager($conn);
    $parameterList = $productManager->__retriveAllParameters($productId);
    $parameterOptionsMap = [];
    $optionMap = [];
    $customOptions = [];
    foreach ($parameterList as $parameter) {
        $name = $parameter->getParameterName();
        $options = $productManager->__retriveAllAvailableOptions($name);
        $parameterOptionsMap[$name] = $options;
        foreach ($options as $option)
            $optionMap[$option->getOptionName()] = $option->toArr();
            if ($option->isCustomizeable()){
                $customOptions[$option->getOptionName()] = $option->getCustomText();
            }
    }
    $tranUtil = new TransitUtil();
    $tranUtil->addData("optionMap", $optionMap);
    $tranUtil->addData("customMap", $customOptions);
?> 

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Customize Product</title>
        <?php require("shared/_imports.php") ?>

 
        <script type="text/javascript">
            var selectedOptions = {};

            function setParam(s) {
                var e = document.getElementById(s);
                //id is preceeded by "opt_" so need to get the substring for true value name
                var name = e.options[e.selectedIndex].id;
                name = name.substring(4);

                var value = e.options[e.selectedIndex].getAttribute("data-price");

                var customInput = e.options[e.selectedIndex].getAttribute("data-customText");

                if (customInput == 'Y'){
                    if (document.getElementById("customText_" + s)){
                        document.getElementById("customText_" + s).style.display = "block";
                    }
                    
                } else if (customInput == 'N') {
                    if (document.getElementById("customText_" + s)){
                        document.getElementById("customText_" + s).style.display = "none";
                        document.getElementById("customText_" + s).value = "";
                    }
                }


                selectedOptions[s] = {name, value};

                document.getElementById("selected_" + s).innerHTML = name;
                document.getElementById("price_" + s).innerHTML = "$" + parseFloat(value).toFixed(2);
                updateTotalPrice();
            }

            function updateTotalPrice(){
                var totalPrice = 0.00;
                for (var key in selectedOptions){
                    totalPrice += parseFloat(selectedOptions[key]["value"]);
                }
                document.getElementById("totalPrice").innerHTML = "$" + parseFloat(totalPrice).toFixed(2);
                //updateAddButton();
            }

            //function to disable cart button if the product costs 0 money
            function updateAddButton(){
                if (document.getElementById("totalPrice").innerHTML == "$0.00"){
                    document.getElementById("add-to-cart-btn").disabled = true;
                } else {
                    document.getElementById("add-to-cart-btn").disabled = false;
                }
            }

            function updateCustomText(s){
                var e = document.getElementById(s);
                
            }
        </script>


    </head>

<body class="bg-light">
    <?php require("shared/_navBar.php") ?>
    <div class="container">
	
        <div class="py-5 text-center">
            <h2>Customize Your <?php echo $productName?></h2>
            <img src="static\img\DiaryQueenLogo.png" class="img-thumbnail" alt="Product Image">
            <p class="lead">Choose from the options below and we will customize your <?php echo $productName?> to your liking! </p>
        </div>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your <?php echo $productName?> :</span>
                </h4>
                <ul class="list-group mb-3">
				    <?php
                        // OOP needed
                        $chosenOptions = array();
                        for($i = 0; $i < sizeof($parameterList); $i++) {
                            //temporary values - just to ensure no null values
                            $chosenOptions[$i] = new Option(".", -1, 1.0, true, false);
                            $paramName = $parameterList[$i]->getParameterName();
					        echo <<<EOT
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    
                                    <h6 class="my-0" >{$paramName}</h6>
                                    <small class="text-muted" id="selected_{$paramName}">{$chosenOptions[$i]->getOptionName()}</small>
                                </div>
                                <div>
                                <span class="text-muted" id="price_{$paramName}">$0.00</span>
                                </div>
                            </li>
EOT;
                        }
                    ?>    
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (AUD)</span>
                        <strong><span id="totalPrice">$0.00</span></strong>
                    </li>
                </ul>			
            </div>
			
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Customization Options</h4>
                <form class="needs-validation" action="addToCart.php" method="post">
                <input hidden name="productId" value="<?php echo $productId ?>">
                <input hidden name="productName" value="<?php echo $productName ?>">
				
                <?php
                    foreach ($parameterOptionsMap as $paramName => $paramOptions) {
                        $anyCustomFields = false;
                        echo <<<EOT
                        <div class="mb-3">
                            <label for="paramName">{$paramName} </label>
                            <select class="custom-select d-block w-100" id="{$paramName}" oninput="setParam(this.id);" name="{$paramName}" required>
EOT;
                        echo "<option id=\"default\" value=\"\">Choose an option..</option>";
                        foreach ($paramOptions as $option) {
                            if (!$option->getAvailability())
                                continue;
                            $optionName = $option->getOptionName();
                            $optionPrice= $option->getPrice();

                            $needsInput = 'N';
                            if ($option->isCustomizeable()){
                                $needsInput = 'Y';
                                $anyCustomFields = true;
                            }
                            echo "<option id=\"opt_$optionName\" value=\"$optionName\" data-customText=\"$needsInput\" data-price=\"$optionPrice\">$optionName - ". "$" . "$optionPrice</option>";
                        }
                        echo <<<EOT
                        </select>   
                        </div>
EOT;
                        if ($anyCustomFields){
                            echo "<input placeholder=\"Enter Custom Text..\" value=\"\" name=\"customText_$paramName\" id=\"customText_$paramName\" style=\"display:none; \" class=\"form-control col-8\"></input>";
                        }
                    }
                ?>
				
				<hr class="mb-2">
				<div class="col-md-2">
                    <label for="quantity">Quantity</label>
                    <input name="quantity" type="number" class="form-control" min="1" id="quantity" value="1" required="">
                    <div class="invalid-feedback">
                        Please enter a valid quantity.
                    </div>
                </div>

                <hr class="mb-4">
                <button id="add-to-cart-btn" class="btn btn-primary btn-lg btn-block" type="submit">Add to cart</button>
                </form>
            </div>
        </div>
		
    </div>

</body>

<footer><p class="invisible">SEPM A2 P3_3</p></footer>



</html>