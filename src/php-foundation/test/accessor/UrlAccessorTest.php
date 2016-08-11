<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.07.16
     * Time: 18:35
     */

    namespace Gismo\Test\Component;


    class UrlAccessorTest extends \PHPUnit_Framework_TestCase {
        
        public function testUrlParsing () {
            $url = goUrl("http://user:pass@host:80/path/to?query=test#fragment");
            $this->assertEquals("http", (string)$url->scheme);
            $this->assertEquals("user", (string)$url->user);
            $this->assertEquals("pass", (string)$url->pass);
            $this->assertEquals("host", (string)$url->host);
            //$this->assertEquals(80, $url->port->asInt());
            $this->assertEquals("/path/to", (string)$url->path);
            $this->assertEquals("query=test", (string)$url->queryString);
            $this->assertEquals("test", (string)$url->query["query"]->expectString());
            $this->assertEquals("fragment", (string)$url->fragment);

        }


        public function testToString() {
            $testUrl = "http://user:pass@host:80/path/to?query=test#fragment";
            $url = goUrl($testUrl);
            self::assertEquals($testUrl, (string)$url);
        }

        public function testChangeQuery() {
            $testUrl = "http://host/?query=test";
            $url = goUrl($testUrl);
            $url->query["query"] = "changed";
            self::assertEquals("http://host/?query=changed", (string)$url);
        }

        public function testChangePath() {
            $testUrl = "http://host/some/path";
            $url = goUrl($testUrl);
            $url->path = "/some/other/path";
            self::assertEquals("http://host/some/other/path", (string)$url);
        }
        
    }
