<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 23:34
     */

    namespace Gismo\Component\Route\Route;

    use Gismo\Component\Route\Type\RouterRequest;

    class GoRouteDefinition {

        /**
         * @var GoRouteComponent[]
         */
        private $components = [];
        private $methods = ["*"];
        private $validMethods = ["GET" => true, "POST" => true, "PUT" => true, "DELETE" => true, "*" => true];

        public function __construct(string $route, array $methods=["*"]) {
            foreach ($methods as $method)
                if ( ! isset ($this->validMethods[$method]))
                    throw new \InvalidArgumentException("Invalid route method: '$method'. Valid are :" . implode(",", array_keys($this->validMethods)));
            $this->methods = $methods;

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


        public function buildLink (array $params) {
            $parts = [];
            foreach ($this->components as $curComponent) {
                if ($curComponent->getType() === GoRouteComponent::TYPE_STATIC) {
                    $parts[] = $curComponent->getStaticName();
                    continue;
                }
                if ($curComponent->getType() === GoRouteComponent::TYPE_PARAM) {
                    if ( ! isset ($params[$curComponent->getParamName()])) {
                        $parts[] = "";
                        continue;
                    }
                    $parts[] = urlencode(urlencode($params[$curComponent->getParamName()]));
                    continue;
                }
                if ($curComponent->getType() === GoRouteComponent::TYPE_ARRAY_PARAM) {
                    if ( ! isset ($params[$curComponent->getParamName()])) {
                        continue;
                    }
                    foreach ($params[$curComponent->getParamName()] as $curParam) {
                        $parts[] = urlencode(urlencode($params[$curComponent->getParamName()]));
                    }
                    continue;
                }
            }
            return "/" . implode("/", $parts);
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