<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 18:49
     */

    namespace Gismo\Component\PhpFoundation\Accessor;
    

    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;

    class PathAccessor extends AbstractAccessor {
        
        
        public function __construct(string $rawValue, $isImmutable = false) {
            $rawValue = str_replace("\\", "/", $rawValue);
            
            parent::__construct($rawValue, $isImmutable);
        }

        /**
         * @return StringAccessor
         */
        public function expectString():StringAccessor {
            return new StringAccessor($this->reference);
        }


        public function __toString() {
            return (string)$this->reference;
        }


        public function isAbsolute():bool {
            if (substr ($this->reference, 0, 1) === "/")
                return true;
            return false;
        }
        
        public function isRelative():bool {
            return ! $this->isAbsolute();
        }


        /**
         * @return StringAccessor
         */
        public function last():StringAccessor {
            $parts = explode("/", $this->reference);
            return new StringAccessor(array_pop($parts));
        }


        public function first():StringAccessor {
            $parts = explode("/", $this->reference);
            return new StringAccessor(array_shift($parts));
        }


        public function prefix($prefix) : PathAccessor {
            return new PathAccessor($prefix . $this->reference);
        }

        public function append($append) : PathAccessor {
            return new PathAccessor($this->reference . $append);
        }


        /**
         * @return $this|PathAccessor
         */
        public function toAbsolutePath($prefix=null):PathAccessor {
            if ($this->isAbsolute())
                return $this;
            if ($prefix !== null) {
                $prefix = (new self($prefix))->toAbsolutePath();
                return $prefix->resolve($this->return());
            }
            return new PathAccessor("/" . $this->reference);
        }

        /**
         * @return $this|PathAccessor
         */
        public function toRelativePath($fromPath=""):PathAccessor {
            if ($this->isRelative())
                return $this;

            $fromPath = (new PathAccessor($fromPath))->toAbsolutePath()->resolve();
            $me = $this->toAbsolutePath()->resolve();

            $fromPathArr = explode("/", $fromPath->return());
            $meArr = explode("/", $me->return());

            for($i = 0; $i<count ($meArr); $i++) {
                if ( ! isset ($fromPathArr[$i]) || ! isset ($meArr[$i]))
                    break;
                if ($fromPathArr[$i] === $meArr[$i]) {
                    continue;
                } else {
                    break;
                }
            }

            $relPath = [];
            for($i2 = $i; $i2<count($fromPathArr); $i2++ ) {
                $relPath[] = "..";
            }

            for ($i2 = $i; $i2<count ($meArr); $i2++) {
                $relPath[] = $meArr[$i2];
            }
            
            return new self(implode("/", $relPath));
        }


        /**
         * Resolves '..', '.' and double slashes.
         *
         *
         *
         * @return self
         */
        public function resolve($addPath=null):PathAccessor {
            $val = $this->reference;
            if ($addPath !== null)
                $val .= "/{$addPath}";
            $parts = explode ("/", $val);

            $ret = [];
            foreach ($parts as $curPart) {
                if ($curPart === "")
                    continue;
                if ($curPart === ".")
                    continue;
                if ($curPart === "..") {
                    if (count ($ret) === 0) {
                        continue;
                    }
                    array_pop($ret);
                    continue;
                }
                $ret[] = $curPart;
            }
            $newPath = implode ("/", $ret);
            if ($this->isAbsolute())
                $newPath = "/" . $newPath;
            return new self($newPath);
        }
        
        
        public function isSubPathOf ($path):bool {
            $testDir = new self($path);

            if ($this->isRelative() !== $testDir->isRelative())
                return false; // Relative Path cannot be subpath of Absolute Path and vice versa

            $testDir = $testDir->resolve()->return();
            $me = $this->resolve()->return();

            echo "\n";
            echo $me. " : ";
            echo $testDir;

            $testDirArr = explode("/", $testDir);
            $meArr = explode("/", $me);



            if (count ($testDirArr) > $meArr)
                return false;

            for ($i=0; $i<count ($testDirArr); $i++) {
                if ($testDirArr[$i] !== $meArr[$i])
                    return false;
            }
            return true;
        }

        /**
         * @param $directory
         * @param \Exception|NULL $failEx
         * @return $this|PathAccessor
         * @throws ExpectationFailedException
         * @throws NULL
         */
        public function expectIsSubPathOf($directory, \Exception $failEx=null):PathAccessor {
            if ( ! $this->isSubPathOf($directory)) {
                if ($failEx === null)
                    $failEx = new ExpectationFailedException(["Path ? is not subpath of ?", $this->reference, $directory]);
                throw $failEx;
            }
            return $this;
        }


        /**
         * @return string[]
         */
        public function asArray() {
            $path = $this->reference;
            if (substr($path, 0, 1) === "/")
                $path = substr($path, 1);
            if ($path === "")
                return [];
            return explode("/", $path);
        }


        /**
         * @return LocalPathAccessor
         */
        public function expectLocalPath () {

        }

        /**
         * @return LocalFileAccessor
         */
        public function expectLocalFile () {

        }



        /**
         * @return StringAccessor
         */
        public function getBaseName():StringAccessor {
            return new StringAccessor(basename($this->reference));
        }
        
    }