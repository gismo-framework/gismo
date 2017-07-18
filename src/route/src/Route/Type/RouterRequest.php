<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 09.08.16
 * Time: 23:46
 */


    namespace Gismo\Component\Route\Type;


    use Gismo\Component\HttpFoundation\Request\Request;
    use Gismo\Component\PhpFoundation\Accessor\UrlAccessor;

    class RouterRequest {


        public function __construct(array $routeArray, string $method="GET") {
            $this->routeArray = $routeArray;
            $this->route = implode("/", $routeArray);
            $this->method = $method;
        }


        /**
         * Route path array
         *
         * @var string[]
         */
        public $routeArray;

        /**
         * Route String
         *
         * @var string
         */
        public $route;

        /**
         * Http Request Method
         *
         * @var string
         */
        public $method;


        public static function BuildFromRequest(Request $request) : RouterRequest {
            $paths = $request->ROUTE_PATH->asArray();
            for ($i=0; $i<count ($paths); $i++) {
                $paths[$i] = urldecode(urldecode($paths[$i]));
            }
            return new self($paths, $request->METHOD);
        }


        public function __toString() {
            return "[RouteRequest:" . implode (" / ", $this->route). "]";
        }

    }