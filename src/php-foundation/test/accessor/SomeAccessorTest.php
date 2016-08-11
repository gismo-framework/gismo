<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 18:46
     */

    namespace Gismo\Test\Component;


    


    use Gismo\Component\PhpFoundation\Accessor\BooleanAccessor;
    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\NumberAccessor;
    use Gismo\Component\PhpFoundation\Accessor\ObjectAccessor;
    use Gismo\Component\PhpFoundation\Accessor\SomeAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StringAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StructAccessor;
    use Gismo\Test\Component\PhpFoundation\_mock\Dummy;


    class SomeAccessorTest extends \PHPUnit_Framework_TestCase {

        public function testSome() {
           /*
            $var = TRUE;

            \pf\some($var)->expectBoolean();
            
            
            some("TRUE")->expectBoolean()->isTrue();
            
            
            some("1")->expectBoolean()->isTrue();
            some("FALSE")->expectBoolean()->isFalse();
            some("0")->expectBoolean()->isFalse();

            some("1.74")->expectNumber()->expectFloat();
            some(123)->expectNumber()->expectFloat();

            some(123)->expectIn(["abc", 123]);

            some("2017-04-05")->expectDate()->expectBetween();

            some("/some/localFile")->expectLocalFile()->getContents();

            $file = some("http://localhost/someFile")->expectRemoteFile()->download()->fopen("r");
            while ( ! $file->feof()) {

            }
            
            some("http://localhost/some/Path")->expectUrl()->path->expectSubPathOf("/some");

            some("192.168.90.1")->expectIp()->expectIpV4()->expectInSubnet("192.168.90.0/24");

            some("localhost")->expectHostName()->resolve()->expectIpV4()->asserInSubnet("127.0.0.0/8");
*/

        }

        public function testExpectData () {
            $type = "string";

            $expectations = [
                "boolean" => "expectBoolean",
                "number" => "expectNumber",
                "string" => "expectString",
                "object" => "expectObject",
                "struct" => "expectStruct"
            ];
            $validTestData = [
                "boolean" => [
                    NULL, TRUE, FALSE, 0, "0", 1, "1", "YES", "yes", "NO", "no", "TRUE", "true", "FALSE", "false"
                ],
                "number" => [
                    NULL, 0, "0", 1, "1", -1000, "-1000", 1000, "1000", 0.01, "0.01", 0.01, "0.01", -0.01, "-0.01"
                ],
                "string" => [
                    NULL, "", " ", "irgeneineZeichenkette", "_üäö?#"
                ],
                "object" => [
                    NULL, new Dummy()
                ],
                "struct" => [
                    NULL, 
                    [],
                    ["paula", "peter"],
                    [
                            "paula" => "nett",
                            "peter" => "doof"
                    ]
                ]
            ];
            $inValidTestData = [
                "boolean" => [
                    "falsch", [], 1000, new Dummy()
                ],
                "number" => [
                    "falsch", [], new Dummy(), 0.01, "0.01", "_üäö?#", TRUE, FALSE
                ],
                "string" => [
                    [], new Dummy(), TRUE, FALSE
                ],
                "object" => [
                    TRUE, FALSE, "falsch", []
                ],
                "struct" => [
                    "falsch", new Dummy(), 0.01, "0.01", "_üäö?#", TRUE, FALSE
                ]
            ];

            $expectReturnClass = [
                "boolean" => BooleanAccessor::class,
                "number" => NumberAccessor::class,
                "string" => StringAccessor::class,
                "object" => ObjectAccessor::class,
                "struct" => StructAccessor::class
            ];

            foreach ($expectations as $expectationId => $expectationFunctionCall) {
                foreach ($validTestData[$expectationId] as $validTestInput) {
                    $ret = goSome($validTestInput)->$expectationFunctionCall();
                    $this->assertEquals($expectReturnClass[$expectationId], get_class($ret), "Testing '{$expectationId}' with valid value: ". print_r($validTestInput, TRUE));
                }
            }

            foreach ($expectations as $expectationId => $expectationFunctionCall) {
                foreach ($inValidTestData[$expectationId] as $inValidTestInput) {
                    try {
                        $ret = goSome($inValidTestInput)->$expectationFunctionCall();
                        $this->fail("Missing exception on testing '{$expectationId}' with invalid value: ". print_r($inValidTestInput, TRUE));
                    } catch (\Exception $ex) {
                        $this->assertEquals(ExpectationFailedException::class, get_class($ex), "Testing '{$expectationId}' with invalid value: ". print_r($inValidTestInput, TRUE));
                    }
                }
            }
            
            
        }


        public function testExpectNotNullThrowsCorrectException () {
            $nullVall = NULL;
            
            $this->setExpectedException(ExpectationFailedException::class, "Expected not NULL value");

            goSome($nullVall)->expectNotNull();
        }

        public function testExpectNotNullReturnCorrectAccessor () {
            $notNullVall = "nichtNull";            

            $this->assertEquals(SomeAccessor::class, get_class(goSome($notNullVall)->expectNotNull()));
        }
        
        public function testIsNull () {
            $nullVall = NULL;
            $notNullVall = "nichtNull";
            
            $this->assertTrue(goSome($nullVall)->isNull());
            $this->assertFalse(goSome($notNullVall)->isNull());
        }


        
        
    }
