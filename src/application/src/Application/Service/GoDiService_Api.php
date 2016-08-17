<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 12.08.16
     * Time: 18:12
     */

    namespace Gismo\Component\Application\Service;
    use Gismo\Component\Di\DiCallChain;


    /**
     * Class GoDiService_Api
     * @package Gismo\Component\Api
     *
     */
    trait GoDiService_Api {

        private function __di_init_service_api() {
            $this["api.__PROTO__"] = $this->service(function () {
                return new DiCallChain($this);
            });
        }

    }