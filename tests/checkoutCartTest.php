<?php
    use PHPUnit\Framework\TestCase;
    
    /*
    prerequisites: 
        None
    dependencies:
        CartUtil->addItem works properly         
    coverage:
        CartUtil->getUnitPrice
        CartUtil->getTotalPrice
        CartUtil->getCart
        CartUtil->getSummary
    */

    class CheckoutCartTest extends TestCase {
        private $cartUtil;
        protected $product = [
            "productID" => "P001",
            "productName" => "Diary"
        ];
        protected $options1 = [
            "Paper Color" => [
                "optionName" => "Yellow", 
                "parameterID" => "Paper Color",
                "price" => 6,
                "availability" => true
            ],
            "Theme" => [
                "optionName" => "Zoo",
                "parameterID" => "Theme",
                "price" => 10,
                "availability" => true
            ],
            "Color of cover" => [
                "optionName" => "Red",
                "parameterID" => "Color of cover",
                "price" => 5,
                "availability" => true
            ],
            "Type of paper" => [
                "optionName" => "Type 1",
                "parameterID" => "Type of paper",
                "price" => 0,
                "availability" => true
            ]
        ];
        protected $options2 = [
            "Paper Color" => [
                "optionName" => "Milk White", 
                "parameterID" => "Paper Color",
                "price" => 7,
                "availability" => true
            ],
            "Theme" => [
                "optionName" => "Unicorn",
                "parameterID" => "Theme",
                "price" => 12,
                "availability" => true
            ],
            "Color of cover" => [
                "optionName" => "Black",
                "parameterID" => "Color of cover",
                "price" => 4,
                "availability" => true
            ],
            "Type of paper" => [
                "optionName" => "Type 2",
                "parameterID" => "Type of paper",
                "price" => 1,
                "availability" => true
            ]
        ];
        protected $quantity1 = 1;
        protected $quantity2 = 2;
        protected $unitPrice1 = 21;
        protected $unitPrice2 = 24;

        public static function setUpBeforeClass(): void {
            unset($_SESSION);
        }

        protected function setUp(): void {
            $this->cartUtil = new CartUtil();
            $this->cartUtil->addItem($this->product, $this->options1, $this->quantity1, "");
            $this->cartUtil->addItem($this->product, $this->options2, $this->quantity2, "");
        }

        protected function tearDown(): void {
            unset($_SESSION);
        }

        public function testGetUnitPrice(): void {
            $this->assertEquals($this->unitPrice1, $this->cartUtil->getUnitPrice($this->options1));
            $this->assertEquals($this->unitPrice2, $this->cartUtil->getUnitPrice($this->options2));
        }

        public function testGetCartTotalPrice(): void {
            $totalPrice = $this->quantity1 * $this->unitPrice1 + $this->quantity2 * $this->unitPrice2;
            $this->assertEquals($totalPrice, $this->cartUtil->getTotalPrice());
        }

        public function testGetSummary(): void {
            $summary = $this->cartUtil->getSummary($this->options1);
            $this->assertRegExp(
                '/Paper Color.+Yellow.+Theme.+Zoo.+Color of cover.+Red.+Type of paper.+Type 1/', 
                $summary
            );
        }

        public function testGetCart(): void {
            $cart = $this->cartUtil->getCart();
            $secondOrderline = $cart[1];
            $this->assertCount(2, $cart);
            $this->assertArrayHasKey(0, $cart);
            $this->assertArrayHasKey(1, $cart);
            $this->assertEquals(2, $secondOrderline["quantity"]);
            $this->assertEquals("Black", $secondOrderline["options"]["Color of cover"]["optionName"]);
            $this->assertEquals(7, $secondOrderline["options"]["Paper Color"]["price"]);
        }
    }
?>