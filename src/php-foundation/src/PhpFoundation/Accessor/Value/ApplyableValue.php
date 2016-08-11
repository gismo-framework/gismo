<?php

    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 04.07.16
     * Time: 16:07
     */

    namespace Gismo\Component\PhpFoundation\Accessor\Value;

    interface ApplyableValue {


        public function __construct($input);

        /**
         * Return the Raw string representation of the value
         * to string()->apply()
         *
         *
         * @return string
         */
        public function getEscaped():string;

    }