<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 25.07.16
     * Time: 12:09
     */

    namespace Gismo\Component\PhpFoundation\PropDef;


    class ObjectPropDef implements PropDef {

        public $className;

        public function __construct(string $className) {
            $this->className = $className;
        }

    }