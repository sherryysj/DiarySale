<?php
use PHPUnit\Framework\TestCase;

    class viewFeedbackTest extends TestCase{
        private static $orderMgr;
        private static $dbMgr;
        private static $conn;
        protected $username = "therealgel";
        protected $productid = "P001";
        protected $feedback = "Love it";
        
        public static function setUpBeforeClass(): void
        {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
            self::$orderMgr = new OrderManager(self::$conn);
        }

        public static function tearDownAfterClass(): void
        {
            self::$dbMgr->returnConn(self::$conn);
        }

        protected function setUp(): void {
            $timestamp = time();
            $this->order = [
                "orderid" => "O$timestamp",
                "ordertime" => "2020-05-11",
                "orderstatus" => "pending",
                "totalprice" => 25.9,
                "paymentmethod" => "paypal",
                "shippingaddress" => "123 Abc St",
                "customer_username" => $this->username
            ];
            $this->_insertOrder($this->order);
        }

        protected function tearDown(): void
        {        
            $sql = "delete from orders where orderid = '{$this->order['orderid']}'";
            $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);
        }

        protected function _insertOrder($o) {
            foreach ($o as $key => $val) {
                $$key = $val;
            }
            $sql = "insert into orders (orderid, ordertime, orderstatus, totalprice, paymentmethod, shippingaddress, customer_username)";
            $sql .= " values ('$orderid', date'$ordertime', '$orderstatus', $totalprice, '$paymentmethod', '$shippingaddress', '$customer_username')";
            $stid = oci_parse(self::$conn, $sql);
            return oci_execute($stid);
        }
		
        public function testEmptyFeedback(): void{
            $allFeedback = self::$orderMgr->__retrieveAllOrderFeedback();
            $feedback = null;
            foreach($allFeedback as $fb){
                $feedback = $fb->getFeedback();
            $this->assertNotEquals(null, $feedback);
            }
        }
		
		public function testFeedbackRetrive():void{
			$orderID = self::$orderMgr->__createOrder(10, 'test feedback', 'test', 'Standard', 'sherryCustomer');
			self::$orderMgr->__addFeedback($orderID, 'test feedback retrive');
		    $allFeedback = self::$orderMgr->__retrieveAllOrderFeedback();
            foreach($allFeedback as $fb){
				if($fb->getOrderID() == $orderID){	
                    $feedback = $fb->getFeedback();
				    $this->assertEquals('test feedback retrive', $feedback);
				}  
            }
		}
    }
?>