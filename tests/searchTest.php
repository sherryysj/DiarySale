<?php

use PHPUnit\Framework\TestCase;

class searchTest extends TestCase{
    private static $prodMgr;
    private static $dbMgr;
    private static $conn;
    protected $existProductName = "diary";
    protected $nonexistProductName = "cheeseball";

    public static function setUpBeforeClass(): void
    {
        self::$dbMgr = new DBManager();
        self::$conn = self::$dbMgr->getConn();
        self::$prodMgr = new ProductManager(self::$conn);
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbMgr->returnConn(self::$conn);
    }

    public function testExistSearch(): void{
       $result = self::$prodMgr->__searchProducts($this->existProductName);
       $this->assertEquals(sizeof($result), 4);
    }

    public function testNonexistSearch(): void{
        $result = self::$prodMgr->__searchProducts($this->nonexistProductName);
        $this->assertEquals(sizeof($result), 0);
     }
}
