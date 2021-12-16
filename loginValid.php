<?php
    session_start();

    require_once("shared/DBManager.php");
    require_once("shared/UserManager.php");
    require_once("shared/utils/AuthUtil.php");
    require_once("shared/utils/MessageUtil.php");
    require_once("shared/SystemManager.php");
    $dbMgr = new DBManager();
    $conn = $dbMgr->getConn();
    $sysMgr = new SystemManager();
    $role = $sysMgr->getRole();

    $uMgr = $role == "customer" ? new CustomerManager($conn) : new AdminManager($conn);
    
    $authUtil = new AuthUtil();
    $msgUtil = new MessageUtil();

    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $success = $uMgr->validateLogin($username, $password);
    if ($success) {
        $authUtil->loginUser($role, $uMgr->getUsernameInDB());
        header('Location: index.php');
    } else {
        $msgUtil->addMessage("loginError", "Username or password incorrect, try again!");
        header("Location: login.php");
    }

    $dbMgr->returnConn($conn);

?>
