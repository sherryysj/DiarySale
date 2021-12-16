<!--
TO DO: pass Customer object back here to refill forms on account creation failure
-->

<?php
    // prerequisite: set $formAction -- where to post the form data
    if (!isset($formAction))
        echo "error!";
    require_once("shared/utils/MessageUtil.php");
    $msgUtil = new MessageUtil();
?>
<form method="post" action=
    <?php echo "$formAction"; ?>
>
    <?php
        $msgUtil->flashHtmlErrorIfAny("signupError");
    ?>

    <div class="card">
        <div class="card-body">
            <div class="form-group row" >
                <label for="mailAddress" class="col-md-6 control-label"><b>Basic Information</b></label>
            </div>
            <div class="form-group row" >
                <label for="firstname" class="col-md-2 control-label">First Name</label>
                <input id="firstname" type="text" name="firstname" class="col-md-2 form-control" required>                
            </div>
            <div class="form-group row">
                <label for="lastname" class="col-md-2 control-label">Last Name</label>
                <input id="lastname" type='text' name="lastname" class='col-md-2 form-control' required>
            </div>
            <div class="form-group row">
                <label for="email" class="col-md-2 control-label">Email</label>
                <input id="email" type='email' name="email" class='col-md-4 form-control' required>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="mailAddress" class="col-md-6 control-label"><b>Billing Address</b></label>
            </div> 
            <div class="form-group row">
                <label for="street" class="col-md-2 control-label">Street Address</label>
                <input id="street" type='text' name="street" pattern="[^:]+" class='col-md-4 form-control' required>
            </div> 
            <div class="form-group row">
                <label for="city" class="col-md-2 control-label">City/Town</label>
                <input id="city" type='text' name="city" pattern="[^:]+" class='col-md-3 form-control' required>
            </div> 
            <div class="form-group row">
                <label for="state" class="col-md-2 control-label">State</label>
                <select id="state" type="text" name="state">
                    <option value="NSW">NSW</option>
                    <option value="VIC">VIC</option>
                    <option value="QLD">QLD</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                    <option value="NT">NT</option>
                </select>
            </div> 
            <div class="form-group row">
                <label for="postcode" class="col-md-2 control-label">Postcode</label>
                <input id="postcode" type='text' name="postcode" pattern="[0-9]{4}" title="Postcode must be 4 digits" class='col-md-2 form-control' required>
            </div> 

        </div>

    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="siteinfo" class="col-md-6 control-label"><b>Site Information</b></label>
            </div> 
            <div class="form-group row">
                <label for="username" class="col-md-2 control-label">Username</label>
                <input id="username" type='text' name="username" class='col-md-3 form-control' required>
            </div>
            <div class="form-group row">
                <label for="password" class="col-md-2 control-label">Password</label>
                <input id="password" type='password' name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain 8 or more letters - at least one uppercase, lowercase" class='col-md-3 form-control' required>
            </div>
        </div>
    </div>

    <button type="submit" class='btn btn-primary'>Submit</button>
</form>