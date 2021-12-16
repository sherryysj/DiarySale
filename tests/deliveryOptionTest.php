<?php
    use PHPUnit\Framework\TestCase;
	
    class deliveryOptionTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        private static $orderMgr;

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
			self::$orderMgr = new OrderManager(self::$conn);
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testExpressDeliverySuccess(): void {
			$orderID = self::$orderMgr->__createOrder(10, 'test express', 'test', 'Express', 'sherryCustomer');
			$query = "SELECT DELIVERYMETHOD from orders WHERE ORDERID = :orderID";
			$stid = oci_parse(self::$conn, $query);
            oci_bind_by_name($stid, ":orderID", $orderID);
            oci_execute($stid);
			
			if(($row = oci_fetch_array($stid, OCI_ASSOC))) {
               $deliverMethod = $row["DELIVERYMETHOD"]; 
            }
			$this->assertEquals('Express',$deliverMethod);
            
        }
        
        public function testStandardDeliverySuccess(): void {
            $orderID = self::$orderMgr->__createOrder(10, 'test standard', 'test', 'Standard', 'sherryCustomer');
			$query = "SELECT DELIVERYMETHOD from orders WHERE ORDERID = :orderID";
			$stid = oci_parse(self::$conn, $query);
            oci_bind_by_name($stid, ":orderID", $orderID);
            oci_execute($stid);
			
			if(($row = oci_fetch_array($stid, OCI_ASSOC))) {
               $deliverMethod = $row["DELIVERYMETHOD"]; 
            }
			$this->assertEquals('Standard',$deliverMethod);
        }
    }
	
?>