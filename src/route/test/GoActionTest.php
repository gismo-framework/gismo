<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:14
     */

    namespace Gismo\Test\Component\Route;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Route\GoAction;
    use Gismo\Component\Route\Route\GoRouteDefinition;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoActionTest extends \PHPUnit_Framework_TestCase {


        public function testMainActionExecutes () {
            $di = new DiContainer();
            $action = new GoAction($di, new GoRouteDefinition("/some/request"));

            $cb = $this->getMock(\stdClass::class, ["onAction"]);
            $cb->expects($this->once())
                    ->method("onAction")
                    ->will($this->returnValue("Some Value"));

            $action->callback([$cb, "onAction"]);


            $ret = $action->dispatch(new RouterRequest(["some", "request"]));


        }


    }
