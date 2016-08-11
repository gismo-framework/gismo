<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:34
     */

    namespace Gismo\Component\Route\Route;

    use Gismo\Component\Route\Type\RouterRequest;

    class GoRoute {

        /**
         * @var GoRouteComponent[]
         */
        private $components = [];
        private $methods = ["*"];

        public function __construct(string $route) {
            if (strpos($route, "@") !== false) {
                list($methods, $route) = explode("@", $route);
                $this->methods = explode("|", $methods);
            }

            if ($route === "/")
                $route = "";

            $parts = explode("/", $route);
            array_shift($parts);

            foreach ($parts as $part) {
                $this->components[] = new GoRouteComponent($part);
            }
        }

        /**
         * @return string[]
         */
        public function getMethods() {
            return $this->methods;
        }


        public function buildParams (RouterRequest $req) : array {
            $params = [];

            $catchAllParamName = null;
            for($i=0; $i<count($req->route); $i++) {
                if ($this->components[$i]->getType() === GoRouteComponent::TYPE_STATIC)
                    continue;
                if ($this->components[$i]->getType() === GoRouteComponent::TYPE_PARAM) {
                    $params[$this->components[$i]->getParamName()] = $req->route[$i];
                    continue;
                }
                if ($this->components[$i]->getType() === GoRouteComponent::TYPE_ARRAY_PARAM) {
                    $catchAllParamName = $this->components[$i]->getParamName();
                    break;
                }
            }

            if ($catchAllParamName !== null) {
                $params[$catchAllParamName] = [];
                for(;$i<count($req->route); $i++) {
                    $params[$catchAllParamName][] = $req->route[$i];
                }
            }
            return $params;
        }

        /**
         * @return GoRouteComponent[]
         */
        public function getComponents () {
            return $this->components;
        }

        public function __toString() {
            return implode ("/", $this->components);
        }




    }