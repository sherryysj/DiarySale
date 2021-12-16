<?php
    if (!headers_sent() && !isset($_SESSION))
        session_start();

    class AuthUtil {

        public function loginUser($role, $username) {
            $_SESSION["role"] = $role;
            $_SESSION["username"] = $username;
        }

        public function logoutUser() {
            unset($_SESSION["role"]);
            unset($_SESSION["username"]);
        }

        public function isLoggedIn() {
            return isset($_SESSION["role"]) && isset($_SESSION["username"]);
        }

        public function getRole() {
            if (!$this->isLoggedIn())
                return NULL;
            return $_SESSION["role"];
        }

        public function getUsername() {
            if (!$this->isLoggedIn())
                return NULL;
            return $_SESSION["username"];
        }
    }
?>