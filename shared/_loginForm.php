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
        $msgUtil->flashHtmlErrorIfAny("loginError");
    ?>
    <div class="form-group row">
        <label for="username" class="col-md-2 control-label">Username</label>
        <input id="username" type='text' name="username" class='col-md-3 form-control' required>
    </div>
    <div class="form-group row">
        <label for="password" class="col-md-2 control-label">Password</label>
        <input id="password" type='password' name="password" class='col-md-3 form-control' required>
    </div>
    <button type="submit" class='btn btn-primary'>Submit</button>
    
</form>
