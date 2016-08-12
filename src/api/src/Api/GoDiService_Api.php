<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 18:12
     */

    namespace Gismo\Component\Api;


    /**
     * Class GoDiService_Api
     * @package Gismo\Component\Api
     *
     * @property GoApiContainer $api
     */
    trait GoDiService_Api {

        private function __di_init_service_api() {
            $this->api = new GoApiContainer($this);
        }

    }