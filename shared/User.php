<?php
    class User {

        private $firstname;
        private $lastname;
        private $username;
        public function __construct(){
        }
/**getters and setters*/
        public function getFirstname(){
            return $this->firstname;
        }
    
        public function setFirstname($firstname){
            $this->firstname = $firstname;
            return $this;
        }

        public function getLastname(){
            return $this->lastname;
        }
    
        public function setLastname($lastname){
            $this->lastname = $lastname;
            return $this;
        }
        public function getUsername(){
            return $this->username;
        }
    
        public function setUsername($username){
            $this->username = $username;
            return $this;
        }
    }
/** end of getters/setters*/

    class CustomerUser extends User{
        private $email;
        private $street;
        private $city;
        private $state;
        private $postcode;

/**getters and setters*/
        public function getStreet(){
            return $this->street;
        }

        public function setStreet($street){
            $this->street = $street;
            return $this;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setEmail($email){
            $this->email = $email;
            return $this;
        }

        public function getCity(){
            return $this->city;
        }

        public function setCity($city){
            $this->city = $city;
            return $this;
        }

        public function getState(){
            return $this->state;
        }

        public function setState($state){
            $this->state = $state;
            return $this;
        }

        public function getPostcode(){
            return $this->postcode;
        }

        public function setPostcode($postcode){
            $this->postcode = $postcode;
            return $this;
        }
/** end of getters/setters*/
        }
?>