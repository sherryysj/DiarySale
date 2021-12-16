<?php
    use PHPUnit\Framework\TestCase;
    // require("shared/Product.php");
    // require("shared/Parameter.php");
    // require("shared/Option.php");
    // require("shared/ProductManager.php");
    
    class RetrieveOptionsTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        private $product;
        private $parameter;
        private $option1;
        private $option2;
        private $option3;

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
        }

        public function setup(): void {
            $timestamp = time();
            $this->product = new Product("P_$timestamp", "OnePiece");
            $this->parameter = new Parameter("Crew", $this->product->getProductID());
            $this->option1 = new Option("Luffy", "Crew", 1500000, true, false);
            $this->option2 = new Option("Chopper", "Crew", 100, true, false);
            $this->option3 = new Option("Sogeking", "Crew", 0, false, false);

            $this->__insertProduct($this->product);
            $this->__insertParameter($this->parameter);
            $this->__inserOptions($this->option1);
            $this->__inserOptions($this->option2);
            $this->__inserOptions($this->option3);
        }

        private function __insertProduct(Product $producte) {
            $query = "INSERT INTO PRODUCT (productid, productname) VALUES ('{$producte->getProductID()}', '{$producte->getProductName()}')";
            $stid = oci_parse(self::$conn, $query);
            oci_execute($stid);
        }

        private function __insertParameter(Parameter $parameter) {
            $query = "INSERT INTO PARAMETER (parametername, product_productid) VALUES ('{$parameter->getParameterName()}', '{$parameter->getProductID()}')";
            $stid = oci_parse(self::$conn, $query);
            oci_execute($stid);
        }

        private function __inserOptions(Option $option) {
            $isAvailable = $option->getAvailability() ? 'Y' : 'N';
            $isCustomizeable = $option->isCustomizeable() ? 'Y' : 'N';
            $query = "INSERT INTO OPTIONS (optionname, price, availability, customizeable, parameter_parametername) VALUES ('{$option->getOptionName()}', '{$option->getPrice()}', '{$isAvailable}', '{$isCustomizeable}', '{$option->getParameterID()}')";
            $stid = oci_parse(self::$conn, $query);
            oci_execute($stid);
        }

        public function testRetrievingOptions(): void {
            $productManager = new ProductManager(self::$conn);
            $options = $productManager->__retriveAllAvailableOptions($this->parameter->getParameterName());
            
            $this->assertEquals(2, sizeof($options));
            
            $luffyOption = $options[0];
            $this->assertEquals($luffyOption->getOptionName(), $this->option1->getOptionName());
            $this->assertEquals($luffyOption->getParameterID(), $this->option1->getParameterID());
            $this->assertEquals($luffyOption->getPrice(), $this->option1->getPrice());
            $this->assertEquals($luffyOption->getAvailability(), $this->option1->getAvailability());
            
            $chopperOption = $options[1];
            $this->assertEquals($chopperOption->getOptionName(), $this->option2->getOptionName());
            $this->assertEquals($chopperOption->getParameterID(), $this->option2->getParameterID());
            $this->assertEquals($chopperOption->getPrice(), $this->option2->getPrice());
            $this->assertEquals($chopperOption->getAvailability(), $this->option2->getAvailability());
        }

        public function tearDown(): void {
            $sql = "DELETE FROM OPTIONS WHERE parameter_parametername = '{$this->parameter->getParameterName()}'";
            $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);

            $sql = "DELETE FROM PARAMETER WHERE product_productid = '{$this->product->getProductID()}'";
            $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);

            $sql = "DELETE FROM PRODUCT WHERE productid = '{$this->product->getProductID()}'";
            $stid = oci_parse(self::$conn, $sql);
            oci_execute($stid);
        }

        public static function tearDownAfterClass(): void {
            self::$dbMgr->returnConn(self::$conn);
        }
    }
?>