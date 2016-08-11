<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 25.07.16
     * Time: 12:21
     */

    namespace Gismo\Component\PhpFoundation\PropDef;


  

    use Gismo\Component\PhpFoundation\Accessor\StringAccessor;

    class StringPropDef extends AbstractBasicPropDef {

        public function castData($input) {
            try {
                new StringAccessor($input, $this->allowEmpty);
                return $input;
            } catch (ExpectationFailedException $e) {
                throw new FillFailedException(["Expected string"]);
            }
        }
    }