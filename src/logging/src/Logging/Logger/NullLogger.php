<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 25.07.16
     * Time: 00:23
     */

    namespace Gismo\Component\Logging\Logger;
    
    

    use Gismo\Component\Logging\Logger;

    class NullLogger implements Logger  {


        public function emergency(array $msg) {
        }

        public function alert(array $msg) {
        }

        public function critical(array $msg) {
        }

        public function error(array $msg) {
        }

        public function warning(array $msg) {
        }

        public function notice(array $msg) {
        }

        public function info(array $msg) {
        }

        public function debug(array $msg) {
        }

        public function log($logLevel, array $msg) {
        }
    }