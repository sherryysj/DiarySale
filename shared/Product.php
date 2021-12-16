<?php
    class Product {

        private $productID;
        private $productName;
        public function __construct($productID, $productName){
            $this->productID = $productID;
            $this->productName = $productName;
        }
/**getters and setters*/
        public function getProductID(){
            return $this->productID;
        }
    
        public function setProductID($productID){
            $this->productID = $productID;
            return $this;
        }
        
        public function getProductName(){
            return $this->productName;
        }
    
        public function setProductName($productName){
            $this->productName = $productName;
            return $this;
        }
        
        public function toArr() {
            return get_object_vars($this);
        }
    }
/** end of getters/setters*/
?>