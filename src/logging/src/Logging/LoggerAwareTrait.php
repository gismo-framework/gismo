<?php

    namespace Gismo\Component\Logging;


    use pf\log\logger\NullLogger;

    trait LoggerAwareTrait {

        private $logger = null;

        public function setLogger (Logger $logger) {
            $this->logger = $logger;
        }

        
        protected function log() : Logger {
            if ($this->logger === null)
                $this->logger = new NullLogger();
            return $this->logger;
        }

    }