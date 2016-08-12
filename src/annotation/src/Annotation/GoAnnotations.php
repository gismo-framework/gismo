<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:35
     */

    namespace Gismo\Component\Annotation;


    use Doctrine\Common\Annotations\AnnotationReader;
    use Doctrine\Common\Annotations\AnnotationRegistry;

    class GoAnnotations {

        private static $loadedPacks = [];

        /**
         * @var AnnotationReader
         */
        private static $sReader = null;

        public static function Require($annotationPackClassName) {
            if ( ! isset (self::$loadedPacks[$annotationPackClassName])) {
                $obj = new $annotationPackClassName();
                if ( ! $obj instanceof GoAnnotationPack)
                    throw new \InvalidArgumentException("Parameter one must be classname of GoAnnotionPack found '$annotationPackClassName'");
                foreach ($obj->getAnnotationClassNames() as $className)
                    AnnotationRegistry::loadAnnotationClass($className);
                self::$loadedPacks[$annotationPackClassName] = true;
            }
        }


        private static function _loadReader() {
            if (self::$sReader === null)
                self::$sReader = new AnnotationReader();
        }

        /**
         * @param $classname
         * @return mixed
         */
        public static function ForClass($classname, $annotationName=null) {
            self::_loadReader();
            $ref = new \ReflectionClass($classname);
            if ($annotationName === null)
                return self::$sReader->getClassAnnotations($ref);
            return self::$sReader->getClassAnnotation($ref, $annotationName);
        }

        /**
         * @param $classname
         * @param $method
         * @param null $annotationName
         * @return array|null|object
         */
        public static function ForMethod($classname, $method, $annotationName=null) {
            self::_loadReader();
            $ref = new \ReflectionMethod($classname, $method);

            if ($annotationName === null)
                return self::$sReader->getClassAnnotations($ref);
            return self::$sReader->getClassAnnotation($ref, $annotationName);
        }

        /**
         * @param $classname
         * @param $property
         * @param null $annotationName
         * @return array|null|object
         */
        public static function ForProperty($classname, $property, $annotationName=null) {
            self::_loadReader();
            $ref = new \ReflectionProperty($classname, $property);

            if ($annotationName === null)
                return self::$sReader->getPropertyAnnotations($ref);
            return self::$sReader->getPropertyAnnotation($ref, $annotationName);
        }

    }