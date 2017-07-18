<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.08.16
     * Time: 20:11
     */


    namespace Gismo\Component\Route;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Route\Ex\NoRouteDefinedException;
    use Gismo\Component\Route\Node\GoRouteNode;
    use Gismo\Component\Route\Route\GoRouteDefinition;
    use Gismo\Component\Route\Route\GoRouteContainer;
    use Gismo\Component\Route\Route\GoRouter;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoDiService_Route_Property {



        /**
         * @var DiContainer
         */
        private $di;

        /**
         * @var GoRouter
         */
        private $router;

        public function __construct(DiContainer $container) {
            $this->di = $container;
            $this->router = new GoRouter($this);
        }


        private $mountedRoutePrefix = "";
        
        
        public function mount($route, callable $cb) {
            $oldMounted = $this->mountedRoutePrefix;
            $this->mountedRoutePrefix .= $route;
            ($this->di)($cb);
            $this->mountedRoutePrefix = $oldMounted;
        }
        
        
        public function add(string $route, callable $cb, $bind=null) : GoAction {
            $route = $this->mountedRoutePrefix . $route;

            $action = new GoAction($this->di, $route);
            $action[0] = $cb;

            $this->router->add($route, $action);
            return $action;
        }


        public function dispatch (RouterRequest $request) {
            $this->router->dispatch($request);
        }

    }