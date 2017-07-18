<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:35
     */

    namespace Gismo\Test\Component\Route;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Route\GoAction;
    use Gismo\Component\Route\Route\GoRouteDefinition;
    use Gismo\Component\Route\Route\GoRouter;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoRouteTest extends \PHPUnit_Framework_TestCase {



        public function testEmptyRoute () {
            $router = new GoRouter($di = new DiContainer());

            $action = new GoAction($di, "/");
            $action[] = function ($someName) {
                echo "Wurst" . $someName;
            };
            
            
            $router->add("::someName", $action);
            $router->add("static/::someName", $action);
            
            $router->dispatch(new RouterRequest(["a", "b"]));

        }
    }
