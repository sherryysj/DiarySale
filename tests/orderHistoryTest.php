<?php
    use PHPUnit\Framework\TestCase;
    /*
    prerequisites: 
      a customer with the username "shiyugao1" already exists
      a product with the productid "P001" already exists
    dependencies:
      DBManager works
      orders table has columns: orderid, ordertime, orderstatus, totalprice, paymentmethod, shippingaddress, customer_username
      orderline table has columns: orderlineid, quantity, price, productsummary, order_orderid, product_productid
    coverage:
      OrderManager->__retrieveAllOrder
    */

    class OrderHistoryTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        protected $orders;
        protected $username = "shiyugao1";
        protected $productid = "P001";
        protected $orderlines;

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
        }

        protected function tearDown(): void
        {
            $sql = "delete from orderline where orderlineid = '{$this->orderline['orderlineid']}'";
            $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);
        
            $sql = "delete from orders where orderid = '{$this->order['orderid']}'";
            $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);
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

            $this->orderline = [
                "orderlineid" => "OL$timestamp",
                "quantity" => 2,
                "price" => 25.9,
                "productsummary" => "paper colour: pure white; paper type: type 1",
                "order_orderid" => $this->order["orderid"],
                "product_productid" => $this->productid
            ];
            
            $this->_insertOrderline($this->orderline);
        }

        protected function _insertOrder($o) {
            foreach ($o as $key => $val) {
                $$key = $val;
            }
            $sql = "insert into orders (ordertime, orderstatus, totalprice, paymentmethod, shippingaddress, customer_username)";
            $sql .= " values (date'$ordertime', '$orderstatus', $totalprice, '$paymentmethod', '$shippingaddress', '$customer_username') RETURNING ORDERID INTO :orderID";
            $stid = oci_parse(self::$conn, $sql);
            oci_bind_by_name($stid, ":orderID", $this->order["orderID"], 40);
            oci_execute($stid);
            return $this->order["orderID"];
        }

        protected function _insertOrderline($oi) {
            foreach ($oi as $key => $val) {
                $$key = $val;
            }
            $sql = "insert into orderline (orderlineid, quantity, price, productsummary, order_orderid, product_productid)";
            $sql .= " values ('$orderlineid', '$quantity', $price, '$productsummary', '$order_orderid', '$product_productid')";
            $stid = oci_parse(self::$conn, $sql);
            return oci_execute($stid);
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }

        public function testRetrieveOrderForCustomer(): void {
            $oMgr = new OrderManager(self::$conn);
            $orders = $oMgr->__retireveAllOrder($this->username);
            $order = NULL;
            foreach ($orders as $o) {
                if ($o->getOrderID() == $this->order["orderid"]) {
                    $order = $o;
                    break;
                }
            }
            $this->assertNotNull($order);
            $this->assertEquals($this->order["totalprice"], $order->getTotalPrice());
            $this->assertEquals($this->order["shippingaddress"], $order->getShippingAddress());
        }
    }
?>