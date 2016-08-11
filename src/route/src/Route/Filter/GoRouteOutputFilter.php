<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 04:11
     */

    namespace Gismo\Component\Route\Filter;


    use Gismo\Component\Route\Type\RouterRequest;

    interface GoRouteOutputFilter {

        public function filterOut(RouterRequest $routerRequest, $return);

    }