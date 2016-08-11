<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 04.07.16
     * Time: 16:01
     */

    namespace Gismo\Component\PhpFoundation\Accessor\Value;

    class BackticksEscapedValue implements StringApplyableInterface{

        public $val = "";

        function __construct($str) {
            $this->val = $str;
        }

    }