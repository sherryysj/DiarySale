<?php

use PHPUnit\Framework\TestCase;

class coustomizeTextTest extends TestCase{
    private static $orderMgr;
    private static $dbMgr;
    private static $conn;
    protected $orderID = "O10362";
    protected $orderLineID = "O10362_10";


    public static function setUpBeforeClass(): void
    {
        self::$dbMgr = new DBManager();
        self::$conn = self::$dbMgr->getConn();
        self::$orderMgr = new OrderManager(self::$conn);
    }

    protected function setUp(): void{

        self::$orderMgr->__createOrderLine($this->orderLineID, 1, 29, "testing123", $this->orderID, "P001");
    }
    protected function tearDown(): void
    {
        $sql = "delete from orderline where ORDERLINEID = '{$this->orderLineID}'";
        $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);
    }
    public static function tearDownAfterClass(): void
    {
        self::$dbMgr->returnConn(self::$conn);
    }

    public function testCustomizeText(): void{
        $sql = "select * from orderline where ORDERLINEID = '{$this->orderLineID}'";
        $stid = oci_parse(self::$conn, $sql);
        oci_execute($stid);
        oci_fetch($stid);
        $this->assertEquals(oci_result($stid, 'PRODUCTSUMMARY'), "testing123");
    }
}
