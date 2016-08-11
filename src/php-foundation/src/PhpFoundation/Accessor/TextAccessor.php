<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 10:45
     */

    namespace Gismo\Component\PhpFoundation\Accessor;
    
    
    class TextAccessor extends AbstractAccessor implements \ArrayAccess {


        public function __construct($rawValue=null, $isImmutable=false, $convert=true) {

            if ($convert) {
                if ( ! is_array($rawValue) && is_string($rawValue)) {
                    $rawValue = explode("\n", $rawValue);
                }
                for ($i = 0; $i < count($rawValue); $i++) {
                    $rawValue[$i] = str_replace(["\n", "\r"], "", $rawValue[$i]);
                }
            }
            parent::__construct($rawValue, $isImmutable);
        }



        public function fromFile($filename) : self {
            if ( ! $filename instanceof FsFileAccessor)
                $filename = new FsFileAccessor($filename);
            $this->reference = explode("\n", $filename->getContents());
            return $this;
        }


        public function getIndent() : string {
            for($i=0; $i< count ($this->reference); $i++) {
                $line = $this->reference[$i];
                if (trim ($line) === "") {
                    continue;
                }
                if (preg_match ("/^(\\s*)/", $line, $matches)) {
                    return $matches[1];
                }
            }
            return "";
        }

        public function unindent($indention = null) : TextAccessor {
            if ($indention === null) {
                $indention = $this->getIndent();
            }

            $text = $this->reference;
            for($i=0; $i<count($text); $i++) {
                if (strpos($text[$i], $indention) === 0) {
                    $text[$i] = substr ($text[$i], strlen($indention));
                }
            }

            return new self($text, false, false);
        }


        public function indent(string $indention = "\t") : TextAccessor {
            $indention = str_replace(["\n", "\r"], "", $indention);
            $text = $this->reference;
            for($i=0; $i<count($text); $i++) {
                $text[$i] = $indention . $text[$i];
            }

            return new self($text, false, false);
        }


        public function wrapDocDomment() : TextAccessor {
            $ret = ["/**"];
            $text = $this->reference;
            for($i=0; $i<count($text); $i++ ) {
                $ret[] = " * " . $text[$i];
            }
            $ret[] = " */";
            return new self($ret, false, false);
        }

        public function unwrapDocComment() : TextAccessor {
            $text = $this->reference;

            $ret = [];
            for ($i = 0; $i < count ($text); $i++) {
                if (preg_match ('|^\s*/\*\*|', $text[$i]))
                    continue;
                if (preg_match ('|^\s*\*/|', $text[$i]))
                    continue;
                if (preg_match ('|^\s*\\*\s?(.*)|', $text[$i], $matches)) {
                    $ret[] = $matches[1];
                    continue;
                }
                $ret[] = $text[$i];
            }

            return new self($ret, false, false);
        }


        public function slice (int $startLineIndex, int $endLineIndex=null) : TextAccessor {
            $count = null;
            if ($endLineIndex !== null) {
                if ($endLineIndex < $startLineIndex)
                    throw new \InvalidArgumentException("endLine ('$endLineIndex') must be bigger than startLine ('$startLineIndex')");
                $count = $endLineIndex - $startLineIndex + 1;
            }
            return new self(array_slice($this->reference, $startLineIndex, $count), false, false);
        }


        public function __toString() : string {
            return implode("\n", $this->reference);
        }


        public function offsetExists($offset) {
            throw new \InvalidArgumentException("offsetExists(): Invalid call on TextAccessor");
        }


        public function offsetGet($offset) {
            if ($offset === "+") {
                ++$this->curIndentIndex;
            } else if ($offset === "-") {
                --$this->curIndentIndex;
            } else {
                throw new \InvalidArgumentException("offsetGet(): Invalid call on TextAccessor. Use slice() or __toString()");
            }

        }


        private $curIndentIndex = 0;



        public function offsetSet($offset, $value) {
            $indent = $this->curIndentIndex;
            if ($offset === "+") {
                $indent = ++$this->curIndentIndex;
                $offset = null;
            }
            if ($offset === "-") {
                $indent = --$this->curIndentIndex;
                $offset = null;
            }
            if ($offset === ">") {
                $indent = $this->curIndentIndex + 1;
                $offset = null;
            }

            if ($value === NULL) {
                return;
            }


            if ($offset === "@") {
                $value = str_replace(["\n", "\r"], "", $value);
                $this->reference[count ($this->reference)-1] .= $value;
            } else if ($offset === null) {
                if ($value instanceof TextAccessor) {
                    foreach ($value->reference as $cur) {
                        $this->reference[] = str_repeat("\t", $indent) . $cur;
                    }
                } else {
                    $value = explode("\n", $value);
                    foreach ($value as $val) {
                        $this->reference[] = str_repeat("\t", $indent) . $val;
                    }
                }
            } else {
                throw new \InvalidArgumentException("offsetSet(): Can only use \$text[] = 'addText' or \$text['@']");
            }
        }

        /**
         * Offset to unset
         * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
         * @param mixed $offset <p>
         *                      The offset to unset.
         *                      </p>
         * @return void
         * @since 5.0.0
         */
        public function offsetUnset($offset) {
            throw new \InvalidArgumentException("offsetUnset(): Invalid call on TextAccessor. Use slice() or __toString()");
        }
    }