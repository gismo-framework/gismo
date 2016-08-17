<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 18:12
     */

    namespace Gismo\Component\Application\Service;
    use Gismo\Component\Annotation\GoAnnotations;
    use Gismo\Component\Application\Builder\GoAnnotationPack_Application;
    use Gismo\Component\Application\Builder\GoApplicationClassAnnotation;
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\DiCallChain;


    /**
     * Class GoDiService_Api
     * @package Gismo\Component\Api
     *
     */
    trait GoDiService_App {

        private function __di_init_service_app() {
            // Register Self reference
            $this[Context::class] = $this->constant($this);
            $this[get_class($this)] = $this->constant($this);
        }

        /**
         * @param string $classname
         * @return $this
         */
        public function provideClass(string $classname) : self {
            GoAnnotations::Require(GoAnnotationPack_Application::class);
            $ref = new \ReflectionClass($classname);
            $annotations = GoAnnotations::ForClass($classname);
            $builderScope = [];
            // Register Class Annotations
            foreach ($annotations as $curAnnotation) {
                if ($curAnnotation instanceof GoApplicationClassAnnotation) {
                    try {
                        $curAnnotation->registerClass($classname, $this, $builderScope);
                    } catch (\Exception $e) {
                        throw new \InvalidArgumentException("[APP-INIT] Class: $classname : " . $e->getMessage(), 0, $e);
                    }
                }
            }

            // Register Method Annotations
            foreach ($ref->getMethods(T_PUBLIC) as $refMethod) {
                $methodName = $refMethod->getName();
                $annotations = GoAnnotations::ForMethod($classname, $methodName);
                foreach ($annotations as $curAnnotation) {
                    if ($curAnnotation instanceof GoApplicationMethodAnnotation) {
                        try {
                            $curAnnotation->registerClass($classname, $methodName, $this, $builderScope);
                        } catch (\Exception $e) {
                            throw new \InvalidArgumentException("[APP-INIT] Method {$classname}::{$methodName}() : " . $e->getMessage(), 0, $e);
                        }
                    }
                }
            }
            return $this;
        }

    }