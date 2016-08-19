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
    use Gismo\Component\Application\Container\GoRoute;
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
         * Placeholder:
         * '@@' : For Api-Routes only - the default api route without parameters
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
            $apiDefaultRoute = null;

            $anno = GoAnnotations::ForMethod($myClassName, $myMethodName, Action::class);
            if ($anno instanceof Action) {
                $bindActionOrApi = $anno->getBindName($myClassName, $myMethodName);
            }
            $anno = GoAnnotations::ForMethod($myClassName, $myMethodName, Api::class);
            if ($anno instanceof Api) {
                $bindActionOrApi = $anno->getBindName($myClassName, $myMethodName);
                $apiDefaultRoute = $anno->getDefaultRoute($myClassName, $myMethodName);
            }

            if (is_string($route)) {
                $route = str_replace("@@", $apiDefaultRoute, $route);
            }

            if ($route === null && $apiDefaultRoute !== null)
                $route = $apiDefaultRoute;

            if ($bindActionOrApi === null) {
                throw new \InvalidArgumentException("Can't add route for $myClassName::$myMethodName: Define at least @Action or @Api");
            }
            if ($route === null)
                throw new \InvalidArgumentException("Can't add empty route for $myClassName::$myMethodName()");

            $routeBindName = $this->getBindName($myClassName, $myMethodName);
            $context[$routeBindName] = $context->service(function () use ($context, $bindActionOrApi, $route, $routeBindName) {
                $call = new GoRoute($context); // Durch Route ersetzen
                $call->origRoute = $route; // Wichtig für link() und linkAbs()
                $call->bindName = $routeBindName;
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


