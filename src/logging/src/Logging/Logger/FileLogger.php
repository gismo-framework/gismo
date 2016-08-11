<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 25.07.16
     * Time: 15:02
     */


    namespace Gismo\Component\Logging\Logger;


    class FileLogger extends AbstractLogger {

        private $mLogFileName;

        public function __construct($filename) {
            $this->mLogFileName = $filename;
        }


        public function log($logLevel, array $msg, array $backtrace = NULL) {
            if ($backtrace === null)
                $backtrace = debug_backtrace(0, 2);
            $message = array_shift($msg);
            $str = $this->_formatLog(goString($message)->apply($msg), $logLevel, $backtrace);


            file_put_contents($this->mLogFileName, $str, FILE_APPEND);
        }
    }