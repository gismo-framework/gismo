<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 06.07.16
     * Time: 18:51
     */

    namespace Gismo\Component\PhpFoundation\Accessor;



    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    class NumberAccessor {

        private $num;

        function __construct($num) {
            if ( ! is_numeric($num)) {
                throw new ExpectationFailedException(["Expected number, has " . gettype($num)]);
            }
            $this->num = $num;
        }

    }