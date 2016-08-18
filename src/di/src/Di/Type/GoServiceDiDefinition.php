<?php


    namespace Gismo\Component\Di\Type;

    use Gismo\Component\Di\DiContainer;

    class GoServiceDiDefinition extends GoAbstractDiDefinition {

        private $factory;

        private $instance = null;

        private $isResolved = false;

        

        public function __debugInfo() {
            $ret = parent::__debugInfo();
            $ret["factory"] = $this->factory;
            return $ret;
        }


        public function __construct(callable $factory) {
            $this->factory = $factory;
            $this->_autodetectReturnType($factory);
        }


        /**
         * Filters are only applied once on services.
         *
         * @param DiContainer $di
         * @return mixed|null
         */
        public function __diGetInstance(DiContainer $di, array $params) {
            if ( ! $this->isResolved) {
                $ret = $di($this->factory, $params);
                $this->instance = $this->_applyFilters($ret, $di);
                $this->isResolved = true;
            }
            return $this->instance;
        }

    }