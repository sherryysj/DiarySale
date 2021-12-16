<?php
    /*
    utility to pass transit data from one page to another
    */
    if (!headers_sent() && !isset($_SESSION))
        session_start();

    class TransitUtil {
        public function __construct() {
            if (!isset($_SESSION["transit"])) {
                $_SESSION["transit"] = [];
            }
        }

        /*
        returns: boolean - success or not
        */
        public function addData($key, $value) {
            if (isset($_SESSION["transit"][$key])) {
                return false;
            }
            $_SESSION["transit"][$key] = $value;
            return true;
        }

        public function popData($key) {
            if (!isset($_SESSION["transit"][$key])) {
                return NULL;
            }
            $val = $_SESSION["transit"][$key];
            unset($_SESSION["transit"][$key]);
            return $val;
        }
    }
?>