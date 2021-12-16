<?php
    use PHPUnit\Framework\TestCase;
    
    /*
    prerequisites: 
      a customer with username "testLFC" and password "Abcd1234" exists
      an admin with username "testLFA" and password "Abcd1234" exists
    dependencies:
      DBManager works
    coverage:
      AdminManager->validateLogin
      CustomerManager->validateLogin
    */

    class LoginFailureTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        protected $adminCred = ["testLFC", "Abcd1234"];
        protected $customerCred = ["testLFA", "Abcd1234"];

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testCustomerLoginWithWrongPassword(): void {
            $cMgr = new CustomerManager(self::$conn);
            $wrongPassword = $this->customerCred[1] . "Wrong";
            $success = $cMgr->validateLogin($this->customerCred[0], $wrongPassword);
            $this->assertFalse($success);
        }

        public function testAdminLoginWithWrongPassword(): void {
            $aMgr = new AdminManager(self::$conn);
            $wrongPassword = $this->adminCred[1] . "Wrong";
            $success = $aMgr->validateLogin($this->adminCred[0], $wrongPassword);
            $this->assertFalse($success);
        }

        public function testCustomerLoginWithNonExistentUsername(): void {
            $cMgr = new CustomerManager(self::$conn);
            $timestamp = time();
            $neUsername = "user$timestamp";
            $success = $cMgr->validateLogin($neUsername, "Abcd1234");
            $this->assertFalse($success);
        }

        public function testAdminLoginWithNonExistentUsername(): void {
            $aMgr = new AdminManager(self::$conn);
            $timestamp = time();
            $neUsername = "user$timestamp";
            $success = $aMgr->validateLogin($neUsername, "Abcd1234");
            $this->assertFalse($success);
        }

    }
?>