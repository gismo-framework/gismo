<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.08.16
     * Time: 10:23
     */

    namespace Gismo\Test\Component\HttpFoundation;


    use Gismo\Component\HttpFoundation\Request\RequestFactory;

    class RequestFactoryTest extends \PHPUnit_Framework_TestCase {


        public function testRequestByUrl() {
            $req = RequestFactory::GetRequestByUrl("http://wurst/some/path?param=value");
            self::assertEquals("GET", $req->METHOD);
            self::assertEquals(["param"=>"value"], $req->GET->getValue());

            self::assertEquals("http://wurst/some/path?param=value", (string)$req->URL);
            self::assertEquals("/some/path", (string)$req->PATH_INFO);
        }

    }
