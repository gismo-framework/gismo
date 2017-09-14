<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.08.16
     * Time: 20:11
     */


    namespace Gismo\Component\Api;

    use Gismo\Component\Api\Container\Procedure;
    use Gismo\Component\Di\DiContainer;

    class GoDiService_Api_Property {



        /**
         * @var DiContainer
         */
        private $di;

        public function __construct(DiContainer $container) {
            $this->di = $container;
        }





        public function define(string $bind, string $route=null) : Procedure {

        }



        public function call (string $bind, array $parameters) {

        }

    }