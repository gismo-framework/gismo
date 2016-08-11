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
    use Gismo\Component\Route\Ex\NoRouteDefiniedException;
    use Gismo\Component\Route\Node\GoRouteNode;
    use Gismo\Component\Route\Route\GoRoute;
    use Gismo\Component\Route\Route\GoRouteContainer;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoDiService_Route_Property {



        /**
         * @var DiContainer
         */
        private $di;

        /**
         * @var GoRouteContainer
         */
        private $container;

        public function __construct(DiContainer $container) {
            $this->di = $container;
            $this->container = new GoRouteContainer();
        }


        private $mountedRoutePrefix = "";
        
        
        public function mount($route, callable $cb) {
            $oldMounted = $this->mountedRoutePrefix;
            $this->mountedRoutePrefix .= $route;
            ($this->di)($cb);
            $this->mountedRoutePrefix = $oldMounted;
        }
        
        
        public function add($route=null, callable $cb=null) : GoAction {
            $routeObj = null;
            if ($route !== null) {
                $route = $this->mountedRoutePrefix . $route;
                $routeObj = new GoRoute($route);
            }
            $action = new GoAction($this->di, $routeObj);
            if ($cb !== null)
                $action->callback($cb);
            if ($routeObj !== null) {
                $this->container->add($routeObj, $action);
            }
            return $action;
        }


        public function dispatch (RouterRequest $request) {
            $action = $this->container->findBestAction($request);
            if ($action === null)
                throw new NoRouteDefiniedException(["No route defined for ?", $request]);
            $action->dispatch($request);
        }

    }