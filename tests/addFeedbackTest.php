<?php
use PHPUnit\Framework\TestCase;

    class addFeedbackTest extends TestCase{
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

        protected function setUp(): void {
            $timestamp = time();
            $this->order = [
                "orderID" => null,
                "ordertime" => "2020-05-11",
                "orderstatus" => "pending",
                "totalprice" => 25.9,
                "paymentmethod" => "paypal",
                "shippingaddress" => "123 Abc St",
                "customer_username" => $this->username
            ];

            $this->order["orderid"] = $this->_insertOrder($this->order);
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
            $sql = "insert into orders (ordertime, orderstatus, totalprice, paymentmethod, shippingaddress, customer_username)";
            $sql .= " values (date'$ordertime', '$orderstatus', $totalprice, '$paymentmethod', '$shippingaddress', '$customer_username') RETURNING ORDERID INTO :orderID";
            $stid = oci_parse(self::$conn, $sql);
            oci_bind_by_name($stid, ":orderID", $this->order["orderid"], 40);
            oci_execute($stid);
            
            return $this->order["orderid"];
        }

        public static function tearDownAfterClass(): void
        {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testAddFeedback(): void{
            self::$orderMgr->__addFeedback($this->order["orderid"], $this->feedback);
            $orders = self::$orderMgr->__retireveAllOrder($this->username);
            $order = NULL;
            foreach ($orders as $o) {
                if ($o->getOrderID() == $this->order["orderid"]) {
                    $order = $o;
                    break;
                }
            }
            $this->assertNotNull($order);
            $this->assertEquals($this->feedback, $order->getFeedback());
        }
    }
?>