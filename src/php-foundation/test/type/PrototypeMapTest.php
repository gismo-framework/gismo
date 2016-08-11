<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 07.08.16
     * Time: 16:13
     */

    namespace Gismo\Test\Component;


    class Demo {

    }

    use Gismo\Component\PhpFoundation\Type\PrototypeMap;

    class PrototypeMapTest extends \PHPUnit_Framework_TestCase {

        public function testIsset() {
            $map = new PrototypeMap(new Demo());

            $this->assertFalse(isset ($map["someOffset"])); // Isset will return the real situation

            $this->assertEquals(Demo::class, get_class($map["someOffset"]));
            $this->assertTrue(isset($map["someOffset"]));

            unset ($map["someOffset"]);
            $this->assertFalse(isset ($map["someOffset"]));
        }

        public function testOverwrite () {
            $map = new PrototypeMap(new Demo());

            $map["key"] = "otherValue";
            $this->assertTrue(isset ($map["key"]));
            $this->assertEquals("otherValue", $map["key"]);
        }

    }
