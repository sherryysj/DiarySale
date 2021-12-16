<?php 
    if (!isset($_SESSION))
        session_start();

    class SystemManager {
        private static $configFile = "shared/system.config";

        public function __construct() {
            if (!isset($_SESSION["system"])) {
                $_SESSION["system"] = [];
            }
            $this->readConfig();
        }

        public function readConfig() {
            $fh = fopen(self::$configFile, "r");
            $_SESSION["system"]["role"] = trim(fgets($fh));
            $_SESSION["delivery"]["std"] = trim(fgets($fh));
            $_SESSION["delivery"]["exp"] = trim(fgets($fh));
            fclose($fh);
        }

        public function getRole() {
            return $_SESSION["system"]["role"];
        }
    }
?>