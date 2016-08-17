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
    use Doctrine\Common\Annotations\Annotation\Enum;
    use Doctrine\Common\Annotations\Annotation\Required;
    use Doctrine\Common\Annotations\Annotation\Target;
    use Gismo\Component\Annotation\GoAnnotations;
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Context;
    use Gismo\Component\Di\DiCallChain;
    use Gismo\Component\Route\GoDiService_Route_Property;

    /**
     * Class Route
     * @package Gismo\Component\Route\Annotations
     *
     * @Annotation
     * @Target("METHOD")
     */
    class Route implements GoApplicationMethodAnnotation {

        /**
         *
         *
         * @var string
         */
        public $route;

        /**
         * @Enum({"POST", "GET", "PUT", "DELETE", "*"})
         */
        public $method = "*";

        /**
         * @var string
         */
        public $bind;


        public function getBindName ($className, $methodName) {
            $bind = $this->bind;
            if ($bind === null) {
                $bind = "route." . str_replace("\\", ".", $className) . ".$methodName";
            }
            return $bind;
        }



        public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
            $bindActionOrApi = null;
            $route = $this->route;
            $anno = GoAnnotations::ForMethod($myClassName, $myMethodName, Action::class);
            if ($anno instanceof Action) {
                $bindActionOrApi = $anno->getBindName($myClassName, $myMethodName);
            }
            $anno = GoAnnotations::ForMethod($myClassName, $myMethodName, Api::class);
            if ($anno instanceof Api) {
                $bindActionOrApi = $anno->getBindName($myClassName, $myMethodName);
                if ($route === null)
                    $route = $anno->getDefaultRoute($myClassName, $myMethodName);
            }

            if ($bindActionOrApi === null) {
                throw new \InvalidArgumentException("Can't add route for $myClassName::$myMethodName: Define at least @Action or @Api");
            }
            if ($route === null)
                throw new \InvalidArgumentException("Can't add empty route for $myClassName::$myMethodName()");

            $routeBindName = $this->getBindName($myClassName, $myMethodName);
            $context[$routeBindName] = $context->service(function () use ($context, $bindActionOrApi) {
                $call = new DiCallChain($context); // Durch Route ersetzen
                $call[0] = function ($§§parameters) use ($bindActionOrApi, $context) {
                    return $context[$bindActionOrApi]($§§parameters);
                };
                return $call;
            });

            // Link Bind-Name to Router
            $context->route->add($route, function ($§§parameters) use ($routeBindName, $context) {
                 $context[$routeBindName]($§§parameters);
            });


        }
    }


