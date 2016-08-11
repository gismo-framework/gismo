<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 20:06
     */

    namespace Gismo\Component\Route;

    /**
     * Class GoDiService_Route
     * @package Gismo\Component\Route
     *
     * @property GoDiService_Route_Property $route
     */
    trait GoDiService_Route {

        /**
         * Called automaticly from dependency injector
         */
        private function __di_init_service_route () {
            $this->route = $this->constant(new GoDiService_Route_Property($this));
        }

    }