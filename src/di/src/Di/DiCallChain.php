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
        private $mDi;

        private $filterOnly;

        private $call = null;

        /**
         * @var OrderedList
         */
        private $inputFilter;
        /**
         * @var OrderedList
         */
        private $outputFilter;

        public function __construct(DiContainer $di, bool $filterOnly = false)
        {
            $this->mDi = $di;
            $this->filterOnly = $filterOnly;
        }


        public function __invoke($params)
        {
            if ($this->inputFilter !== null) {
                $this->inputFilter->each(function ($fn) use (&$params) {
                    $params = $this->mDi->__invoke($fn,
                                                   [
                                                           "§§parameters" => $params,
                                                           "§§apiCall" => $this,
                                                   ]
                    );
                    if ($params === false)
                        return false;
                });
            }
            if ($this->filterOnly)
                return $params;

            $ret = null;
            if ($this->call !== null) {
                $ret = $this->mDi->__invoke($this->call, $params);
            }

            if ($this->outputFilter !== null) {
                $this->outputFilter->each(function ($fn) use (&$ret, $params) {
                    $ret = $this->mDi->__invoke($fn,
                                                [
                                                        "§§parameters" => $params,
                                                        "§§return"  => $ret,
                                                        "§§apiCall"  => $this,
                                                ]
                    );
                    if ($ret === false)
                        return false;
                });
            }
            return $ret;
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
                if ($offset > 0) {
                    if ($this->inputFilter === null)
                        $this->inputFilter = new OrderedList();
                    $this->inputFilter->add($offset, $value);
                } else if ($offset < 0) {
                    if ($this->outputFilter === null)
                        $this->outputFilter = new OrderedList();
                    $this->outputFilter->add($offset, $value);
                }

                return;
            }
        }

        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }
    }