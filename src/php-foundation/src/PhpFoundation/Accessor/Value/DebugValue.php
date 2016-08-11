<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 05.07.16
     * Time: 12:05
     */

    namespace Gismo\Component\PhpFoundation\Accessor\Value;

    class DebugValue implements ApplyableValue {

        private $mVal;

        public function __construct($input) {
            $this->mVal = $input;
        }

        /**
         * Return the Raw string representation of the value
         * to string()->apply()
         *
         *
         * @return string
         */
        public function getEscaped():string {
            if ($this->mVal === NULL)
                return "NULL";
            if (is_object($this->mVal))
                return "object(" . get_class($this->mVal) . ")";
            if (is_array($this->mVal))
                return "array[" . count ($this->mVal). "]";
            if (is_string($this->mVal))
                return "'" . $this->mVal . "'";
            if (is_int($this->mVal))
                return "int(" . $this->mVal . ")";
            if (is_float($this->mVal))
                return "float(" . $this->mVal . ")";
            if (is_bool($this->mVal)) {
                if ($this->mVal === TRUE) {
                    return "TRUE";
                }
                return "FALSE";
            }
            if (is_resource($this->mVal)) {
                return "ressource()";
            }

            if (is_callable($this->mVal)) {

                return "callable()";
            }
            return "mixed()";
        }
    }