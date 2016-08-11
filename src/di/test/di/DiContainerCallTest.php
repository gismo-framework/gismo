<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 23:29
     */


    namespace Gismo\Test\Component;
    
    use Gismo\Component\Di\DiContainer;

    class DiDemoClass {

    }


    class DiContainerCallTest extends \PHPUnit_Framework_TestCase {


        public function testDiCallBuildParameter () {
            $di = new DiContainer();
            $di["§diNamedParameter"] = "diNamedParameterValue";

            $argVal = $di(function ($§diNamedParameter) {
                return func_get_args();
            });

            $this->assertEquals("diNamedParameterValue", $argVal[0]);
        }


        public function testDiCallBuildClassParameter () {
            $di = new DiContainer();

            $di[DiDemoClass::class] = function () {
                return new DiDemoClass();
            };


            $argVal = $di(function (DiDemoClass $arg1) {
                return func_get_args();
            });

            $this->assertInstanceOf(DiDemoClass::class, $argVal[0]);

        }


    }
