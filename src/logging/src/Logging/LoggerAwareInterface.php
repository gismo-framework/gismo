<?php



    namespace Gismo\Component\Logging;

    interface LoggerAwareInterface {

        public function setLogger(Logger $logger);
        
    }