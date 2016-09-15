<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 15.09.16
     * Time: 14:57
     */

    namespace Gismo\Component\Partial;


    use Gismo\Component\Di\Core\Invokeable;
    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Type\OrderedList;

    class HtmlPartial implements Invokeable, \ArrayAccess
    {
        /**
         * @var DiContainer
         */
        protected $mDi;

        /**
         * @var OrderedList
         */
        private $chain;


        public function __construct(DiContainer $di)
        {
            $this->mDi = $di;
            $this->chain = new OrderedList();
        }


        public function __debugInfo() {
            return [
                    "chain" => $this->chain
            ];
        }


        public function __invoke($§§data = [])
        {
            $ret = "";
            $this->chain->each(function ($fn) use (&$§§data, &$ret) {
                $ret .= $this->mDi->__invoke($fn, ["§§data" => $§§data]);
            });

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
            if ( ! is_int($offset))
                throw new \InvalidArgumentException("Offset must be integer value. '$offset' found");

            if (is_string($value)) {
                $this->chain->add($offset, function ($§§data) use ($value) {
                     return $this->mDi[$value]($§§data);
                });
                return;
            }

            if (is_callable($value)) {
                $this->chain->add($offset, $value);
                return;
            }
            throw new \InvalidArgumentException("Parameter must be string (bindName) or callable");
        }

        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }
    }