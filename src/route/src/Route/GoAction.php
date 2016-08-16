<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.08.16
     * Time: 19:32
     */

    namespace Gismo\Component\Route;


    use Gismo\Component\Di\DiCallChain;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\PhpFoundation\Type\ArrayAccessOrderedList;
    use Gismo\Component\Route\Route\GoRoute;
    use Gismo\Component\Route\Type\RouterRequest;

    /**
     * Class GoAction
     * @package Gismo\Component\Route
     *
     *
     * @property ArrayAccessOrderedList $inputFilters
     * @property ArrayAccessOrderedList $outputFilters
     */
    class GoAction extends DiCallChain {


        /**
         * @var GoRoute|null
         */
        private $mRoute;




        public function __construct(DiContainer $di, GoRoute $route=null) {
            parent::__construct($di);
            $this->mRoute = $route;
        }



        public function getLink(array $params = []) : string {
            //$this->mRoute->

        }

        public function getLinkAbs (array $params = []) : string {

        }




        public function dispatch(RouterRequest $request) {
            if ($this->mRoute === null)
                throw new \InvalidArgumentException("Action has no route assigned. Use call() instead of dispatch() for direct calling");

            $params = $this->mRoute->buildParams($request);
            $output = $this->__invoke($params);
        }

        public function __debugInfo() {
            return [
                "route" => (string)$this->mRoute
            ];
        }
    } 