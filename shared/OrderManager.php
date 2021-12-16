<?php
    class OrderManager {
        private $conn;
        private $ORDER_TABLE_NAME = "orders";
        private $ORDER_LINE_TABLE_NAME = "orderline";

        public function __construct($conn) {
            $this->conn = $conn;
        }
        
        public function __retrieveAllOrderFeedback() {
            $query = "SELECT * FROM $this->ORDER_TABLE_NAME WHERE feedback IS NOT NULL ORDER BY ORDERTIME DESC";
            $stid = oci_parse($this->conn, $query);
            oci_execute($stid);

            $orderFeedbackList = array();
            for($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) != false; $i++) {
                $order = new Order($row['ORDERID'], $row['ORDERTIME'], $row['ORDERSTATUS'], $row['SHIPPEDDATE'], $row['TOTALPRICE'], $row['PAYMENTMETHOD'], $row['SHIPPINGADDRESS'], $row['FEEDBACK'], $row['CUSTOMER_USERNAME'], $row["DELIVERYMETHOD"]);
                $orderFeedbackList[$i] = $order;
            }

            oci_free_statement($stid);
            return $orderFeedbackList;
        }

        public function __retireveAllOrder($username){
            $query = "SELECT * FROM $this->ORDER_TABLE_NAME WHERE UPPER(CUSTOMER_USERNAME) = UPPER(:username)";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":username", $username);
            oci_execute($stid);
            $orderList = array();
            for($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) != false; $i++) {
                $order = new Order($row['ORDERID'], $row['ORDERTIME'], $row['ORDERSTATUS'], $row['SHIPPEDDATE'], $row['TOTALPRICE'], $row['PAYMENTMETHOD'], $row['SHIPPINGADDRESS'], $row['FEEDBACK'] ?? null, $row['CUSTOMER_USERNAME'], $row["DELIVERYMETHOD"]);
                $orderList[$i] = $order;
            }
            oci_free_statement($stid);
            return $orderList;
        }

        public function __retrieveOrderLines($orderID){
            $query = "SELECT * FROM $this->ORDER_LINE_TABLE_NAME WHERE ORDER_ORDERID = :orderID";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":orderID", $orderID);
            oci_execute($stid);
            $orderLines = array();
            for($i = 0; ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) != false; $i++) {
                $orderLine = new OrderLine($row['ORDER_ORDERID'], $row['PRODUCT_PRODUCTID'], $row['QUANTITY'], $row['PRODUCTSUMMARY']);
                $orderLines[$i] = $orderLine;
            }
            oci_free_statement($stid);
            return $orderLines;
        }

        public function __addFeedback($orderID, $feedBack){
            $query = "UPDATE $this->ORDER_TABLE_NAME SET FEEDBACK = :feedBack WHERE ORDERID = :orderID";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":orderID", $orderID);
            oci_bind_by_name($stid, ":feedBack", $feedBack);
            return oci_execute($stid);
        }

        //order statuses = placed, confirmed, paid, shipped?
        //returns an order ID of created order
        public function __createOrder($totalPrice, $shippingAddress, $paymentMethod, $deliveryMethod, $username){
            $query = "INSERT INTO $this->ORDER_TABLE_NAME (TOTALPRICE, PAYMENTMETHOD, SHIPPINGADDRESS, CUSTOMER_USERNAME, DELIVERYMETHOD) 
            VALUES (:totalPrice, :paymentMethod, :shippingAddress, :username, :deliveryMethod)  RETURNING ORDERID INTO :orderID";
            $stid = oci_parse($this->conn, $query);
            
            oci_bind_by_name($stid, ":totalPrice", $totalPrice);
            oci_bind_by_name($stid, ":paymentMethod", $paymentMethod);
            oci_bind_by_name($stid, ":shippingAddress", $shippingAddress);
            oci_bind_by_name($stid, ":username", $username);
            oci_bind_by_name($stid, ":orderID", $orderID, 40);
            oci_bind_by_name($stid, ":deliveryMethod", $deliveryMethod);
            oci_execute($stid);
            return $orderID;
        }

        public function __createOrderLine($orderLineID, $quantity, $linePrice, $p_summary, $orderID, $productID){
            $query = "INSERT INTO $this->ORDER_LINE_TABLE_NAME (ORDERLINEID, QUANTITY, PRICE, PRODUCTSUMMARY, ORDER_ORDERID, PRODUCT_PRODUCTID)
            VALUES (:orderLineID, :quantity, :linePrice, :p_summary, :orderID, :productID)";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":orderLineID", $orderLineID);
            oci_bind_by_name($stid, ":quantity", $quantity);
            oci_bind_by_name($stid, ":linePrice", $linePrice);
            oci_bind_by_name($stid, ":p_summary", $p_summary);
            oci_bind_by_name($stid, ":orderID", $orderID);
            oci_bind_by_name($stid, ":productID", $productID);
            oci_execute($stid);
            return oci_error($stid);
        }

    }
?>