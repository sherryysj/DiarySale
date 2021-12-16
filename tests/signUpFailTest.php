<?php 

    use PHPUnit\Framework\TestCase; 
    // require("shared/DBManager.php");
    // require("shared/UserManager.php");

           /*
          prerequisites: 
                a customer with username "sherrycustomer" already signup
                a customer with username "sherryCustomer" already signup
                a customer with username "sherryCustomer1234" is not signup
          dependencies:
                DBManager works
          coverage:
                CustomerManager->checkUniqueID
         */

    class UsernameTest extends TestCase
    {       
                private static $dbMgr;
         private static $conn;
         private static $cMgr;
 
        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
                        self::$cMgr = new CustomerManager(self::$conn);
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testCannotUseDuplicateUsername(): void
        {
                        
            $this->assertFalse(
                self::$cMgr->checkUniqueID("sherryCustomer")
            );

        }

        public function testCannotUseDuplicateUsername2(): void
        {

            $this->assertFalse(
                self::$cMgr->checkUniqueID("sherrycustomer")
            );
        }

        public function testInduplicateUsernameCanUse(): void
        {
                        
            $this->assertTrue(
                self::$cMgr->checkUniqueID("sherryCustomer1234")
            );

        }


    }

?>