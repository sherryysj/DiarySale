<?php

use PHPUnit\Framework\TestCase;

class StorePaymentMethodTest extends TestCase{
    private static $conn;
    private static $dbMgr;
    private static $orderMgr;

    public static function setUpBeforeClass(): void {
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
            "paymentmethod" => "Credit",
            "deliveryMethod" => "Standard",
            "shippingaddress" => "UserTest_shipAddress",
            "username" => "testLFC"
        ];

    }

    //public function __createOrder($totalPrice, $shippingAddress, $paymentMethod, $deliveryMethod, $username){
    public function testCreditCardOrder(): void {
        $this->order["orderID"] = self::$orderMgr->__createOrder($this->order["totalprice"], $this->order["shippingaddress"], $this->order["paymentmethod"], $this->order["deliveryMethod"], $this->order["username"]);

        $orders = self::$orderMgr->__retireveAllOrder($this->order["username"]);
        $order = NULL;
        foreach ($orders as $o) {
            if ($o->getOrderID() == $this->order["orderID"]) {
                $order = $o;
                break;
            }
        }

        $this->assertEquals($this->order["paymentmethod"], $order->getPaymentMethod());
    }

    public function tearDown(): void {
        $sql = "DELETE FROM ORDERS WHERE ORDERID = '{$this->order["orderID"]}'";
        $stid = oci_parse(self::$conn, $sql);
        oci_execute($stid);

    }

    public static function tearDownAfterClass(): void {
        self::$dbMgr->returnConn(self::$conn);
    }

}



?>