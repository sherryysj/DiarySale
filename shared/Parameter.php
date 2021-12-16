<?php
    class Parameter {

        private $parameterName;
        private $productID;
        public function __construct($parameterName, $productID){
            $this->parameterName = $parameterName;
            $this->productID = $productID;
        }
/**getters and setters*/
        public function getParameterName(){
            return $this->parameterName;
        }
    
        public function setParameterName($parameterName){
            $this->parameterName = $parameterName;
            return $this;
        }

        public function getProductID(){
            return $this->productID;
        }
    
        public function setProductID($productID){
            $this->productID = $productID;
            return $this;
        }
    }
/** end of getters/setters*/
?>