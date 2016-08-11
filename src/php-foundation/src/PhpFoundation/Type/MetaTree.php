<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 07.08.16
     * Time: 11:20
     */


     namespace Gismo\Component\PhpFoundation\Type;


     class MetaTree implements \ArrayAccess {


          private $children = [];



          public function offsetExists($offset) {
               return isset ($this->children[$offset]);
          }


          /**
           * @param mixed $offset
           * @return self|null
           */
          public function offsetGet($offset) {
               if ( ! isset ($this->children[$offset]))
                    return null;
               return $this->children[$offset];
          }


          public function offsetSet($offset, $value) {
               if ( ! $value instanceof self)
                    throw new \InvalidArgumentException("You can add only MataTree Nodes");
               $this->children[$offset] = $value;
          }


          public function offsetUnset($offset) {
               unset ($this->children[$offset]);
          }
     }