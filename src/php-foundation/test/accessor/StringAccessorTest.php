<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 29.06.16
     * Time: 14:25
     */

    namespace Gismo\Test\Component;


    

    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\StringAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StructAccessor;

    class StringAccessorTest extends \PHPUnit_Framework_TestCase {

        public function testCallPhpFunction() {
            $funcName = "str_pad";
            $funcParams = [20, "_", STR_PAD_LEFT];
            $fill = "_";
            $funcParamsAccessor = [20, goString($fill), STR_PAD_LEFT];
            $funcParamsFalse = [20, goSome($fill), STR_PAD_LEFT];

            $str = "Guten Tag!";
            $this->assertEquals(StringAccessor::class, get_class(goString($str)->callPhpFunction($funcName, $funcParams)));
            $this->assertEquals(StringAccessor::class, get_class(goString($str)->callPhpFunction($funcName, $funcParamsAccessor)));

            try {
                $ret = goString($str)->callPhpFunction($funcName, $funcParamsFalse);
                $this->fail("Missing exception on testing '{$funcName}' with invalid value: " . print_r($funcParams, TRUE));
            } catch (\Exception $ex) {
                $this->assertEquals(\InvalidArgumentException::class, get_class($ex), "Testing '{$funcName}' with invalid value: " . print_r($funcParams, TRUE));
            }

        }


        public function testExpectData() {

            $expectations = [
                    "notNull"          => "expectNotNull",
                    "startsWith"       => "expectStartsWith",
                    "endsWith"         => "expectEndsWith",
                    "pregMatch"        => "expectPregMatch",
                    "strLengthBetween" => "expectStrLengthBetween",
                    "minLength"        => "expectMinLength",
                    "maxLength"        => "expectMaxLength"
            ];
            $validTestData = [
                    "notNull"          => [
                            "nichtNull"
                    ],
                    "startsWith"       => [
                            "abcdef", "apfel", "alle Leute tanzen"
                    ],
                    "endsWith"         => [
                            "abcdef", "Hof", "Ein kleines Dorf"
                    ],
                    "pregMatch"        => [
                            "abcdef"
                    ],
                    "strLengthBetween" => [
                            "Der Text ist kurz", "Hallo"
                    ],
                    "minLength"        => [
                            "Der Text ist mindestens 5 Zeichen lang", "12345"
                    ],
                    "maxLength"        => [
                            "Max. 15 Zeichen", "Hallo"
                    ]
            ];
            $inValidTestData = [
                    "notNull"          => [
                            NULL
                    ],
                    "startsWith"       => [
                            "Apfel", "Kein A", ""
                    ],
                    "endsWith"         => [
                            "ABCDEF", "Höfchen"
                    ],
                    "pregMatch"        => [
                            "lalalalalala", " "
                    ],
                    "strLengthBetween" => [
                            "Hi", "Der Text ist auf jeden Fall länger als 20 Zeichen, das sieht man doch", "1234567891011121314151617181920"
                    ],
                    "minLength"        => [
                            "Hi", "abc"
                    ],
                    "maxLength"        => [
                            "Hallöchen zusammen, dieser String muss lang genug werden, damit er die Maximallänge überschreitet"
                    ]
            ];
            $arguments = [
                    "notNull"          => [

                    ],
                    "startsWith"       => [
                            "a"
                    ],
                    "endsWith"         => [
                            "f"
                    ],
                    "pregMatch"        => [
                            "/bcde/"
                    ],
                    "strLengthBetween" => [
                            3, 20
                    ],
                    "minLength"        => [
                            5
                    ],
                    "maxLength"        => [
                            15
                    ]
            ];
            $expectReturnClass = [
                    "notNull"          => StringAccessor::class,
                    "startsWith"       => StringAccessor::class,
                    "endsWith"         => StringAccessor::class,
                    "pregMatch"        => StringAccessor::class,
                    "strLengthBetween" => StringAccessor::class,
                    "minLength"        => StringAccessor::class,
                    "maxLength"        => StringAccessor::class
            ];

            foreach ($expectations as $expectationId => $expectationFunctionCall) {
                foreach ($validTestData[$expectationId] as $validTestInput) {
                    $ret = goString($validTestInput)->$expectationFunctionCall(...$arguments[$expectationId]);
                    $this->assertEquals($expectReturnClass[$expectationId], get_class($ret), "Testing '{$expectationId}' with valid value: " . print_r($validTestInput, TRUE));
                }
            }

            foreach ($expectations as $expectationId => $expectationFunctionCall) {
                foreach ($inValidTestData[$expectationId] as $inValidTestInput) {
                    try {
                        $ret = goString($inValidTestInput)->$expectationFunctionCall(...$arguments[$expectationId]);
                        $this->fail("Missing exception on testing '{$expectationId}' with invalid value: " . print_r($inValidTestInput, TRUE));
                    } catch (\Exception $ex) {
                        $this->assertEquals(ExpectationFailedException::class, get_class($ex), "Testing '{$expectationId}' with invalid value: " . print_r($inValidTestInput, TRUE));
                    }
                }
            }


        }


        public function testExplode() {
            $input = "Dies ist ein Test";
            $leer = " ";
            $ret = goString($input)->explode(" ");
            $this->assertEquals(StructAccessor::class, get_class($ret));
            $this->assertEquals(["Dies", "ist", "ein", "Test"], $ret->getValue());
        }

        public function testPregReplace() {
            $input = "abcdefgh";
            $ret = goString($input)->pregReplace("/abc/", "xyz");
            $this->assertEquals(StringAccessor::class, get_class($ret));
            $this->assertEquals("xyzdefgh", (string)$ret);
        }

        public function testPregReplaceCallback() {
            $input = "blablabla";
            $ret = goString($input)->pregReplaceCallback("/bl/", function ($matches) {
                return strtoupper($matches[0]);
            });
            $this->assertEquals(StringAccessor::class, get_class($ret));
            $this->assertEquals("BLaBLaBLa", (string)$ret);
        }


        public function testNotNull() {
            $nullVall = NULL;
            $notNullVall = "nichtNull";

            $this->assertTrue(goString($nullVall)->notNull());
            $this->assertFalse(goString($notNullVall)->notNull());
        }

        public function testStartsWith() {
            $str = "abc";
            $this->assertTrue(goString($str)->startsWith(''));
            $this->assertTrue(goString($str)->startsWith('a'));
            $this->assertTrue(goString($str)->startsWith('ab'));
            $this->assertTrue(goString($str)->startsWith('abc'));

            $this->assertFalse(goString($str)->startsWith('abcd'));
            $this->assertFalse(goString($str)->startsWith('bc'));
            $this->assertFalse(goString($str)->startsWith('c'));

            $this->assertTrue(goString($str)->startsWith(goString($str)));
        }

        public function testEndsWith() {
            $str = "abc";
            $this->assertTrue(goString($str)->endsWith(''));
            $this->assertTrue(goString($str)->endsWith('c'));
            $this->assertTrue(goString($str)->endsWith('bc'));
            $this->assertTrue(goString($str)->endsWith('abc'));

            $this->assertFalse(goString($str)->endsWith('abcd'));
            $this->assertFalse(goString($str)->endsWith('ab'));
            $this->assertFalse(goString($str)->endsWith('a'));

            $this->assertTrue(goString($str)->endsWith(goString($str)));
        }

        public function testSetDefault() {
            $input = NULL;
            $ret = goString($input)->setDefault("Test");
            $this->assertEquals(StringAccessor::class, get_class($ret));
            $this->assertEquals("Test", (string)$ret);
        }

    }
