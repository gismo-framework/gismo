<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 25.07.16
     * Time: 12:11
     */

    namespace Gismo\Component\PhpFoundation\PropDef;


    class ArrayPropDef implements PropDef {

        public $arrayElemType;
        public $min;
        public $max;

        public function __construct(PropDef $arrayElemType, $min = 0, $max = NULL) {
            $this->arrayElemType = $arrayElemType;
            $this->max = $max;
            $this->min = $min;
        }

    }