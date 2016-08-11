<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.07.16
     * Time: 14:40
     */

    namespace Gismo\Test\Component;


    

    use Gismo\Component\PhpFoundation\Accessor\Ex\ApplyFailedException;
    use Gismo\Component\PhpFoundation\Accessor\Value\DebugValue;
    use Gismo\Component\PhpFoundation\Accessor\Value\UNESCAPEDValue;

    class StringAccessorApplyTest extends \PHPUnit_Framework_TestCase {

        
        
        public function testApplyQuestionMarks () {
            $this->assertEquals("Some 'data'",          (string)goString("Some ?")->apply(["data"]));
            $this->assertEquals("Some array '1','2'",   (string)goString("Some array ??")->apply([["1", "2"]]));

            $this->assertEquals("Some 'data' and 'data2'",  (string)goString("Some ? and ?")->apply(["data", "data2"]));
            $this->assertEquals("Some '1','2' and '3','4'", (string)goString("Some ?? and ??")->apply([["1","2"], ["3","4"]]));
        }


        public function testApplyKeys () {
            $this->assertEquals("Some 'data'",          (string)goString("Some :key")->apply(["key" => "data"]));
            $this->assertEquals("Some array '1','2'",   (string)goString("Some array ::key")->apply(["key" =>["1", "2"]]));

            $this->assertEquals("Some 'data' and 'data2'",  (string)goString("Some :key1 and :key2")->apply(["key1" =>"data", "key2" =>"data2"]));
            $this->assertEquals("Some '1','2' and '3','4'", (string)goString("Some ::key1 and ::key2")->apply(["key1" => ["1","2"], "key2" => ["3","4"]]));
        }


        public function testCorrectEscaping () {
            $this->assertEquals("some '\\'' data",  (string)goString("some ? data")->apply(["'"]));
            $this->assertEquals("some ' data",      (string)goString("some ? data")->apply([new UNESCAPEDValue("'")]));
        }

        public function testChangedDefaultEscaping () {
            $this->assertEquals("some ''' data",                (string)goString("some ? data")->apply(["'"], DebugValue::class));
            $this->assertEquals("some object(stdClass) data",   (string)goString("some ? data")->apply([new \stdClass()], DebugValue::class));

        }

        public function testThrowsOnOffsetMissing () {
            $this->setExpectedException(ApplyFailedException::class);
            goString("Some ? ? ")->apply(["1"]);

        }

        public function testThrowsOnKeyMissing () {
            $this->setExpectedException(ApplyFailedException::class);
            goString("Some :missingKey ")->apply(["key" =>"1"]);
        }
    }
