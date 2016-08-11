<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 25.07.16
     * Time: 12:29
     */

    namespace Gismo\Component\PhpFoundation\PropDef;



    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\Ex\FillFailedException;
    use Gismo\Component\PhpFoundation\Accessor\NumberAccessor;

    class IntegerPropDef extends AbstractBasicPropDef {

        public function castData($input) {
            try {
                $na = new NumberAccessor($input);
                $na->expectInteger();
                return $input;
            } catch (ExpectationFailedException $e) {
                throw new FillFailedException(["Expected integer"]);
            }
        }
    }