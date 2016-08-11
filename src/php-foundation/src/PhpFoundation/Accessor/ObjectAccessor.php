<?php
    /**
     * Created by PhpStorm.
     * User: alina
     * Date: 07.07.16
     * Time: 12:06
     */

    namespace Gismo\Component\PhpFoundation\Accessor;


    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\Ex\FillFailedException;
    use Gismo\Component\PhpFoundation\PropDef\AbstractBasicPropDef;
    use Gismo\Component\PhpFoundation\PropDef\ArrayPropDef;
    use Gismo\Component\PhpFoundation\PropDef\ObjectPropDef;
    use Gismo\Component\PhpFoundation\PropDef\PropDef;
    use Gismo\Component\PhpFoundation\PropDef\StringPropDef;

    class ObjectAccessor {

        public $obj;

        function __construct($obj) {
            if ( ! is_object($obj)) {
                throw new ExpectationFailedException(["Expected object, has " . gettype($obj)]);
            }
            $this->obj = $obj;
        }

        public function fill($data) {
            if ( ! is_array($data)) {
                throw new \InvalidArgumentException("Expected argument to be an array");
            }
            if ( ! method_exists($this->obj, "__getPrototypes")) {
                $prototypes = [];
                foreach (get_object_vars($this->obj) as $key => $value) {
                    $prototypes[$key] = new StringPropDef();
                }
            } else {
                $prototypes = $this->obj->__getPrototypes();
            }

            foreach ($data as $prop => $val) {
                if ( ! property_exists(get_class($this->obj), $prop)) {
                    throw new FillFailedException(["Argument {$prop} does not exist in class " . get_class($this->obj)]);
                }
                if ( ! isset( $prototypes[$prop] )) {
                    $prototypes[$prop] = new StringPropDef();
                }
                $protoType = $prototypes[$prop];
                if ( ! $protoType instanceof PropDef) {
                    throw new \InvalidArgumentException("Expected prototype to be instance of " . PropDef::class . ", got: " . get_class($protoType));
                }
                if ($protoType instanceof AbstractBasicPropDef) {
                    try {
                        $x = $protoType->castData($val);
                        $this->obj->$prop = $x;
                    } catch (FillFailedException $e) {
                        throw new FillFailedException([$e->getMessage() . ", got " . gettype($val)]);
                    }
                } elseif ($protoType instanceof ArrayPropDef) {
                    if ( ! is_array($val)) {
                        throw new FillFailedException(["Expected value to be array, got: " . get_class($val)]);
                    }
                    if ( ! is_array($this->obj->$prop)) {
                        if (is_null($this->obj->$prop)) {
                            $this->obj->$prop = [];
                        } else {
                            throw new \InvalidArgumentException("Property {$prop} is not an array");
                        }
                    }
                    $arrCount = count($val);
                    if ($arrCount < $protoType->min || ( ! is_null($protoType->max) && $arrCount > $protoType->max )) {
                        throw new FillFailedException(["Number of objects should be between {$protoType->min} and {$protoType->max}"]);
                    }
                    foreach ($val as $item) {
                        $oAcc = new ObjectAccessor(new $protoType->arrayElemType->className());
                        $o = $oAcc->fill($item);
                        $this->obj->$prop[] = $o;
                    }
                } elseif ($protoType instanceof ObjectPropDef) {
                    $oAcc = new ObjectAccessor(new $protoType->className());
                    $o = $oAcc->fill($val);
                    $this->obj->$prop = $o;
                }

            }
            return $this->obj;
        }

    }

    ?>