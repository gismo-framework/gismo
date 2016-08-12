<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:32
     */


    namespace Gismo\Component\Route\Annotation;
    use Doctrine\Common\Annotations\Annotation\Attribute;
    use Doctrine\Common\Annotations\Annotation\Attributes;
    use Doctrine\Common\Annotations\Annotation\Enum;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Route\GoDiService_Route_Property;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("METHOD")
     */
    class Route implements GoRouteAnnotation {

        /**
         *
         * @var string
         */
        public $route;

        /**
         * @Enum("POST", "GET", "PUT", "DELETE", "*")
         */
        public $method = "*";

        /**
         * @var string
         */
        public $bind;


        public function registerClass($object, GoDiService_Route_Property $route, Context $context, &$classScope) {
            throw new \InvalidArgumentException("Cannot use @Route on class");
        }

        public function registerMethod($object, $methodName, GoDiService_Route_Property $route, Context $context, &$classScope) {
            // TODO: Implement registerMethod() method.
        }
    }


