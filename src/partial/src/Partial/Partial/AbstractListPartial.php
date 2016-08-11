<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 05.08.16
 * Time: 21:52
 */

    namespace Gismo\Component\Partial\Partial;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\Partial\Renderable;
    use Gismo\Component\Partial\Section\Section;
    use Gismo\Component\PhpFoundation\Type\OrderedList;

    abstract class AbstractListPartial implements \ArrayAccess, Renderable {

        /**
         * @var DiContainer
         */
        protected $mDi;
        
        /**
         * @var OrderedList
         */
        protected $mOrderedList;
        
        public function __construct(DiContainer $container) {
            $this->mDi = $container;
            $this->mOrderedList = new OrderedList();
        }
        


     
        public function offsetExists($offset) {
            throw new \InvalidArgumentException("Cannot offsetExistis() on ListPartial.");
        }


        
        public function offsetGet($offset) {
            throw new \InvalidArgumentException("Cannot offsetGet() on ListPartial.");
        }

       
        public function offsetSet($offset, $value) {
            if ($offset === null)
                $offset = 0;
            if ($value instanceof Section)
                throw new \InvalidArgumentException("You are only allowed to add Section-Objects or callbacks returning Sections");
            $this->mOrderedList->add($offset, $value);
        }

       
        public function offsetUnset($offset) {
            throw new \InvalidArgumentException("Cannot unset on ListPartial.");
        }
    }