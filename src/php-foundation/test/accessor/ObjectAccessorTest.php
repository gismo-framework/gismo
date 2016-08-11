<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 14.07.16
     * Time: 11:45
     */

    namespace Gismo\Test\Component;

   

    use Gismo\Component\PhpFoundation\PropDef\ArrayPropDef;
    use Gismo\Component\PhpFoundation\PropDef\ObjectPropDef;

    class TestObject {

        public $bla;
        public $blub;

        /**
         * @var Test2Object
         */
        public $test2;

        /**
         * @var Test2Object[]
         */
        public $test2arr = [];

        public function __getPrototypes() {
            return [
                    "test2"    => new ObjectPropDef(Test2Object::class),
                    "test2arr" => new ArrayPropDef(new ObjectPropDef(Test2Object::class), 1, 10)
            ];
        }

    }

    class Test2Object {
        public $a;
        public $b;

    }

    class ObjectAccessorTest extends \PHPUnit_Framework_TestCase {

        function testFill() {
            $obj = new TestObject();
            $data = [
                    "bla"      => "BLA",
                    "blub"     => "BLUB",
                    "test2"    => [
                            "a" => "abc",
                            "b" => "def"
                    ],
                    "test2arr" => [
                            [
                                    "a" => "abc",
                                    "b" => "def"
                            ],
                            [
                                    "a" => "abc",
                                    "b" => "def"
                            ]
                    ]
            ];
            $ret = goObject($obj);
            $ret->fill($data);
            $this->assertEquals($ret->obj->bla, $data["bla"]);
            $this->assertEquals($ret->obj->blub, $data["blub"]);
            $this->assertEquals($ret->obj->test2->a, $data["test2"]["a"]);
            $this->assertEquals($ret->obj->test2->b, $data["test2"]["b"]);
            $this->assertEquals($ret->obj->test2arr[0]->a, $data["test2arr"][0]["a"]);
            $this->assertEquals($ret->obj->test2arr[1]->b, $data["test2arr"][1]["b"]);
        }

    }
