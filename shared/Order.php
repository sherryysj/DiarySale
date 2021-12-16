<?php
    //I think this class could be factored out? Since it's currently only being used in the Order Manager and the client site doesn't need to store multiple orders in session data
    class Order {

        private $orderID;
        private $orderTime;
        private $orderStatus;
        private $shippedDate;
        private $totalPrice;
        private $paymentMethod;
        private $shippingAddress;
        private $feedback;
        private $username;
        
        public function __construct($orderID, $orderTime, $orderStatus, $shippedDate, $totalPrice, $paymentMethod, $shippingAddress, $feedback, $username, $deliveryMethod){
            $this->orderID = $orderID;
            $this->orderTime = $orderTime;
            $this->orderStatus = $orderStatus;
            $this->shippedDate = $shippedDate;
            $this->totalPrice = $totalPrice;
            $this->paymentMethod = $paymentMethod;
            $this->shippingAddress = $shippingAddress;
            $this->feedback = $feedback;
            $this->username = $username;
            $this->deliveryMethod = $deliveryMethod;
        }
/**getters and setters*/
        public function getOrderID(){
            return $this->orderID;
        }
    
        public function setOrderID($orderID){
            $this->orderID = $orderID;
            return $this;
        }

        public function getOrderTime(){
            return $this->orderTime;
        }

        public function getPrettyOrderTime(){
            $dateObj = DateTime::createFromFormat("d#M#y H#i#s*A", $this->getOrderTime());
            return $dateObj->format('d/m/y G:ia');
            
        }

        public function setOrderTime($orderTime){
            $this->orderTime = $orderTime;
            return $this;
        }

        public function getOrderStatus() {
            return $this->orderStatus;
        }

        public function setOrderStatus($orderStatus) {
            $this->orderStatus = $orderStatus;
            return $this;
        }

        public function getShippedDate() {
            return $this->shippedDate;
        }

        public function setShippedDate($shippedDate) {
            $this->shippedDate = $shippedDate;
            return $this;
        }
        
        public function getTotalPrice() {
            return $this->totalPrice;
        }

        public function setTotalPrice($totalPrice) {
            $this->totalPrice = $totalPrice;
            return $this;
        }

        public function getPaymentMethod() {
            return $this->paymentMethod;
        }

        public function setPaymentMethod($paymentMethod) {
            $this->paymentMethod = $paymentMethod;
            return $this;
        }
        
        public function getShippingAddress() {
            return $this->shippingAddress;
        }

        public function setShippingAddress($shippingAddress) {
            $this->shippingAddress = $shippingAddress;
            return $this;
        }

        public function getFeedback() {
            return $this->feedback;
        }

        public function setFeedback($feedback) {
            $this->feedback = $feedback;
            return $this;
        }

        public function getUsername(){
            return $this->username;
        }
    
        public function setUsername($username){
            $this->username = $username;
            return $this;
        }

        public function getDeliveryMethod(){
            return $this->deliveryMethod;
        }

        public function setDeliveryMethod($deliveryMethod){
            $this->deliveryMethod = $deliveryMethod;
        }
    }

/** end of getters/setters*/
?>