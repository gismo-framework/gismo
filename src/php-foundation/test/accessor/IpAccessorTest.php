<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 25.07.16
     * Time: 16:42
     */

    namespace Gismo\Test\Component;



    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    class IpAccessorTest extends \PHPUnit_Framework_TestCase {

        
        public function testIsInSubnet () {
            $this->assertTrue(goIp("46.183.102.2")->isIpv4());
            $this->assertFalse(goIp("46.183.102.2")->isIpv6());
            $this->assertFalse(goIp("::bd")->isIpv4());
            $this->assertTrue(goIp("::1")->isIpv6());

            $this->assertFalse(goIp("46.183.102.2")->isPrivateSpace());
            $this->assertTrue(goIp("192.168.90.1")->isPrivateSpace());
            $this->assertTrue(goIp("10.11.12.14")->isPrivateSpace());


            $this->assertTrue(goIp("192.168.90.4")->isInSubnet("192.168.90.0/24"));

            $this->assertTrue(goIp("46.183.102.4")->isInSubnet("0.0.0.0/0"));
            $this->assertFalse(goIp("1.2.3.4")->isInSubnet("0.0.0.0/32"));
        }


        public function testExpectIsInSubnet () {
            goIp("46.183.102.2")->expectIsInSubnet("46.183.102.0/24");
        }

        public function testExpectIsInSubnetThrowsException () {
            $this->setExpectedException(ExpectationFailedException::class);
            goIp("46.183.102.2")->expectIsInSubnet("46.183.99.0/24");

        }

    }
