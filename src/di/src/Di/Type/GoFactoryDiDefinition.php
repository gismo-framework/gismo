<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 23:10
     */

    namespace Gismo\Component\Di\Type;

    use Gismo\Component\Di\DiContainer;

    class GoFactoryDiDefinition extends GoAbstractDiDefinition {

        private $factory;

        public function __construct(callable $factory) {
            $this->factory = $factory;
            $this->_autodetectReturnType($factory);
        }


        public function __diGetInstance(DiContainer $di) {
            $val = $di($this->factory);
            $val = $this->_applyFilters($val, $di);
            return $val;
        }

    }