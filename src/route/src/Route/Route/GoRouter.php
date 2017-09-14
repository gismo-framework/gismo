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
        }


        private function _extractMethod (string &$in) {
            if (strpos($in, "@") === false)
                return "*";
            $method = substr($in, 0, strpos($in, "@"));
            $in = substr($in, strpos($in, "@")+1);
            return $method;
        }
        
        private function _routeToPreg ($in) {
            $in = preg_replace("|\\:\\:([a-zA-Z0-9\\_]+)|", '(?<$1>.*)', $in);
            $in = preg_replace("|\\:([a-zA-Z0-9\\_]+)|", '(?<$1>[^/]*)', $in);
            return $in;
        }

        private function _routeToWeight (string $in) : int {
            // Weight is number of elements
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

            if (substr($route, 0, 1) !== "/")
                throw new \InvalidArgumentException("Route must start with '/'. Found: '$route'");
            $route = substr ($route, 1);

            if ($weight === null)
                $weight = $this->_routeToWeight($route);
            
            $this->addPreg($this->_routeToPreg($route), $weight, $action, $method);
            return $action;
        }

        
        public function _getAction (RouterRequest $request, $method, &$params) {
            foreach ($this->routes[$method] as $curRoute) {
                if ( preg_match ("|^{$curRoute[1]}$|", $request->route, $matches) ) {
                    $params = [];
                    // Strip nummeric keys
                    foreach ($matches as $key => $value) {
                        if (is_int($key))
                            continue;
                        $params[$key] = $matches[$key];
                    }
                    return $curRoute[2];
                }
            }
            return false;
        }
        

        public function dispatch (RouterRequest $request) {
            // Sort Routes so the most specific (highest weight) come first
            foreach ($this->routes as $key => $val) {
                uasort($this->routes[$key], function ($a, $b) {
                    if ($a[0] == $b[0])
                        return 0;
                    return  $a[0] > $b[0] ? -1 : 1;
                });
            }
            //print_r ($this->routes["*"]);
            if ( ($action = $this->_getAction($request, $request->method, $params)) === false) {
                if (($action = $this->_getAction($request, "*", $params)) === false ) {
                    throw new NoRouteDefinedException(["No route defined for: ?@?", $request->method, $request->route]);
                }
            }
            /* @var $action GoAction  */
            $action->dispatch($request, $params);
        }


    }