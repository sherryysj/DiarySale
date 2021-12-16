<?php
    if (!headers_sent() && !isset($_SESSION))
        session_start();

    class MessageUtil {
        public function __construct() {
            if (!isset($_SESSION["message"])) {
                $_SESSION['message'] = array();
            }
        }

        public function addMessage($key, $message) {
            $_SESSION["message"][$key] = $message;
        }

        public function hasMessage($key) {
            return isset($_SESSION["message"][$key]);
        }

        public function popMessage($key) {
            if ($this->hasMessage($key)) {
                $msg = $_SESSION["message"][$key];
                unset($_SESSION["message"][$key]);
                return $msg;
            }
            return "";
        }

        public function flashHtmlErrorIfAny($key) {
            if ($this->hasMessage($key)) {
                echo <<<EOT
                    <div class="form-group row">
                        <label for="siteinfo" class="alert alert-danger"><b>{$this->popMessage($key)}</b></label>
                    </div>              
EOT;
            }
        }

        public function flashHtmlSuccessIfAny($key) {
            if ($this->hasMessage($key)) {
                echo <<<EOT
                <div class="form-group row">
                    <label for="siteinfo" class="alert alert-success"><b>{$this->popMessage($key)}</b></label>
                </div>              
EOT;
            }
        }
        public function displayAlertAndJump($message, $destination){
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo "<script type='text/javascript'>window.location = '$destination';</script>";
        }
    }
?>