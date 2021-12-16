<?php

use PHPUnit\Framework\TestCase;

final class signUpTest extends TestCase{
    //Account information without the 'username'
    protected $newUserAccount = ["passme", "Matt Natures", "primaryShipping", "Own St, MelTra VIC 3000", "Matt@gmail.com", "Active"];
    private static $dbMgr;
    private static $cMgr;
    private static $conn;

    public static function setUpBeforeClass(): void{
        self::$dbMgr = new DBManager();
        self::$conn = self::$dbMgr->getConn();
        self::$cMgr = new CustomerManager(self::$conn);
    }

    public static function tearDownAfterClass(): void{
        self::$dbMgr->returnConn(self::$conn);
    }

    public function setUp(): void{
        self::$cMgr->createNewAccount("usertest", "password", "username", "AU", "mail", "email", "none");
    }

    public function tearDown(): void{
        $this->deleteCus();
    }

    public function deleteCus(): void{
        $sql = "delete from customer where username = 'usertest'";
        $stid = oci_parse(self::$conn, $sql);
        oci_execute($stid);
    }

    public function testUniqName(): void{
        $uniqName = self::$cMgr->checkUniqueID("usertest");
        $this->assertFalse($uniqName);
    }

    public function testSignUp(): void{
        $this->deleteCus();
        $signUp = self::$cMgr->createNewAccount("usertest", $this->newUserAccount[0], $this->newUserAccount[1], $this->newUserAccount[2], $this->newUserAccount[3], $this->newUserAccount[4], $this->newUserAccount[5]);
        $this->assertTrue($signUp);
    }
}
