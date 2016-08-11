<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.07.16
     * Time: 14:14
     */

    namespace Gismo\Test\Component;


    

    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\PathAccessor;

    class PathAccessorTest extends \PHPUnit_Framework_TestCase {


        public function testTransformBackslashToSlash () {
            $this->assertEquals("/some/path", (string)goPath("\\some\\path"));
        }

        public function testResolve() {
            $this->assertEquals("/some/path", (string)goPath("/some/../some/./path")->resolve());
            $this->assertEquals("/some/path", (string)goPath("/some/../../../some/./path")->resolve());
            
            $this->assertEquals("/some/path/subdir",    (string)goPath("/some/../../../some/./path")->resolve("/subdir"));
            $this->assertEquals("some/path/subdir",     (string)goPath("some/../../../some/./path")->resolve("/subdir"));
        }



        public function testLast () {
            $this->assertEquals("last", (string)goPath("/some/part/last")->last());
            $this->assertEquals("", (string)goPath("/some/part/last/")->last());
        }

        public function testFirst () {
            $this->assertEquals("some", (string)goPath("some/part/last")->first());
            $this->assertEquals("", (string)goPath("/some/part/last")->first());
        }

        public function testToAbsolutePath () {
            $this->assertEquals("/prefix/some/path", (string)goPath("some/path")->toAbsolutePath("/prefix"));
            $this->assertEquals("/prefix/some/path", (string)goPath("some/path")->toAbsolutePath("prefix"));
        }


        public function testToRelativePath () {
            $this->assertEquals("some/path", (string)goPath("/prefix/some/path")->toRelativePath("/prefix"));
            $this->assertEquals("../some/path", (string)goPath("/some/path")->toRelativePath("/prefix"));


            // Is already relative
            $this->assertEquals("some/path", (string)goPath("some/path")->toRelativePath("prefix/other"));


            $this->assertEquals("../path", (string)goPath("/some/path")->toRelativePath("/some/other"));
            $this->assertEquals("../../", (string)goPath("/")->toRelativePath("/some/other"));
        }


        public function testIsRelativePath () {
            $this->assertTrue(goPath("/some/path")->isAbsolute());
            $this->assertFalse(goPath("some/path")->isAbsolute());

            $this->assertFalse(goPath("/some/path")->isRelative());
            $this->assertTrue(goPath("some/path")->isRelative());
        }


        public function testIsSubPathOf() {
            // Positivtests
            $this->assertTrue(goPath("/some/absolute/path")->isSubPathOf("/some/absolute"));
            $this->assertTrue(goPath("some/absolute/path")->isSubPathOf("some/absolute"));
            $this->assertTrue(goPath("/some/../../absolute/path")->isSubPathOf("/absolute"));

            // NegativTests
            $this->assertFalse(goPath("/some/absolute/../path")->isSubPathOf("/some/absolute"));
            $this->assertFalse(goPath("some/absolute/path")->isSubPathOf("/some/absolute"));
            $this->assertFalse(goPath("some/absolute/path")->isSubPathOf("/some/absolute"));
        }


        public function testExpectSubPathOfPassesCorrectObject() {
            $this->assertEquals(PathAccessor::class, get_class(goPath("/some/absolute/path")->expectIsSubPathOf("/some/absolute")));
        }

        public function testExpectIsSubPathOfThrowsCorrectException () {
            $this->setExpectedException(ExpectationFailedException::class);
            goPath("/some/../absolute")->expectIsSubPathOf("/some/absolute");
        }


        public function testAsArray () {
            self::assertEquals([], goPath("/")->asArray());
            self::assertEquals(["some", "path"], goPath("some/path")->asArray());
            self::assertEquals(["some", "path"], goPath("/some/path")->asArray());
        }



    }
