<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 27.07.16
     * Time: 22:17
     */


    namespace pf\test\helper;



    class ClassBuilderTest extends \PHPUnit_Framework_TestCase {


        public function testClassBuilderBasicClass () {
            $code = ClassBuilder::Class("DemoClass")->extends(ClassBuilderTest::class)
                ->public()->function("someFn", function($some, $params) {
                        $this->wurst = "muh";
                    })
                ->generate();

            echo $code;

        }

    }
