<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 18:05
     */

    namespace Gismo\Component\PhpFoundation\Accessor;


    use Gismo\Component\PhpFoundation\Accessor\Ex\ApplyFailedException;
    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Accessor\Value\ApplyableValue;
    use Gismo\Component\PhpFoundation\Accessor\Value\EscapedValue;

    class StringAccessor {

        private $value;

        const MAX_LENGTH = 4000;

        function __construct($string, $allowNull = TRUE, $maxLength = self::MAX_LENGTH) {
            if ( ! is_string($string)) {
                if ($allowNull === TRUE && is_null($string)) {

                } else {
                    throw new ExpectationFailedException(["Expected string"]);
                }
            }
            $this->value = $string;
        }

        public function __toString() {
            if ($this->value === NULL) {
                return "";
            }
            return $this->value;
        }

        public function callPhpFunction($funcName, array $funcParams) : StringAccessor {

            for ($i = 0; $i < count($funcParams); $i++) {
                if (is_object($funcParams[$i])) {
                    if ($funcParams[$i] instanceof StringAccessor) {
                        $funcParams[$i] = (string)$funcParams[$i];
                    } else {
                        throw new \InvalidArgumentException("Invalid Object in parameter $i");
                    }
                }
            }
            array_unshift($funcParams, $this->value);

            $ret = call_user_func_array($funcName, $funcParams);

            return new StringAccessor($ret);
        }

        public function expectNotNull() : StringAccessor {
            if (is_null($this->value)) {
                throw new ExpectationFailedException(["Expected not null, has: {$this->value}"]);
            }
            return $this;
        }

        public function expectStartsWith($start) : StringAccessor {
            if (substr($this->value, 0, strlen($start)) != $start) {
                throw new ExpectationFailedException(["Expected string to start with '{$start}'"]);
            }
            return $this;
        }

        public function expectEndsWith($end) : StringAccessor {
            if (substr($this->value, strlen($end) * -1) != $end) {
                throw new ExpectationFailedException(["Expected string to end with '{$end}'"]);
            }
            return $this;
        }

        public function expectPregMatch($search) : StringAccessor {
            if (preg_match($search, $this->value) != 1) {
                throw new ExpectationFailedException(["Expected string to match '{$search}'"]);
            }
            return $this;
        }

        public function expectStrLengthBetween($min, $max) : StringAccessor {
            $strlen = strlen($this->value);
            if ($strlen > $max || $strlen < $min) {
                throw new ExpectationFailedException(["Expected length between {$min} and {$max}"]);
            }
            return $this;
        }

        public function expectMinLength($min) : StringAccessor {
            $strlen = strlen($this->value);
            if ($strlen < $min) {
                throw new ExpectationFailedException(["Expected length with min-length {$min}"]);
            }
            return $this;
        }

        public function expectMaxLength($max) : StringAccessor {
            $strlen = strlen($this->value);
            if ($strlen > $max) {
                throw new ExpectationFailedException(["Expected length with max-length {$max}"]);
            }
            return $this;
        }


        public function explode($val) : StructAccessor {
            $arr = explode($val, $this->value);
            return new StructAccessor($arr);
        }

        public function pregReplace($pattern, $replace) : StringAccessor {
            return new StringAccessor(preg_replace($pattern, $replace, $this->value));
        }

        public function pregReplaceCallback($pattern, $callable) : StringAccessor {
            return new StringAccessor(preg_replace_callback($pattern, $callable, $this->value));
        }

        public function notNull() : bool {
            return is_null($this->value);
        }

        public function startsWith($str) : bool {
            return substr($this->value, 0, strlen($str)) == $str || $str === "";
        }

        public function endsWith($str) : bool {
            return substr($this->value, strlen($str) * -1) == $str || $str === "";
        }

        public function setDefault($str) : StringAccessor {
            if (is_null($this->value)) {
                $this->value = $str;
            }
            return $this;
        }

        /**
         * @param $data
         * @param $defaultEscaperClass
         * @return StringAccessor
         */
        public function apply($data, $defaultEscaperClass = EscapedValue::class) {
            $index = 0;
            $newStr = preg_replace_callback("/(\\?\\?|\\?|\\:\\:[a-z0-9]+|\\:[a-z0-9]+)/im",
                    function ($matches) use (&$index, $data, $defaultEscaperClass) {
                        $token = $matches[1];

                        if ($token === "?") {
                            if ( ! isset ( $data[$index] )) {
                                throw new ApplyFailedException(["Array Offset ? not set in data", $index]);
                            }
                            $curData = $data[$index];
                            if (is_array($curData)) {
                                throw new ApplyFailedException(["Array is not allowed on offset ?", $index]);
                            }
                            try {
                                if ( ! $curData instanceof ApplyableValue) {
                                    $curData = new $defaultEscaperClass($curData);
                                }
                            } catch (\Exception $e) {
                                throw new ApplyFailedException(["Exception building offset $index: ?", $e->getMessage()], 1, $e);
                            }
                            $index++;
                            return $curData->getEscaped();

                        } else {
                            if ($token === "??") {
                                if ( ! isset ( $data[$index] )) {
                                    throw new ApplyFailedException(["Array Offset ? not set in data", $index]);
                                }
                                $curData = $data[$index];
                                if ( ! is_array($curData)) {
                                    throw new ApplyFailedException(["Array is expected on offset ?", $index]);
                                }
                                $ret = [];
                                try {
                                    foreach ($curData as $curDataSet) {
                                        if ( ! $curDataSet instanceof ApplyableValue) {
                                            $curDataSet = new $defaultEscaperClass($curDataSet);
                                        }
                                        $ret[] = $curDataSet->getEscaped();
                                    }
                                } catch (\Exception $e) {
                                    throw new ApplyFailedException(["Exception building offset $index: ?", $e->getMessage()], 1, $e);
                                }
                                $index++;
                                return implode(",", $ret);

                            } else {
                                if (substr($token, 0, 2) === "::") {
                                    if ( ! isset ( $data[substr($token, 2)] )) {
                                        throw new ApplyFailedException(["Array Key ? not found in data", $token]);
                                    }
                                    $curData = $data[substr($token, 2)];
                                    if ( ! is_array($curData)) {
                                        throw new ApplyFailedException(["Array is expected on key ?", $token]);
                                    }
                                    $ret = [];
                                    try {
                                        foreach ($curData as $curDataSet) {
                                            if ( ! $curDataSet instanceof ApplyableValue) {
                                                $curDataSet = new $defaultEscaperClass($curDataSet);
                                            }
                                            $ret[] = $curDataSet->getEscaped();
                                        }
                                    } catch (\Exception $e) {
                                        throw new ApplyFailedException(["Exception building key $token: ?", $e->getMessage()], 1, $e);
                                    }
                                    return implode(",", $ret);

                                } else {
                                    if ( ! isset ( $data[substr($token, 1)] )) {
                                        throw new ApplyFailedException(["Key ? not found in data", $token]);
                                    }
                                    $curData = $data[substr($token, 1)];
                                    if (is_array($curData)) {
                                        throw new ApplyFailedException(["Array is not allowed on key ?", $token]);
                                    }
                                    try {
                                        if ( ! $curData instanceof ApplyableValue) {
                                            $curData = new $defaultEscaperClass($curData);
                                        }
                                    } catch (\Exception $e) {
                                        throw new ApplyFailedException(["Exception building key $token: ?", $e->getMessage()], 1, $e);
                                    }
                                    $index++;
                                    return $curData->getEscaped();
                                }
                            }
                        }
                    },
                                            $this->value
            );
            return new self($newStr);
        }


    }