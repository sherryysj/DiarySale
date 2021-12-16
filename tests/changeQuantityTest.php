<?php
use PHPUnit\Framework\TestCase;

class changeQuantityTest extends TestCase{
    //private static $CUtil;
    protected $_SESSION;
    protected $id = 0;
    protected $product = "Diary";
    protected $quantity = 1;
    protected $options = "some options";

    public static function setUpBeforeClass(): void
    {
        //$CUtil = new CartUtil();
    }
    protected function setUp(): void {
        $orderLine = [
            "product" => $this->product,
            "quantity" => $this->quantity,
            "options" => $this->options
        ];
        $_SESSION["cart"][$this->id] = $orderLine;

    }

    public function addItem($product, $options, $quantity = 1) {
        if ($quantity <= 0)
            return false;
        $id +=1;
        $orderLine = [
            "product" => $product,
            "quantity" => $quantity,
            "options" => $options
        ];
        $_SESSION["cart"][$id] = $orderLine;
        return true;
    }

    public function setQuantity($id, $qtt): void {
            if ($qtt == 0) {
                $_SESSION["cart"][$id]= null;
            } else {
                $_SESSION["cart"][$id]["quantity"] = $qtt;
            }
    }

    public function testSetQuantity(): void{
        $qtt = 2;
        $this->setQuantity($this->id, $qtt);
        $this->assertEquals($qtt, $_SESSION["cart"][$this->id]["quantity"]);
    }

    public function testRemoveProduct(): void{
        $qtt = 0;
        $this->setQuantity($this->id, $qtt);
        $this->assertNotContains($_SESSION["cart"][$this->id], $_SESSION);
    }
}

?>