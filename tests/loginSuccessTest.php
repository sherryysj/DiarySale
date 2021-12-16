<?php
    use PHPUnit\Framework\TestCase;
    
    /*
    prerequisites: 
      a customer with username "testLFC" and password "ABC123abc" exists
      an admin with username "testLFA" and password "Abcd1234" exists
    dependencies:
      DBManager works
    coverage:
      AdminManager->validateLogin
      CustomerManager->validateLogin
    */

    class LoginSuccessTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        protected $adminCred = ["testLFA", "Abcd1234"];
        protected $customerCred = ["testLFC", "ABC123abc"];

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testCustomerLoginSuccess(): void {
            $cMgr = new CustomerManager(self::$conn);
            $success = $cMgr->validateLogin($this->customerCred[0], $this->customerCred[1]);
            $this->assertTrue($success);
        }
        
        public function testAdminLoginSuccess(): void {
            $aMgr = new AdminManager(self::$conn);
            $success = $aMgr->validateLogin($this->adminCred[0], $this->adminCred[1]);
            $this->assertTrue($success);
        }
    }
?>