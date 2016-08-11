<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 05.08.16
     * Time: 21:41
     */


    namespace Gismo\Component\Di\Type;


    class GoFilterDefinition {

        private $filter = null;
        private $priority = 0;

        public function __construct(callable $filter) {
            $this->filter = $filter;
        }


        public function priority($priority=0) : self {
            $this->priority = $priority;
            return $this;
        }

        public function __diGetFilter () {
            return [$this->priority, $this->filter];
        }


    }