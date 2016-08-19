<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:40
     */

    namespace Gismo\Test\Component\Route;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Helper\StopWatch;
    use Gismo\Component\Route\GoAction;
    use Gismo\Component\Route\Route\GoRouteDefinition;
    use Gismo\Component\Route\Route\GoRouteComponent;
    use Gismo\Component\Route\Route\GoRouteContainer;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoRouteContainerTest extends \PHPUnit_Framework_TestCase {




        public function addRoute(string $routeName, GoRouteContainer $container) {
            $route = new GoRouteDefinition($routeName);
            $container->add($route, (new GoAction(new DiContainer(), $route))->bind($routeName));
        }

        public function assertRouteMatch(GoRouteContainer $container, array $requestRoute, string $mustEqalRouteName=null) {
            $ret = $container->findBestAction(new RouterRequest($requestRoute));

            if ($ret instanceof GoAction) {
                if ($ret->getBindName() !== $mustEqalRouteName) {
                    throw new \PHPUnit_Framework_AssertionFailedError("Failed asserting that Route '$requestRoute' will resolve to '$mustEqalRouteName'");
                }
                self::assertTrue(TRUE);
                return;
            }
            if ($ret === $mustEqalRouteName)
                return true;
            throw new \PHPUnit_Framework_AssertionFailedError("Route returned null where it should return '$mustEqalRouteName'");
        }


        public function testReturnTheRightAction() {
            $container = new GoRouteContainer();

            $this->addRoute("/static" , $container);
            $this->addRoute("/static/:param", $container);
            $this->addRoute("/static/:param/some", $container);
            $this->addRoute("/::", $container);
            $this->addRoute("/", $container);

            $this->assertRouteMatch($container, ["go", "to", "hell"], "/::");
            $this->assertRouteMatch($container, ["static"], "/static");
            $this->assertRouteMatch($container, ["static", "value"], "/static/:param");
            $this->assertRouteMatch($container, ["static", "value", "some"], "/static/:param/some");
            $this->assertRouteMatch($container, ["static", "value", "next"], "/::");
            $this->assertRouteMatch($container, [], "/");
        }

        public function testReturnTheRightActionII() {
            $container = new GoRouteContainer();

            $this->addRoute("/static" , $container);
            $this->addRoute("/static/:param", $container);
            $this->addRoute("/:param/someFile", $container);
            $this->addRoute("/", $container);

            $this->assertRouteMatch($container, ["go", "to", "hell"], null);
            $this->assertRouteMatch($container, ["static"], "/static");
            $this->assertRouteMatch($container, ["static", "value"], "/static/:param");
            $this->assertRouteMatch($container, ["static", "value", "next"], null);
            $this->assertRouteMatch($container, ["param", "someFile"], "/:param/someFile");
            $this->assertRouteMatch($container, [], "/");
        }


        public function testBenchmark() {
            $container = new GoRouteContainer();
            $sw = new StopWatch();
            for ($i=0; $i<1000; $i++) {
                $this->addRoute("/static/:param$i/:sub$i/static$i", $container);
            }
            $sw->echoLap("Adding 1000 Routes");

            for ($i=0; $i<1000; $i++) {
                $container->findBestAction(new RouterRequest(["static", "some", "other", "static$i"]));

            }
            $sw->echoLap("Finding 1000 Routes");
        }
    }
