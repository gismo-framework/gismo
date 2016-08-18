<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 21:41
     */

     namespace Gismo\Component\Di\Type;
     
     use Gismo\Component\Di\DiContainer;

     class GoConstantDiDefinition extends GoAbstractDiDefinition {
         
         private $value;
         
         public function __construct($value) {
             $this->value = $value;
             if (is_object($value)) {
                 $this->returnClassName = get_class($value);
             }
         }


         public function __debugInfo() {
             $ret = parent::__debugInfo();
             $ret["value"] = $this->value;
             return $ret;
         }


         public function __diGetInstance(DiContainer $di, array $params) {
             $val = $this->value;
             $val = $this->_applyFilters($val, $di);
             return $val;
         }
         
     }
