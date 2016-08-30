<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 07.08.16
     * Time: 16:07
     */

    namespace Gismo\Component\PhpFoundation\Type;


    /**
     * Class PrototypeMap
     *
     * Provide a single-dimension array.
     *
     * @package Gismo\Component\PhpFoundation\Type
     */
    class PrototypeMap implements \ArrayAccess {

        private $prototype;
        private $mapData = [];


        public function isEmpty() {
            return count ($this->mapData) === 0;
        }
        
        public function __debugInfo() {
            $data = $this->mapData;
            foreach ($this as $key => $value) {
                if ($key != "prototype" && $key != "mapData")
                    $data[$key] = $value;
            }

            return $data;
        }


        public function getDefinedKeys() : array {
            return array_keys($this->mapData);
        }

        public function __construct($prototype) {
            $this->prototype = $prototype;
        }


        public function __clone() {
            // Prevent cloning mapData if Prototype is meself
            if ($this->prototype instanceof self) {
                $this->mapData = [];
            }
            foreach ($this as $key => $val) {
                if (is_object($val)) {
                    if ($key === "prototype" || $key === "mapData")
                        continue;
                    $this->$key = null;
                }
            }
        }


        /**
         * @internal 
         * @param mixed $offset
         * @return bool
         */
        public function offsetExists($offset) {
            return isset ($this->mapData[$offset]);
        }


        /**
         * @internal
         * @param mixed $offset
         * @return mixed
         */
        public function offsetGet($offset) {
            if ( ! isset ($this->mapData[$offset])) {
                $this->mapData[$offset] = clone $this->prototype;
            }
            return $this->mapData[$offset];
        }


        /**
         * @internal
         * @param mixed $offset
         * @param mixed $value
         */
        public function offsetSet($offset, $value) {
            $this->mapData[$offset] = $value;
        }


        /**
         * @internal 
         * @param mixed $offset
         */
        public function offsetUnset($offset) {
            unset ($this->mapData[$offset]);
        }
    }