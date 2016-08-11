<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:35
     */

    namespace Gismo\Test\Component\Route;


    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Route\Route\GoRoute;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoRouteTest extends \PHPUnit_Framework_TestCase {



        public function testBuildParams () {
            $route = new GoRoute("/static/:name1/:name2/::name3");

            self::assertEquals(
                    ["name1" => "val1", "name2" => "val2", "name3" => ["val3", "val4"]],
                    $route->buildParams(new RouterRequest(["static", "val1", "val2", "val3", "val4"], "/"))
            );


        }
    }
