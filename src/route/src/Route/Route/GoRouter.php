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

        public function __construct(DiContainer $container) {
            $this->di = $container;
            $this->container = new GoRouteContainer();
        }


        public function add(GoAction $action) : GoAction {

            $this->container->add($action, $action);
            return $action;
        }


        public function dispatch (RouterRequest $request) {
            $action = $this->container->findBestAction($request);
            if ($action === null)
                throw new NoRouteDefinedException(["No route defined for ?", (string)$request]);
            $action->dispatch($request);
        }


    }