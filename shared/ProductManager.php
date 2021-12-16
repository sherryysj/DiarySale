<?php
    class ProductManager {
        private $conn;
        private $PRODUCT_TABLE_NAME = "product";
        private $PARAMETER_TABLE_NAME = "parameter";
        private $OPTION_TABLE_NAME = "options";

        public function __construct($conn) {
            $this->conn = $conn;
        }
        
        public function __retriveAllProducts() {
            $query = "SELECT * FROM $this->PRODUCT_TABLE_NAME";
            $stid = oci_parse($this->conn, $query);
            oci_execute($stid);

            $productList = array();
            for($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC)) != false; $i++) {
                $product = new Product($row['PRODUCTID'], $row['PRODUCTNAME']);
                $productList[$i] = $product;
            }
            oci_free_statement($stid);
            return $productList;
        }

        public function __searchProducts($productName){
            $query = "SELECT * FROM $this->PRODUCT_TABLE_NAME WHERE UPPER(PRODUCTNAME) LIKE UPPER('%$productName%')";
            $stid = oci_parse($this->conn, $query);
            oci_execute($stid);

            $searchResult = array();
            for($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC)) != false; $i++) {
                $product = new Product($row['PRODUCTID'], $row['PRODUCTNAME']);
                $searchResult[$i] = $product;
            }
            oci_free_statement($stid);
            return $searchResult;
        }

        public function __retriveAllParameters($productId) {
            $query = "SELECT * FROM $this->PARAMETER_TABLE_NAME WHERE UPPER(PRODUCT_PRODUCTID) = UPPER(:productid)";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":productid", $productId);
            oci_execute($stid);

            $parameterList = array();
            for($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC)) != false; $i++) {
                $parameter = new Parameter($row['PARAMETERNAME'], $row['PRODUCT_PRODUCTID']);
                $parameterList[$i] = $parameter;
            }
            oci_free_statement($stid);
            return $parameterList;
        }

        public function __retriveAllAvailableOptions($parameterName) {
            $query = "SELECT * FROM $this->OPTION_TABLE_NAME WHERE UPPER(PARAMETER_PARAMETERNAME) = UPPER(:parameterName) AND UPPER(AVAILABILITY) = 'Y'";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":parameterName", $parameterName);
            oci_execute($stid);

            $optionList = array();
            for ($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC)) != false; $i++) {
                $av = $row["AVAILABILITY"] == 'Y' ? true : false;
                $customizeable = $row["CUSTOMIZEABLE"] == 'Y' ? 1 : 0;
                $option = new Option($row['OPTIONNAME'], $row['PARAMETER_PARAMETERNAME'], $row["PRICE"], $av, $customizeable);
                $optionList[$i] = $option;
            }
            oci_free_statement($stid);
            return $optionList;
        }

    }
?>