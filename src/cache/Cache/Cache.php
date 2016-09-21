<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 21.09.16
     * Time: 13:04
     */

    namespace gismo\Cache;


    use Gismo\Component\Di\DiContainer;
    use Gismo\Component\PhpFoundation\Accessor\CallableAccessor;

    class Cache
    {

        /**
         * @var DiContainer
         */
        private $mDi;

        public function __construct(DiContainer $di)
        {
            $this->mDi = $di;
        }


        public function hasKey(string $key) : bool {

        }

        public function set(string $key, $value) {

        }


        public function setMulti (array $keyValueArr) {

        }

        public function get(string $key) {

        }

        public function getMulti (array $keyValueArr) {

        }


        public function __invoke(callable $fn, array $parameters=[])
        {
            $ref = (new CallableAccessor($fn))->getReflection();

            $cacheKey = sha1(serialize([$ref->getFileName(), $ref->getStartLine(), $ref->getEndLine(), $parameters]));
            if ( ! $this->hasKey($cacheKey)) {
                $ret = ($this->mDi)($fn, $parameters);
                $this->set($cacheKey, $ret);
                return $ret;
            }
            return $this->get($cacheKey);
        }


    }