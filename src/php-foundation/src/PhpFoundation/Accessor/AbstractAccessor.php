<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 19:34
     */

    namespace Gismo\Component\PhpFoundation\Accessor;

    abstract class AbstractAccessor {

        protected $reference;
        private $isImmutable;

        public function __construct($rawValue, $isImmutable = false) {
            $this->isImmutable = $isImmutable;
            if ($rawValue instanceof AbstractAccessor) {
                $this->reference = $rawValue->reference;
            } else {
                $this->reference = $rawValue;
            }
        }


        public function makeImmutable () {
            $this->isImmutable = true;
        }


        public function return() {
            return $this->reference;
        }

        public function &reference() {
            if ($this->isImmutable)
                throw new \InvalidArgumentException("Write access to immutable value denied!");
            return $this->reference;
        }

        public function isImmutable():bool {
            return $this->isImmutable;
        }

        public function expectImmutable():self {
            // TODO: Implement assertImmutal() method.
        }


    }