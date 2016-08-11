<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:51
     */

    namespace Gismo\Test\Component\Route;


    use Gismo\Component\Route\Route\GoRouteComponent;

    class GoRouteComponentTest extends \PHPUnit_Framework_TestCase {


        public function testTypes () {

            $c = new GoRouteComponent("static");
            self::assertEquals(GoRouteComponent::TYPE_STATIC, $c->getType());
            self::assertEquals("static", $c->getStaticName());

            $c = new GoRouteComponent("::params");
            self::assertEquals(GoRouteComponent::TYPE_ARRAY_PARAM, $c->getType());
            self::assertEquals("params", $c->getParamName());

            $c = new GoRouteComponent(":param");
            self::assertEquals(GoRouteComponent::TYPE_PARAM, $c->getType());
            self::assertEquals("param", $c->getParamName());


        }

    }
