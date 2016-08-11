<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 12:45
     */


    namespace Gismo\Component\PhpFoundation\Accessor;



    class CallableAccessor {

        private $callable;

        /**
         * @var \ReflectionFunction
         */
        private $reflection;

        public function __construct(callable $callable) {
            $this->callable = $callable;
            if (is_array($callable)) {
                if (is_object($callable[0])) {
                    $oRef = new \ReflectionObject($callable[0]);
                    $this->reflection = $oRef->getMethod($callable[1]);
                    return;
                }
            } else {
                $this->reflection = new \ReflectionFunction($callable);
                return;
            }
            throw new \InvalidArgumentException("Cannot find callable in parameter 1");
        }


        /**
         * @return \ReflectionFunction
         */
        public function getReflection() {
           return $this->reflection;
        }

        private $paramRref = null;

        private function _buildParamRef () {
            if ($this->paramRref !== null)
                return;
            $this->paramRref = [];
            foreach ($this->reflection->getParameters() as $param) {
                $this->paramRref[$param->getName()] = $param;
            }
        }

        /**
         * @return string[]
         */
        public function getParamNames() {
            $this->_buildParamRef();
            return array_keys($this->paramRref);
        }

        /**
         * @param $paramName
         * @return null|\ReflectionParameter
         */
        public function getParamByName($paramName) {
            $this->_buildParamRef();
            if ( ! isset ($this->paramRref[$paramName]))
                return null;
            return $this->paramRref[$paramName];
        }



    }