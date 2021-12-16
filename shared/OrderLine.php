<?php

class OrderLine{
    private $orderID; 
    private $productID;
    private $quantity; //int quantity of product for this order line
    private $productSummary;

    public function __construct($orderID, $productID, $quantity, $productSummary){
        $this->orderID= $orderID;
        $this->productID= $productID;
        $this->quantity=$quantity;
        $this->productSummary=$productSummary;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function getProductID(){
        return $this->productID;
    }

    public function getOrderID(){
        return $this->orderID;
    }

    public function getLinePrice(){
        $sum = 0;
        foreach($this->options as $option){
            $sum += $option->getPrice();
        }
        return $sum;
    }

    public function getProductSummary(){
        return $this->productSummary;
    }

}


?>