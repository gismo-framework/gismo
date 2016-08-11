<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 25.07.16
     * Time: 12:24
     */

    namespace Gismo\Component\PhpFoundation\PropDef;


    

    use Gismo\Component\PhpFoundation\Accessor\BooleanAccessor;
    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\Ex\FillFailedException;

    class BooleanPropDef extends AbstractBasicPropDef {

        public function castData($input) {
            try {
                new BooleanAccessor($input);
                return $input;
            } catch (ExpectationFailedException $e) {
                throw new FillFailedException(["Expected boolean"]);
            }
        }
    }