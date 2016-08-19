<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 00:12
     */


    namespace Gismo\Component\Di;




    use Gismo\Component\Di\Core\Invokeable;
    use Gismo\Component\Di\Type\DiClosureFactory;
    use Gismo\Component\Di\Type\DiFactory;
    use Gismo\Component\Di\Type\DiInterceptor;
    use Gismo\Component\Di\Type\DiProvider;
    use Gismo\Component\Di\Core\GoDiParameterBuilder;
    use Gismo\Component\Di\Ex\NoFactoryException;
    use Gismo\Component\Di\Type\GoAbstractDiDefinition;
    use Gismo\Component\Di\Type\GoConstantDiDefinition;
    use Gismo\Component\Di\Type\GoFactoryDiDefinition;
    use Gismo\Component\Di\Type\GoFilterDefinition;
    use Gismo\Component\Di\Type\GoNullFactoryDiDefinition;
    use Gismo\Component\Di\Type\GoServiceDiDefinition;
    use Gismo\Component\PhpFoundation\Accessor\CallableAccessor;
    use Gismo\Component\PhpFoundation\Type\OrderedList;
    use Gismo\Component\PhpFoundation\Type\PrototypeMap;

    class DiContainer implements \ArrayAccess {


        public function __construct() {
            $this->diProvider = new OrderedList();
            $this->diDef = new PrototypeMap(new GoNullFactoryDiDefinition());



            // Initialize DiServiceTraits
            $reflect = new \ReflectionObject($this);
            foreach ($reflect->getMethods(\ReflectionMethod::IS_PRIVATE) as $curMethod) {
                if (strpos($curMethod->getName(), "__di_init_service_") === 0) {
                    $curMethod->setAccessible(true);
                    $curMethod->invoke($this);
                    $curMethod->setAccessible(false);
                }
            }
        }

        /**
         * @var PrototypeMap|GoAbstractDiDefinition[]
         */
        private $diDef = [];


        /**
         * Providers are used to create Values that
         * are not defined in diDef.
         *
         * The first matched creates the Object.
         *
         * @var OrderedList
         */
        private $diProvider;


        /**
         * A service is called only once, when the
         * Object is requested the first time
         *
         * @param callable $fn
         * @return GoServiceDiDefinition
         */
        public function service(callable $fn) : GoServiceDiDefinition{
            return new GoServiceDiDefinition($fn);
        }



        public function __debug_getDiDef() : PrototypeMap {
            return $this->diDef;
        }


        /**
         * A Factory is called each time the Object is
         * requested.
         *
         * @param callable $fn
         * @return GoFactoryDiDefinition
         */
        public function factory(callable $fn) : GoFactoryDiDefinition {
            return new GoFactoryDiDefinition($fn);
        }

        /**
         * Set the value directly.
         *
         * @param $mixed
         * @return GoConstantDiDefinition
         */
        public function constant($mixed) : GoConstantDiDefinition {
            return new GoConstantDiDefinition($mixed);
        }

        /**
         * Set a Filter on a DiName
         *
         * Available Parameters:
         * $§§input : The Value to be filtered
         *
         * Return Values:
         * void     : Keep the Value
         * false    : Skip preceeding filters
         * <mixed>  : Overwrite $§§input for next filter
         *
         * @param callable $fn
         * @return GoFilterDefinition
         */
        public function filter(callable $fn, $priority=0) : GoFilterDefinition {
            return new GoFilterDefinition($fn, $priority);
        }




        /**
         *
         * @param callable $fn
         * @param array $params
         * @return mixed
         */
        public function __invoke(callable $fn, array $params = []) {
            if ($fn instanceof Invokeable) {
                return $fn($params);
            }
            $paramBuilder = new GoDiParameterBuilder($this);
            foreach ($params as $key => $val) {
                $paramBuilder->override($key, $val);
            }

            $ref = ($accessor = new CallableAccessor($fn))->getReflection();
            $paramValues = $paramBuilder->build($ref->getParameters());
            return $fn(...$paramValues);
        }

        /**
         * Create a new Instance of a class calling the Constructor.
         *
         * @param $className
         * @param array $params
         * @return mixed
         */
        public function construct ($className, array $params = []) {
            $paramBuilder = new GoDiParameterBuilder($this);
            foreach ($params as $key => $val) {
                $paramBuilder->override($key, $val);
            }
            $ref = new \ReflectionClass($className);
            if ($ref->getConstructor() === null) {
                return new $className();
            }
            $ref->getConstructor()->getParameters();

            $paramValues = $paramBuilder->build($ref->getConstructor()->getParameters());
            return new $className(...$paramValues);
        }



        private function getFactoryKeyForSettaGetta($varName) {
            return "properties.{$varName}";
        }
        
        public function __set($name, $val) {
            $this[$this->getFactoryKeyForSettaGetta($name)] = $val;
        }

        
        public function __get($name) {
            return $this[$this->getFactoryKeyForSettaGetta($name)];
        }


        /**
         * @internal
         * @param mixed $offset
         * @return bool
         */
        public function offsetExists($offset) {
            if ( ! isset ($this->diDef[$offset]))
                return false;
            if ($this->diDef[$offset] instanceof GoNullFactoryDiDefinition)
                return false;
            return true;
        }

        /**
         * @internal
         * @param mixed $offset
         * @return mixed
         * @throws NoFactoryException
         */
        public function offsetGet($offset) {
            $def = $this->diDef[$offset];
            if ($def instanceof GoNullFactoryDiDefinition) {

                $firstPart = explode(".", $offset)[0];
                $prototypeName = $firstPart . ".__PROTO__";
                if (isset ($this->diDef[$prototypeName])) {
                    $proto = $this->diDef[$prototypeName];
                    $proto = clone $proto;
                    $def->__diReplace($proto);
                    $this->__setDiDef($offset, $proto);
                    $def = $proto;
                } else {


                    $this->diProvider->each(function ($what) use ($offset, &$def) {
                        $ret = $this($what, ["§§name" => $offset]);
                        if ($ret instanceof GoAbstractDiDefinition) {
                            $def->__diReplace($ret);
                            $def = $ret;
                            $this[$offset] = $def;
                            return false; // skip further processing
                        }
                    });
                }
                if ($def instanceof GoNullFactoryDiDefinition)
                    throw new NoFactoryException("No factory found for '$offset'");
            }
            return $def->__diGetInstance($this, ["§§name" => $offset]);
        }


        private function __setDiDef ($key, GoAbstractDiDefinition $def) {
            if ( ! isset ($this->diDef[$key])) {
                $this->diDef[$key] = $def;
                return;
            }
            $oldDef = $this->diDef[$key];
            if ($oldDef instanceof GoNullFactoryDiDefinition) {
                $this->diDef[$key] = $oldDef->__diReplace($def);
                return;
            }
            throw new \InvalidArgumentException("Definition for key '$key' already set. Unset value before overwriting!");
        }


        /**
         * @internal
         * @param mixed $offset
         * @param mixed $value
         */
        public function offsetSet($offset, $value) {

            // Autodetect key
            if ($offset === null) {
                throw new \InvalidArgumentException("NULL - offset in container[NULL] not allowed!");
                if (is_callable($value)) {
                    $value = new GoServiceDiDefinition($value);
                }

                if ($value instanceof GoAbstractDiDefinition) {
                    if ($value->__diGetReturnClassName() === null)
                        throw new \InvalidArgumentException("No return-type specified on factory.");
                    $this->__setDiDef($value->__diGetReturnClassName(), $value);
                    return;
                }

                if (is_object($value)) {
                    $this->__setDiDef(get_class($value), new GoConstantDiDefinition($value));
                    return;
                }

                throw new \InvalidArgumentException("Cannot dynamicly add value without name. Select a name for the parameter!");
            }

            // Explicit key
            if (is_string($offset)) {
                $offset = (string)$offset; // Translate Classes to plain type (Accessors)

                if (is_callable($value)) {
                    $value = new GoServiceDiDefinition($value); // Assume it is a service
                }

                if ($value instanceof GoAbstractDiDefinition) {
                    $this->__setDiDef($offset, $value);
                    return;
                }

                if ($value instanceof GoFilterDefinition) {
                    $filterData = $value->__diGetFilter();
                    $this->diDef[$offset]->addFilter($filterData[1], $filterData[0]);
                    return;
                }

                $this->__setDiDef($offset, new GoConstantDiDefinition($value));
                return;
            }


            // Setting Provider
            if (is_int($offset)) {
                if ( ! is_callable($value))
                    throw new \InvalidArgumentException("Prodider must be callable");
                $this->diProvider->add($offset, $value);
                return;
            }
            throw new \InvalidArgumentException("DiContainer: Invalid offset " . gettype($offset) . ": Must be string or integer!");
        }

        /**
         * Unset a Factory or Service. Filters will not be unset.
         *
         * @internal
         * @param mixed $offset
         */
        public function offsetUnset($offset) {
            if ( ! isset ($this->diDef[$offset]))
                return;
            if ($this->diDef[$offset]->isProtected($message))
                throw new \InvalidArgumentException("Key '$offset' is protected with message: {$message}");

            $this->diDef[$offset] = $this->diDef[$offset]->__diReplace(new GoNullFactoryDiDefinition());
        }


        public function __debugInfo() {
            return ["DiContainer"=>"Use DiContainer::debug() to view contents"];
        }
    }
