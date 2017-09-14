<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 20:06
     */

    namespace Gismo\Component\Api;


    /**
     * Class GoDiService_Route
     * @package Gismo\Component\Api
     *
     * @property GoDiService_Route_Property $route
     */
    trait GoDiService_Api {

        /**
         * Called automaticly from dependency injector
         */
        private function __di_init_service_route () {
            $this->api = $this->constant(new GoDiService_Route_Property($this));
        }

    }