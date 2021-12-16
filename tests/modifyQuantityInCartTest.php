<?php
    use PHPUnit\Framework\TestCase;
    // require("shared/Product.php");
    // require("shared/Parameter.php");
    // require("shared/Option.php");
    // require("shared/ProductManager.php");
    // require("shared/utils/CartUtil.php");
    
    class ModifyQuantityInCartTest extends TestCase {
        private static $conn;
        private static $dbMgr;
        private $product;

        public static function setUpBeforeClass(): void {
            self::$dbMgr = new DBManager();
            self::$conn = self::$dbMgr->getConn();
        }

        public function setup(): void {
            $this->generateData();
            $this->addItemIntoCart();
        }

        private function generateData() {
            $timestamp = time();
            $this->product = new Product("P_$timestamp", "OnePiece");
            $parameter = new Parameter("Crew", $this->product->getProductID());
            $option1 = new Option("Luffy", "Crew", 1500000, true, false);
            $option2 = new Option("Chopper", "Crew", 100, true, false);
            $option3 = new Option("Sogeking", "Crew", 0, false, false);

            $this->__insertProduct($this->product);
            $this->__insertParameter($parameter);
            $this->__inserOptions($option1);
            $this->__inserOptions($option2);
            $this->__inserOptions($option3);
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

        private function __inserOptions(Option $option) {
            $isAvailable = $option->getAvailability() ? 'Y' : 'N';
            $isCustomizeable = $option->isCustomizeable() ? 'Y' : 'N';
            $query = "INSERT INTO OPTIONS (optionname, price, availability, customizeable, parameter_parametername) VALUES ('{$option->getOptionName()}', '{$option->getPrice()}', '{$isAvailable}', '{$isCustomizeable}', '{$option->getParameterID()}')";
            $stid = oci_parse(self::$conn, $query);
            oci_execute($stid);
        }

        private function addItemIntoCart() {
            $cartUtil = new CartUtil();
            $productManager = new ProductManager(self::$conn);
            $parameterList = $productManager->__retriveAllParameters($this->product->getProductID());
            $parameterOptionsMap = [];
            $optionMap = [];

            foreach ($parameterList as $parameter) {
                $parameterName = $parameter->getParameterName();
                $options = $productManager->__retriveAllAvailableOptions($parameterName);
                $parameterOptionsMap[$parameterName] = $options[0];
                $optionMap[$options[0]->getOptionName()] = $options[0]->toArr();
            }

            $cartUtil->addItem($this->product->toArr(), $parameterOptionsMap, 3, "");
        }

        public function testRemoveProduct(): void {
            $cartUtil = new CartUtil();
            $orderLineArr = $cartUtil->getCart();

            foreach ($orderLineArr as $idInCart => $orderLine) {
                $cartUtil->setQuantity($idInCart, 0);
            }

            $this->assertEmpty($cartUtil->getCart());
        }

        public function testIncreaseQuantity(): void {
            $cartUtil = new CartUtil();
            $orderLineArr = $cartUtil->getCart();

            foreach ($orderLineArr as $idInCart => $orderLine) {
                $quantity = $orderLine["quantity"];
                $quantity = $quantity + 3;
                $cartUtil->setQuantity($idInCart, $quantity);
            }

            $this->assertEquals(1, sizeof($cartUtil->getCart()));
            $this->assertEquals(6, $cartUtil->getCart()[0]["quantity"]);
        }
        
        public function testDecreaseQuantity(): void {
            $cartUtil = new CartUtil();
            $orderLineArr = $cartUtil->getCart();

            foreach ($orderLineArr as $idInCart => $orderLine) {
                $quantity = $orderLine["quantity"];
                $quantity = $quantity - 2;
                $cartUtil->setQuantity($idInCart, $quantity);
            }

            $this->assertEquals(1, sizeof($cartUtil->getCart()));
            $this->assertEquals(1, $cartUtil->getCart()[0]["quantity"]);
        }

        public function tearDown(): void {
            $cartUtil = new CartUtil();
            $cartUtil->clear();

            $sql = "DELETE FROM OPTIONS WHERE parameter_parametername = 'Crew'";
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