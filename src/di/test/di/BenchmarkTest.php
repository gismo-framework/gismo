<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 17:47
     */

    namespace Gismo\Test\Component;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Helper\StopWatch;

    class BenchmarkTest extends \PHPUnit_Framework_TestCase {

        
        public function testAdding1000Factories () {
            $di = new DiContainer();

            $sw = new StopWatch();

            for ($i = 0; $i<1000; $i++) {
                $name = "some{$i}";
                $di[$name] = $di->factory(function () {

                });
            }
            $sw->echoTotal("Creating 1000 factories");

        }
        public function testAdding1000Values () {
            $di = new DiContainer();

            $sw = new StopWatch();

            for ($i = 0; $i<1000; $i++) {
                $name = "some{$i}";
                $di[$name] = $di->constant("Some value");
            }
            $sw->echoTotal("Creating 1000 constants");

        }

        public function testAdding1000Filters () {
            $di = new DiContainer();

            $sw = new StopWatch();

            for ($i = 0; $i<1000; $i++) {
                $di["someValue"] = $di->filter(function () {

                });
            }
            $sw->echoTotal("Creating 1000 Filters");

        }
        
        public function testCalling1000Service () {
            $di = new DiContainer();

            $sw = new StopWatch();
            $di["someValue"] = $di->service(function () {
                return "Some value";
            });
            for ($i = 0; $i<1000; $i++) {
                $ret = $di["someValue"];
            }
            $sw->echoTotal("Getting 1000 Times from Service");

        }
        
        public function testCalling1000Factory () {
            $di = new DiContainer();

            $sw = new StopWatch();
            $di["someValue"] = $di->factory(function () {
                return "Some value";
            });
            for ($i = 0; $i<1000; $i++) {
                $ret = $di["someValue"];
            }
            $sw->echoTotal("Getting 1000 Times from Factory");

        }
        
        public function testCalling1000Constant () {
            $di = new DiContainer();

            $sw = new StopWatch();
            $di["someValue"] = $di->constant("Some value");

            for ($i = 0; $i<1000; $i++) {
                $ret = $di["someValue"];
            }
            $sw->echoTotal("Getting 1000 Times from Constant");
        }
        
        public function testCalling1000functions () {
            $di = new DiContainer();

            $sw = new StopWatch();
            $di["someValue"] = $di->constant("Some value");

            for ($i = 0; $i<1000; $i++) {
                $di(function ($a=0, $b=0, $c=0) {});
            }
            $sw->echoTotal("Calling 1000 functions with 3 parameters");
        }
    }
