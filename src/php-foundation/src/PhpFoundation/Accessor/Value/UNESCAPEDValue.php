<?php

    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 04.07.16
     * Time: 15:33
     */

    namespace Gismo\Component\PhpFoundation\Accessor\Value;

    class UNESCAPEDValue implements ApplyableValue {

        public $val = "";

        function __construct($str) {
            $this->val = $str;
        }


        /**
         * Return the Raw string representation of the value
         * to string()->apply()
         *
         *
         * @return string
         */
        public function getEscaped():string {
            return $this->val;
        }
    }