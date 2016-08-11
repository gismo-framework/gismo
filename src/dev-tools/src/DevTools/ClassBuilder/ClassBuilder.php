<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 27.07.16
     * Time: 21:54
     */
    
    namespace Gismo\Component\DevTools\ClassBuilder;
    

    class ClassBuilder {

        private $type;
        private $namespace = null;
        private $className;
        
        private $extends = null;
        private $implements = [];
        private $use = [];
        
        private $modifier = [];

        private $comment = null;

        private $methods = [];

        
        
        
        public function __construct($className, $type="class") {
            $this->type = $type;
            $className = str_replace("\\", "/", $className);
            $namespace = dirname($className);

            if ($namespace !== ".") {
                // Dot is returned if no slash was found
                $this->namespace = $namespace;
                $this->className = basename($className);
            } else {
                $this->namespace = null;
                $this->className = $className;
            }
        }
        
        
        public static function Class ($name) : self {
            return new self($name, "class");
        }
        
        public static function Interface ($name) : self {
            return new self($name, "interface");
        }
        
        public static function Trait ($name) : self {
            return new self($name, "trait");
        }
        
        public function extends($className) : self {
            $this->extends = $className;
            return $this;
        }

        public function implements(array $names) : self {
            $this->implements[] = $names;
            return $this;
        }

        public function use(array $names) : self {
            $this->use = $names;
            return $this;
        }

        public function final() : self {
            $this->modifier[] = "final";
            return $this;
        }

        public function public() : self {
            $this->modifier[] = "public";
            return $this;
        }

        public function protected() : self {
            $this->modifier[] = "protected";
            return $this;
        }


        public function private() : self {
            $this->modifier[] = "private";
            return $this;
        }


        public function static() : self {
            $this->modifier[] = "static";
            return $this;
        }

        public function comment($comment) : self {
            if ($this->comment === null) {
                $this->comment = $comment;
            } else {
                $this->comment .= "\n" . $comment;
            }
            return $this;
        }


        private function getLambdaCodeAndParams (callable $fn) : TextAccessor {
            $ref = new \ReflectionFunction($fn);

            if ($ref->getEndLine() - 2 < $ref->getStartLine()) {
                throw new \InvalidArgumentException("Cannot extract closure code from file '{$ref->getFileName()}': Lines: {$ref->getStartLine()} - {$ref->getEndLine()}: Function header and body must have linebreaks");
            }

            $code = \pf\text()->fromFile($ref->getFileName());
            return $code->slice($ref->getStartLine(), $ref->getEndLine()-2)->unindent();
        }


        public function function(string $name, callable $fn=null, $params = null, $code = null) : self {
            if ($fn !== null) {
                $code = $this->getLambdaCodeAndParams($fn);
            }
            $this->methods[] = [
                "modifiers" => $this->modifier,
                "comment" => $this->comment,
                "name" => $name,
                "params" => $params,
                "code" => $code
            ];
            $this->comment = null;
            $this->modifier = []; // Reset Modifier
            return $this;
        }



        public function generate() : string {
            $r = \pf\text();
            if ($this->namespace !== null) {
                $r[] = "namespace " . str_replace("/", "\\", $this->namespace) . " {";
                $r["+"];
            }


            $r["+"] = "{$this->type} {$this->className}";
            if ($this->extends !== null) {
                $r["@"] = " extends {$this->extends}";
            }
            if (count ($this->implements) > 0) {
                $r["@"] = " implements " . implode (", ", $this->implements);
            }
            $r["@"] = " {";

            if (count ($this->use) > 0) {
                $r[">"] = "use " . implode (",", $this->use) . ";";
            }


            foreach ($this->methods as $curMethod) {
                $r["+"] = "";
                if (count ($curMethod["modifiers"]) > 0)
                    $r["@"] = implode (" ", $curMethod["modifiers"]) . " ";
                $r["@"] = "function {$curMethod["name"]}() {";
                $r[] = $curMethod["code"];
                $r["-"] = "}";
            }

            $r["-"] = "}";
            if ($this->namespace !== null) {
                $r["-"] = "};"; // Close Namespace
            }
            return $r;
        }



    }    
    
