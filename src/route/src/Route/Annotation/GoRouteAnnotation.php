<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 16:58
     */

    namespace Gismo\Component\Route\Annotation;



    use Gismo\Component\Application\Context;
    use Gismo\Component\Route\GoDiService_Route_Property;

    interface GoRouteAnnotation {


        public function registerClass($object, GoDiService_Route_Property $route, Context $context, &$classScope);

        public function registerMethod($object, $methodName, GoDiService_Route_Property $route, Context $context, &$classScope);

    }
