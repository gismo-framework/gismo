<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.07.16
     * Time: 14:55
     */

    namespace Gismo\Component\PhpFoundation\Accessor\Value;

    class EscapedValue implements ApplyableValue {
        
        private $mInput;
        
        public function __construct ($input) {
            if (is_array($input))
                throw new \InvalidArgumentException("Cannot handle array-data.");
            if (is_object($input))
                throw new \InvalidArgumentException("Cannot handle object-data.");
            $this->mInput = $input;
        }


        /**
         * Return the Raw string representation of the value
         * to string()->apply()
         *
         *
         * @return string
         */
        public function getEscaped():string {
            if ($this->mInput === null)
                return "NULL";
            return "'" . addslashes($this->mInput) . "'";
        }
    }