<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 07.07.16
     * Time: 12:06
     */

    namespace Gismo\Component\PhpFoundation\Accessor;



    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    class BooleanAccessor {

        private $bool;

        function __construct($bool) {
            if (is_bool($bool) || $bool === 1 || $bool === 0) {
                $this->bool = $bool;
            } else {
                throw new ExpectationFailedException(["Expected boolean, has " . gettype($bool)]);
            }

        }

    }