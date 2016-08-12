<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.08.16
     * Time: 19:32
     */

    namespace Gismo\Component\Route;


    use Doctrine\Instantiator\Exception\InvalidArgumentException;
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
    class GoAction {
        /**
         * @var DiContainer
         */
        private $mDi;

        /**
         * @var callable
         */
        private $fn;


        /**
         * @var GoRoute|null
         */
        private $mRoute;


        /**
         * @var null|string
         */
        private $mBindName = null;


        /**
         * @var null|ArrayAccessOrderedList
         */
        private $beforeCall = null;

        /**
         * @var null|ArrayAccessOrderedList
         */
        private $afterCall = null;


        /**
         * @var null|ArrayAccessOrderedList
         */
        private $inputFilters = null;

        /**
         * @var null|ArrayAccessOrderedList
         */
        private $outputFilters = null;


        public function __get($name) {
            switch ($name) {
                case "beforeCall":
                    if ($this->beforeCall === null)
                        $this->beforeCall = new ArrayAccessOrderedList();
                    return $this->beforeCall;

                case "afterCall":
                    if ($this->afterCall === null)
                        $this->afterCall = new ArrayAccessOrderedList();
                    return $this->afterCall;

                case "inputFilters":
                    if ($this->inputFilters === null)
                        $this->inputFilters = new ArrayAccessOrderedList();
                    return $this->inputFilters;

                case "outputFilters":
                    if ($this->outputFilters === null)
                        $this->outputFilters = new ArrayAccessOrderedList();
                    return $this->outputFilters;
            }
            throw new InvalidArgumentException("Property '$name' not existing");
        }

        public function __set($name, $value) {
            throw new InvalidArgumentException("Setting value ($name) on GoAction not allowed");
        }


        public function __construct(DiContainer $di, GoRoute $route=null) {
            $this->mDi = $di;
            $this->mRoute = $route;
        }


        
        public function callback(callable $fn) : self {
            $this->fn = $fn;
            return $this;
        }
        


        public function bind($name) : self {
            if ($this->mBindName !== null)
                throw new \InvalidArgumentException("Cannot bind to more than one name (maybe missing feature?)");
            $this->mBindName = $name;
            $this->mDi[$name] = $this->mDi->constant($this);
            return $this;
        }

        /**
         * @return string|null
         */
        public function getBindName() : string {
            return $this->mBindName;
        }


        public function getLink(array $params = []) : string {
            //$this->mRoute->

        }

        public function getLinkAbs (array $params = []) : string {

        }

        public function __invoke(array $params = []) {

        }

        private $mUseHttpBodyAsParameter = null;

        public function useHttpBodyAsParameter($name) : self {
            $this->mUseHttpBodyAsParameter = $name;
            return $this;
        }

        public function useGetMap(array $map) : self {
            return $this;
        }


        protected function _runInputFilters(RouterRequest $request, array &$params) {
            if ($this->inputFilters === null)
                return  $params;
            $this->inputFilters->each(function ($what, $prio) {

            });
        }

        protected function _runOutputFilters(RouterRequest $request, $output) {

        }


        public function dispatch(RouterRequest $request) {
            if ($this->mRoute === null)
                throw new InvalidArgumentException("Action has no route assigned. Use call() instead of dispatch() for direct calling");

            $params = $this->mRoute->buildParams($request);
            $this->_runInputFilters($request, $params);

            $output = $this->__invoke($params);

            return $this->_runOutputFilters($request, $output);
        }

        public function __debugInfo() {
            return [
                "route" => (string)$this->mRoute,
                "bindName" => $this->getBindName(),
            ];
        }
    } 