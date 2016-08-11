<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 11.08.16
     * Time: 22:38
     */

    namespace Gismo\Component\Route\Route;

    class GoRouteComponent {

        const TYPE_STATIC = "TYPE_STATIC";
        const TYPE_PARAM = "TYPE_PARAM";
        const TYPE_ARRAY_PARAM = "TYPE_ARRAY_PARAM";

        private $type;
        private $paramName = null;

        public function __construct (string $routeSegment) {
            if (strpos($routeSegment, "::") === 0) {
                $this->type = self::TYPE_ARRAY_PARAM;
                $this->paramName = substr($routeSegment, 2);
                return;
            }
            if (strpos($routeSegment, ":") === 0) {
                $this->type = self::TYPE_PARAM;
                $this->paramName = substr($routeSegment, 1);
                return;
            }
            $this->type = self::TYPE_STATIC;
            $this->staticName = $routeSegment;
        }


        public function getType() : string {
            return $this->type;
        }

        public function getStaticName() {
            return $this->staticName;
        }

        public function getParamName() {
            return $this->paramName;
        }


        public function __toString() {
            if ($this->type === self::TYPE_STATIC)
                return $this->getStaticName();
            if ($this->type === self::TYPE_PARAM)
                return ":" . $this->getParamName();
            if ($this->type === self::TYPE_ARRAY_PARAM)
                return "::" . $this->getParamName();
            throw new \InvalidArgumentException("Invalid type");
        }


    }