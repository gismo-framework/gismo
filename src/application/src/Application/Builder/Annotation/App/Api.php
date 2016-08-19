<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:32
     */


    namespace Gismo\Component\Application\Builder\Annotation\App;
    use Doctrine\Common\Annotations\Annotation\Attribute;
    use Doctrine\Common\Annotations\Annotation\Attributes;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;
    use Gismo\Component\Annotation\GoAnnotations;
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Container\GoApi;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\DiCallChain;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("METHOD")
     */
    class Api implements GoApplicationMethodAnnotation {

        /**
         *
         *
         * @var string
         */
        public $bind;



        public function getBindName ($className, $methodName) : string {
            $bind = $this->bind;
            if ($bind === null) {
                $bind = "api." . str_replace("\\", ".", $className) . ".$methodName";
            }
            return $bind;
        }

        public function getDefaultRoute ($classname, $methodName) : string {
            $bindName = $this->getBindName($classname, $methodName);
            if (strpos($bindName, "api.") === 0)
                $bindName = substr($bindName, 4);
            return "/api/" . $bindName;
        }


        public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
            // Make the Class Available
            if ( ! isset ($context[$myClassName])) {
                $context[$myClassName] = $context->service(function () use ($myClassName, $context) {
                    return $context->construct($myClassName);
                });
            }

            // Search for a Associated Route
            $anno = GoAnnotations::ForMethod($myClassName, $myMethodName, Route::class);
            $routeBindName = null;
            if ($anno instanceof Action) {
                $routeBindName = $anno->getBindName($myClassName, $myMethodName);
            }


            // Register the Action
            $context[$this->getBindName($myClassName, $myMethodName)] = $context->service(function () use ($context, $myClassName, $myMethodName, $routeBindName) {
                $stack = new GoApi($context);

                // Enable calling link() and linkAbs() on Api
                $stack->__setAssociatedRouteBindName($routeBindName);

                // Connect Main Action to class and Method
                $stack[0] = [$context[$myClassName], $myMethodName];
                return $stack;
            });


        }
    }


