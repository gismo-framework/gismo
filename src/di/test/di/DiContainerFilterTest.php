<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 21:38
     */

    namespace Gismo\Test\Component;


    use Gismo\Component\Di\DiContainer;

    class DiContainerFilterTest extends \PHPUnit_Framework_TestCase {

        public function testFilterWithoutFactoryWontAffectIsset() {
            $di = new DiContainer();
            $this->assertFalse(isset ($di["key"]));

            $di["key"] = $di->filter(function ($§§) {
                return $§§;
            });
            $this->assertFalse(isset ($di["key"]));
        }

        

    }
