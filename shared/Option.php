<?php
    class Option {

        private $optionName;
        private $parameterID;
        private $price;
        private $availability;
        private $customizeable; #bool - stored in db as 'Y' or 'N'

        public function __construct($optionName, $parameterID, $price, $availability, $customizeable){
            $this->optionName = $optionName;
            $this->parameterID = $parameterID;
            $this->price = $price;
            $this->availability = $availability;
            $this->customizeable = $customizeable;
        }
/**getters and setters*/
        public function setPrice($price) {
            $this->price = $price;
        }

        public function setAvailability($a) {
            $this->availability = $a;
        }

        public function getPrice() {
            return $this->price;
        }

        public function getAvailability() {
            return $this->availability;
        }

        public function getOptionName(){
            return $this->optionName;
        }
    
        public function setOptionName($optionName){
            $this->optionName = $optionName;
            return $this;
        }

        public function getParameterID(){
            return $this->parameterID;
        }
    
        public function setParameterID($parameterID){
            $this->parameterID = $parameterID;
            return $this;
        }

        public function isCustomizeable(){
            return $this->customizeable;
        }

        public function toArr() {
            return get_object_vars($this);
        }
    }
/** end of getters/setters*/
?>