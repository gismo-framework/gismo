<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 19:06
     */

    namespace Gismo\Component\PhpFoundation\Accessor;



    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    class StructAccessor implements \ArrayAccess {

        private $container;

        private $path;

        /**
         * @var StructAccessor
         */
        private $parent;

        private $caseSens;


        public function __debugInfo() {
            return $this->container;
        }


        function __construct(&$arr, array $path = [], StructAccessor $parent = NULL, bool $caseSens = TRUE) {
            if ( ! is_array($arr)) {
                throw new ExpectationFailedException(["Expected array, has " . gettype($arr)]);
            }
            $this->container =& $arr;
            $this->path = $path;
            $this->parent = $parent;
            $this->caseSens = $caseSens;
        }

        /**
         * Return the complex element from here.
         *
         * Returns NULL if the element was not found.
         *
         * @return array|mixed|null
         */
        public function getValue() {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }
            if ($found === false)
                return null;
            return $curElem;
        }




        /**
         * Whether a offset exists
         * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
         * @param mixed $offset <p>
         *                      An offset to check for.
         *                      </p>
         * @return boolean true on success or false on failure.
         *                      </p>
         *                      <p>
         *                      The return value will be casted to boolean if non-boolean was returned.
         */
        public function offsetExists($offset) {

            if ( ! $this->caseSens) {
                $offset = strtoupper($offset);
            }

            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    return FALSE;
                }
                $curElem =& $curElem[$curOffset];
            }

            return isset( $curElem[$offset] );
        }

        /**
         * Offset to retrieve
         * @link  http://php.net/manual/en/arrayaccess.offsetget.php
         * @param mixed $offset <p>
         *                      The offset to retrieve.
         *                      </p>
         * @return StructAccessor
         */
        public function offsetGet($offset) {
            if ( ! $this->caseSens) {
                $offset = strtoupper($offset);
            }

            $newPath = $this->path;
            $newPath[] = $offset;
            return new StructAccessor($this->container, $newPath, $this);
        }

        /**
         * Offset to set
         * @link  http://php.net/manual/en/arrayaccess.offsetset.php
         * @param mixed $offset <p>
         *                      The offset to assign the value to.
         *                      </p>
         * @param mixed $value  <p>
         *                      The value to set.
         *                      </p>
         * @return void
         */
        public function offsetSet($offset, $value) {
            if ( ! $this->caseSens) {
                $offset = strtoupper($offset);
            }

            // @ TODO : Copy Paste Entsorgen!!! (siehe getValue())
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $curElem[$curOffset] = [];
                }
                $curElem =& $curElem[$curOffset];
            }
            if (is_null($offset)) {
                $curElem[] = $value;
            } else {
                $curElem[$offset] = $value;
            }
        }

        /**
         * Offset to unset
         * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
         * @param mixed $offset <p>
         *                      The offset to unset.
         *                      </p>
         * @return void
         */
        public function offsetUnset($offset) {
            if ( ! $this->caseSens) {
                $offset = strtoupper($offset);
            }
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }

            if ($found) {
                unset( $curElem[$offset] );
            }
        }

        public function expectNotNull() : StructAccessor {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    throw new ExpectationFailedException(["Offset '" . $curOffset . "' does not exist'"]);
                }
                if (is_null($curElem[$curOffset])) {
                    throw new ExpectationFailedException(["Not null expected. Value at offset '" . $curOffset . "' is null"]);
                }
                $curElem =& $curElem[$curOffset];
            }
            return $this;
        }

        public function expectString() : StringAccessor {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }
            if ($found) {
                return new StringAccessor($curElem);
            } else {
                return new StringAccessor(NULL);
            }
        }

        public function expectBoolean() : BooleanAccessor {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }
            if ($found) {
                return new BooleanAccessor($curElem);
            } else {
                return new BooleanAccessor(NULL);
            }
        }

        public function expectNumber() : NumberAccessor {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }
            if ($found) {
                return new NumberAccessor($curElem);
            } else {
                return new NumberAccessor(NULL);
            }
        }

        public function expectObject() : ObjectAccessor {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }
            if ($found) {
                return new ObjectAccessor($curElem);
            } else {
                return new ObjectAccessor(NULL);
            }
        }

        public function expectStruct() : StructAccessor {
            $found = TRUE;
            $curElem =& $this->container;
            foreach ($this->path as $curOffset) {
                if ( ! isset( $curElem[$curOffset] )) {
                    $found = FALSE;
                    break;
                }
                $curElem =& $curElem[$curOffset];
            }
            if ($found) {
                return new StructAccessor($curElem);
            } else {
                $var = NULL;
                return new StructAccessor($var);
            }
        }

    }