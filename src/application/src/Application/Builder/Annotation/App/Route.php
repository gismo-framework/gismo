<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 01:32
     */


    namespace Gismo\Component\Application\Builder\Annotation\App;
    use Gismo\Component\Application\Builder\GoApplicationMethodAnnotation;
    use Gismo\Component\Application\Container\GoDeferBind;
    use Gismo\Component\Application\Container\GoRoute;
    use Gismo\Component\Application\Context;
    use Phore\Annotations\Annotations;

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
        public $bind = null;



        private function _buildBind ($myClassName, $myMethodName) {
            $bindActionOrApi = null;
            $anno = Annotations::ForMethod($myClassName, $myMethodName, Action::class);
            if ($anno instanceof Action) {
                $bindActionOrApi = $anno->getBindName($myClassName, $myMethodName);
            }
            $anno = Annotations::ForMethod($myClassName, $myMethodName, Api::class);
            if ($anno instanceof Api) {
                $bindActionOrApi = $anno->getBindName($myClassName, $myMethodName);
                $apiDefaultRoute = $anno->getDefaultRoute($myClassName, $myMethodName);
            }
            if (isset ($apiDefaultRoute)) {
                $this->route = str_replace("@@", $apiDefaultRoute, $this->route);
            }
            if ($this->bind === null)
                $this->bind = "route." . $this->route . "[{$this->method}]";
            return $bindActionOrApi;
        }

        public function getBindName ($myClassName, $myMethodName) {
            $this->_buildBind($myClassName, $myMethodName);
            return $this->bind;
        }


        public function registerClass($myClassName, $myMethodName, Context $context, array &$builderScope) {
            $bindActionOrApi = $this->_buildBind($myClassName, $myMethodName);

            if ($bindActionOrApi === null) {
                throw new \InvalidArgumentException("Can't add route for $myClassName::$myMethodName: Define at least @Action or @Api");
            }

            $routeBindName = $this->getBindName($myClassName, $myMethodName);

            $context[$routeBindName] = $context->service(function () use ($context, $bindActionOrApi) {
                $call = new GoRoute($context); // Durch Route ersetzen
                $call->origRoute = $this->route; // Wichtig für link() und linkAbs()
                $call->bindName = $this->bind;
                $call[0] = function ($§§parameters) use ($bindActionOrApi, $context) {
                    return $context[$bindActionOrApi]($§§parameters);
                };
                return $call;
            });

            // Link Bind-Name to Router
            $context->route->add($this->route, new GoDeferBind($context, $routeBindName));


        }
    }


