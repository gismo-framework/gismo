<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 07.08.16
 * Time: 22:22
 */

    namespace Gismo\Component\PhpFoundation\Type;



    class ArrayAccessOrderedList implements \ArrayAccess
    {
        /**
         * @var callable|null
         */
        private $onAdd = null;
        private $itemCount = 0;
        private $list = [];

        private $isClean = false;

        public function __construct(callable $onAdd=null)
        {
            $this->onAdd = $onAdd;
        }


        protected function add($priority, $what) {
            $this->list[] = [$priority, $what];
            $this->itemCount++;
            $this->isClean = false;
        }

        private function _build() {
            if ($this->isClean)
                return;
            if ($this->itemCount === 0) {
                $this->isClean = true;
                return;
            }

            usort($this->list, function ($a, $b) {
                if ($a[0] == $b[0])
                    return 0;
                return ($a[0] > $b[0] ? -1 : 1);
            });
            $this->isClean = true;
        }


        /**
         * <example>
         *  $c->each(function ($what, $prio, $alias) {
         *  });
         * </example>
         *
         * @param callable $fn
         */
        public function each(callable $fn) {
            $this->_build();

            foreach ($this->list as $curListItem) {
                $prio = $curListItem[0];
                $what = $curListItem[1];
                if ($fn($what, $prio) === false)
                    break;
            }
        }


        public function offsetExists($offset)
        {
            throw new \InvalidArgumentException("Cannot offsetExists()");
        }


        public function offsetGet($offset)
        {
            throw new \InvalidArgumentException("Cannot offsetGet()");
        }


        public function offsetSet($offset, $value)
        {
            if ($this->onAdd !== null) {
                $value = ($this->onAdd)($value);
            }
            if ($offset === null)
                $offset = 0;
            $this->add($offset, $value);
        }


        public function offsetUnset($offset)
        {
            throw new \InvalidArgumentException("Cannot offsetUnset()");
        }
    }