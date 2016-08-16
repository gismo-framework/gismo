<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 21:36
     */

    namespace Gismo\Component\Di\Type;

    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Accessor\CallableAccessor;
    use Gismo\Component\PhpFoundation\Type\OrderedList;

    abstract class GoAbstractDiDefinition {


        private $isProtected = false;


        /**
         * Protect the instance against overwriting
         *
         * @return GoAbstractDiDefinition
         */
        public function protect($message = null) : self {
            $this->isProtected = true;
            return $this;
        }


        public function isProtected (&$message) {
            return $this->isProtected;
        }


        /**
         * @var OrderedList|null
         */
        protected $filters = null;

        public function addFilter(callable $filter, $priority=0) : self {
            if ($this->filters === null) {
                $this->filters = new OrderedList();
            }
            $this->filters->add($priority, $filter);
            return $this;
        }


        protected function _applyFilters ($input, DiContainer $di) {
            if ($this->filters === null)
                return $input;

            $requireClassName = null;
            if (is_object($input)) {
                $requireClassName = get_class($input);
            }

            $this->filters->each(function ($what, $prio, $alias) use (&$input, $di, $requireClassName) {

                $input = $di($what, ["§§input" => $input]);
                if ($requireClassName !== null) {
                    if ( ! is_object($input)) {
                        $acc = new CallableAccessor($what);
                        throw new \InvalidArgumentException("Filter {$acc} must return object type $requireClassName.");
                    }
                    if (get_class($input) !== $requireClassName) {
                        $acc = new CallableAccessor($what);
                        throw new \InvalidArgumentException("Filter {$acc} must return object type $requireClassName");
                    }
                }
            });
            return $input;
        }


        protected $returnClassName = null;

        protected function _autodetectReturnType(callable $factory) {
            $reflection = (new CallableAccessor($factory))->getReflection();
            $returnType = $reflection->getReturnType();
            if ($returnType !== null) {
                if ( ! $returnType->isBuiltin()) {
                    $this->returnClassName = (string)$returnType;
                }
            }
        }

        public function __diGetReturnClassName() : string {
            return $this->returnClassName;
        }

        /**
         * Called whenever fileter was defined before the actual factory
         * was set or a factory is unset.
         *
         * @param GoAbstractDiDefinition $newDef
         * @return GoAbstractDiDefinition
         */
        public function __diReplace (GoAbstractDiDefinition $newDef) {
            $newDef->filters = $this->filters;
            return $newDef;
        }

        
        abstract public function __diGetInstance(DiContainer $container, array $params);

    }