<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 10.08.16
 * Time: 22:59
 */


    namespace Gismo\Component\Logging\Logger;


    class FirePhpLogger extends AbstractLogger {

        /**
         * @var \FirePHP
         */
        private $firePhp;

        public function __construct()
        {
            $this->firePhp = new \FirePHP();
        }

        public function log($logLevel, array $msg, array $backtrace = null) {
            if ($backtrace === null)
                $backtrace = debug_backtrace(0, 2);
            $message = array_shift($msg);
            $str = $this->_formatLog((string)goString($message)->apply($msg), $logLevel, $backtrace);
            $strArr = explode("\n", $str);
            foreach ($strArr as $line)
                $this->firePhp->log($line);
        }
    }