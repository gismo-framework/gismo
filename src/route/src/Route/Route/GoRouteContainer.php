<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 22:52
     */


    namespace Gismo\Component\Route\Route;


    use Gismo\Component\Route\GoAction;
    use Gismo\Component\Route\Type\RouterRequest;

    class GoRouteContainer {

        private $validMethods = ["POST" => true, "GET" => true, "PUT" => true, "DELETE"=>true, "HEADER"=>true, "*"=>true];
        private $methodContainers = [];


        private function _registerSingleRoute (GoRouteNode $node, GoRoute $route, GoAction $action) {
            $curNode = $node;
            foreach ($route->getComponents() as $curComponent) {
                if ($curComponent->getType() === GoRouteComponent::TYPE_STATIC) {
                    $curNode = $curNode[$curComponent->getStaticName()];
                    continue;
                }
                if ($curComponent->getType() === GoRouteComponent::TYPE_PARAM) {
                    $curNode = $curNode[":"];
                    continue;
                }
                if ($curComponent->getType() === GoRouteComponent::TYPE_ARRAY_PARAM) {
                    $curNode = $curNode["::"];
                    break;
                }
                throw new \InvalidArgumentException("Invalid type");
            }
            if ($curNode->action !== null)
                throw new \InvalidArgumentException("Conflicting route: '$route'");
            $curNode->action = $action;
        }


        public function add(GoRoute $route, GoAction $action) {
            foreach ($route->getMethods() as $curMethod) {
                if ( ! isset ($this->validMethods[$curMethod]))
                    throw new \InvalidArgumentException("Invalid Method '$curMethod' in route '$route'");
                if ( ! isset ($this->methodContainers[$curMethod]))
                    $this->methodContainers[$curMethod] = new GoRouteNode();
                $this->_registerSingleRoute($this->methodContainers[$curMethod], $route, $action);
            }
        }


        private function _findBestActionInNode(GoRouteNode $curNode, array $route, $index=0) {
            if ($index >= count ($route)) {
                if ($curNode->action !== NULL) {
                    return $curNode->action;
                }
                return null;
            }

            // Search for static Route first
            if (isset ($curNode[$route[$index]])) {
                $ret = $this->_findBestActionInNode($curNode[$route[$index]], $route, $index+1);
                if ($ret !== null)
                    return $ret;
            }

            // Search for Param node
            if (isset ($curNode[":"])) {
                $ret = $this->_findBestActionInNode($curNode[":"], $route, $index+1);
                if ($ret !== null)
                    return $ret;
            }

            if (isset ($curNode["::"])) {
                return $curNode["::"]->action;
            }
            return null;
        }


        /**
         * @param RouterRequest $request
         * @return bool|GoAction
         */
        public function findBestAction (RouterRequest $request) {
            $ret = null;
            if (isset ($this->methodContainers[$request->method])) {
                $ret = $this->_findBestActionInNode($this->methodContainers[$request->method], $request->route);
            }
            if ($ret === null) {
                if (isset ($this->methodContainers["*"])) {
                    $ret = $this->_findBestActionInNode($this->methodContainers["*"], $request->route);
                }
            }
            return $ret;
        }

    }