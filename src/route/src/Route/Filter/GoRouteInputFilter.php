<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 22:11
     */

    namespace Gismo\Component\Route\Filter;


    use Gismo\Component\Route\Type\RouterRequest;

    interface GoRouteInputFilter {

        public function filterIn(RouterRequest $routerRequest, array &$params);

    }
