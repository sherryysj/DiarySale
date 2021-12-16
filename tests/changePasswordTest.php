<?php
    use PHPUnit\Framework\TestCase;

    class ChangePasswordTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        private static $cMgr;
        private static $aMgr;
        protected $customerCred = ["username", "Password8"];
        protected $adminCred = ["testAdmin", "Password8"];

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
        }

        public function setup(): void {
            self::$cMgr = new CustomerManager(self::$conn);
            self::$aMgr = new AdminManager(self::$conn);
        }

        public function tearDown(): void {
            self::$cMgr->changePassword($this->customerCred[0], $this->customerCred[1]);
            self::$aMgr->changePassword($this->adminCred[0], $this->adminCred[1]);
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testCustomerChangePasswordSuccess(): void {
            $newPassword = "Password9";

            $isLoginSuccessful = self::$cMgr->validateLogin($this->customerCred[0], $this->customerCred[1]);
            $this->assertTrue($isLoginSuccessful);
            
            self::$cMgr->changePassword($this->customerCred[0], $newPassword);
            $isNewPasswordSuccessful = self::$cMgr->validateLogin($this->customerCred[0], $newPassword);
            $this->assertTrue($isNewPasswordSuccessful);

            $isOldPasswordSuccessful = self::$cMgr->validateLogin($this->customerCred[0], $this->customerCred[1]);
            $this->assertFalse($isOldPasswordSuccessful);
        }
        
        public function testAdminChangePasswordSuccess(): void {
            $newPassword = "Password9";

            $isLoginSuccessful = self::$aMgr->validateLogin($this->adminCred[0], $this->adminCred[1]);
            $this->assertTrue($isLoginSuccessful);
            
            self::$aMgr->changePassword($this->adminCred[0], $newPassword);
            $isNewPasswordSuccessful = self::$aMgr->validateLogin($this->adminCred[0], $newPassword);
            $this->assertTrue($isNewPasswordSuccessful);

            $isOldPasswordSuccessful = self::$aMgr->validateLogin($this->adminCred[0], $this->adminCred[1]);
            $this->assertFalse($isOldPasswordSuccessful);
        }
    }
?>