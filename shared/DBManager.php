<?php
    class DBManager {
        private $configFile = "shared/db.config";
        private $user;
        private $password;
        private $connStr;

        public function __construct() {
            $fh = fopen($this->configFile, "r");
            $this->user = trim(fgets($fh));
            $this->password = trim(fgets($fh));
            $this->connStr = trim(fgets($fh));
            fclose($fh);
        }
        
        public function getConn() {
            /*
            The oci_pconnect() function uses a persistent cache of connections that can be re-used across different script requests. 
            This means that the connection overhead will typically only occur once per PHP process (or Apache child).
            https://www.php.net/manual/en/oci8.connection.php
            */
            return oci_pconnect($this->user, $this->password, $this->connStr);
        }

        public function returnConn($conn) {
            oci_close($conn);
        }
    }
?>