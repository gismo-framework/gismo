<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 14:10
     */

    namespace Gismo\Component\Application\Builder\Annotation\App;
    use Doctrine\Common\Annotations\Annotation\Target;
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\Core\DiCallStack;
    use Gismo\Component\Di\DiCallChain;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("METHOD")
     */
    class Action implements GoApplicationMethodAnnotation {

        /**
         * @var string
         */
        public $bind = null;

        public function getBindName ($className, $methodName) : string {
            $bind = $this->bind;
            if ($bind === null) {
                $bind = "action." . str_replace("\\", ".", $className) . ".$methodName";
            }
            return $bind;
        }

        public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
            // Make the Class Available
            if ( ! isset ($context[$myClassName])) {
                $context[$myClassName] = $context->service(function () use ($myClassName, $context) {
                    return $context->construct($myClassName);
                });
            }


            // Register the Action
            $context[$this->getBindName($myClassName, $myMethodName)] = $context->service(function () use ($context, $myClassName, $myMethodName) {
                $stack = new DiCallChain($context);
                // Connect Main Action to class and Method
                $stack[0] = [$context[$myClassName], $myMethodName];
                return $stack;
            });


        }

    }

