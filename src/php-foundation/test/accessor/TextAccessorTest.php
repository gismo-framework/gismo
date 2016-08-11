<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 11:29
     */

    namespace Gismo\Test\Component;


    class TextAccessorTest extends \PHPUnit_Framework_TestCase {

        public function testGetIndent() {
            $text = "   \n\t SomeIndentedText\n\t Some Other line";
            $this->assertEquals("\t ", goText($text)->getIndent());
        }

        public function testUnIndent() {
            $text = "   \n\t SomeIndentedText\n\t Some Other line";
            $this->assertEquals("   \nSomeIndentedText\nSome Other line", (string)goText($text)->unindent());
        }


        public function testIndent() {
            $text ="Some unindentet Text\nNextLine";
            $this->assertEquals("\tSome unindentet Text\n\tNextLine", (string)goText($text)->indent());
        }


        public function testWrapDocComment () {
            $text = "Some Text\nNextLine";
            $this->assertEquals("/**\n * Some Text\n * NextLine\n */", (string)goText($text)->wrapDocDomment());
        }

        public function testUnWrapDocComment () {
            $text = "/**\n * Some Text\n * NextLine\n */";
            $this->assertEquals("Some Text\nNextLine", (string)goText($text)->unwrapDocComment());
        }


        public function testSlice () {
            $text = "Line1\nLine2\nLine3";
            $this->assertEquals("Line2\nLine3", (string)goText($text)->slice(1, 2));
        }


        public function testAppend () {
            $t = goText("Line1");

            $t[] = "Line2\nLine3";

            $t["@"] = " Some Content";

            $this->assertEquals("Line1\nLine2\nLine3 Some Content", (string)$t);
        }



        public function testAppendWithIndent() {
            $t = goText();
            $t[] = "Line1";
            $t["+"] = "Line2";
            $t[] = "Line3";
            $t[">"] = "Line4";
            $t[] = "Line5";
            $t["-"] = "Line6";

            $t["+"];
            $t[] = "Line7";

            $t["-"];
            $t[] = "Line8";

            $this->assertEquals(
                "Line1" .
                "\n\tLine2" .
                "\n\tLine3" .
                "\n\t\tLine4" .
                "\n\tLine5" .
                "\nLine6" .
                "\n\tLine7" .
                "\nLine8",
                (string)$t
            );

        }

    }
