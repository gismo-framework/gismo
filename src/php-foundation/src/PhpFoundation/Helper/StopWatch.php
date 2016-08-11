<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 17:50
     */     
    
    namespace Gismo\Component\PhpFoundation\Helper;

    
    
    class StopWatch {
        
        private $startTime;
        private $lastLap;
        
        public function __construct() {
            $this->startTime = $this->lastLap = microtime(true);
        }
        
        public function lap() : float {
            $lap = microtime(true) - $this->lastLap;
            $this->lastLap = microtime(true);
            return $lap;
        }
        
        public function total() : float {
            return microtime(true) - $this->startTime;
        }
        
        public function echoLap($description= "") {
            echo "\n--Stopwatch: Lap: " . number_format($this->lap(), 4) . "[sec] ($description)";
        }
        
        public function echoTotal ($description="") {
            echo "\n--Stopwatch: Total: " . number_format($this->total(), 4) . "[sec] ($description)";
        }
        
    }