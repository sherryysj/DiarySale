<?php

class Address{
    private $street = "";
    private $city = "";
    private $state = "";
    private $postcode = "";
    private $leftovers = "";

    private $DELIMITER = ':';
    private $loopVars;

    // the "&$" stores a reference to the variable, rather than the variable value
    public function __construct(){  
        $this->loopVars = [&$this->street, &$this->city, &$this->state, &$this->postcode];
    }

    public function populate($street, $city, $state, $postcode){
        for($i=0; $i<count(func_get_args()); $i++){
            $this->loopVars[$i] = func_get_args()[$i];
        }
    }

    public function populateWithString($addressString){
        $this->convertToVars($addressString);
    }

    public function convertToVars($stringAddress){
        $leftovers = "";

        $this->loopVars[0] = strtok($stringAddress, $this->DELIMITER);
        for($i=1; $i< count($this->loopVars); $i++){
            $this->loopVars[$i] = strtok("$this->DELIMITER");
        }
        while (strtok($this->DELIMITER)!=null){
            $leftovers .= strtok($this->DELIMITER);
        }
        
    }

    public function getStringAddress(){
        $output = "";

        for($i=0; $i<count($this->loopVars);$i++){
            $output .= (String)$this->loopVars[$i];
            if ($i < count($this->loopVars) - 1){
                $output .= ":";
            }
        }
        return $output;
    }

    public function getPostcode(){
        return $this->postcode;
    }
    public function getStreet(){
        return $this->street;
    }
    public function getState(){
        return $this->state;
    }
    public function getCity(){
        return $this->city;
    }
    public function getLeftOvers(){
        return $this->leftovers;
    }

    public function setPostcode($postcode){
        $this->postcode = $postcode;
    }
    public function setStreet($street){
        $this->street = $street;
    }
    public function setState($state){
        $this->state = $state;
    }
    public function setCity($city){
        $this->city = $city;
    }

}

?>