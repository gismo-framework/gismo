<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 25.07.16
     * Time: 15:05
     */    
    
    namespace Gismo\Test\Component\Logging;


    use Gismo\Component\Logging\Logger\FileLogger;

    class FileLoggerTest extends \PHPUnit_Framework_TestCase {

        const LOGFILE = "/tmp/mockLogfile";


        public function setUp() {
            parent::setUp();
            @unlink(self::LOGFILE);
        }

        public function testFormatLog () {
            $logger = new FileLogger(self::LOGFILE);
            $logger->info(["Some message of ?", "Some string value"]);
            echo file_get_contents(self::LOGFILE);
            $this->assertTrue(true);
        }
        
    }
