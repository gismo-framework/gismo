<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 15.08.16
 * Time: 22:34
 */


    namespace Gismo\Component\Di;

    use Gismo\Component\PhpFoundation\Type\OrderedList;

    class DiCallChain implements \ArrayAccess {

        /**
         * @var DiContainer
         */
        private $diContainer;
        private $filterOnly;

        private $call = null;

        private $inputFilter;
        private $outputFilter;

        public function __construct(DiContainer $di, bool $filterOnly = false)
        {
            $this->diContainer = $di;
            $this->filterOnly = $filterOnly;
            $this->inputFilter = new OrderedList();
            $this->outputFilter = new OrderedList();
        }


        public function __invoke($params)
        {

        }


        public function offsetExists($offset)
        {
            // TODO: Implement offsetExists() method.
        }


        public function offsetGet($offset)
        {
            throw new \InvalidArgumentException("Cannot read DiCallChain");
        }


        public function offsetSet($offset, $value)
        {
            if ($offset === 0) {
                if ($this->filterOnly === true)
                    throw new \InvalidArgumentException("Filter only: Cannot assign callback");
                if ( ! is_callable($value)) {
                    throw new \InvalidArgumentException("Action must be valid callable");
                }
                $this->call = $value;
                return;
            }
            if (is_int($offset)) {
                if ($this->filterOnly === true && $offset < 1)
                    throw new \InvalidArgumentException("Filter only: Offset '$offset' < 1 not allowed here");
                if ( ! is_callable($value)) {
                    throw new \InvalidArgumentException("Filter must be valid callable");
                }
                $this->inputFilter->add($offset, $value);
                return;
            }
        }

        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }
    }
