<?php
    if (!headers_sent() && !isset($_SESSION))
        session_start();
    

    class CartUtil {

        public function __construct() {
            if (!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = [];
                $_SESSION["cart_max_id"] = 0;
            }
        }

        private function getMaxId() {
            return $_SESSION["cart_max_id"];
        }

        private function setMaxId($mid) {
            $_SESSION["cart_max_id"] = $mid;
        }

        public function getSummary($options) {
            $smy = [];
            foreach ($options as $paramName => $option) {

                $smy[] = "$paramName: {$option["optionName"]}";
            }
            return join("; ", $smy);
        }

        public function getCustomText($customTextOption){
            $customTextSummary = [];

            $noCustom = true;
            
            foreach($customTextOption as $customText => $c){
                if ($c){ $noCustom = false;}
                $label = substr($customText, 11);
                $customTextSummary[] = "{$label}: \"{$c}\"";
            }
            if ($noCustom){
                return "";
            }
            return join("; ", $customTextSummary);
        }

        public function getUnitPrice($paramOptionMap) {
            $sum = 0;
            foreach ($paramOptionMap as $option) {
                $sum = $sum + $option["price"];
            }
            return $sum;
        }

        public function getTotalPrice() {
            $cartPrice = 0;
            foreach ($_SESSION["cart"] as $orderLine) {
                $options = $orderLine["options"];                          
                $quantity = $orderLine["quantity"];
                $unitPrice = $this->getUnitPrice($options);
                $totalPrice = $unitPrice * $quantity;
                $cartPrice += $totalPrice;
            }
            return $cartPrice;
        }

        /*
        product: type array
        options: type array, where key is parameter name and value is the option array
        */
        public function addItem($product, $options, $quantity = 1, $customText) {
            if ($quantity <= 0)
                return false;
            $id = $this->getMaxId();
            $this->setMaxId($id + 1);
            $orderLine = [
                "product" => $product,
                "quantity" => $quantity,
                "options" => $options,
                "customText" => $customText
            ];
            $_SESSION["cart"][$id] = $orderLine;
            return true;
        }

        public function setQuantity($id, $qtt) {
            if (isset($_SESSION["cart"][$id])) {
                if ($qtt == 0) {
                    unset($_SESSION["cart"][$id]);
                } else {
                    $_SESSION["cart"][$id]["quantity"] = $qtt;
                }
                return true;
            }
            return false;
        }

        public function clear() {
            unset($_SESSION["cart"]);
            unset($_SESSION["cart_max_id"]);
        }

        public function getCart() {
            if(isset($_SESSION["cart"])){
                return $_SESSION["cart"];
            } else {
                return null;
            }
            
        }


    }
?>