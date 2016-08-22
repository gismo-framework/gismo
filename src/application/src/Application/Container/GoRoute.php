<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 19.08.16
     * Time: 14:43
     */


    namespace Gismo\Component\Application\Container;


    use Gismo\Component\Di\DiCallChain;
    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\Route\Route\GoRouteDefinition;

    class GoRoute extends DiCallChain {


        public $origRoute;

        public $bindName;


        private $routeDef = null;


        public function link($params, $getParams=null) : string {
            $req = $this->mDi[Request::class];
            /* @var $req Request */

            if ($this->routeDef === null) {
                $this->routeDef = new GoRouteDefinition($this->origRoute);
            }

            $path = $req->ROUTE_START_PATH . $this->routeDef->buildLink($params);
            if ($getParams !== null) {
                $path .= "?" . http_build_query($getParams);
            }
            return $path;
        }

        public function linkAbs($params, $getParams = null) : string {
            $req = $this->mDi[Request::class];
            /* @var $req Request */


        }




    }