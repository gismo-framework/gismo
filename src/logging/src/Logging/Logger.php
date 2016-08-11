<?php

    namespace Gismo\Component\Logging;

    interface Logger {

        public function emergency(array $msg);
        public function alert (array $msg);
        public function critical(array $msg);

        public function error (array $msg);

        public function warning(array $msg);
        public function notice(array $msg);
        public function info (array $msg);
        public function debug (array $msg);

        public function log ($logLevel, array $msg);

    }