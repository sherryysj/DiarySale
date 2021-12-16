<?php
class DQ_Dictionary {
    private $loopVars = [];
    private $DELIMITER = ";";
    private $DICT_DELIMITER = ":";

    public function __construct(){
        //if any arguments supplied to constructor
        if (func_num_args() > 0){
            //iterate over arguments supplied
            for($i = 0; $i< func_num_args(); $i++){
                //if they are a key-value pair, add to loopVars array
                if (count(func_get_arg($i)) == 2){
                    $varName = func_get_arg($i)[0];
                    $this->loopVars[$varName] = func_get_arg($i)[1];
                } 
                else {
                    //ignore anything that's not a key value paird
                    //will get a warning here
                };
            }
        }
    }

    public function setVariable($key, $value){
        $this->loopVars[$key] = $value;
    }

    public function overrideDelimiter($delimiter){
        $this->DELIMITER = $delimiter;
    }

    public function overrideDictDelimiter($delimiter){
        $this->DICT_DELIMITER = $delimiter;
    }
    
    public function setVarsFromString($string){
        $leftovers = "";

        $array = explode($this->DELIMITER, $string);

        foreach($array as $pair){
            $keyValuePair = explode($this->DICT_DELIMITER, $pair, 2);
            if (count($keyValuePair) == 2){
                $this->setVariable($keyValuePair[0], $keyValuePair[1]);
            } else {
                //bad source string
                return 'invalid source string: "' . $string . '"';
            }
            
        }

        return $this->getKeyValuesString();
    }

    public function getByIndex($i){
        $iterator = new ArrayIterator($this->loopVars);
        $indexedArray = iterator_to_array($iterator, false);
        if ($i < count($indexedArray)){
            return $indexedArray[$i];
        }else {
            return 'invalid index: "' . $i . '"';
        }
    }

    public function getByName($string){
        if (array_key_exists($string, $this->loopVars)){
            return $this->loopVars[$string];
        }else {
            return 'invalid name: "' . $string . '"';
        }   
    }

    public function getValuesString(){
        $result = "";
        $isFirst = true;
        foreach($this->loopVars as $name => $value){
            if ($isFirst){
                $result .= $value;
                $isFirst = false;
            } else {
                $result .= $this->DELIMITER . $value;
            } 
        }
        return $result;
    }

    public function getKeyValuesString(){
        $result = "";
        $isFirst = true;
        foreach($this->loopVars as $name => $value){
            if ($isFirst){
                $result .= $name . $this->DICT_DELIMITER . $value;
                $isFirst = false;
            } else {
                $result .= $this->DELIMITER . $name . $this->DICT_DELIMITER . $value;
            } 
        }
        return $result;       
    }
}

?>

