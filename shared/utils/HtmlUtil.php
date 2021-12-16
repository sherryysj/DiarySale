<?php
    if (!headers_sent() && !isset($_SESSION))
        session_start();

    class HtmlUtil {
        public function echoEachWithPosVar($htmlContext, $posVarArr = []) {
            foreach ($posVarArr as $valArr) {
                vprintf($htmlContext, $valArr);
            }
        }
    }
?>