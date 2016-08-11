<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 19:35
     */

    namespace Gismo\Component\PhpFoundation\Accessor\Ex;
    
    use Gismo\Component\PhpFoundation\Exception\PfException;

    class ExpectationFailedException extends PfException {

        private $mFailedKey;
        private $mFailedValue;

        public function __construct(array $template, $failedKey = null, $failedValue = null, $code = null, \Exception $previous = null) {
            parent::__construct($template, $code, $previous);
        }

        public function getFailedKey () {
            return $this->mFailedKey;
        }

        public function setFailedKey (string $keyName) {
            $this->mFailedKey = $keyName;
        }

        public function getFailedValue () {
            return $this->mFailedValue;
        }

        public function setFailedValue($val) {
            $this->mFailedValue = $val;
        }


    }