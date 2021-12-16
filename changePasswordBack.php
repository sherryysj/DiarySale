 <?php
    session_start();

    require_once("shared/DBManager.php");
    require_once("shared/UserManager.php");
    require_once("shared/utils/AuthUtil.php");
    require_once("shared/utils/MessageUtil.php");
    require_once("shared/SystemManager.php");
    $authUtil = new AuthUtil();
    if (!(new AuthUtil())->isLoggedIn()) {
        header("Location: login.php");
    }

    $dbMgr = new DBManager();
    $conn = $dbMgr->getConn();
    $sysMgr = new SystemManager();
    $role = $sysMgr->getRole();
    $uMgr = $role == "customer" ? new CustomerManager($conn) : new AdminManager($conn);
    
    $msgUtil = new MessageUtil();

    $oldPassword = isset($_POST["oldPassword"]) ? $_POST["oldPassword"] : "";
    $newPassword = isset($_POST["newPassword"]) ? $_POST["newPassword"] : "";
	$username = $authUtil->getUsername();
    $oldPasswordRight = $uMgr->validatePassword($username, $oldPassword);
	
    if ($oldPasswordRight) {
		$uMgr->changePassword($username, $newPassword);
		$msgUtil->addMessage("passwordChanged", "Password successfully changed!");
    } else {
		$msgUtil->addMessage("oldPasswordError", "Old password is incorrect, please enter correct old password!");
    }

	header("Location: changePassword.php");

    $dbMgr->returnConn($conn);

?>