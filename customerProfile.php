<?php
    require_once("shared/utils/AuthUtil.php");

    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Profile</title>
        <?php require("shared/_imports.php") ?>
    </head>
    <body>
        <?php require("shared/_navBar.php") ?>
        <div class="container">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your profile</span>
            </h4>
			
			<span class="text-muted">Show customer information here</span><br>
			<span class="text-muted"></span><br>
			<span class="text-muted">Show customer information here</span><br>
			<span class="text-muted">Show customer information here</span><br>
			<span class="text-muted">Show customer information here</span><br>
			<span class="text-muted">Show customer information here</span><br>
			
			<hr class="mb-4">
			<div class="col-md-3 mb-3">
                <button class="btn btn-primary btn-lg btn-block" onclick="location.href='changePassword.php'">Reset Password</button>
            </div>
            		
		</div>
	
    </body>
</html>