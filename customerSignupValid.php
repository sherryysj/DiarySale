<!--
TO DO: check database for existing email address before creating account
-->

<?php
    session_start();
    
    require_once("shared/DBManager.php");
    require_once("shared/UserManager.php");
    require_once("shared/utils/MessageUtil.php");
    $dbMgr = new DBManager();
    $conn = $dbMgr->getConn();
    $cMgr = new CustomerManager($conn);
    $msgUtil = new MessageUtil();

    const ROLE = "customer";
    $status = "active";


    //make global variables of all posted values
    foreach ($_POST as $p_name => $p_value){
        $$p_name = $p_value ? $p_value : "";
    }

    //combine first and last name for database field
    $name = $firstname . ' ' . $lastname;

    //address is combination of above variables 
    $mailAddress  = (isset($street)?$street : "") ."::" . (isset($city)?$city : "") . "::" .( isset($state)?$state : "") . "::" . (isset($postcode)?$postcode : "");

    $isUniqueID = $cMgr->checkUniqueID($username);
    if ($isUniqueID) {
        if ($cMgr->createNewAccount($username, $password, $name, $primaryShippingAddress, $mailAddress, $email, $status)){
            $msgUtil->addMessage("accountCreated", "Account created - please log in.");
            header('Location: login.php');
        } else {
            $msgUtil->addMessage('signupError', "Failed to create an account - Database error");
            header('Location: customerSignUp.php');
        } 
    } else {
        $msgUtil->addMessage('signupError', "Username is already taken!");
        header('Location: customerSignUp.php');
    }

    $dbMgr->returnConn($conn);
?>
