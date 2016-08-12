<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 17:57
     */

    namespace Gismo\Component\Api;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Type\PrototypeMap;

    class GoApiContainer implements \ArrayAccess {

        private $mDi;

        private $mApis = [];

        public function __construct(DiContainer $container) {
            $this->mDi = $container;
        }



        public function offsetExists($offset) {
            return $this->mApis[$offset];
        }

        /**
         * @param mixed $offset
         * @return GoApiCall
         */
        public function offsetGet($offset) {
            if ( ! isset ($this->mApis[$offset]))
                $this->mApis[$offset] = new GoApiCall($this->mDi, $offset);
            return $this->mApis[$offset];
        }


        public function offsetSet($offset, $value) {
            throw new \InvalidArgumentException("Cannot set ApiCall directly. Just connect to some.");
        }


        public function offsetUnset($offset) {

        }
    }