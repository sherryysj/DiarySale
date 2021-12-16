<?php
use PHPUnit\Framework\TestCase;

class ProductInfoTest extends TestCase
{
    private static $conn;
    private static $dbMgr;

    private static $pMgr;

    protected $products;
    protected $parameters;


    public static function setUpBeforeClass(): void {
        self::$dbMgr = new DBManager();
        self::$conn = self::$dbMgr->getConn();
        self::$pMgr = new ProductManager(self::$conn);
    }

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
        $sql = "DELETE FROM Parameter where PRODUCT_PRODUCTID LIKE 'UT/_P/_%' ESCAPE '/'";
        $stid = oci_parse(self::$conn, $sql);
        oci_execute($stid);    

        $sql = "DELETE FROM Product where PRODUCTID LIKE 'UT/_P/_%' ESCAPE '/'";
        $stid = oci_parse(self::$conn, $sql);
        oci_execute($stid);
    }

    public static function tearDownAfterClass(): void {
        self::$dbMgr->returnConn(self::$conn);
    }

    private function __insertProduct(Product $product) {
        $query = "INSERT INTO PRODUCT (productid, productname) VALUES ('{$product->getProductID()}', '{$product->getProductName()}')";
        $stid = oci_parse(self::$conn, $query);
        oci_execute($stid);
    }

    private function __insertParameter(Parameter $parameter) {
        $query = "INSERT INTO PARAMETER (parametername, product_productid) VALUES ('{$parameter->getParameterName()}', '{$parameter->getProductID()}')";
        $stid = oci_parse(self::$conn, $query);
        oci_execute($stid);
    }

    private function __getNumProducts(){
        $query = "SELECT COUNT(*) FROM PRODUCT where PRODUCTID LIKE 'UT/_P/_%' ESCAPE '/' ";
        $stid = oci_parse(self::$conn, $query);
        return oci_execute($stid);
    }

    public function testNumberOfProducts(){
        $initialCount = count(self::$pMgr->__retriveAllProducts());
        $this->insertProductsAndParameters(3, 4);
        $this->assertEquals(count(self::$pMgr->__retriveAllProducts()) - $initialCount, $this->__getNumProducts());
    }


    public function testProductParamters(){
        $this->insertProductsAndParameters(2, 5);
        foreach($this->products as $product){
            $this->assertEquals(5, count(self::$pMgr->__retriveAllParameters($product->getProductID())));
        }
    }

    private function insertProductsAndParameters($numOfProducts, $numOfParameters){
        for($i=0; $i < $numOfProducts; $i++){
            $this->products[$i] = new Product("UT_P_".$i, "Prod__". $i);
            for($j=0; $j < $numOfParameters; $j++){
                $this->parameters[$i ."". $j] = new Parameter("UT_Pm_" .$i.$j, "UT_P_" . $i);
            }
        }
        foreach($this->products as $p){
            $this->__insertProduct($p);
        }
        foreach($this->parameters as $pm){
            $this->__insertParameter($pm);
        }
    }
    

}