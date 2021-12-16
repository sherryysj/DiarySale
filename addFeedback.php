<?php
    require_once("shared/utils/AuthUtil.php");

    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }

?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopping Cart</title>
    <?php require("shared/_imports.php") ?>
    <script>
        function getOrderID() {
            var orderID = sessionStorage.getItem("orderid");
            document.getElementById("orderID").innerHTML = "for " + orderID;
            document.getElementById("postOrderID").value = orderID;
            sessionStorage.removeItem("orderid");
        }
    </script>
</head>

<body class="bg-light" onload="javascript:getOrderID()">
    <?php require("shared/_navBar.php") ?>

    <div class="container">
            <div class="py-5 text-center">
                <h2>Add Feedback</h2>
                <p class="lead" id="orderID">Show Order information here</p>
            </div>
            <form method="post" action="addFeedbackPost.php">
            <div class="container">
                <input type="hidden" id="postOrderID" name="postOrderID" value="" />
                <textarea id="Feedback" type="text" class="form-control" name="Feedback" rows="4" cols="50" placeholder="write your feedback here"></textarea>
                <div class="invalid-feedback">
                    Please enter the amount you need.
                </div>
                <br>

                <?php // BACKEND NEEDED code need to add the feedback in database to the order it belong accordingly 
                // and return to order record page
                ?>
                <div class="col-md-3">

                    <button class="btn btn-primary btn-lg btn-block" type="submit">Submit feedback</button>

                </div>
            </div>
        </form>
    </div>


</body>


</html>