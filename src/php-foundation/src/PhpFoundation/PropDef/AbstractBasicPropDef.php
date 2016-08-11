<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 25.07.16
     * Time: 12:14
     */

    namespace Gismo\Component\PhpFoundation\PropDef;


    abstract class AbstractBasicPropDef implements PropDef {

        public $allowEmpty;

        public function __construct($allowEmpty = FALSE) {
            $this->allowEmpty = $allowEmpty;
        }

        abstract public function castData($input);

    }