<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 17:40
     */

    namespace Gismo\Component\Api;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Type\ArrayAccessOrderedList;

    /**
     * Class GoApiCall
     * @package Gismo\Component\Api
     *
     * @property ArrayAccessOrderedList $beforeCall
     * @property ArrayAccessOrderedList $afterCall
     */
    class GoApiCall {

        /**
         * @var null|string
         */
        private $mBindName;

        /**
         * @var DiContainer
         */
        private $mDi;


        public function __construct(DiContainer $di, $bindName) {
            $this->mDi = $di;
            $this->mBindName = $bindName;
        }





        /**
         * @var callable
         */
        private $fn;




        /**
         * @var null|ArrayAccessOrderedList
         */
        private $beforeCall = null;

        /**
         * @var null|ArrayAccessOrderedList
         */
        private $afterCall = null;


        /**
         * @var null|ArrayAccessOrderedList
         */
        private $inputFilters = null;

        /**
         * @var null|ArrayAccessOrderedList
         */
        private $outputFilters = null;


        public function __get($name) {
            switch ($name) {
                case "beforeCall":
                    if ($this->beforeCall === null)
                        $this->beforeCall = new ArrayAccessOrderedList();
                    return $this->beforeCall;

                case "afterCall":
                    if ($this->afterCall === null)
                        $this->afterCall = new ArrayAccessOrderedList();
                    return $this->afterCall;
            }
            throw new \InvalidArgumentException("Property '$name' not existing");
        }

        public function __set($name, $value) {
            throw new \InvalidArgumentException("Setting value ($name) on GoAction not allowed");
        }



        public function bindCallback(callable $fn) : self {
            $this->fn = $fn;
            return $this;
        }


        public function __invoke(array $params = []) {
            if ($this->beforeCall !== null) {
                $this->beforeCall->each(function ($fn) use (&$params) {
                    $params = $this->mDi->__invoke($fn,
                                                   [
                                                           "§§parameters" => $params,
                                                           "§§apiCall" => $this,
                                                           GoApiCall::class => $this
                                                   ]
                    );
                });
            }
            $ret = null;
            if ($this->fn !== null) {
                $ret = $this->mDi->__invoke($this->fn, $params);
            }

            if ($this->afterCall !== null) {
                $this->afterCall->each(function ($fn) use (&$ret, $params) {
                    $ret = $this->mDi->__invoke($fn,
                                                [
                                                        "§§parameters" => $params,
                                                        "§§return"  => $ret,
                                                        "§§apiCall"  => $this,
                                                        GoApiCall::class => $this
                                                ]
                    );
                });
            }
            return $ret;
        }


    }