<?php
    abstract class UserManager {
        private $conn;
        private $tablename = "";
        private $usernameInDB = "";

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function setTableName($tn) {
            $this->tablename = $tn;
        }

        public function validateLogin($username, $password) {
            $query = "SELECT * from $this->tablename WHERE UPPER(USERNAME) = UPPER(:username)";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":username", $username);
            oci_execute($stid);
            
            $match = false;
            if(($row = oci_fetch_array($stid, OCI_ASSOC))) {
               if ($row["PASSWORD"] == $password) {
                  $this->usernameInDB = $row["USERNAME"];
                  $match = true;
               }
            }
            oci_free_statement($stid);
            return $match;
        }
		
		public function validatePassword($username, $password){
		    $query = "SELECT PASSWORD from $this->tablename WHERE USERNAME = :username";
			$stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":username", $username);
            oci_execute($stid);
			
			$match = false;
            if(($row = oci_fetch_array($stid, OCI_ASSOC))) {
               if ($row["PASSWORD"] == $password) {
                  $match = true;
               }
            }
            oci_free_statement($stid);
            return $match;
		}
		
		public function changePassword($username, $password){
		    $query = "UPDATE $this->tablename SET PASSWORD = :password WHERE USERNAME = :username";
			$stid = oci_parse($this->conn, $query);
		    oci_bind_by_name($stid, ":username", $username);
		    oci_bind_by_name($stid, ":password", $password);
			return(oci_execute($stid));
		}
        
        public function getUsernameInDB(){
            return $this->usernameInDB;
        }

        public function checkUniqueID($username){
            $query = "SELECT * from $this->tablename WHERE UPPER(USERNAME) = UPPER(:username)";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":username", $username);
            oci_execute($stid);

            $unique = true;
            if($row = oci_fetch_array($stid, OCI_ASSOC)) {
                $unique = false;
            }
            oci_free_statement($stid);
            return $unique;
        }

        public function createNewAccount($username, $password, $name, $primaryShippingAddress, $mailAddress, $email, $status) {
            $query = "INSERT INTO $this->tablename (USERNAME, PASSWORD, NAME, PRIMARYSHIPPINGADDRESS, MAILADDRESS, EMAIL, STATUS) 
            VALUES (:username, :password, :name, :primaryShippingAddress, :mailAddress, :email, :status)";
            $stid = oci_parse($this->conn, $query);
            oci_bind_by_name($stid, ":username", $username);
            oci_bind_by_name($stid, ":password", $password);
            oci_bind_by_name($stid, ":name", $name);
            oci_bind_by_name($stid, ":primaryShippingAddress", $primaryShippingAddress);
            oci_bind_by_name($stid, ":mailAddress", $mailAddress);
            oci_bind_by_name($stid, ":email", $email);
            oci_bind_by_name($stid, ":status", $status);
            return oci_execute($stid);
        }

    }

    class CustomerManager extends UserManager {
        public function __construct($conn) {
            parent::__construct($conn);
            $this->setTableName("CUSTOMER");
        }
    }

    class AdminManager extends UserManager {
        public function __construct($conn) {
            parent::__construct($conn);
            $this->setTableName("ADMIN");
        }       
    }
?>