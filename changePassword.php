<?php
    require_once("shared/utils/MessageUtil.php");
    require_once("shared/utils/AuthUtil.php");

    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }

    $msgUtil = new MessageUtil();
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Change Password</title>
        <?php require("shared/_imports.php") ?>
    </head>
    <body>
        <?php require("shared/_navBar.php") ?>
		<div class="container">
            <form method="post" action="changePasswordBack.php"> 
                <div class="form-group row">
                    <label for="siteinfo" class="col-md-6 control-label"><b>Change Password</b></label>
                </div> 
			
                <div class="form-group row">
                    <label for="password" class="col-md-2 control-label">Old Password</label>
                    <input id="oldPassword" type='password' name="oldPassword" class='col-md-3 form-control' required>
                </div>
    
                <div class="form-group row">
                    <label for="password" class="col-md-2 control-label">New Password</label>
                    <input id="newPassword" type='password' name="newPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain 8 or more letters - at least one uppercase, lowercase" class='col-md-3 form-control' required>
                </div>
                <?php
                    $msgUtil->flashHtmlErrorIfAny("oldPasswordError");
					$msgUtil->flashHtmlSuccessIfAny("passwordChanged");
                ?>
			    <button type="submit" class='btn btn-primary'>Submit</button>
			</form>
        </div>	
	
	</body>
</html>