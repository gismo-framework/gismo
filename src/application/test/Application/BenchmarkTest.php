<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 19:50
     */
    
    namespace Gismo\Test\Component\Application;
    
    use Gismo\Component\Application\AbstractApplicationContext;
    use Gismo\Component\PhpFoundation\Helper\StopWatch;

    class BenchmarkTest extends \PHPUnit_Framework_TestCase {
        
        
        public function test1000RouteAdds () {
            $s = new StopWatch();
            $app = new AbstractApplicationContext();
            
            for ($i=0; $i<1000; $i++) {
                $name = "some$i";
                $app->route->add("/some/crazy/{$name}/{param1}/{param2}", function ($param1, $param2) {
                    
                }, $name);
            }
            $s->echoTotal("1000 Route Adds");
        }

    }
