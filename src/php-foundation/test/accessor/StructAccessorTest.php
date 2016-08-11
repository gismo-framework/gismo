<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 04.07.16
     * Time: 14:09
     */

    namespace Gismo\Test\Component;

    

    use Gismo\Component\PhpFoundation\Accessor\BooleanAccessor;
    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\NumberAccessor;
    use Gismo\Component\PhpFoundation\Accessor\ObjectAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StringAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StructAccessor;
    use Gismo\Test\Component\PhpFoundation\_mock\Dummy;

    class StructAccessorTest extends \PHPUnit_Framework_TestCase {

        public function testExpects() {
            $expectations = [
                    "boolean" => "expectBoolean",
                    "number"  => "expectNumber",
                    "string"  => "expectString",
                    "object"  => "expectObject",
                    "struct"  => "expectStruct"
            ];
            $validTestData = [
                    "boolean" => [
                            ["elem1" => ["null", ["elem2" => [TRUE]]]],
                            ["elem1" => ["null", ["elem2" => [FALSE]]]],
                            ["elem1" => ["null", ["elem2" => [0]]]],
                            ["elem1" => ["null", ["elem2" => [1]]]]
                    ],
                    "number"  => [
                            ["elem1" => ["null", ["elem2" => [0]]]],
                            ["elem1" => ["null", ["elem2" => ["0"]]]],
                            ["elem1" => ["null", ["elem2" => [1]]]],
                            ["elem1" => ["null", ["elem2" => ["1"]]]],
                            ["elem1" => ["null", ["elem2" => [-0.01]]]],
                            ["elem1" => ["null", ["elem2" => ["-0.01"]]]]
                    ],
                    "string"  => [
                            ["elem1" => ["null", ["elem2" => [""]]]],
                            ["elem1" => ["null", ["elem2" => [" "]]]],
                            ["elem1" => ["null", ["elem2" => ["irgeneineZeichenkette"]]]],
                            ["elem1" => ["null", ["elem2" => ["_üäö?#"]]]]
                    ],
                    "object"  => [
                            ["elem1" => ["null", ["elem2" => [new Dummy()]]]]
                    ],
                    "struct"  => [
                            ["elem1" => ["null", ["elem2" => [[]]]]],
                            ["elem1" => ["null", ["elem2" => [["paula", "peter"]]]]],
                            ["elem1" => ["null", ["elem2" => [["paula" => "nett", "peter" => "doof"]]]]]
                    ]
            ];
            $inValidTestData = [
                    "boolean" => [
                            ["elem1" => ["null", ["elem2" => ["falsch"]]]],
                            ["elem1" => ["null", ["elem2" => [[]]]]],
                            ["elem1" => ["null", ["elem2" => [1000]]]],
                            ["elem1" => ["null", ["elem2" => [new Dummy()]]]]
                    ],
                    "number"  => [
                            ["elem1" => ["null", ["elem2" => ["falsch"]]]],
                            ["elem1" => ["null", ["elem2" => [[]]]]],
                            ["elem1" => ["null", ["elem2" => [new Dummy()]]]],
                            ["elem1" => ["null", ["elem2" => ["_üäö?#"]]]],
                            ["elem1" => ["null", ["elem2" => [TRUE]]]]
                    ],
                    "string"  => [
                            ["elem1" => ["null", ["elem2" => [[]]]]],
                            ["elem1" => ["null", ["elem2" => [new Dummy()]]]],
                            ["elem1" => ["null", ["elem2" => [FALSE]]]]

                    ],
                    "object"  => [
                            ["elem1" => ["null", ["elem2" => [TRUE]]]],
                            ["elem1" => ["null", ["elem2" => ["falsch"]]]],
                            ["elem1" => ["null", ["elem2" => [[]]]]]
                    ],
                    "struct"  => [
                            ["elem1" => ["null", ["elem2" => ["falsch"]]]],
                            ["elem1" => ["null", ["elem2" => [new Dummy()]]]],
                            ["elem1" => ["null", ["elem2" => [0.01]]]],
                            ["elem1" => ["null", ["elem2" => [TRUE]]]]
                    ]
            ];

            $expectReturnClass = [
                    "boolean" => BooleanAccessor::class,
                    "number"  => NumberAccessor::class,
                    "string"  => StringAccessor::class,
                    "object"  => ObjectAccessor::class,
                    "struct"  => StructAccessor::class
            ];

            foreach ($expectations as $expectationId => $expectationFunctionCall) {
                foreach ($validTestData[$expectationId] as $validTestInput) {
                    $ret = goStruct($validTestInput)["elem1"][1]["elem2"][0]->$expectationFunctionCall();
                    $this->assertEquals($expectReturnClass[$expectationId], get_class($ret), "Testing '{$expectationId}' with valid value: " . print_r($validTestInput, TRUE));
                }
            }

            foreach ($expectations as $expectationId => $expectationFunctionCall) {
                foreach ($inValidTestData[$expectationId] as $inValidTestInput) {
                    try {
                        $ret = goStruct($inValidTestInput)["elem1"][1]["elem2"][0]->$expectationFunctionCall();
                        $this->fail("Missing exception on testing '{$expectationId}' with invalid value: " . print_r($inValidTestInput, TRUE));
                    } catch (\Exception $ex) {
                        $this->assertEquals(ExpectationFailedException::class, get_class($ex), "Testing '{$expectationId}' with invalid value: " . print_r($inValidTestInput, TRUE));
                    }
                }
            }
        }

        public function testExpectNotNull() {
            $nullVall = ["elem1" => ["null", ["elem2" => [NULL]]]];
            $notNullVall = ["elem1" => ["null", ["elem2" => ["nichtNull"]]]];

            $this->assertEquals(StructAccessor::class, get_class(goStruct($notNullVall)["elem1"][1]["elem2"][0]->expectNotNull()));

            try {
                $ret = goStruct($nullVall)["elem1"][1]["elem2"][0]->expectNotNull();
                $this->fail("Missing exception on testing 'expectMotNull' with invalid value: " . print_r($nullVall, TRUE));
            } catch (\Exception $e) {
                $this->assertEquals(ExpectationFailedException::class, get_class($e), "Testing 'ExpectNotNull' with invalid value: " . print_r($nullVall, TRUE));

            }
        }

        public function testSetValue() {
            $arr = [];
            $ret = goStruct($arr);
            $ret["name"][] = "Franz";
            $this->assertEquals(StructAccessor::class, get_class($ret));
            $this->assertEquals("Franz", (string)$ret["name"][0]->expectString());
        }

    }