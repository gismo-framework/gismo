<?php

    namespace Gismo\Test\Component;
    use Gismo\Component\Di\DiContainer;

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 23:42
     */
    class DiContainerBaseTest extends \PHPUnit_Framework_TestCase {

        
        public function testBasicValue () {
            $di = new DiContainer();
            $di["some_key_name"] = "Some Value";

            $this->assertEquals("Some Value", $di["some_key_name"]);
        }

        public function testBasicValueFactory () {
            $di = new DiContainer();
            $numCalls = 0;
            $di["some_key_name"] = function () use (&$numCalls) {
                $numCalls++;
                return "Some value";
            };

            $this->assertEquals("Some value", $di["some_key_name"]);
            $this->assertEquals("Some value", $di["some_key_name"]);
            $this->assertEquals(1, $numCalls);
        }

        


    }
