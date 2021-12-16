<?php
require_once("shared/Country.php");
require_once("shared/utils/CartUtil.php");
require_once("shared/utils/AuthUtil.php");

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
  <title>Checkout</title>
  <?php require("shared/_imports.php") ?>

  <script type="text/javascript">
    function getTotalCartPrice() {
      var productPrice = document.getElementById('totalCartPrice').innerHTML.substring(1);
      document.getElementById('storeCartPrice').innerHTML = productPrice;
    }

    function setPaymentType(payment_div_id) {
      var allPayMethods = document.getElementsByName("paymentOption");

      console.log(allPayMethods);
      for (i = 0; i < allPayMethods.length; i++) {
        allPayMethods[i].style.display = "none";
      }

      document.getElementById(payment_div_id).style.display = "block";
    }

    function setDeliveryPrice() {
      var dPrice;
      var price = parseFloat(document.getElementById('storeCartPrice').innerHTML);
      if (document.getElementById('standard').checked) {
        dPrice = parseFloat(document.getElementById('standard').value);

        document.getElementById('deliveryOption').innerHTML = 'Standard Delivery';
        document.getElementById('chosenDeliveryMethod').value = 'Standard';
      } else {
        dPrice = parseFloat(document.getElementById('express').value);
        document.getElementById('deliveryOption').innerHTML = 'Express Delivery';
        document.getElementById('chosenDeliveryMethod').value = 'Express';

      }
      document.getElementById('delivery-cost').innerHTML = "$" + dPrice.toFixed(2);

      document.getElementById('totalCartPrice').innerHTML = '$' + (price + dPrice).toFixed(2);
    }
  </script>
</head>

<body class="bg-light" onload="getTotalCartPrice(); setDeliveryPrice(); setPaymentType('credit-payment');">
  <?php require("shared/_navBar.php") ?>
  <?php
  require_once("shared/utils/MessageUtil.php");
  $msgUtil = new MessageUtil();
  $msgUtil->flashHtmlErrorIfAny("order_fail");
  ?>
  <div class="container">
    <div class="py-5 text-center">
      <h2>Checkout</h2>
      <p class="lead">Please fill below form to checkout.</p>
    </div>

    <div class="row">
      <div class="col-md-4 order-md-2 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted" id="tryme">Your cart</span>
          <label hidden id="storeCartPrice"></label>
          <span class="badge badge-secondary badge-pill" id="totalCartPrice">$<?php echo $cartUtil->getTotalPrice() ?></span>
        </h4>
        <ul class="list-group mb-3">


          <?php
          foreach ($orderLineArr as $idInCart => $orderLine) {
            $product = $orderLine["product"];
            $options = $orderLine["options"];
            $quantity = $orderLine["quantity"];
            $customText = $orderLine["customText"];
            $unitPrice = $cartUtil->getUnitPrice($options);
            $totalPrice = $unitPrice * $quantity;
            // $cartPrice += $totalPrice;
            echo <<<EOT
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                  <div>
                    <h6 class="my-0">{$product["productName"]}</h6>
                    <small class="text-muted">{$cartUtil->getSummary($options)}</small>
EOT;
                    if ($cartUtil->getCustomText($customText) != ""){
                      echo '<hr class="mb-2">';
                      echo '<small class="text-muted">' . $cartUtil->getCustomText($customText) . ' </small>';
                    }
            echo <<<EOT

                  </div>
                  <span class="text">\${$totalPrice}</span>
                  <span class="text-muted">x{$quantity}</span>

                </li>

EOT;
          }
          ?>
          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <?php
            echo <<<EOT
              <div> <h6 class="my-0">Delivery</h6>
              <small class="text-muted" id="deliveryOption"></small>
              </div>
              <span class="text-muted" id="delivery-cost">
                  {$_SESSION["delivery"]["std"]}
              </span>
