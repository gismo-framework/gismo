<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 17:30
     */


    namespace Gismo\Component\Route\Route;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Route\Ex\NoRouteDefinedException;
    use Gismo\Component\Route\GoAction;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoRouter {


        /**
         * @var DiContainer
         */
        private $di;

        /**
         * @var GoRouteContainer
         */
        private $container;


        private $routes = [
            "GET" => [],
            "POST" => [],
            "PUT"   => [],
            "DELETE" => [],
            "HEADER" => [],
            "*" => []
        ];


        public function __construct(DiContainer $container) {
            $this->di = $container;
            $this->container = new GoRouteContainer();
        }


        private function _extractMethod (string &$in) {
            if (strpos($in, "@") === null)
                return "*";
            $method = substr($in, 0, strpos($in, "@"));
            $in = substr($in, strpos($in, "@")+1);
            return $method;
        }
        
        private function _routeToPreg ($in) {
            $in = preg_replace("|\\:\\:([a-zA-Z0-9\\_]+)|", '(?<$1>.*)', $in);
            $in = preg_replace("|\\:([a-zA-Z0-9\\_]+)|", '(?<$1>[^/])', $in);
            return $in;
        }

        private function _routeToWeight (string $in) : int {
            // Replace Variables with Empty Space and count words
            return substr_count($in, "/");
        }


        public function addPreg (string $regex, int $weight, GoAction $action, $method="*") : GoAction {
            if ( ! isset ($this->routes[$method]))
                throw new \Exception("Invalid Method '$method' in RegEx-Route '$regex'");
            $this->routes[$method][] = [$weight, $regex, $action];
            return $action;
        }


        public function add($route, GoAction $action, $weight=null) : GoAction {
            $method = $this->_extractMethod($route);
            
            if ($weight === null)
                $weight = $this->_routeToWeight($route);
            
            $this->addPreg($this->_routeToPreg($route), $weight, $action, $method);
            return $action;
        }


        public function dispatch (RouterRequest $request) {
            $action = $this->container->findBestAction($request);
            if ($action === null)
                throw new NoRouteDefinedException(["No route defined for ?", (string)$request]);
            $action->dispatch($request);
        }


    }