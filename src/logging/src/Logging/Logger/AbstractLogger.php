<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 25.07.16
     * Time: 14:26
     */


    namespace Gismo\Component\Logging\Logger;


   

    use Gismo\Component\Logging\Logger;
    use Gismo\Component\Logging\LogLevel;

    abstract class AbstractLogger implements Logger {

        private static $sLogStartTime = null;
        private static $sLastLogTime = null;

        private $mLogSource = true;

        private $mLogAlias = "default";

        private $mLastTrace = null;

        public function setLogSource (bool $logSource = true) {
            $this->mLogSource = $logSource;
        }

        public function setLogAlias (string $alias) {
            $this->mLogAlias = $alias;
        }





        public function _formatLog (string $args, $level, $trace) {
            $logLine = "";

            $traceOrig = $trace[0];
            if (isset ( $trace[1] )) {
                $trace2 = $trace[1];
            } else {
                $trace2 = ["class" => "main", "function" => ""];
            }

            for ($i = 0; $i < count($args); $i++) {
                if (is_callable($args[$i])) {
                    $args[$i] = $args[$i]();
                }
            }

            if ($this->mLogSource) {
                if (isset ( $trace2["class"] )) {
                    $traceStr = ">>>{$traceOrig["file"]} -> {$trace2["class"]}::{$trace2["function"]}()";
                } else {
                    $traceStr = ">>>{$traceOrig["file"]} -> {$trace2["function"]}()";
                }
                if ($this->mLastTrace != $traceStr) {
                    $logLine .= "\n" . $traceStr;
                    $this->mLastTrace = $traceStr;
                }
            }

            if (self::$sLogStartTime === null) {
                self::$sLogStartTime = microtime(true);
                self::$sLastLogTime = microtime(true);
            }

            $timeTotal = number_format((microtime(true) - self::$sLogStartTime), 3);
            $diffTime =  number_format((microtime(true) - self::$sLastLogTime), 3);
            self::$sLastLogTime = microtime(true);


            $printLogLevel = strtoupper($level);

            $lineNo = "";
            if ($this->mLogSource)
                $lineNo = "[:{$traceOrig["line"]}]";
            $logLine .= "\n[{$this->mLogAlias}][{$timeTotal}+{$diffTime}s][$printLogLevel]{$lineNo} " . (string)$args;
            return $logLine;
        }


        public function emergency(array $msg) {
            $this->log(LogLevel::EMERGENCY, $msg, debug_backtrace(0, 2));
        }

        public function alert(array $msg) {
            $this->log(LogLevel::ALERT, $msg, debug_backtrace(0, 2));
        }

        public function critical(array $msg) {
            $this->log(LogLevel::CRITICAL, $msg, debug_backtrace(0, 2));
        }

        public function error(array $msg) {
            $this->log(LogLevel::ERROR, $msg, debug_backtrace(0, 2));
        }

        public function warning(array $msg) {
            $this->log(LogLevel::WARNING, $msg, debug_backtrace(0, 2));
        }

        public function notice(array $msg) {
            $this->log(LogLevel::NOTICE, $msg, debug_backtrace(0, 2));
        }

        public function info(array $msg) {
            $this->log(LogLevel::INFO, $msg, debug_backtrace(0, 2));
        }

        public function debug(array $msg) {
            $this->log(LogLevel::DEBUG, $msg, debug_backtrace(0, 2));
        }

        abstract public function log($logLevel, array $msg, array $backtrace=null);
    }