EOT;
            ?>
          </li>
        </ul>
      </div>
      <div class="col-md-8 order-md-1">
        <h4 class="mb-3">Shipping address</h4>
        <form class="needs-validation" method="POST" action="checkoutValid.php">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="firstName">First name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" pattern="[^:]+" placeholder="" value="" required="">
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="lastName">Last name</label>
              <input type="text" class="form-control" id="lastName" name="lastName" pattern="[^:]+" placeholder="" value="" required="">
              <div class="invalid-feedback">
                Valid last name is required.
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="street">Street</label>
            <input type="text" class="form-control" id="street" name="street" pattern="[^:]+" placeholder="1234 Main St" required="">
            <div class="invalid-feedback">
              Please enter your shipping address.
            </div>
          </div>

          <div class="mb-3">
            <label for="city">City/Town <span class="text-muted"></span></label>
            <input type="text" class="form-control" id="city" name="city" pattern="[^:]+" placeholder="">
          </div>

          <div class="row">
            <div class="col-md-5 mb-3">
              <label for="country">Country</label>
              <select class="custom-select d-block w-100" id="country" name="country" pattern="[^:]" required="">
                <option value="">Choose...</option>
                <?php
                foreach (Country::$countryArr as $country) {
                  echo <<<EOT
                    <option value="$country">$country</option>
EOT;
                }
                ?>
              </select>
              <div class="invalid-feedback">
                Please select a valid country.
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <label for="state">State</label>
              <select class="custom-select d-block w-100" id="state" name="state" required="">
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
                <option value="NT">NT</option>
              </select>
              <div class="invalid-feedback">
                Please provide a valid state.
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <label for="postcode">Postcode</label>
              <input type="text" class="form-control" id="postcode" name="postcode" pattern="[0-9]{4}" placeholder="" required="">
              <div class="invalid-feedback">
                Zip code required.
              </div>
            </div>
            <!-- <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="same-address">
            <label class="custom-control-label" for="same-address">Billing address is the same as my Shipping Address</label>
          </div> -->
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="save-info">
              <label class="custom-control-label" for="save-info">Save my shipping information for next time</label>
            </div>
          </div>
          <hr class="mb-4">
          <div class="mb-3">
            <label for="email">Send a reciept to this email address <span class="text-muted">(Optional --not functional --)</span></label>
            <input type="email" class="form-control" id="email" placeholder="you@example.com">
            <div class="invalid-feedback">
              Please enter a valid email address for shipping updates.
            </div>
          </div>
          <hr class="mb-4">

          <div class="mb-3">
            <h4 for="address2">Delivery Options</h4>

            <input hidden="true" id="chosenDeliveryMethod" name="chosenDeliveryMethod" value="Standard"> </input>
            <div class="custom-control custom-radio">
              <input id="standard" name="deliveryMethod" type="radio" class="custom-control-input" value=<?php echo $_SESSION["delivery"]["std"]; ?> onchange="setDeliveryPrice()" checked="checked" required="">
              <label class="custom-control-label" for="standard">Standard - $<?php echo $_SESSION["delivery"]["std"]; ?></label>
            </div>
            <div class="custom-control custom-radio">
              <input id="express" name="deliveryMethod" type="radio" class="custom-control-input" value=<?php echo $_SESSION["delivery"]["exp"]; ?> onchange="setDeliveryPrice()" required="">
              <label class="custom-control-label" for="express">Express - $<?php echo $_SESSION["delivery"]["exp"]; ?> </label>
            </div>

          </div>


          <hr class="mb-4">
          <h4 class="mb-3">Payment</h4>

          <!-- RADIO BUTTONS -->
          <div id="payment-radio-buttons" class="d-block my-3">
            <div class="custom-control custom-radio">
              <input id="credit" value="credit" name="paymentMethod" type="radio" class="custom-control-input" checked="" required="" onchange="setPaymentType('credit-payment');">
              <label class="custom-control-label" for="credit">Credit card</label>
            </div>
            <div class="custom-control custom-radio">
              <input id="debit" value="debit" name="paymentMethod" type="radio" class="custom-control-input" required="" onchange="setPaymentType('debit-payment');">
              <label class="custom-control-label" for="debit">Debit card</label>
            </div>
            <div class="custom-control custom-radio">
              <input id="paypal" value="paypal" name="paymentMethod" type="radio" class="custom-control-input" required="" onchange="setPaymentType('paypal-button-container');">
              <label class="custom-control-label" for="paypal">PayPal</label>
            </div>
          </div>

          <!-- END RADIO BUTTONS -->


          <!-- CREDIT CARD -->

          <div id="credit-payment" name="paymentOption" class="card bg-light mb-3">
            <div class="card-header"><strong>--Credit card payment not functional--</strong></div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="cc-name">Name on card</label>
                  <input type="text" class="form-control" id="cc-name" placeholder="John Smith">
                  <small class="text-muted">Full name as displayed on card</small>
                  <div class="invalid-feedback">
                    Name on card is required
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="cc-number">Credit card number</label>
                  <input type="text" class="form-control" id="cc-number" placeholder="1111-1111-1111-1111">
                  <div class="invalid-feedback">
                    Credit card number is required
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label for="cc-expiration">Expiration</label>
                  <input type="text" class="form-control" id="cc-expiration" placeholder="11/11">
                  <div class="invalid-feedback">
                    Expiration date required
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <label for="cc-cvv">CVV</label>
                  <input type="text" class="form-control" id="cc-cvv" placeholder="111">
                  <div class="invalid-feedback">
                    Security code required
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- END CREDIT CARD -->

          <!-- DEBIT CARD -->
          <div id="debit-payment" name="paymentOption" class="card bg-success mb-3">
            <div class="card-header"><strong>--Debit card payment not functional--</strong></div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="dc-name">Name on card</label>
                  <input type="text" class="form-control" id="dc-name" placeholder="John Smith">
                  <small class="text-muted">Full name as displayed on card</small>
                  <div class="invalid-feedback">
                    Name on card is required
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="dc-number">Debit card number</label>
                  <input type="text" class="form-control" id="dc-number" placeholder="1111-1111-1111-1111">
                  <div class="invalid-feedback">
                    Credit card number is required
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label for="dc-expiration">Expiration</label>
                  <input type="text" class="form-control" id="dc-expiration" placeholder="11/11">
                  <div class="invalid-feedback">
                    Expiration date required
                  </div>
                </div>
                <div class="col-md-3 mb-3">
                  <label for="dc-cvv">CVV</label>
                  <input type="text" class="form-control" id="dc-cvv" placeholder="111">
                  <div class="invalid-feedback">
                    Security code required
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- END DEBIT CARD -->

          <!-- PAYPAL -->
          <div id="paypal-button-container" name="paymentOption" class="form-row text-center">
            <div class="col-12">
              <button type="button" class="btn btn-lg btn-warning col-md-8">PayPal -- not functional</button>
            </div>
          </div>
          <!-- END PAYPAL -->

          <hr class="mb-4">
          <button class="btn btn-primary btn-lg btn-block" type="submit">Checkout</button>
        </form>
      </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
      <p class="mb-1">© 2020 Diary Queen</p>
    </footer>
  </div>

</body>


</html>